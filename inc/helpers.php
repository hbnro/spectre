<?php

function run_specs()
{
  return \Spectre\Base::instance()->run();
}

function expect($value)
{
  return \Spectre\Base::instance()->expect($value);
}

function local($key, $value)
{
  return \Spectre\Base::instance()->local($key, $value);
}

function describe($desc, $cases)
{
  \Spectre\Base::instance()->describe($desc, $cases);
}

function it($desc, $test)
{
  \Spectre\Base::instance()->it($desc, $test);
}
