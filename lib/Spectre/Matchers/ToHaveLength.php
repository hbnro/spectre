<?php

namespace Spectre\Matchers;

class ToHaveLength extends Base
{
  public function execute($value)
  {
    return (is_array($this->expected) ? count($this->expected) : strlen((string) $this->expected)) == $value;
  }
}
