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
}