<?php


namespace Jackal\Giffhanger\Generator;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Filters\Audio\SimpleFilter;
use FFMpeg\Media\Video;
use Jackal\Giffhanger\Configuration\Configuration;
use Jackal\Giffhanger\FFMpeg\ext\Filters\CropCenterFilter;

abstract class AbstractVideoGenerator implements GeneratorInterface
{
    abstract protected function getVideoFormat();

    protected $destination;
    protected $sourceFile;
    protected $cutPoints;

    protected $options = [];
    private $tempFilesToRemove = [];

    public function __construct($sourceFile, $destionationFile, Configuration $options)
    {
        $this->options = $options;

        if (!is_dir($this->options->getTempFolder())) {
            if (!mkdir($this->options->getTempFolder(), 0777, true)) {
                $this->__destruct();
                throw new \Exception('Cannot create temp folder in path "'.$this->options->getTempFolder().'"');
            }
        }

        $this->sourceFile = $sourceFile;
        $this->destination = $destionationFile;

        $videoDuration = $this->getDuration();
        for ($i=1;$i<=$this->options->getNumberOfFrames();$i++) {
            $this->cutPoints[] = (($videoDuration / $this->options->getNumberOfFrames()) - ($videoDuration / $this->options->getNumberOfFrames() / 2)) * $i;
        }
    }


    /**
     * @return FFMpeg
     */
    protected function getFFMpeg()
    {
        return FFMpeg::create([
            'ffmpeg.binaries'  => $this->options->getFFMpegBinaries(),
            'ffprobe.binaries' => $this->options->getFFProbeBinaries(),
            'timeout' => 3600,
        ]);
    }

    protected function getDuration() : int
    {
        $ffmpeg = $this->getFFMpeg();
        $ffmpeg = $ffmpeg->open($this->sourceFile);
        return $ffmpeg->getFFProbe()->format($this->sourceFile)->get('duration');
    }

    protected function getRatio()
    {
        $ffmpeg = $this->getFFMpeg();
        $ffmpeg = $ffmpeg->open($this->sourceFile);
        return $ffmpeg->getStreams()->first()->getDimensions()->getRatio()->getValue();
    }

    public function __destruct()
    {
        foreach (array_unique($this->tempFilesToRemove) as $fileToRemove) {
            if (is_file($fileToRemove)) {
                unlink($fileToRemove);
            }
            //if folder is empty, remove
            if (is_dir(dirname($fileToRemove)) and count(scandir(dirname($fileToRemove))) == 2) {
                rmdir(dirname($fileToRemove));
            }
        }
    }

    protected function addTempFileToRemove($filePath)
    {
        $this->tempFilesToRemove[] = $filePath;
    }

    public function generate()
    {
        $ffmpeg = $this->getFFMpeg();
        $videoFormat = $this->getVideoFormat();

        /** @var Video $video */
        $video = $ffmpeg->open($this->sourceFile);

        $files = [];
        foreach ($this->cutPoints as $k => $cutPoint) {
            $partFile = $this->options->getTempFolder().'/'.md5($this->sourceFile).'_'.($k+1).'.avi';

            //remove audio track
            $video->addFilter(new SimpleFilter(['-an']));

            $video->filters()->clip(
                TimeCode::fromSeconds($cutPoint),
                TimeCode::fromSeconds($this->options->getOutputDuration() / count($this->cutPoints))
            );

            $video->filters()->resize(
                new Dimension(
                    (int)round($this->options->getDimensionWidth()),
                    (int)round($this->options->getDimensionWidth() / $this->getRatio())
                )
            );
            $video->filters()->framerate(new FrameRate($this->options->getFrameRate()), 1);
            $video->save($videoFormat, $partFile);

            $files[] = $partFile;
            $this->addTempFileToRemove($partFile);
        }

        if (count($files) == 1) {
            rename($files[0], $this->destination);
        } else {
            /** @var Video $video */
            $video = $ffmpeg->open($files[0]);

            if (is_file($this->destination)) {
                unlink($this->destination);
            }

            $video
                ->concat($files)
                ->saveFromSameCodecs($this->destination);
        }

        if ($this->options->getCropRatio() and ($this->options->getCropRatio() != $this->getRatio())) {
            $fileCropped = $this->options->getTempFolder().'/'.md5($this->sourceFile).'_cropped.avi';

            $video = $ffmpeg->open($this->destination);
            $video->addFilter(new CropCenterFilter($this->options->getCropRatio()));
            $video->save($videoFormat, $fileCropped);
            rename($fileCropped, $this->destination);
        }
    }
}
