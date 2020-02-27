<?php


namespace Jackal\Generator;


use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Jackal\FFMpeg\ext\Gif;
use Symfony\Component\Process\Process;

class GifGenerator extends VideoMP4Generator
{

    public function generate(){
        $originalDestionation = $this->destination;
        $this->destination = $this->destination.'-temp.avi';
        $this->tempFilesToRemove[] = $this->destination;
        parent::generate();

        $ffmpeg = FFMpeg::create();

        $video = $ffmpeg->open($this->destination);

        (new Gif($video, $video->getFFMpegDriver(), $video->getFFProbe(),TimeCode::fromSeconds(0),
            new Dimension($this->dimentionWidth, $this->dimentionWidth / $this->getRatio()), $this->getDuration()
        ))->save($originalDestionation);
        /*$video
            ->gif(
                TimeCode::fromSeconds(0),
                new Dimension($this->dimentionWidth, $this->dimentionWidth / $this->getRatio()), $this->getDuration()
            )->save($originalDestionation);
        */
    }

    // https://superuser.com/questions/556029/how-do-i-convert-a-video-to-gif-using-ffmpeg-with-reasonable-quality
    //
    // ffmpeg -ss 30 -t 3 -i input.mp4 -vf "fps=10,scale=320:-1:flags=lanczos,split[s0][s1];[s0]palettegen[p];[s1][p]paletteuse" -loop 0 output.gif
}