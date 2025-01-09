<?php

namespace Endrika73\LaravelRepository\Facades;

use Endrika73\LaravelRepository\Repository as BaseClass;
use Illuminate\Support\Facades\Facade;

class Repository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BaseClass::class;
    }
}