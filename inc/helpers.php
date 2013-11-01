<?php

use Habanero\Spectre\Base as Scope,
    Habanero\Spectre\Expect as Assert;

function expect($value)
{
  return Assert::that($value);
}

function let($key, $value)
{
  Scope::local($key, $value);
}

function describe($desc, $cases)
{
  Scope::add($desc, $cases);
}

function it($desc, $test)
{
  Scope::push($desc, $test);
}
