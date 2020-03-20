<?php


namespace Jackal\Giffhanger\Tests\Unit\Configuration;


use Jackal\Giffhanger\Configuration\Configuration;
use Jackal\Giffhanger\Exception\GiffhangerConfigurationException;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    public function testDefaultConfiguration(){

        $conf = new Configuration();
        $this->assertEquals(null, $conf->getCropRatio());
        $this->assertEquals(sys_get_temp_dir(),$conf->getTempFolder());
        $this->assertEquals(640,$conf->getDimensionWidth());
        $this->assertEquals(3,$conf->getNumberOfFrames());
        $this->assertEquals(6,$conf->getOutputDuration());
        $this->assertEquals(600,$conf->getVideoBitrate());
        $this->assertEquals(10,$conf->getFrameRate());
    }

    public function testRaiseExceptionOnInvalidOption(){

        $this->expectException(GiffhangerConfigurationException::class);
        $this->expectExceptionMessage('The option "invalid" does not exist. Defined options are: "bitrate", "crop_ratio", "duration", "frame_rate", "frames", "resize_width", "temp_dir".');

        $conf = new Configuration([
            'invalid' => 1
        ]);
    }
}