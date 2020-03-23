<?php


namespace Jackal\Giffhanger\FFMpeg\ext\Media;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFProbe;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Media\Video;

class Gif extends \FFMpeg\Media\Gif
{
    /** @var TimeCode */
    private $timecode;
    /** @var Dimension */
    private $dimension;
    /** @var integer */
    private $duration;
    /** @var Video */
    private $video;

    public function __construct(Video $video, FFMpegDriver $driver, FFProbe $ffprobe, TimeCode $timecode, Dimension $dimension, $duration = null)
    {
        parent::__construct($video, $driver, $ffprobe, $timecode, $dimension, $duration);
        $this->timecode = $timecode;
        $this->dimension = $dimension;
        $this->duration = $duration;
        $this->video = $video;
    }

    /**
     * Saves the gif in the given filename.
     *
     * @param string  $pathfile
     *
     * @return Gif
     *
     * @throws RuntimeException
     */
    public function save($pathfile)
    {
        /**
         * @see http://ffmpeg.org/ffmpeg.html#Main-options
         */
        $commands = array(
            '-ss', (string)$this->timecode
        );

        if (null !== $this->duration) {
            $commands[] = '-t';
            $commands[] = (string)$this->duration;
        }

        $commands[] = '-i';
        $commands[] = $this->pathfile;
        $commands[] = '-vf';
        $commands[] = 'scale=' . $this->dimension->getWidth() . ':-1:flags=lanczos,split[s0][s1];[s0]palettegen[p];[s1][p]paletteuse';
        $commands[] = '-gifflags';
        $commands[] = '+transdiff';
        $commands[] = '-y';

        foreach ($this->filters as $filter) {
            $commands = array_merge($commands, $filter->apply($this));
        }

        $commands = array_merge($commands, array($pathfile));

        try {
            $this->driver->command($commands);
        } catch (ExecutionFailureException $e) {
            $this->cleanupTemporaryFile($pathfile);
            throw new RuntimeException('Unable to save gif', $e->getCode(), $e);
        }

        return $this;
    }
}
