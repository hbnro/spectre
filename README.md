Spectre
=======

Aims to write-and-run your specs in a easy way, quickly.

```bash
$ composer require habanero/spectre:dev-master --prefer-dist
  ...
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

[![Build Status](https://travis-ci.org/pateketrueke/spectre.png)](https://travis-ci.org/pateketrueke/spectre)
