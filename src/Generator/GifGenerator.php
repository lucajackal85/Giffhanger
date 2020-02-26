<?php


namespace Jackal\Generator;


use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Symfony\Component\Process\Process;

class GifGenerator extends VideoMP4Generator
{

    public function generate(){
        $originalDestionation = $this->destination;
        $this->destination = $this->destination.'-temp.avi';
        $this->tempFilesToRemove[] = $this->destination;
        parent::generate();

        $ffmpeg = FFMpeg::create();

        $video = $ffmpeg->open($this->destination);
        $video
            ->gif(
                TimeCode::fromSeconds(0),
                new Dimension($this->dimentionWidth, $this->dimentionWidth / $this->getRatio()), $this->getDuration()
            )->save($originalDestionation);


    }
}