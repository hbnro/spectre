<?php

namespace Spectre\Matchers;

class ToContain extends Base
{
  public function execute($value)
  {
    if (is_array($this->expected)) {
      return false !== @array_search($value, $this->expected);
    }

    return is_string($value) && $value &&
            is_string($this->expected) && $this->expected &&
            (false !== strpos($this->expected, $value));
  }
}
