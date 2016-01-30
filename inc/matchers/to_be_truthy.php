<?php

return function ($expected) {
    return array(
        'result' => (bool) $expected,
        'positive' => "Expected '{subject}' to be truthy, but it was not",
        'negative' => "Did not expect '{subject}' to be truthy, but it was",
    );
};
