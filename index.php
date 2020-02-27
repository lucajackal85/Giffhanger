<?php

use Jackal\Downloader\DownloaderFactory;
use Jackal\Giffhanger\Giffhanger;
use \Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Process;

require 'vendor/autoload.php';

$youtubeVideoId = 'yHV7CWHuCPY';

$tmpFolder = __DIR__.'/videos/';

if(!is_dir($tmpFolder)){
    mkdir($tmpFolder);
}
$fileName = $tmpFolder.$youtubeVideoId;

if(!is_file($fileName)) {
    $video = DownloaderFactory::getInstance(DownloaderFactory::TYPE_YOUTUBE,$youtubeVideoId);
    $video->download($fileName);
}

$giffhanger = new Giffhanger($fileName,[
    'temp_dir' => __DIR__.'/temp',
    'output_dimension' => 320
]);

$giffhanger->generateGIF(__DIR__.'/output.gif');
$giffhanger->generateVideo(__DIR__.'/output.avi');



