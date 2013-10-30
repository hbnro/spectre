<?php

namespace Spectre\Matchers;

class ToEqual extends Base
{
  public function execute($value)
  {
    return $this->expected == $value;
  }
}
