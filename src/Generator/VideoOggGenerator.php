<?php


namespace Jackal\Giffhanger\Generator;


use FFMpeg\Format\Video\Ogg;
class VideoOggGenerator extends AbstractVideoGenerator
{
    /**
     * @return Ogg
     */
    protected function getVideoFormat()
    {
        $videoFormat = new Ogg();
        if ($this->options->getVideoBitrate()) {
            $videoFormat->setKiloBitrate($this->options->getVideoBitrate());
        }

        return $videoFormat;
    }
}