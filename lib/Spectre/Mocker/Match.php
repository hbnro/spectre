<?php

namespace Spectre\Mocker;

class Match
{
    private static $matchers;

    public static function __callStatic($method, $arguments)
    {
        if (!static::$matchers) {
            static::$matchers = array(
                'any' => function () {
                    return new \PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount();
                },
                'never' => function () {
                    return new \PHPUnit_Framework_MockObject_Matcher_InvokedCount(0);
                },
                'atLeast' => function ($count) {
                    return new \PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastCount($count);
                },
                'atLeastOnce' => function () {
                    return new \PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastOnce();
                },
                'once' => function () {
                    return new \PHPUnit_Framework_MockObject_Matcher_InvokedCount(1);
                },
                'exactly' => function ($count) {
                    return new \PHPUnit_Framework_MockObject_Matcher_InvokedCount($count);
                },
                'atMost' => function ($count) {
                    return new \PHPUnit_Framework_MockObject_Matcher_InvokedAtMostCount($count);
                },
                'at' => function ($index) {
                    return new \PHPUnit_Framework_MockObject_Matcher_InvokedAtIndex($index);
                },
                'returnValue' => function ($test) {
                    return new \PHPUnit_Framework_MockObject_Stub_Return($test);
                },
                'returnValueMap' => function (array $test) {
                    return new \PHPUnit_Framework_MockObject_Stub_ReturnValueMap($test);
                },
                'returnArgument' => function ($index) {
                    return new \PHPUnit_Framework_MockObject_Stub_ReturnArgument($index);
                },
                'returnCallback' => function ($fn) {
                    return new \PHPUnit_Framework_MockObject_Stub_ReturnCallback($fn);
                },
                'returnSelf' => function () {
                    return new \PHPUnit_Framework_MockObject_Stub_ReturnSelf();
                },
                'throwException' => function ($e) {
                    return new \PHPUnit_Framework_MockObject_Stub_Exception($e);
                },
                'onConsecutiveCalls' => function () {
                    return new \PHPUnit_Framework_MockObject_Stub_ConsecutiveCalls(func_get_args());
                },
            );
        }

        return call_user_func_array(static::$matchers[$method], $arguments);
    }
}
