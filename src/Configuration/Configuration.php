<?php


namespace Jackal\Giffhanger\Configuration;


use Jackal\Giffhanger\Exception\GiffhangerConfigurationException;
use Jackal\Giffhanger\Exception\GiffhangerException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Configuration
{
    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * Configuration constructor.
     * @param array $configuration
     * @throws GiffhangerConfigurationException
     */
    public function __construct($configuration = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'temp_dir' => sys_get_temp_dir(),
            'resize_width' => 640,
            'crop_ratio' => null,
            'frames' => 3,
            'duration' => 6,
            'bitrate' => 600,
            'frame_rate' => 10
        ]);

        try {
            $this->configuration = $resolver->resolve($configuration);

            $this->assertPositiveIntegerNumber($this->getDimensionWidth());
            $this->assertPositiveIntegerNumber($this->getFrameRate());
            $this->assertPositiveIntegerNumber($this->getNumberOfFrames());
            $this->assertPositiveIntegerNumber($this->getOutputDuration());
            $this->assertPositiveIntegerNumber($this->getVideoBitrate());

        }catch (UndefinedOptionsException $e){
            throw GiffhangerConfigurationException::invalidOption($e->getMessage());
        }
    }

    protected function assertPositiveIntegerNumber($value){
        if(!is_numeric($value) or $value < 0){
            throw GiffhangerConfigurationException::invalidPositiveIntegerValue($value);
        }
    }

    public function getTempFolder(){
        return $this->configuration['temp_dir'];
    }

    public function getDimensionWidth(){
        return $this->configuration['resize_width'];
    }

    public function getNumberOfFrames(){
        return $this->configuration['frames'];
    }

    public function getOutputDuration(){
        return $this->configuration['duration'];
    }

    public function getVideoBitrate(){
        return $this->configuration['bitrate'];
    }

    public function getCropRatio(){
        return $this->configuration['crop_ratio'];
    }

    public function getFrameRate(){
        return $this->configuration['frame_rate'];
    }
}