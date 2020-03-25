<?php


namespace Jackal\Giffhanger\Generator;

use FFMpeg\FFMpeg;
use Jackal\Giffhanger\Configuration\Configuration;

abstract class BaseGenerator implements GeneratorInterface
{
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
}
