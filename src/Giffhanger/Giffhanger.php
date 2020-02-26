<?php


namespace Jackal\Giffhanger;


use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Jackal\Generator\GifGenerator;
use Jackal\Generator\VideoMP4Generator;
use Symfony\Component\Process\Process;

class Giffhanger
{
    protected $videoFile;
    protected $config;

    public function __construct($sourceVideoFile,$config = [])
    {
        $this->videoFile = $sourceVideoFile;
        $this->config = $config;
    }

    public function generateGIF($destinationGIFFile,$numberOfGif = 3, $totalDuration = 6,$dimentionWidth = 640){

        $generator = new GifGenerator($this->videoFile,$destinationGIFFile,$numberOfGif,$totalDuration,$dimentionWidth,$this->config);
        return $generator->generate();
    }

    public function generateVideo($destinationVideoFile,$numberOfGif = 3, $totalDuration = 6,$dimentionWidth = 640){
        $generator = new VideoMP4Generator($this->videoFile,$destinationVideoFile,$numberOfGif,$totalDuration,$dimentionWidth,$this->config);
        return $generator->generate();
    }










}