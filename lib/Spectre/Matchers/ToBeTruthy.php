<?php

namespace Spectre\Matchers;

class ToBeTruthy extends Base
{
  public $positive = "Expected '{subject}' to be truthy, but it does not";
  public $negative = "Not expected '{subject}' to be truthy, but it does";

  public function execute()
  {
    return !!$this->expected;
  }
}
