<?php return function ($expected, $value)
{
  if (is_array($expected)) {
    return false !== @array_search($value, $expected);
  }

  return is_string($value) && $value &&
         is_string($expected) && $expected &&
         (false !== strpos($expected, $value));
};
