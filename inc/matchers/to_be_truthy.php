<?php return function ($expected) {
  return array(
    'result' => !!$expected,
    'positive' => "Expected '{subject}' to be truthy, but it does not",
    'negative' => "Not expected '{subject}' to be truthy, but it does",
  );
};
