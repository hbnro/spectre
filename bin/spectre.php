<?php

error_reporting(-1);

require dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

call_user_func(function () use ($argv) {
  array_shift($argv);

  $args = array();
  $input = array();

  foreach ($argv as $i => $one) {
    if (substr($one, 0, 1) !== '-') {
      if (is_dir($one)) {
        foreach (glob("$one/*-spec.php") as $file) {
          require realpath($file);
        }
      } else {
        require realpath($one);
      }
    } elseif (preg_match('/--reporter=(\w+)|-r(\w+)/', $one, $match)) {
      $args['reporter'] = !empty($match[2]) ? $match[2] : $match[1];
    }
  }


  run_specs(function ($report) use($args) {
    if (!$report) {
      echo "Missing specs\n";
      exit(1);
    }

    $reporter = !empty($args['tap']) ? 'TAP' : (!empty($args['reporter']) ? $args['reporter'] : 'Basic');
    $klass = "\\Spectre\\Report\\$reporter";
    $tap = new $klass($report);

    echo $tap;
    exit((int)(!!$tap->status));
  });
});

