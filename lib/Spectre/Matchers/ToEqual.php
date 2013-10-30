<?php

namespace Spectre\Matchers;

class ToEqual extends Base
{
  public function execute($value)
  {
    return (is_scalar($this->expected) && is_scalar($value)) && ($this->expected == $value);
  }
}
