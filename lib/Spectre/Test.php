<?php

namespace Spectre;

class Test {
  private $subject;
  private $inverse = false;

  public function __construct($value)
  {
    $this->subject = $value;
  }

  public function __get($key)
  {
    $this->inverse = $key === 'not';

    return $this;
  }


  public function toBe($expected)
  {
    $this->assert($expected === $this->subject, "expected '$this->subject' to be '$expected'");
  }

  public function toEqual($expected)
  {
    $this->assert($expected == $this->subject, "expected '$this->subject' to equal '$expected'");
  }

  public function toBeNull()
  {
    $this->assert(null === $this->subject, "expected '$this->subject' to be null");
  }

  public function toBeTruthy()
  {
    $this->assert(true === $this->subject, "expected '$this->subject' to be truthy");
  }

  public function toBeFalsy()
  {
    $this->assert(false === $this->subject, "expected '$this->subject' to be falsy");
  }

  public function toThrow($expected = null)
  {
    try {
      call_user_func($this->subject);
    } catch (\Exception $e) {
      $err = $expected ?  " '$expected'" : '';
      $this->assert($expected ? ($e instanceof $expected) : true, "expected '{closure}' to throw$err");
    }
  }

  public function toContain($expected)
  {
    if (is_array($this->subject)) {
      $test = preg_replace('/\s+/', '', @var_export($this->subject, 1));
      $this->assert(array_search($expected, $this->subject) !== false, "expected '$test' to contain '$expected'");
    } else {
      $this->assert(strpos($this->subject, $expected) !== false, "expected '$this->subject' to contain '$expected'");
    }
  }

  public function toBeEmpty()
  {
    if (is_array($this->subject)) {
      $test = preg_replace('/\s+/', '', @var_export($this->subject, 1));
      $this->assert(sizeof($this->subject) === 0, "expected '$test' to be empty");
    } elseif (is_string($this->subject)) {
      $this->assert(strlen($this->subject) === 0, "expected '$this->subject' to be empty");
    } else {
      $this->assert(empty($this->subject), "expected '$this->subject' to be empty");
    }
  }

  public function toMatch($expected, $count = 1)
  {
    $nth = $count <> 1 ? " $count times" : '';
    $this->assert($count == @preg_match($expected, $this->subject), "expected '$this->subject' to match '$expected'$nth");
  }


  private function assert($test, $msg)
  {
    if ($this->inverse ? $test : !$test) {
      throw new \Exception(($this->inverse ? 'not ' : '') . $msg);
    }
  }
}
