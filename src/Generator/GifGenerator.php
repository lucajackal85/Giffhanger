<?php


namespace Jackal\Giffhanger\Generator;


use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;

class GifGenerator extends VideoMP4Generator
{
    public function generate(){
        $originalDestionation = $this->destination;
        $this->destination = $this->getTempFolder().'/'.md5($this->sourceFile).'-temp.avi';
        parent::generate();
        $this->addTempFileToRemove($this->destination);

        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($this->destination);

        $video
            ->gif(
                TimeCode::fromSeconds(0),
                new Dimension($this->getDimensionWidth(), $this->getDimensionWidth() / $this->getRatio()), $this->getDuration()
            )->save($originalDestionation);
    }
}