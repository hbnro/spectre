<?php

return function ($expected, $value = null) {
    return null === $value ? !empty($expected) : ((is_array($expected) ? count($expected) : strlen((string) $expected)) == $value);
};
