<?php

namespace Jackal\Giffhanger\Tests\Functional;

use FFMpeg\FFMpeg;
use PHPUnit\Framework\TestCase;

abstract class BaseFFMpegFunctionalTest extends TestCase
{
    protected function getVideoDuration($filePath){
        $ffprobe = \FFMpeg\FFProbe::create();

        return $ffprobe->format($filePath)
        ->get('duration');
    }

    protected function getVideoWidth($filePath){
        $ffmpeg = FFMpeg::create();
        $ffmpeg = $ffmpeg->open($filePath);

        return $ffmpeg->getStreams()->first()->getDimensions()->getWidth();
    }

    protected function getVideoHeight($filePath){
        $ffmpeg = FFMpeg::create();
        $ffmpeg = $ffmpeg->open($filePath);

        return $ffmpeg->getStreams()->first()->getDimensions()->getHeight();
    }

    protected function getGIFWidth($filePath){
        return getimagesize($filePath)[0];
    }

    protected function getGIFHeight($filePath){
        return getimagesize($filePath)[1];
    }

    protected function getGIFFrames($filePath){

        $content = file_get_contents($filePath);

        $images = explode("\x00\x21\xF9\x04", $content);

        return $images;
    }

}