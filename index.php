<?php

use Jackal\Downloader\DownloaderFactory;
use Jackal\Giffhanger\Giffhanger;
use \Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Process;

require 'vendor/autoload.php';

$youtubeVideoId = '8SAY4UR4UR8';

$tmpFolder = __DIR__.'/videos/';

if(!is_dir($tmpFolder)){
    mkdir($tmpFolder);
}
$fileName = $tmpFolder.$youtubeVideoId;

$output = new ConsoleOutput();

if(!is_file($fileName)) {
    $output->write('Downloading video... ');
    $video = DownloaderFactory::getInstance(DownloaderFactory::TYPE_YOUTUBE,$youtubeVideoId);
    $video->download($fileName);
}else{
    $output->write('Getting video from cache... ');
}

$output->writeln('Done!');

$giffhanger = new Giffhanger($fileName,[
    'temp_dir' => __DIR__.'/temp'
]);

$numberOfFrames = 3;
$duration = 6;
$dimentionWidth = 320;

$output->write('Creating GIF ('.$numberOfFrames.' frames)... ');
$giffhanger->generateGIF(__DIR__.'/output.gif',$numberOfFrames,$duration,$dimentionWidth);
$output->writeln(' Done!');
$output->write('Creating AVI ('.$numberOfFrames.' frames)... ');
$giffhanger->generateVideo(__DIR__.'/output.avi',$numberOfFrames,$duration,$dimentionWidth);
$output->writeln(' Done!');



