<?php

class PHPUnit_Framework_Constraint_IsEqual extends PHPUnit_Framework_Constraint
{
    protected $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function evaluate($other, $description = '', $returnResult = false) {
        $success = false;

        if ($this->value === $other) {
            $success = true;
        }

        if ($returnResult) {
            return $success;
        }

        if (!$success) {
            throw new \PHPUnit_Framework_Exception($description);
        }
    }
}
