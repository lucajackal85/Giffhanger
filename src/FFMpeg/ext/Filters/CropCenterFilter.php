<?php


namespace Jackal\Giffhanger\FFMpeg\ext\Filters;

use FFMpeg\Filters\Video\VideoFilterInterface;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

class CropCenterFilter implements VideoFilterInterface
{
    protected $priority;

    protected $cropRatio;

    public function __construct($cropRatio, $priority = 0)
    {
        $this->priority = $priority;
        $this->cropRatio = $cropRatio;
    }

    /**
     * Returns the priority of the filter.
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Applies the filter on the the Video media given an format.
     *
     * @param Video $video
     * @param VideoInterface $format
     *
     * @return array An array of arguments
     */
    public function apply(Video $video, VideoInterface $format)
    {
        $videoWidth = 0;
        $videoHeight = 0;
        $videoRatio = 0;

        foreach ($video->getStreams()->videos() as $stream) {
            if ($stream->has('width') && $stream->has('height')) {
                $videoWidth = $stream->get('width');
                $videoHeight = $stream->get('height');
                $videoRatio = $videoWidth / $videoHeight;
                break;
            }
        }

        if ($this->cropRatio > $videoRatio) {
            $cropWidth = $videoWidth;
            $cropHeight = round($videoWidth / $this->cropRatio);
        } else {
            $cropHeight = $videoHeight;
            $cropWidth = round($videoHeight * $this->cropRatio);
        }

        $cropX = round(($videoWidth - $cropWidth) / 2);
        $cropY = round(($videoHeight - $cropHeight) / 2);


        foreach ($video->getStreams()->videos() as $stream) {
            if ($stream->has('width') && $stream->has('height')) {
                $stream->set('width', $cropWidth);
                $stream->set('height', $cropHeight);
            }
        }

        return array(
            '-filter:v',
            'crop=' .
            $cropWidth .':' . $cropHeight . ':' . $cropX . ':' . $cropY
        );
    }
}
