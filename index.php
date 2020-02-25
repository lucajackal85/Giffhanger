<?php

//https://superuser.com/questions/556029/how-do-i-convert-a-video-to-gif-using-ffmpeg-with-reasonable-quality

use Jackal\Downloader\DownloaderFactory;
use \Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Process\Process;

require 'vendor/autoload.php';

$youtubeVideoId = 'H-0UGyseHvE';

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


$numberOfGif = 3;

$output->write('Creating GIF ('.$numberOfGif.' frames)... ');
$ffmpeg = new \Jackal\FFMpeg\FFMpeg($fileName);
$duration = $ffmpeg->getDuration();

$files = [];
for($i=1;$i<=$numberOfGif;$i++){
    $point = (($duration / $numberOfGif) - ($duration / $numberOfGif / 2)) * $i;
    $filename = __DIR__.'/output'.$i.'.gif';
    $ffmpeg->createGif($filename,$point);
    $files[] = $filename;
}

if($numberOfGif > 1) {
    $cmd = 'gifsicle --merge ' . implode(' ', $files) . ' -o output.gif';

    $p = Process::fromShellCommandline($cmd);
    $p->run();

    if ($p->getErrorOutput()) {
        throw new \Exception($p->getErrorOutput());
    }

    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
}else{
    copy('output1.gif','output.gif');
    unlink('output1.gif');
}
$output->writeln('Done!');


