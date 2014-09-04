<?php return function ($expected, $value = null)
{
  $klass = is_string($value) || is_object($value);
  $params = array();

  if (!$klass) {
    $params['positive'] = "Expected '{subject}' to throw, but it does not";
    $params['negative'] = "Not expected '{subject}' to throw, but it does";
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
