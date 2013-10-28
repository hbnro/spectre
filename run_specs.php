<?php

error_reporting(-1);

require 'vendor/autoload.php';

call_user_func(function () {
  $files = glob('specs/*-spec.php');

  foreach ($files as $spec) {
    require $spec;
  }

  run_specs(function ($report) {
    $tap = new \Spectre\Report\TAP($report);

    echo $tap;
    exit((int)(!!$tap->status));
  });
});
