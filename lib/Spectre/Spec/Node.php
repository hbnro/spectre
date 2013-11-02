<?php

namespace Spectre\Spec;

class Node
{
  public $tree = array();
  public $tests = array();
  public $context;

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

  public function report($coverage)
  {
    $out = array();

    foreach ($this->tree as $group) {
      isset($out['groups']) || $out['groups'] = array();
      $out['groups'][$group->description] = array();

      empty($group->tests) || $this->reduce($coverage, $group, $out);

      $this->filter($coverage, $group, $out);
    }

    return $out;
  }

  private function reduce($coverage, $group, &$out)
  {
    foreach ($group->tests as $desc => $fn) {
      isset($out['groups'][$group->description]['results']) || $out['groups'][$group->description]['results'] = array();
      $out['groups'][$group->description]['results'][$desc] = \Spectre\Helpers::execute($fn, $group, $coverage, $desc);
    }
  }

  private function filter($coverage, $group, &$out)
  {
    if (!empty($group->tree)) {
      $out['groups'][$group->description] += $group->report($coverage);
    }

    if (empty($out['groups'][$group->description])) {
      unset($out['groups'][$group->description]);
    }

    if (empty($out['groups'])) {
      unset($out['groups']);
    }
  }
}
