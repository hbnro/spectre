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
