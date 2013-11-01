<?php

namespace Habanero\Spectre\Matchers;

class ToStartWith extends Base
{
  public function execute($value)
  {
    return is_string($value) && $value &&
            is_string($this->expected) && $this->expected &&
            (0 === strpos($this->expected, $value));
  }
}
