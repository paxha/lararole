<?php

namespace Lararole\Facades;

use Illuminate\Support\Facades\Facade;

class Role extends Facade
{
    public static function __callStatic($name, $arguments)
    {
        return (self::resolveFacade('role'))->$name(...$arguments);
    }

    protected static function resolveFacade($name)
    {
        return app($name);
    }
}
