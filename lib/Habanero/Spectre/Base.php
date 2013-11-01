<?php

namespace Habanero\Spectre;

use Habanero\Spectre\Spec\Base as Spec;

class Base
{
  private static $spectre;

  public static function __callStatic($method, array $arguments)
  {
    if (!static::$spectre) {
      static::$spectre = new Spec;
    }

    return call_user_func_array(array(static::$spectre, $method), $arguments);
  }
}
