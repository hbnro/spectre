<?php

namespace Spectre;

class Base {
  private static $spectre;

  public static function instance()
  {
    if (!static::$spectre) {
      static::$spectre = new \Spectre\Spec\Base;
    }

    return static::$spectre;
  }

  public static function execute(\Closure $test, $node)
  {
    if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
      $test = $test->bindTo($node->context);
    }

    $fun = new \ReflectionFunction($test);
    $args = array();

    foreach ($fun->getParameters() as $param) {
      $args []= $node->context->{$param->getName()};
    }

    try {
      return array(0, call_user_func_array($test, $args));
    } catch (\Exception $e) {
      return array(1, $e->getMessage());
    }
  }
}
