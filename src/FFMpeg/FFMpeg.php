<?php


namespace Jackal\FFMpeg;


class FFMpeg
{
    protected $videoPath;
    protected $video;
    protected $videoRatio;

    public function __construct($videoPath)
    {
        $this->videoPath = $videoPath;

        $frame = sys_get_temp_dir().'/'.uniqid(true);

        $ffmpeg = \FFMpeg\FFMpeg::create();
        $this->video = $ffmpeg->open($this->videoPath);
        $this->video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(0))->save($frame);
        $dimentions = getimagesize($frame);
        $this->videoRatio = $dimentions[0] / $dimentions[1];
        unlink($frame);
    }

    public function getDuration() : int {
        return $this->video->getFFProbe()->format($this->videoPath)->get('duration');
    }

    public function createGif($filename, $fromSeconds = 0,$duration = 2, $dimention = 320){
        $this->video
            ->gif(
                \FFMpeg\Coordinate\TimeCode::fromSeconds($fromSeconds),
                new \FFMpeg\Coordinate\Dimension($dimention, 320 / $this->videoRatio), $duration
            )->save($filename);
    }
}