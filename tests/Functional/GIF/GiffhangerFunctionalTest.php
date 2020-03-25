<?php


namespace Jackal\Giffhanger\Tests\Functional\GIF;


use Jackal\Giffhanger\Giffhanger\Giffhanger;
use Jackal\Giffhanger\Tests\Functional\BaseFFMpegFunctionalTest;

class GiffhangerFunctionalTest extends BaseFFMpegFunctionalTest
{
    protected $fileInput1 = __DIR__.'/../../samples/dolbycanyon.avi';

    protected $fileOutput1 = __DIR__.'/testCreateGIF.gif';
    protected $fileOutput2 = __DIR__.'/testCropGIF.gif';

    protected function tearDown(): void
    {
        parent::tearDown();

        $files = [
            $this->fileOutput1,$this->fileOutput2
        ];

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function testCreateGIF(){

        $gif = new Giffhanger($this->fileInput1);
        $gif->generate($this->fileOutput1);

        $this->assertFileExists($this->fileOutput1);
        $this->assertEquals(640,$this->getGIFWidth($this->fileOutput1));
        $this->assertEquals(480,$this->getGIFHeight($this->fileOutput1));
        $this->assertCount(61,$this->getGIFFrames($this->fileOutput1));
    }

    public function testCropGIF(){

        $gif = new Giffhanger($this->fileInput1,[
            'crop_ratio' => 16/9
        ]);
        $gif->generate($this->fileOutput2);

        $this->assertFileExists($this->fileOutput2);
        $this->assertCount(61,$this->getGIFFrames($this->fileOutput2));
        $this->assertEquals(640,$this->getGIFWidth($this->fileOutput2));
        $this->assertEquals(360,$this->getGIFHeight($this->fileOutput2));
    }
}