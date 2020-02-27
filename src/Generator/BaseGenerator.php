<?php


namespace Jackal\Generator;

use FFMpeg\FFMpeg;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class BaseGenerator implements GeneratorInterface
{
    protected $destination;
    protected $sourceFile;
    protected $cutPoints;

    protected $options = [];
    private $tempFilesToRemove = [];

    public function __construct($sourceFile, $destionationFile,$options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'temp_dir' => sys_get_temp_dir(),
            'output_dimension' => 640,
            'frames' => 3,
            'duration' => 6,
            'bitrate' => 600,
            'frame_rate' => 10
        ]);

        $this->options = $resolver->resolve($options);

        if(!is_dir($this->getTempFolder())){
            if(!mkdir($this->getTempFolder(),0777,true)){
                $this->__destruct();
                throw new \Exception('Cannot create temp folder in path "'.$this->getTempFolder().'"');
            }
        }

        $this->sourceFile = $sourceFile;
        $this->destination = $destionationFile;

        $videoDuration = $this->getDuration();
        for($i=1;$i<=$this->getNumberOfFrames();$i++){
            $this->cutPoints[] = (($videoDuration / $this->getNumberOfFrames()) - ($videoDuration / $this->getNumberOfFrames() / 2)) * $i;
        }
    }

    protected function getDuration() : int {
        $ffmpeg = FFMpeg::create();
        $ffmpeg = $ffmpeg->open($this->sourceFile);
        return $ffmpeg->getFFProbe()->format($this->sourceFile)->get('duration');
    }

    protected function getRatio(){

        $ffmpeg = FFMpeg::create();
        $ffmpeg = $ffmpeg->open($this->sourceFile);
        return $ffmpeg->getStreams()->first()->getDimensions()->getRatio()->getValue();
    }

    public function __destruct()
    {
        foreach (array_unique($this->tempFilesToRemove) as $fileToRemove){
            if(is_file($fileToRemove)){
                unlink($fileToRemove);
            }
        }
    }

    protected function addTempFileToRemove($filePath){
        $this->tempFilesToRemove[] = $filePath;
    }

    protected function getTempFolder(){
        return $this->options['temp_dir'];
    }

    protected function getDimensionWidth(){
        return $this->options['output_dimension'];
    }

    protected function getNumberOfFrames(){
        return $this->options['frames'];
    }

    protected function getOutputDuration(){
        return $this->options['duration'];
    }

    protected function getVideoBitrate(){
        return $this->options['bitrate'];
    }

    protected function getFrameRate(){
        return $this->options['frame_rate'];
    }
}