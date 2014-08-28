<?php

namespace Spectre\Matchers;

class ToPrint extends Base
{
  public function execute($value)
  {
    ob_start();
    $args = array_slice(func_get_args(), 1);
    print_r(is_callable($this->expected) ? call_user_func_array($this->expected, $args) : $this->expected);
    $test = ob_get_clean();

    return false !== strpos($test, $value);
  }
}
