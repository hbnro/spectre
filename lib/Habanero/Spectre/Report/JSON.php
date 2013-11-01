<?php

namespace Habanero\Spectre\Report;

class JSON
{
  public $status = -1;

  public function __construct(array $report)
  {
    $this->spec = $report;
  }

  public function __toString()
  {
    list($err, $all) = $this->report($this->spec);

    $this->status = $err;

    return $this->format(array(
      'tests' => $all,
      'errors' => $err,
      'success' => $all - $err,
      'results' => $this->spec,
    )) . "\n";
  }

  private function report(array $set)
  {
    $tests = 0;
    $errors = 0;

    foreach ($set['groups'] as $label => $sub) {
      if (!empty($sub['results'])) {
        foreach ($sub['results'] as $subject => $fails) {
          $tests++;
          $errors += sizeof($fails);
        }
      }

      if (!empty($sub['groups'])) {
        list($err, $all) = $this->report($sub);

        $tests += $all;
        $errors += $err;
      }
    }

    return array($errors, $tests);
  }

  private function format($data, $depth = 1)
  {
    $out = array();
    $tabs = str_repeat('  ', $depth);
    $assoc = is_object($data) || is_string(key($data));

    foreach ($data as $key => $val) {
      $key = json_encode($key, JSON_NUMERIC_CHECK);

      if (is_object($val) || is_array($val)) {
        $val = $this->format($val, $depth + 1);
      } else {
        $val = json_encode($val);
      }

      $out []= $assoc ? "$tabs$key: $val" : $val;
    }

    if ($assoc) {
      return "{\n" . join(",\n", $out) . '}';
    } else {
      return '[' . join(',', $out) . ']';
    }
  }
}
