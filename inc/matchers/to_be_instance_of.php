<?php

return function ($expected, $value) {
  return is_object($value) || is_string($value) ? $expected instanceof $value : false;
};
