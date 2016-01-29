<?php

return function ($expected, $value = null) {
  $klass = is_string($value) || is_object($value);
  $params = array();

  if (!$klass) {
      $params['positive'] = "Expected '{subject}' to throw, but it did not";
      $params['negative'] = "Did not expect '{subject}' to throw, but it did";
  }

  try {
      $result = is_callable($expected) && call_user_func($expected);
  } catch (\Exception $e) {
      $result = $klass ? $e instanceof $value : true;
  }

  return array_merge(array(
    'result' => $result,
  ), $params);
};
