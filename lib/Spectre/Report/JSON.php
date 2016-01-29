<?php

namespace Spectre\Report;

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

        return json_encode(array(
      'tests' => $all,
      'errors' => $err,
      'success' => $all - $err,
      'results' => $this->spec,
    ), JSON_NUMERIC_CHECK | (defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 0))."\n";
    }

    private function report(array $set)
    {
        $tests = 0;
        $errors = 0;

        foreach ($set['groups'] as $label => $sub) {
            if (!empty($sub['results'])) {
                foreach ($sub['results'] as $subject => $fails) {
                    ++$tests;
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
}
