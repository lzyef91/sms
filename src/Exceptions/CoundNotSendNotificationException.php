<?php

namespace Nldou\SMS\Exceptions;

class CoundNotSendNotificationException extends Exception
{
    public static function serviceRespondedWithAnError($exception)
    {
        return $exception;
    }
}