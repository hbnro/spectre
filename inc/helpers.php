<?php

use Spectre\Base;
use Spectre\Expect;
use Spectre\Mocker\Fun;
use Spectre\Mocker\Stub;

function fun($ns, $name)
{
    return Fun::factory($ns, $name);
}

function spy($ns, $name = null)
{
    return Stub::factory($ns, $name);
}

function any()
{
    return new PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount();
}

function never()
{
    return new PHPUnit_Framework_MockObject_Matcher_InvokedCount(0);
}

function atLeast($count)
{
    return new PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastCount($count);
}

function atLeastOnce()
{
    return new PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastOnce();
}

function once()
{
    return new PHPUnit_Framework_MockObject_Matcher_InvokedCount(1);
}

function exactly($count)
{
    return new PHPUnit_Framework_MockObject_Matcher_InvokedCount($count);
}

function atMost($count)
{
    return new PHPUnit_Framework_MockObject_Matcher_InvokedAtMostCount($count);
}

function at($index)
{
    return new PHPUnit_Framework_MockObject_Matcher_InvokedAtIndex($index);
}

function returnValue($test)
{
    return new PHPUnit_Framework_MockObject_Stub_Return($test);
}

function returnValueMap(array $test)
{
    return new PHPUnit_Framework_MockObject_Stub_ReturnValueMap($test);
}

function returnArgument($index)
{
    return new PHPUnit_Framework_MockObject_Stub_ReturnArgument($index);
}

function returnCallback($fn)
{
    return new PHPUnit_Framework_MockObject_Stub_ReturnCallback($fn);
}

function returnSelf()
{
    return new PHPUnit_Framework_MockObject_Stub_ReturnSelf();
}

function throwException($e)
{
    return new PHPUnit_Framework_MockObject_Stub_Exception($e);
}

function onConsecutiveCalls()
{
    return new PHPUnit_Framework_MockObject_Stub_ConsecutiveCalls(func_get_args());
}

function xdescribe($desc)
{
    Base::add($desc);
}

function xit($desc)
{
    Base::push($desc);
}

function matchers($key, $value = null)
{
    Base::customMatchers($key, $value);
}

function let($key, $value = null)
{
    Base::local($key, $value);
}

function describe($desc, $cases)
{
    Base::add($desc, $cases);
}

function it($desc, $test = null)
{
    Base::push($desc, $test);
}

function expect($value)
{
    return Expect::that($value);
}

function before($block)
{
    Base::prepend($block);
}

function beforeEach($block)
{
    Base::prepend($block, true);
}

function after($block)
{
    Base::append($block);
}

function afterEach($block)
{
    Base::append($block, true);
}

Base::customMatchers(array(
  'toBe' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_be.php')),
  'toBeA' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_be_a.php')),

  'toBeEmpty' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_be_empty.php')),
  'toBeFalsy' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_be_falsy.php')),
  'toBeTruthy' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_be_truthy.php')),

  'toBeLessThan' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_be_less_than.php')),
  'toBeGreaterThan' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_be_greater_than.php')),

  'toContain' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_contain.php')),
  'toContains' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_contain.php')),

  'toEndWith' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_end_with.php')),
  'toStartWith' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_start_with.php')),

  'toBeLike' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_equal.php')),
  'toEquals' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_equal.php')),
  'toEqual' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_equal.php')),

  'toBeAnInstanceOf' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_be_instance_of.php')),
  'toBeInstanceOf' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_be_instance_of.php')),

  'toHaveKey' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_have_key.php')),
  'toHaveKeys' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_have_key.php')),
  'toHaveValues' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_contain.php')),
  'toHaveLength' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_have_length.php')),

  'toMatch' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_match.php')),
  'toPrint' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_print.php')),

  'toReturn' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_return.php')),
  'toThrow' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_throw.php')),
  'toWarn' => require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'matchers', 'to_warn.php')),
));
