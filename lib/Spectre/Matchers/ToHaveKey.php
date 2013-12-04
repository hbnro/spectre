<?php

namespace Spectre\Matchers;

class ToHaveKey extends Base
{
  public function execute($value)
  {
    return is_array($this->expected) && isset($this->expected[$value]);
  }
}
