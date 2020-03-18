<?php


namespace Jackal\Giffhanger\Giffhanger;

use Jackal\Giffhanger\Generator\GifGenerator;
use Jackal\Giffhanger\Generator\VideoMP4Generator;

class Giffhanger
{
    protected $videoFile;
    protected $config;

    public function __construct($sourceVideoFile,$config = [])
    {
        $this->videoFile = $sourceVideoFile;
        $this->config = $config;
    }

    public function generateGIF($destinationGIFFile){

        $generator = new GifGenerator($this->videoFile,$destinationGIFFile,$this->config);
        return $generator->generate();
    }

    public function generateVideo($destinationVideoFile){
        $generator = new VideoMP4Generator($this->videoFile,$destinationVideoFile,$this->config);
        return $generator->generate();
    }










}