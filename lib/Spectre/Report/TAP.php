<?php

namespace Spectre\Report;

class TAP
{
  public $status = -1;

  public function __construct(array $report)
  {
    $this->spec = $report;
  }

  public function __toString()
  {
    $out = array();

    list($err, $all, $test) = $this->report($this->spec);

    $this->status = $err;

    if (!empty($test)) {
      foreach ($test as $k => $v) {
        if (preg_match('/^(not )?ok\b/', $v)) {
          list($pre, $suff) = explode('-', $v, 2);

          $nth = $k + 1;
          $out []= "$pre$nth -$suff";
        } else {
          $out []= $v;
        }
      }
    }

    $out []= "\n1..$all";
    $out []= "\n# tests $all";
    $out []= "\n# pass  " . ($all - $err);
    $out []= "\n# fail  $err";
    $out []= "\n\n# ";
    $out []= $err ? 'not ' : '';
    $out []= "ok\n";

    return join($out);
  }

  private function report(array $set, $top = '')
  {
    $out = array();
    $tests = 0;
    $errors = 0;

    foreach ($set['groups'] as $label => $sub) {
      $prefix = $top ? "$top $label" : $label;

      if (!empty($sub['results'])) {
        foreach ($sub['results'] as $subject => $result) {
          $tests++;
          $errors += $result[0];
          $detail = $result[0] ? 'not ok' : 'ok';

          $out []= "$detail - $prefix $subject\n";

          if ($result[1]) {
            $out []= "  ---\n  message: >\n    $result[1]\n  ---\n";
          }
        }
      }

      if (!empty($sub['groups'])) {
        list($err, $all, $set) = $this->report($sub, $prefix);

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
