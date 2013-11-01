<?php

namespace Habanero\Spectre\Matchers;

abstract class Base
{
  protected $expected;

  public $positive = "Expected '{subject}' {verb} '{value}', but it does not";
  public $negative = "Not expected '{subject}' {verb} '{value}', but it does";

  public function __construct($subject)
  {
    $this->expected = $subject;
  }
}
