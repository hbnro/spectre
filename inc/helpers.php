<?php

use \Habanero\Spectre\Base as Spectre,
    \Habanero\Spectre\Expect as Assert;

function expect($value)
{
  return Assert::that($value);
}

function local($key, $value)
{
  return Spectre::local($key, $value);
}

function describe($desc, $cases)
{
  Spectre::describe($desc, $cases);
}

function it($desc, $test)
{
  Spectre::it($desc, $test);
}
