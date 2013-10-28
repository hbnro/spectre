<?php

require dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
require __DIR__.DIRECTORY_SEPARATOR.'spec.php';


$report = run_specs();
$tap = new \Spectre\Report\TAP($report);

echo $tap;
exit((int)(!!$tap->status));
