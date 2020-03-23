<?php


namespace Jackal\Giffhanger\Tests\Functional;


use Jackal\Giffhanger\Giffhanger\Giffhanger;

class GiffhagerFunctionalTest extends BaseFFMpegFunctionalTest
{
    protected $fileOutput1 = __DIR__.'/testCreateVideoDefault.avi';
    protected $fileOutput2 = __DIR__.'/testCropVideo.avi';
    protected $fileOutput3 = __DIR__.'/testSinglePiece.avi';

    protected function tearDown(): void
    {
        parent::tearDown();

        $files = [
            $this->fileOutput1,$this->fileOutput2,$this->fileOutput3
        ];

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function testCreateVideoDefault(){

        $gif = new Giffhanger(__DIR__.'/../samples/dolbycanyon.avi');
        $gif->generate($this->fileOutput1);

        $this->assertFileExists($this->fileOutput1);
        $this->assertEquals(6,$this->getVideoDuration($this->fileOutput1));
        $this->assertEquals(640,$this->getVideoWidth($this->fileOutput1));
        $this->assertEquals(480,$this->getVideoHeight($this->fileOutput1));
    }

    public function testCropVideo(){

        $gif = new Giffhanger(__DIR__.'/../samples/dolbycanyon.avi',[
            'crop_ratio' => 16/9
        ]);
        $gif->generate($this->fileOutput2);

        $this->assertFileExists($this->fileOutput2);
        $this->assertEquals(6,$this->getVideoDuration($this->fileOutput2));
        $this->assertEquals(640,$this->getVideoWidth($this->fileOutput2));
        $this->assertEquals(360,$this->getVideoHeight($this->fileOutput2));
    }

    public function testSinglePiece(){

        $gif = new Giffhanger(__DIR__.'/../samples/dolbycanyon.avi',[
            'frames' => 1
        ]);
        $gif->generate($this->fileOutput3);

        $this->assertFileExists($this->fileOutput3);
        $this->assertEquals(6,$this->getVideoDuration($this->fileOutput3));
        $this->assertEquals(640,$this->getVideoWidth($this->fileOutput3));
        $this->assertEquals(480,$this->getVideoHeight($this->fileOutput3));
    }
}