<?php return function ($expected, $value = null)
{
  $last_error_reporting = error_reporting(-1);
  $last_error = error_get_last();

  $display_errors = ini_get('display_errors');
  ini_set('display_errors', 1);

  set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "\nerrno{{$errno}}\nerrstr{{$errstr}}\nerrfile{{$errfile}}\nerrline{{$errline}}\n";

    return true;
  });

  ob_start();
  $args = array_slice(func_get_args(), 2);
  call_user_func_array($expected, $args);
  $output = ob_get_clean();

  ini_set('display_errors', $display_errors);
  error_reporting($last_error_reporting);
  restore_error_handler();

  $parts = array();

  if (preg_match_all('/(\w+)\{(.+?)\}/s', $output, $matches)) {
    foreach (array_keys($matches[0]) as $key) {
      $parts[$matches[1][$key]] = $matches[2][$key];
    }
  }

  if (empty($parts['errno'])) {
    return false;
  } elseif (!func_num_args()) {
    return true;
  } elseif (is_numeric($value)) {
    return $parts['errno'] == $value;
  }

  return false !== strpos($parts['errstr'], $value);
};
