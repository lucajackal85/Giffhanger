<?php


namespace Jackal\Giffhanger\Generator;


use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Filters\Audio\SimpleFilter;
use Jackal\Giffhanger\FFMpeg\ext\Filters\CropCenterFilter;

abstract class AbstractVideoGenerator extends BaseGenerator
{

    abstract protected function getVideoFormat();

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

            $video->filters()->resize(new Dimension($this->options->getDimensionWidth(), round($this->options->getDimensionWidth() / $this->getRatio())));
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