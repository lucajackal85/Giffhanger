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
        $videoFormat->setKiloBitrate(200);

        $ffmpeg = FFMpeg::create();

        $video = $ffmpeg->open($this->sourceFile);

        $files = [];
        foreach ($this->cutPoints as $k => $cutPoint){
            $video->filters()->clip(
                TimeCode::fromSeconds($cutPoint),
                TimeCode::fromSeconds($this->totalDuration / count($this->cutPoints))
            );
            $file = $this->tempFolder.'/'.md5($this->sourceFile).'_'.($k+1).'.avi';
            $video->filters()->resize(new Dimension($this->dimentionWidth, round($this->dimentionWidth / $this->getRatio())));
            $video->filters()->framerate(new FrameRate(10),1);
            $video->save($videoFormat,$file);
            $files[] = $file;

            $this->tempFilesToRemove[] = $file;
        }

        if(count($files) > 1) {
            $video = $ffmpeg->open($files[0]);

            if (is_file($this->destination)) {
                unlink($this->destination);
            }
            $video
                ->concat(array_slice($files, 0, count($files)))
                ->saveFromSameCodecs($this->destination);
        }else{
            rename($files[0],$this->destination);
        }
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}