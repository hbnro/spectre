<?php

namespace Spectre\Matchers;

class ToPrint extends Base
{
  public function execute($value)
  {
    ob_start();
    $args = func_get_args();
    echo is_callable($this->expected) ? call_user_func_array($this->expected, $args) : $this->expected;
    $test = ob_get_clean();

    return $test === $value;
  }
}
