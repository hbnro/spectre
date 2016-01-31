<?php

class PHPUnit_Framework_TestFailure
{
    public static function exceptionToString(Exception $e)
    {
        return $e->getMessage();
    }
}
