<?php

namespace Spectre\Matchers;

class ToWarn extends Base
{
  public function execute($value)
  {
    $last_error_reporting = error_reporting(-1);
    $last_error = error_get_last();

    $display_errors = ini_get('display_errors');
    ini_set('display_errors', 1);

    set_error_handler(function($errno, $errstr, $errfile, $errline) {
      echo "\n$errstr\n$errfile\n";

      return true;
    });

    ob_start();
    $args = array_slice(func_get_args(), 1);
    call_user_func_array($this->expected, $args);
    $output = ob_get_clean();

    ini_set('display_errors', $display_errors);
    error_reporting($last_error_reporting);
    restore_error_handler();

    return false !== strpos($output, $value);
  }
}
