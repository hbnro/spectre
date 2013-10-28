<?php

error_reporting(-1);

require dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

call_user_func(function () {
  $files = glob(__DIR__.'/*-spec.php');

  foreach ($files as $spec) {
    require $spec;
  }

  run_specs(function ($report) {
    $tap = new \Spectre\Report\TAP($report);

    echo $tap;
    exit((int)(!!$tap->status));
  });
});
