Spectre
=======
Aims to write-and-run your specs in a easy way. Quickly.

  - Code-coverage reporting with PHPUnit
  - Don't struggle with classes!
  - Save as TAP or JSON
  - Watch mode

[![Build Status](https://travis-ci.org/pateketrueke/spectre.png)](https://travis-ci.org/pateketrueke/spectre)

## How to?

**composer.json**

```json
{
  "require": {
    "habanero/spectre": "dev-master"
  },
  "minimum-stability": "dev"
}
```

**inc/sum.php**

```php
<?php

function sum($a, $b)
{
  return $a + $b;
}
```

**specs/sum-spec.php**

```php
<?php

require 'inc/sum.php';

describe('sum()', function () {
  it('sums two numbers', function () {
    expect(sum(2, 2))->toBe(4);
  });
});
```

Execute your specs:

```bash
$ vendor/bin/spectre specs

Running specs...
  sum()
    ✓ sums two numbers ... OK

Done (0.0017s)
```

## Available matchers

- **toBe($value)** &mdash; Strict equal comparison (with `===`)
- **toBeLike($value)** &mdash; Alias for **toEqual**
- **toBeGreaterThan($value)** &mdash; Comparison using the `>` operator
- **toBeLessThan($value)** &mdash; Comparison using the `<` operator
- **toBeAnInstanceOf($value)** &mdash; Alias for **toBeInstanceOf**
- **toBeInstanceOf($value)** &mdash; Comparison using the `instanceof` operator
- **toBeEmpty()** &mdash; Tests agains `empty()`
- **toBeTruthy()** &mdash; Test for _truthy-values_
- **toBeFalsy()** &mdash; Test for _falsy-values_
- **toBeArray()** &mdash; Test using `is_array()`
- **toBeBoolean()** &mdash; Alias for **toBeBool**
- **toBeBool()** &mdash; Test using `is_bool()`
- **toBeCallable()** &mdash; Test using `is_callable()`
- **toBeDouble()** &mdash; Test using `is_double()`
- **toBeFloat()** &mdash; Test using `is_float()`
- **toBeInt()** &mdash; Test using `is_int()`
- **toBeInteger()** &mdash; Test using `is_integer()`
- **toBeLong()** &mdash; Test using `is_long()`
- **toBeNull()** &mdash; Test using `is_null()`
- **toBeNumeric()** &mdash; Test using `is_numeric()`
- **toBeObject()** &mdash; Test using `is_object()`
- **toBeReal()** &mdash; Test using `is_real()`
- **toBeResource()** &mdash; Test using `is_resource()`
- **toBeScalar()** &mdash; Test using `is_scalar()`
- **toBeString()** &mdash; Test using `is_string()`
- **toHaveKey($value)** &mdash; Test arrays using `isset()`
- **toHaveLength($value)** &mdash; Tests using `count()` and `strlen()`
- **toEndWith($value)** &mdash; Test for trailing substrings
- **toStartWith($value)** &mdash; Test for leading substrings
- **toContain($value)** &mdash; Test for including substrings
- **toEqual($value)** &mdash; Weak equal comparison (with `==`)
- **toMatch($value)** &mdash; Test strings with regular expressions
- **toPrint($value)** &mdash; Test for buffered-substrings (buffers + includes/echos)
- **toThrow([$value])** &mdash; Test for exceptions, if `$value` is provided will test against `instanceof`
- **toWarn($value)** &mdash; Test for buffered-substrings at user-level errors, notices and warnings (no fatal ones)

## Custom matchers

To register your own matchers you should implements the following code:

```php
use Spectre\Matchers\Base;

class CustomMatcher extends Base
{
  public $positive = "Expected '{subject}' {verb} '{value}', but it does not";
  public $negative = "Not expected '{subject}' {verb} '{value}', but it does";

  public function execute($value)
  {
  // test $value against $this->expected
  // then return true or false
  }
}

// Base::addMatcher($class[, $method])
Base::addMatcher('CustomMatcher', 'toBeSomething');
```

Note that `$method` is optional, if missing will use the `$class` name instead.

## CLI options

Type `vendor/bin/spectre` without arguments to get the following:

```bash
Usage: vendor/bin/spectre [options] <folders|files>

  -h --help      Display this help
  -w --watch     Enables the watch mode
  -t --timeout   Timeout in seconds for watch mode
  -c --coverage  Enables code coverage instrumentation
  -x --exclude   Folders and files to exclude from coverage
  -o --output    Custom filename for saving coverage report
  -r --reporter  Default reporter for coverage. Options: JSON, TAP
```

## Examples

You can mix almost all arguments on several ways, i.e:

```bash
$ vendor/bin/spectre specs -r TAP -c -x vendor -xspecs
$ vendor/bin/spectre ./specs /path/to/specs --coverage --exclude docs
$ vendor/bin/spectre $PWD/specs --output results.json
```
