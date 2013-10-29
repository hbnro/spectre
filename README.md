Spectre
=======

Aims to write-and-run your specs in a easy way. Quickly.

  - Includes PHPSpec matchers used on expect()
  - Report results using TAP or Basic-output
  - Code-coverage reporting with PHPUnit
  - Don't struggle with classes!

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
$ vendor/bin/spectre specs -rTAP
  ok 1 - sum() sums two numbers

  1..1
  # tests 1
  # pass  1
  # fail  0

  # ok
```

## Options

```bash
# examples
$ vendor/bin/spectre specs -rTAP -c -xvendor -xspecs
$ vendor/bin/spectre ./specs /path/to/specs --cover --exclude=docs
$ vendor/bin/spectre $PWD/specs --reporter=Basic
```

  - `[-r|--reporter=]<TAP|Basic>` choose the reporter (default: Basic)
  - `[-x|--exclude=]<file|path>` ignored for code-coverage
  - `[-c|--cover]` enables the code-coverage
  - `<file|path>` any input file or path

[![Build Status](https://travis-ci.org/pateketrueke/spectre.png)](https://travis-ci.org/pateketrueke/spectre)
