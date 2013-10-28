<?php

namespace Spectre\Spec;

class Context {
  public $locals = array();

  public function __get($key)
  {
    return $this->locals[$key];
  }

  public function __set($key, $value)
  {
    $this->locals[$key] = $value;
  }
}
