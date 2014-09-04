<?php return function ($expected)
{
  if (is_array($expected)) {
    $result = 0 === sizeof($expected);
  } elseif (is_string($expected)) {
    $result = 0 === strlen($expected);
  } else {
    $result = empty($expected);
  }

  return array(
    'result' => $result,
    'positive' => "Expected '{subject}' to be empty, but it does not",
    'negative' => "Not expected '{subject}' to be empty, but it does",
  );
};
