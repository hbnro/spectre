<?php

namespace Spectre\Spec;

class Base
{
  private $tree;

  public function __construct()
  {
    $this->tree = new \Spectre\Spec\Node;
  }

  public function __call($method, array $arguments)
  {
    return call_user_func_array(array($this->tree, $method), $arguments);
  }

  public function set(array $group)
  {
    foreach ($group as $ctx) {
      @list($node, $block) = $ctx;

      $this->tree = $node;

      call_user_func($block);
    }
  }

  public function add($desc, $cases)
  {
    $fail = false;
    $this->tree = $this->tree->add($desc);

    try {
      call_user_func($cases);
    } catch (\Exception $e) {
      $this->abort($e);
      $fail = true;
    }

    if (!$fail) {
      $old = $this->tree;
      $this->tree = $this->tree->parent;
    }
  }

  public function push($desc, $test)
  {
    $this->tree->push($desc, $test);
  }

  public function abort($error)
  {
    echo "$error\n";
    exit(1);
  }

  public function run($coverage = null)
  {
    if (!($retval = $this->tree->report($coverage))) {
      throw new \Exception('Missing specs!');
    }

    return $retval;
  }
}
