<?php

namespace Habanero\Spectre\Matchers;

class Is extends Base
{
  public function execute()
  {
    $args = func_get_args();
    $type = array_shift($args);

    array_unshift($args, $this->expected);

    $this->negative = "Expected is_$type('{subject}') to be true, but it does not";
    $this->positive = "Not expected is_$type('{subject}') to be true, but it does";

    return @call_user_func_array("is_$type", $args);
  }
}
