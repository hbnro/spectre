<?php return function ($expected, $value)
{
  return is_array($expected) && isset($expected[$value]);
};
