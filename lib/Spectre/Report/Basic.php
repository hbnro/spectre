<?php

namespace Spectre\Report;

class Basic
{
  public $status = -1;

  private $start;
  private $time;
  private $spec;

  public function __construct(array $report)
  {
    $this->spec = $report;
    $this->start = microtime(true);
  }

  public function __toString()
  {
    list($err, $all, $test) = $this->report($this->spec);

    $ok = $all - $err;
    $this->status = $err;

    $time = abs(round($this->start - microtime(true), 4));
    $test = join($test);

    if ($err) {
      return "$test\n$ok/$all ... fail with errors ({$time}s)\n";
    } else {
      return "$test\n$ok/$all ... everything is alright ({$time}s)\n";
    }
  }

  private function report($set, $depth = 0)
  {
    $out = array();
    $tests = 0;
    $errors = 0;
    $prefix = str_repeat('  ', $depth);

    foreach ($set['groups'] as $label => $sub) {
      $out []= "$prefix$label\n";

      if (!empty($sub['results'])) {
        foreach ($sub['results'] as $subject => $result) {
          $tests++;
          $errors += $result[0];
          $detail = $result[0] ? 'FAIL' : 'OK';
          $detail = $result[1] && $detail === 'FAIL' ? "$detail ($result[1])" : $detail;

          $out []= "$prefix- $subject ... $detail\n";
        }
      }

      if (!empty($sub['groups'])) {
        list($err, $all, $set) = $this->report($sub, $depth + 1);;

        foreach ($set as $k => $v) {
          $out []= $v;
        }

        $tests += $all;
        $errors += $err;
      }
    }

    return array($errors, $tests, $out);
  }
}
