<?php

namespace Habanero\Spectre\Matchers;

class ToBe extends Base
{
  public function execute($value)
  {
    return $this->expected === $value;
  }
}
