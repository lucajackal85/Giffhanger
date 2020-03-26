<?php

namespace Jackal\Giffhanger\Generator;

use FFMpeg\Format\Video\X264;

class VideoH264Generator extends AbstractVideoGenerator
{
    /**
     * @return X264
     */
    protected function getVideoFormat() : X264
    {
        $videoFormat = new X264();
        if ($this->options->getVideoBitrate()) {
            $videoFormat->setKiloBitrate($this->options->getVideoBitrate());
        }
        $videoFormat->setPasses(1);

        return $videoFormat;
    }
}
