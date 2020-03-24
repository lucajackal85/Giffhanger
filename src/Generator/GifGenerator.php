<?php


namespace Jackal\Giffhanger\Generator;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Jackal\Giffhanger\FFMpeg\ext\Media\Gif;

class GifGenerator extends BaseGenerator
{
    public function generate()
    {
        $originalDestionation = $this->destination;
        $this->destination = $this->options->getTempFolder().'/'.md5($this->sourceFile).'-temp.avi';
        parent::generate();
        $this->addTempFileToRemove($this->destination);

        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($this->destination);

        $gif = new Gif(
            $video,
            $video->getFFMpegDriver(),
            $video->getFFProbe(),
            TimeCode::fromSeconds(0),
            new Dimension($this->options->getDimensionWidth(), $this->options->getDimensionWidth() / $this->getRatio()),
            $this->getDuration()
        );

        $gif->save($originalDestionation);
    }
}
