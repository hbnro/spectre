<?php

namespace Spectre\Spec;

class Base
{
  private $tree;
  private $logger;

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

  public function run($coverage = null)
  {
    if (!($retval = $this->tree->report($coverage, $this->logger))) {
      throw new \Exception('Missing specs!');
    }

    return $retval;
  }

  public function log(\Closure $block)
  {
    $this->logger = $block;
  }
}
