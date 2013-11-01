<?php

namespace Habanero\Spectre\Matchers;

class ToBeEmpty extends Base
{
  public $positive = "Expected '{subject}' to be empty, but it does not";
  public $negative = "Not expected '{subject}' to be empty, but it does";

  public function execute()
  {
    if (is_array($this->expected)) {
      return 0 === sizeof($this->expected);
    } elseif (is_string($this->expected)) {
      return 0 === strlen($this->expected);
    } else {
      return empty($this->expected);
    }
  }
}
