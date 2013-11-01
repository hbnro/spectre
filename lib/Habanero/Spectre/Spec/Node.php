<?php

namespace Habanero\Spectre\Spec;

use Habanero\Spectre\Base as Spectre,
    Habanero\Spectre\Helpers as Test,
    Habanero\Spectre\Spec\Node as Group,
    Habanero\Spectre\Spec\Context as Scope;

class Node
{
  public $tree = array();
  public $tests = array();
  public $context;

  public function __construct()
  {
    $this->context = new Scope;
  }

  public function add($spec)
  {
    foreach ($this->tree as $one) {
      if ($one->description === $spec) {
        return $one;
      }
    }

    $node = new Group;
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

      if (!empty($group->tests)) {
        foreach ($group->tests as $desc => $fn) {
          isset($out['groups'][$group->description]['results']) || $out['groups'][$group->description]['results'] = array();
          $out['groups'][$group->description]['results'][$desc] = Test::execute($fn, $group, $coverage, $desc);
        }
      }

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

    return $out;
  }
}
