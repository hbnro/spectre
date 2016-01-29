<?php

return function ($expected, $value) {
  if (func_num_args() > 2) {
      $args = func_get_args();
      $value = array_slice($args, 1);
  }

  if (is_object($expected)) {
      $expected = get_object_vars($expected);
  }

  if (is_array($expected)) {
      if (is_array($value)) {
          return sizeof(array_intersect($value, $expected)) === count($value);
      } else {
          return false !== @array_search($value, $expected);
      }
  }

  return is_string($value) && $value &&
         is_string($expected) && $expected &&
         (false !== strpos($expected, $value));
};
