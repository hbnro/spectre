<?php

namespace Spectre\Spec;

class Node
{
  public $tree = array();
  public $tests = array();
  public $context;

  private $after = array();
  private $afterEach = array();

  private $before = array();
  private $beforeEach = array();

  public function __construct()
  {
    $this->context = new \Spectre\Spec\Context;
  }

  public function add($spec)
  {
    foreach ($this->tree as $one) {
      if ($one->description === $spec) {
        return $one;
      }
    }

    $node = new \Spectre\Spec\Node;
    $node->parent = $this;
    $node->description = $spec;

    $this->tree []= $node;

    return $node;
  }

  public function push($desc, \Closure $block)
  {
    isset($this->tests[$desc]) || $this->tests[$desc] = array();
    $this->tests[$desc] []= $block;
  }

  public function local($key, $value)
  {
    $this->context->{$key} = $value;
  }

  public function report($coverage, \Closure $logger = null)
  {
    $out = array();

    \Spectre\Base::set($this->before);

    foreach ($this->tree as $group) {
      isset($out['groups']) || $out['groups'] = array();
      $out['groups'][$group->description] = array();

      \Spectre\Base::set($this->beforeEach);

      empty($group->tests) || $this->reduce($coverage, $logger, $group, $out);

      \Spectre\Base::set($this->afterEach);

      $this->filter($coverage, $logger, $group, $out);
    }

    \Spectre\Base::set($this->after);

    return $out;
  }

  public function prepend(\Closure $block, $each = false)
  {
    if ($each) {
      $this->beforeEach []= array($this, $block);
    } else {
      $this->before []= array($this, $block);
    }
  }

  public function append(\Closure $block, $each = false)
  {
    if ($each) {
      $this->afterEach []= array($this, $block);
    } else {
      $this->after []= array($this, $block);
    }
  }

  private function reduce($coverage, $logger, $group, &$out)
  {
    \Spectre\Base::set($group->before);

    foreach ($group->tests as $desc => $fn) {
      isset($out['groups'][$group->description]['results']) || $out['groups'][$group->description]['results'] = array();

      \Spectre\Base::set($group->beforeEach);

      $out['groups'][$group->description]['results'][$desc] = \Spectre\Helpers::execute($fn, $group, $coverage, $logger, $desc);

      \Spectre\Base::set($group->afterEach);
    }

    \Spectre\Base::set($group->after);
  }

  private function filter($coverage, $logger, $group, &$out)
  {
    if (!empty($group->tree)) {
      $out['groups'][$group->description] += $group->report($coverage, $logger);
    }

    if (empty($out['groups'][$group->description])) {
      unset($out['groups'][$group->description]);
    }

    if (empty($out['groups'])) {
      unset($out['groups']);
    }
  }
}
