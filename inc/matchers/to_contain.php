<?php return function ($expected, $value)
{
  if (is_array($expected)) {
    return false !== @array_search($value, $expected);
  }

  if (is_object($expected)) {
    return false !== @array_search($value, get_object_vars($expected));
  }

  return is_string($value) && $value &&
         is_string($expected) && $expected &&
         (false !== strpos($expected, $value));
};
