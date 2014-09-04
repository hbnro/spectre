<?php return function ($expected, $value)
{
  return (is_array($expected) ? count($expected) : strlen((string) $expected)) == $value;
};
