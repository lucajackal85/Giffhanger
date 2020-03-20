<?php


namespace Jackal\Giffhanger\Exception;


class GiffhangerConfigurationException extends \Exception
{
    public static function invalidPositiveIntegerValue($value){
        throw new GiffhangerConfigurationException(sprintf('value "%s" is not a valid value',$value));
    }

    public static function invalidOption($message){
        throw new GiffhangerConfigurationException($message);
    }
}