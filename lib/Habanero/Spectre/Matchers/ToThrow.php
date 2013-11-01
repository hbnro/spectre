<?php

namespace Habanero\Spectre\Matchers;

class ToThrow extends Base
{
  public function execute($value = null)
  {
    $klass = is_string($value) || is_object($value);

    if (!$klass) {
      $this->positive = "Expected '{subject}' to throw, but it does not";
      $this->negative = "Not expected '{subject}' to throw, but it does";
    }

    try {
      is_callable($this->expected) && call_user_func($this->expected);
    } catch (\Exception $e) {
      return $klass ? $e instanceof $value : true;
    }
  }
}
