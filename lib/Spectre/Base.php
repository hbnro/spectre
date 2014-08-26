<?php

namespace Spectre;

class Base
{
  private static $spectre;
  private static $matchers = [];

  public static function addMatcher($klass, $method = null)
  {
    if (!$method) {
      $method = explode('\\', $klass);
      $method = array_pop($method);
    }

    static::$matchers[$method] = $klass;
  }

  public static function customMatchers()
  {
    return static::$matchers;
  }

  public static function __callStatic($method, array $arguments)
  {
    if (!static::$spectre) {
      static::$spectre = new \Spectre\Spec\Base;
    }

    return call_user_func_array(array(static::$spectre, $method), $arguments);
  }
}
