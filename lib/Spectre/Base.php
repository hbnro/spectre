<?php

namespace Spectre;

class Base
{
  public static $node;

  private static $spectre;
  private static $matchers = array();

  public static function customMatchers($method = null, \Closure $callback = null)
  {
    if (!func_num_args()) {
      return static::$matchers;
    }

    if (!is_array($method)) {
      $method = array($method => $callback);
    }

    foreach ($method as $name => $fn) {
      if (!($fn instanceof \Closure)) {
        throw new \Exception("Cannot use '$fn' as matcher");
      }

      static::$matchers[$name] = $fn;
    }
  }

  public static function __callStatic($method, array $arguments)
  {
    if (!static::$spectre) {
      static::$spectre = new \Spectre\Spec\Base;
    }

    return call_user_func_array(array(static::$spectre, $method), $arguments);
  }
}
