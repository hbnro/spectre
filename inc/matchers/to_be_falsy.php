<?php

return function ($expected) {
    return array(
        'result' => !$expected,
        'positive' => "Expected '{subject}' to be falsy, but it was not",
        'negative' => "Did not expect '{subject}' to be falsy, but it was",
    );
};
