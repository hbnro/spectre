<?php

class PHPUnit_Framework_ExpectationFailedException
{
    protected $comparisonFailure;

    public function __construct($message, $comparisonFailure = null, Exception $previous = null)
    {
        $this->comparisonFailure = $comparisonFailure;
    }

    public function getComparisonFailure()
    {
        return $this->comparisonFailure;
    }
}
