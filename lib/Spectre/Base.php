<?php

namespace Spectre;

class Base
{
    public static $node;

    private static $spectre;

    public static function __callStatic($method, array $arguments)
    {
        if (!static::$spectre) {
            static::$spectre = new \Spectre\Spec\Base();
        }

        return call_user_func_array(array(static::$spectre, $method), $arguments);
    }
}
