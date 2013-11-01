<?php

namespace Habanero\Spectre\Matchers;

class ToBeInstanceOf extends Base
{
  public function execute($value)
  {
    return is_object($value) || is_string($value) ? $this->expected instanceof $value : false;
  }
}
