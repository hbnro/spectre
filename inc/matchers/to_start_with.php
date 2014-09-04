<?php return function ($expected, $value)
{
  return is_string($value) && $value &&
         is_string($expected) && $expected &&
         (0 === strpos($expected, $value));
};
