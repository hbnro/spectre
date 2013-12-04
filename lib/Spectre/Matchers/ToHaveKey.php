<?php

namespace Spectre\Matchers;

class ToHaveKey extends Base
{
  public function execute($value)
  {
    return isset($this->expected[$value]);
  }
}
