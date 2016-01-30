<?php

return function ($expected, $type) {
    $args = func_get_args();
    $args = array_slice($args, 2);

    array_unshift($args, $expected);

    return array(
        'result' => @call_user_func_array("is_$type", $args),
        'negative' => "Expected is_$type('{subject}') to be true, but it was not",
        'positive' => "Did not expect is_$type('{subject}') to be true, but it was",
    );
};
