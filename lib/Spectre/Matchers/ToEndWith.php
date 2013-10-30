<?php

namespace Spectre\Matchers;

class ToEndWith extends Base
{
  public function execute($value)
  {
    return is_string($value) && $value &&
            is_string($this->expected) && $this->expected &&
            ($value === substr($this->expected, -strlen($value)));
  }
}
