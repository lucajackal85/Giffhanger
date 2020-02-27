<?php


namespace Jackal\Generator;


use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;

class VideoMP4Generator extends BaseGenerator
{

    public function generate()
    {
        $videoFormat = new X264();
        $videoFormat->setKiloBitrate($this->getVideoBitrate());
        $videoFormat->setPasses(1);

        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($this->sourceFile);

        $files = [];
        foreach ($this->cutPoints as $k => $cutPoint){
            $partFile = $this->getTempFolder().'/'.md5($this->sourceFile).'_'.($k+1).'.avi';

            $video->filters()->clip(
                TimeCode::fromSeconds($cutPoint),
                TimeCode::fromSeconds($this->getOutputDuration() / count($this->cutPoints))
            );

            $video->filters()->resize(new Dimension($this->getDimensionWidth(), round($this->getDimensionWidth() / $this->getRatio())));
            $video->filters()->framerate(new FrameRate($this->getFrameRate()), 1);
            $video->save($videoFormat, $partFile);

            $files[] = $partFile;
            $this->addTempFileToRemove($partFile);
        }

        if(count($files) == 1) {
            rename($files[0],$this->destination);
            return;
        }

        $video = $ffmpeg->open($files[0]);

        if (is_file($this->destination)) {
            unlink($this->destination);
        }
        $video
            ->concat(array_slice($files, 0, count($files)))
            ->saveFromSameCodecs($this->destination);
    }
}