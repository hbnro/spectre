<?php

return function ($expected, $value) {
    return @preg_match($value, $expected);
};
