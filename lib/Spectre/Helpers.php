<?php

namespace Spectre;

class Helpers
{
  public static function execute(array $test, $node, $coverage, $logger, $description, $indentation)
  {
    $err = array();

    $coverage && $coverage->start("$node->description $description");

    foreach ($test as $callback) {
      Base::$node = $node;

      if ($result = is_callable($callback)) {
        try {
          static::invoke($callback, $node);
        } catch (\Exception $e) {
          $result = false;
          $err []= $e->getMessage();
        }
      } else {
        $result = null;
      }

      if ($logger) {
        $icon = null === $result ? '↺' : ($result ? '✓' : '✗');
        $color = null === $result ? 'cyan' : ($result ? 'green' : 'red');
        $status = null === $result ? 'PENDING' : ($result ? 'OK' : 'FAIL');

        call_user_func($logger, $color, $indentation, "$icon $description ... $status", end($err));
      }
    }

    $coverage && $coverage->stop();

    return $err;
  }

  public static function invoke(\Closure $block, $node)
  {
    $func = new \ReflectionFunction($block);
    $vars = $node->values();
    $args = array();

    foreach ($func->getParameters() as $param) {
      $args []= isset($vars[$param->getName()]) ? $vars[$param->getName()] : null;
    }

    call_user_func_array($block, $args);
  }

  public static function scalar($args)
  {
    $out = array();

    foreach ($args as $one) {
      $type = gettype($one);
      $one = static::value($one);

      $out []= strlen($one) ? "($type) $one" : "($type)";
    }

    return $out;
  }

  public static function value($test)
  {
    if (is_array($test)) {
      $test = join(', ', static::scalar($test));
      $test = sizeof($test) > 1 ? "[$test]" : $test;
    } elseif (is_scalar($test)) {
      $test = false === $test ? 'false' : $test;
      $test = true === $test ? 'true' : $test;
      $test = null === $test ? 'null' : $test;
    } elseif ($test instanceof \Closure) {
      $test = '{closure}';
    } elseif (is_object($test)) {
      $test = get_class($test);
    } else {
      $test = (string) $test;
    }

    return $test;
  }

  public static function secs($since)
  {
    $hours = floor($since / 3600);
    $mins = floor($since % 3600 /60);
    $now = sprintf('%d:%02d:%02d', $hours, $mins, $since % 60);
    $now = preg_replace('/^0+:/', '', $now);

    return $now;
  }
}
