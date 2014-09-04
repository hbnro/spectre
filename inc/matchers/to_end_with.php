<?php return function ($expected, $value)
{
  return is_string($value) && $value &&
         is_string($expected) && $expected &&
         ($value === substr($expected, -strlen($value)));
};
