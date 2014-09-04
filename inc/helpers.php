<?php

use Spectre\Base;
use Spectre\Expect;

function xdescribe() {}
function xit() {}

function let($key, $value) { Base::local($key, $value); }

function describe($desc, $cases) { Base::add($desc, $cases); }
function it($desc, $test) { Base::push($desc, $test); }

function expect($value) { return Expect::that($value); }

function before($block) { Base::prepend($block); }
function beforeEach($block) { Base::prepend($block, true); }
function after($block) { Base::append($block); }
function afterEach($block) { Base::append($block, true); }