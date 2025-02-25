<?php

namespace VantomDev\SmsMisr\Facades;

use Illuminate\Support\Facades\Facade;

class SmsMisr extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'smsmisr';
    }
}
