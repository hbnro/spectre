<?php

namespace Spectre\Matchers;

class ToBeUndefined extends Base
{
  public $positive = "Expected '{subject}' to be defined, but it does not";
  public $negative = "Not expected '{subject}' to be defined, but it does";

  public function execute()
  {
    return !isset($this->expected);
  }
}
