<?php

namespace Habanero\Spectre\Spec;

class Context
{
  public $locals = array();

  public function __get($key)
  {
    return isset($this->locals[$key]) ? $this->locals[$key] : null;
  }

  public function __set($key, $value)
  {
    $this->locals[$key] = $value;
  }
}
