<?php

return function ($expected, $value) {
    ob_start();
    $args = array_slice(func_get_args(), 1);
    print_r(is_callable($expected) ? call_user_func_array($expected, $args) : $expected);
    $test = ob_get_clean();

    return false !== strpos($test, $value);
};
