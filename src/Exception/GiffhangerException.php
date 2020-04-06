<?php

namespace Jackal\Giffhanger\Exception;

class GiffhangerException extends \Exception
{
    public static function invalidExtension($extension) : GiffhangerException
    {
        return new GiffhangerException(sprintf('"%s" is not a valid extension', $extension));
    }

    public static function inputFileNotFoundOrNotReadable($filePath) : GiffhangerException
    {
        if (is_file($filePath)) {
            $filePath = realpath($filePath);
        }
        return new GiffhangerException(sprintf('File "%s" not found or not readable', $filePath));
    }

    public static function inputFileIsNotVideo($filePath, $mimeType) : GiffhangerException
    {
        return new GiffhangerException(sprintf('File "%s" is not a video file (actual mime-type: "%s")', $filePath, $mimeType));
    }
}
