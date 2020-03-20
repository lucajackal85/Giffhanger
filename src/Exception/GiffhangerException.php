<?php


namespace Jackal\Giffhanger\Exception;


class GiffhangerException extends \Exception
{
    public static function invalidExtension($extension){
        throw new GiffhangerException(sprintf('"%s" is not a valid extension',$extension));
    }
}