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

  public function report($coverage, \Closure $logger = null, $tabs = 0)
  {
    $out = array();

    \Spectre\Base::set($this->before);

    foreach ($this->tree as $group) {
      isset($out['groups']) || $out['groups'] = array();
      $out['groups'][$group->description] = array();

      \Spectre\Base::set($this->beforeEach);

      $this->reduce($coverage, $logger, $group, $tabs + 1, $out);

      \Spectre\Base::set($this->afterEach);

      $this->filter($coverage, $logger, $group, $tabs + 1, $out);
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

  private function reduce($coverage, $logger, $group, $tabs, &$out)
  {
    \Spectre\Base::set($group->before);

    $logger && call_user_func($logger, null, $tabs, $group->description, null);

    foreach ($group->tests as $desc => $fn) {
      isset($out['groups'][$group->description]['results']) || $out['groups'][$group->description]['results'] = array();

      \Spectre\Base::set($group->beforeEach);

      $out['groups'][$group->description]['results'][$desc] = \Spectre\Helpers::execute($fn, $group, $coverage, $logger, $desc, $tabs + 1);

      \Spectre\Base::set($group->afterEach);
    }

    \Spectre\Base::set($group->after);

    if ($logger && sizeof($group->tests)) {
      call_user_func($logger);
    }
  }

  private function filter($coverage, $logger, $group, $tabs, &$out)
  {
    if (!empty($group->tree)) {
      $out['groups'][$group->description] += $group->report($coverage, $logger, $tabs);
    }

    if (empty($out['groups'][$group->description])) {
      unset($out['groups'][$group->description]);
    }

    if (empty($out['groups'])) {
      unset($out['groups']);
    }
  }
}
