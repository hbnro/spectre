<?php

namespace Spectre\Matchers;

class ToBeFalsy extends Base
{
  public $positive = "Expected '{subject}' to be falsy, but it does not";
  public $negative = "Not expected '{subject}' to be falsy, but it does";

  public function execute()
  {
    return !$this->expected;
  }
}
