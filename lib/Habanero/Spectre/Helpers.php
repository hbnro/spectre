<?php

namespace Habanero\Spectre;

class Helpers
{
  public static function execute(array $test, $node)
  {
    $err = array();

    foreach ($test as $callback) {
      $fun = new \ReflectionFunction($callback);
      $args = array();

      foreach ($fun->getParameters() as $param) {
        $args []= $node->context->{$param->getName()};
      }

      try {
        call_user_func_array($callback, $args);
      } catch (\Exception $e) {
        $err []= $e->getMessage();
      }
    }

    return $err;
  }

  public static function scalar($args)
  {
    $out = array();

    foreach ($args as $one) {
      $type = gettype($one);

      if (is_array($one)) {
        $one = static::scalar($out);
      } elseif (is_scalar($one)) {
        $one = false === $one ? 'false' : $one;
        $one = true === $one ? 'true' : $one;
        $one = null === $one ? 'null' : $one;
      } elseif ($one instanceof \Closure) {
        $one = '{closure}';
      } elseif (is_object($one)) {
        $one = get_class($one);
      }

      $out []= strlen($one) ? "($type) $one" : "($type)";
    }

    $out = join(', ', $out);
    $out = sizeof($args) > 1 ? "[$out]" : $out;

    return $out;
  }
}
