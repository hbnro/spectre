<?php return function ($expected) {
  return array(
    'result' => !$expected,
    'positive' => "Expected '{subject}' to be falsy, but it does not",
    'negative' => "Not expected '{subject}' to be falsy, but it does",
  );
};
