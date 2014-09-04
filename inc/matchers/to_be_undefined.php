<?php return function ($expected) {
  return array(
    'result' => null === $expected,
    'positive' => "Expected '{subject}' to be defined, but it does not",
    'negative' => "Not expected '{subject}' to be defined, but it does",
  );
};
