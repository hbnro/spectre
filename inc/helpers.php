<?php

use Spectre\Base;
use Spectre\Expect;

function xdescribe() {}
function xit($desc) { Base::push($desc); }

function matchers($key, $value = null) { Base::customMatchers($key, $value); }
function let($key, $value = null) { Base::local($key, $value); }

function describe($desc, $cases) { Base::add($desc, $cases); }
function it($desc, $test = null) { Base::push($desc, $test); }

function expect($value) { return Expect::that($value); }

function before($block) { Base::prepend($block); }
function beforeEach($block) { Base::prepend($block, true); }
function after($block) { Base::append($block); }
function afterEach($block) { Base::append($block, true); }

Base::customMatchers(array(
  'toBe' => require __DIR__ . '/matchers/to_be.php',
  'toBeA' => require __DIR__ . '/matchers/to_be_a.php',

  'toBeEmpty' => require __DIR__ . '/matchers/to_be_empty.php',
  'toBeFalsy' => require __DIR__ . '/matchers/to_be_falsy.php',
  'toBeTruthy' => require __DIR__ . '/matchers/to_be_truthy.php',

  'toBeLessThan' => require __DIR__ . '/matchers/to_be_less_than.php',
  'toBeGreaterThan' => require __DIR__ . '/matchers/to_be_greater_than.php',

  'toContain' => require __DIR__ . '/matchers/to_contain.php',
  'toContains' => require __DIR__ . '/matchers/to_contain.php',

  'toEndWith' => require __DIR__ . '/matchers/to_end_with.php',
  'toStartWith' => require __DIR__ . '/matchers/to_start_with.php',

  'toBeLike' => require __DIR__ . '/matchers/to_equal.php',
  'toEquals' => require __DIR__ . '/matchers/to_equal.php',
  'toEqual' => require __DIR__ . '/matchers/to_equal.php',

  'toBeAnInstanceOf' => require __DIR__ . '/matchers/to_be_instance_of.php',
  'toBeInstanceOf' => require __DIR__ . '/matchers/to_be_instance_of.php',

  'toHaveKey' => require __DIR__ . '/matchers/to_have_key.php',
  'toHaveKeys' => require __DIR__ . '/matchers/to_have_key.php',
  'toHaveValues' => require __DIR__ . '/matchers/to_contain.php',
  'toHaveLength' => require __DIR__ . '/matchers/to_have_length.php',

  'toMatch' => require __DIR__ . '/matchers/to_match.php',
  'toPrint' => require __DIR__ . '/matchers/to_print.php',

  'toReturn' => require __DIR__ . '/matchers/to_return.php',
  'toThrow' => require __DIR__ . '/matchers/to_throw.php',
  'toWarn' => require __DIR__ . '/matchers/to_warn.php',
));
