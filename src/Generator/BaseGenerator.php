<?php


namespace Jackal\Generator;


use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;

abstract class BaseGenerator implements GeneratorInterface
{
    protected $destination;
    protected $numberOfFrames;
    protected $totalDuration;
    protected $dimentionWidth;
    protected $sourceFile;
    protected $cutPoints;

    protected $ffmpeg;

    protected $tempFilesToRemove = [];
    protected $tempFolder;

    public function __construct($sourceFile, $destionationFile,$numberOfFrames = 3, $totalDuration = 6,$dimentionWidth = 640,$config = [])
    {
        if(!isset($config['temp_dir'])){
            $config['temp_dir'] = sys_get_temp_dir();
        }

        $this->tempFolder = $config['temp_dir'];

        if(!is_dir($this->tempFolder)){
            if(!mkdir($this->tempFolder,0777,true)){
                $this->__destruct();
                throw new \Exception('Cannot create temp folder in path "'.$this->tempFolder.'"');
            }
        }

        $this->sourceFile = $sourceFile;
        $this->destination = $destionationFile;
        $this->numberOfFrames = $numberOfFrames;
        $this->totalDuration = $totalDuration;
        $this->dimentionWidth = $dimentionWidth;

        $ffmpeg = FFMpeg::create();
        $this->ffmpeg = $ffmpeg->open($this->sourceFile);

        $videoDuration = $this->getDuration();
        for($i=1;$i<=$this->numberOfFrames;$i++){
            $this->cutPoints[] = (($videoDuration / $this->numberOfFrames) - ($videoDuration / $this->numberOfFrames / 2)) * $i;
        }


    }

    protected function getDuration() : int {
        return $this->ffmpeg->getFFProbe()->format($this->sourceFile)->get('duration');
    }

    protected function getRatio(){

        $frame = $this->tempFolder.'/'.md5($this->sourceFile).'.jpg';

        if(!is_file($frame)) {
            $this->ffmpeg->frame(TimeCode::fromSeconds(0))->save($frame);
        }
        $dimentions = getimagesize($frame);
        $videoRatio = $dimentions[0] / $dimentions[1];
        $this->tempFilesToRemove[] = $frame;

        return $videoRatio;
    }

    public function __destruct()
    {
        foreach (array_unique($this->tempFilesToRemove) as $fileToRemove){
            if(is_file($fileToRemove)){
                unlink($fileToRemove);
            }
        }
    }
}