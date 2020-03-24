<?php


namespace Jackal\Giffhanger\Tests\Functional\Video;


use Jackal\Giffhanger\Giffhanger\Giffhanger;
use Jackal\Giffhanger\Tests\Functional\BaseFFMpegFunctionalTest;

class GiffhagerFunctionalTest extends BaseFFMpegFunctionalTest
{
    protected $fileInput1 = __DIR__.'/../../samples/dolbycanyon.avi';

    protected $fileOutput1 = __DIR__.'/testCreateVideoDefault.avi';
    protected $fileOutput2 = __DIR__.'/testCropVideo.mp4';
    protected $fileOutput3 = __DIR__.'/testSinglePiece.ogg';
    protected $fileOutput4 = __DIR__.'/testCreateVideoWebM.webm';

    protected function tearDown(): void
    {
        parent::tearDown();

        $files = [
            $this->fileOutput1,$this->fileOutput2,$this->fileOutput3,$this->fileOutput4
        ];

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function testCreateVideoDefault(){

        $gif = new Giffhanger($this->fileInput1);
        $gif->generate($this->fileOutput1);

        $this->assertFileExists($this->fileOutput1);
        $this->assertEquals(6,$this->getVideoDuration($this->fileOutput1));
        $this->assertEquals(640,$this->getVideoWidth($this->fileOutput1));
        $this->assertEquals(480,$this->getVideoHeight($this->fileOutput1));
    }

    public function testCropVideo(){

        $gif = new Giffhanger($this->fileInput1,[
            'crop_ratio' => 16/9
        ]);
        $gif->generate($this->fileOutput2);

        $this->assertFileExists($this->fileOutput2);
        $this->assertEquals(6,$this->getVideoDuration($this->fileOutput2));
        $this->assertEquals(640,$this->getVideoWidth($this->fileOutput2));
        $this->assertEquals(360,$this->getVideoHeight($this->fileOutput2));
    }

    public function testSinglePiece(){

        $gif = new Giffhanger($this->fileInput1,[
            'frames' => 1
        ]);
        $gif->generate($this->fileOutput3);

        $this->assertFileExists($this->fileOutput3);
        $this->assertEquals(6,$this->getVideoDuration($this->fileOutput3));
        $this->assertEquals(640,$this->getVideoWidth($this->fileOutput3));
        $this->assertEquals(480,$this->getVideoHeight($this->fileOutput3));
    }

    public function testCreateVideoWebM(){

        $gif = new Giffhanger($this->fileInput1);
        $gif->generate($this->fileOutput4);

        $this->assertFileExists($this->fileOutput4);
        $this->assertEquals(6,$this->getVideoDuration($this->fileOutput4));
        $this->assertEquals(640,$this->getVideoWidth($this->fileOutput4));
        $this->assertEquals(480,$this->getVideoHeight($this->fileOutput4));
    }
}