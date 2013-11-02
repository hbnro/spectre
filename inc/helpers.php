<?php

function expect($value)
{
  return \Spectre\Expect::that($value);
}

function let($key, $value)
{
  \Spectre\Base::local($key, $value);
}

function describe($desc, $cases)
{
  \Spectre\Base::add($desc, $cases);
}

function it($desc, $test)
{
  \Spectre\Base::push($desc, $test);
}

function before($block)
{
  \Spectre\Base::prepend($block);
}

function beforeEach($block)
{
  \Spectre\Base::prepend($block, true);
}

function after($block)
{
  \Spectre\Base::append($block);
}

function afterEach($block)
{
  \Spectre\Base::append($block, true);
}
