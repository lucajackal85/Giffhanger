<?php


namespace Jackal\Giffhanger\Generator;


use FFMpeg\Format\Video\WebM;

class VideoWebMGenerator extends AbstractVideoGenerator
{
    /**
     * @return WebM
     */
    protected function getVideoFormat()
    {
        $videoFormat = new WebM();
        if($this->options->getVideoBitrate()) {
            $videoFormat->setKiloBitrate($this->options->getVideoBitrate());
        }

        return $videoFormat;
    }
}