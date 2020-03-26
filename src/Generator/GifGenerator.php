<?php

namespace Jackal\Giffhanger\Generator;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Media\Video;
use Jackal\Giffhanger\FFMpeg\ext\Media\Gif;

class GifGenerator extends VideoWebMGenerator
{
    public function generate() : void
    {
        $originalDestionation = $this->destination;
        $this->destination = $this->options->getTempFolder() . '/' . md5($this->sourceFile) . '-temp.avi';

        parent::generate();

        $this->addTempFileToRemove($this->destination);

        $ffmpeg = $this->getFFMpeg();
        /** @var Video $video */
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
