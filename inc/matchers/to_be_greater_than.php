<?php

return function ($expected, $value) {
  if (is_array($expected) && is_array($value)) {
      return sizeof($expected) > sizeof($value);
  } elseif (is_numeric($expected) && is_numeric($value)) {
      return $expected > $value;
  }
};
