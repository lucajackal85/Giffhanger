<?php


namespace Jackal\Downloader;


class DownloaderFactory
{
    const TYPE_YOUTUBE = 'youtube';

    /**
     * @param $videoType
     * @param $id
     * @return YoutubeDownloader
     * @throws \Exception
     */
    public static function getInstance($videoType,$id)
    {
        switch ($videoType) {
            case self::TYPE_YOUTUBE :{
                return new YoutubeDownloader($id,360);
            }
            default:
                throw new \Exception('Invalid video_type ['.$videoType.']');
        }
    }
}