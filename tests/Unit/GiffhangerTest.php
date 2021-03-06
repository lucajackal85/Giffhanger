<?php

namespace Jackal\Giffhanger\Tests\Unit;

use Jackal\Giffhanger\Exception\GiffhangerException;
use Jackal\Giffhanger\Giffhanger\Giffhanger;
use PHPUnit\Framework\TestCase;

class GiffhangerTest extends TestCase
{
    public function testGiffhangerNoOptions(){

        $gif = new Giffhanger(__DIR__ . '/../samples/dolbycanyon.avi');
        $this->assertInstanceOf(Giffhanger::class, $gif);
    }

    public function testRaiseExceptionOnInvalidExtension(){
        $this->expectException(GiffhangerException::class);
        $this->expectExceptionMessage('"ext" is not a valid extension');

        $gif = new Giffhanger(__DIR__ . '/../samples/dolbycanyon.avi');
        $gif->generate(__DIR__ . 'file.ext');
    }

    public function testRaiseExceptionOnFileNotFound(){
        $this->expectException(GiffhangerException::class);
        $this->expectExceptionMessage('File "' . __DIR__ . '/invalid-file.avi" not found or not readable');

        $gif = new Giffhanger(__DIR__ . '/invalid-file.avi');
        $gif->generate(__DIR__ . 'file.avi');
    }
}