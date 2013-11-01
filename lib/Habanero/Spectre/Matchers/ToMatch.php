<?php

namespace Habanero\Spectre\Matchers;

class ToMatch extends Base
{
  public function execute($value)
  {
    return @preg_match($value, $this->expected);
  }
}
