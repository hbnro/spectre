<?php

namespace Spectre\Spec;

class Node {
  public $tree = array();
  public $tests = array();

  public function add($spec)
  {
    $node = new \Spectre\Spec\Node;
    $node->context = new \Spectre\Spec\Context;
    $node->parent = $this;
    $node->description = $spec;

    if ($prepend) {
      array_unshift($this->tree, $node);
    } else {
      $this->tree []= $node;
    }

    return $node;
  }

  public function push($desc, \Closure $block)
  {
    $this->tests[$desc] = $block;
  }

  public function local($key, $value)
  {
    $this->context->{$key} = $value;
  }

  public function report()
  {
    $out = array();

    foreach ($this->tree as $group) {
      isset($out['groups']) || $out['groups'] = array();
      $out['groups'][$group->description] = array();

      if (!empty($group->tests)) {
        foreach ($group->tests as $desc => $fn) {
          isset($out['groups'][$group->description]['results']) || $out['groups'][$group->description]['results'] = array();
          $out['groups'][$group->description]['results'][$desc] = \Spectre\Base::execute($fn, $group);
        }
      }

      if (!empty($group->tree)) {
        $out['groups'][$group->description] += $group->report();
      }
    }

    return $out;
  }
}
