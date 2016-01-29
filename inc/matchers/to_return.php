<?php

return function ($expected, $value) {
  return array(
    'result' => is_callable($expected) && (call_user_func($expected) === $value),
    'positive' => "Expected '{subject}' to return '{value}', but it did not",
    'negative' => "Did not expect '{subject}' to return '{value}', but it did",
  );
};
