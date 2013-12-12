<?php

if (!function_exists('expect')) {
  function expect($value) { return \Spectre\Expect::that($value); }
}

if (!function_exists('let')) {
  function let($key, $value) { \Spectre\Base::local($key, $value); }
}

if (!function_exists('describe')) {
  function describe($desc, $cases) { \Spectre\Base::add($desc, $cases); }
}

if (!function_exists('it')) {
  function it($desc, $test) { \Spectre\Base::push($desc, $test); }
}

if (!function_exists('before')) {
  function before($block) { \Spectre\Base::prepend($block); }
}

if (!function_exists('beforeEach')) {
  function beforeEach($block) { \Spectre\Base::prepend($block, true); }
}

if (!function_exists('after')) {
  function after($block) { \Spectre\Base::append($block); }
}

if (!function_exists('afterEach')) {
  function afterEach($block) { \Spectre\Base::append($block, true); }
}
