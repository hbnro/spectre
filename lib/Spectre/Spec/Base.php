<?php

namespace Spectre\Spec;

class Base
{
  private $tree;
  private $logger;
  private $matchers = array();

  public function __construct()
  {
    $this->tree = new \Spectre\Spec\Node;
  }

  public function __call($method, array $arguments)
  {
    return call_user_func_array(array($this->tree, $method), $arguments);
  }

  public function customMatchers($method = null, \Closure $callback = null)
  {
    if (!func_num_args()) {
      return $this->matchers;
    }

    if (!is_array($method)) {
      $method = array($method => $callback);
    }

    foreach ($method as $name => $fn) {
      if (!($fn instanceof \Closure)) {
        throw new \Exception("Cannot use '$fn' as matcher");
      }

      $this->matchers[$name] = $fn;
    }
  }

  public function set(array $group)
  {
    foreach ($group as $ctx) {
      @list($node, $block) = $ctx;

      $this->tree = $node;

      \Spectre\Helpers::invoke($block, $this->tree);
    }
  }

  public function add($desc, \Closure $cases)
  {
    $fail = false;
    $this->tree = $this->tree->add($desc);

    try {
      \Spectre\Helpers::invoke($cases, $this->tree);
    } catch (\Exception $e) {
      $fail = true;
    }

    if (!$fail) {
      $old = $this->tree;
      $this->tree = $this->tree->parent;
    }
  }

  public function push($desc, $test = null)
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
