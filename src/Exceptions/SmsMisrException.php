<?php

namespace VantomDev\SmsMisr\Exceptions;

use Exception;

class SmsMisrException extends Exception
{
    public static function invalidResponse($message)
    {
        return new static("SmsMisr Invalid Response: " . $message);
    }
}
