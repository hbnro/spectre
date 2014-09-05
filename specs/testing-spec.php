<?php

// based on the jasmine test suite

\Spectre\Base::customMatchers('toBeCustomValue', function ($expected, $value) {
  return $expected === $value;
});

describe('About expect()', function () {
  it('receive a single value for test', function () {
    expect(function () {
      expect();
    })->toWarn('Undefined variable: value');
  });

  describe('custom matchers', function () {
    it('should validate custom matchers', function () {
      expect('foo')->toBeCustomValue('foo');
    });
  });

  describe('chaining matchers', function () {
    it('should chain all matchers', function () {
      expect(1)
        ->toBeA('integer')
        ->and->toBe(1)
        ->and->not->toBe('1');

      expect('hawaiian pizza')
        ->toContain('pizza')
        ->and->not->toContain('pepperoni');
    });
  });

  describe('included matchers', function () {
    let('a', '3');
    let('b', 4);
    let('c', 5);

    it('toBe() compares with ===', function ($a, $b, $c) {
      expect(($a * $a) + ($b * $b))->toBe($c * $c);
      expect($a)->not->toBe(null);
    });

    describe('toEqual()', function () {
      let('foo', array(
        'a' => 12,
        'b' => 34,
      ));

      let('bar', array(
        'a' => 12,
        'b' => 34,
      ));

      it('works for scalar variables', function ($a, $b) {
        expect($a * $b)->toEqual(12);
      });

      it('should work for objects', function ($foo, $bar) {
        expect($foo)->toEqual($bar);
      });
    });

    it('toMatch() is for regular expressions', function () {
      $message = 'foo bar baz';

      expect($message)->toMatch('/bar/');
      expect($message)->not->toMatch('/quux/');
    });

    it('toBeNull() compares against null', function ($x) {
      expect($x)->toBeNull();
      expect(null)->toBeNull();
      expect('foo')->not->toBeNull();
    });

    it('toBeTruthy() is for boolean casting testing', function ($x) {
      expect('foo')->toBeTruthy();
      expect($x)->not->toBeTruthy();
    });

    it('toBeFalsy() is for boolean casting testing', function ($x) {
      expect($x)->toBeFalsy();
      expect('foo')->not->toBeFalsy();
      expect(new \stdClass())->not->toBeFalsy();
    });

    describe('toContain()', function () {
      it('works finding values on arrays', function () {
        $x = array('foo', 'bar', 'baz');

        expect($x)->toContain('bar');
        expect($x)->not->toContain('quux');
      });

      it('should work with strings', function () {
        $x = 'foo bar baz';

        expect($x)->toContain('bar');
        expect($x)->not->toContain('quux');
      });
    });

    describe('toBeEmpty()', function () {
      it('will test empty strings', function () {
        expect('')->toBeEmpty();
        expect('abc')->not->toBeEmpty();
      });

      it('will test empty arrays', function () {
        expect(array())->toBeEmpty();
        expect(array(1, 2, 3))->not->toBeEmpty();
      });

      it('will test other empty values', function () {
        expect(0)->toBeEmpty();
        expect(null)->toBeEmpty();
        expect(false)->toBeEmpty();
        expect(true)->not->toBeEmpty();
        expect(new \stdClass())->not->toBeEmpty();
      });
    });

    describe('toHaveLength()', function () {
      it('will test the length of scalars', function () {
        expect(-1234)->toHaveLength(5);
        expect('abc')->toHaveLength(3);
        expect('')->toHaveLength(0);
        expect('')->not->toHaveLength();
        expect(false)->not->toHaveLength();
      });

      it('will test the size of arrays', function () {
        expect(array(1, 2))->toHaveLength(2);
        expect(array())->toHaveLength(0);
        expect(array())->not->toHaveLength();
      });
    });

    describe('toBeLessThan()', function () {
      it('should work for numeric comparisons', function () {
        $pi = 3.1415926;
        $e = 2.78;

        expect($e)->toBeLessThan($pi);
        expect($pi)->not->toBeLessThan($e);
      });

      it('should work for array-size comparisons', function () {
        $foo = array(1, 2, 3);
        $bar = array(4, 5);

        expect($bar)->toBeLessThan($foo);
        expect($foo)->not->toBeLessThan($bar);
      });
    });

    describe('toBeGreaterThan()', function () {
      it('should work for numeric comparisons', function () {
        $pi = 3.1415926;
        $e = 2.78;

        expect($pi)->toBeGreaterThan($e);
        expect($e)->not->toBeGreaterThan($pi);
      });

      it('should work for array-size comparisons', function () {
        $foo = array(1, 2, 3);
        $bar = array(4, 5);

        expect($foo)->toBeGreaterThan($bar);
        expect($bar)->not->toBeGreaterThan($foo);
      });
    });

    it('toPrint() will test if a function prints something', function () {
      expect(function () {
        phpinfo();
      })->toPrint(phpversion());

      expect('foo')->toPrint('foo');
      expect('foo')->not->toPrint('bar');
    });

    it('toThrow() will test if a function throws an exception', function () {
      expect(function () {
        throw new \Exception();
      })->toThrow();

      expect(null)->not->toThrow();
    });

    it('toWarn() will test if a function raises a warning', function () {
      $foo = function () {
        return 1 + 2;
      };

      $bar = function () {
        return $a + 1;
      };

      expect($foo)->not->toWarn();
      expect($bar)->toWarn(E_NOTICE);

      expect(function () {
        file_get_contents(uniqid('http://some.random.site.com/'));
      })->toWarn();
    });

    it('toBeA() will test if a value has a certain type', function () {
      expect(array())->toBeA('array');
      expect(123)->not->toBeA('string');
    });

    it('toBeInstanceOf() will test a value using the instanceof operator', function () {
      expect(new \stdClass())->toBeInstanceOf('\\stdClass');
      expect(new \stdClass())->not->toBeInstanceOf('\\Exception');
    });

    it('toBeString() will test a value using is_string()', function () {
      expect('abc')->toBeString();
      expect(array())->not->toBeString();
    });

    it('toBeInteger() will test a value using is_integer()', function () {
      expect(123)->toBeInteger();
      expect('123')->not->toBeInteger();
    });

    it('toBeArray() will test a value using is_array()', function () {
      expect(array())->toBeArray();
      expect(false)->not->toBeArray();
    });

    it('toBeBoolean() will test a value using is_bool()', function () {
      expect(true)->toBeBoolean();
      expect(null)->not->toBeBoolean();
    });

    it('toBeObject() will test a value using is_object()', function () {
      expect(new \stdClass())->toBeObject();
      expect(array())->not->toBeObject();
    });

    it('toBeCallable() will test a value using is_callable()', function () {
      expect('strtolower')->toBeCallable();
      expect('unknown_function')->not->toBeCallable();
    });

    it('toBeDouble() will test a value using is_double()', function () {
      expect(1.2)->toBeDouble();
      expect('1.2')->not->toBeDouble();
    });

    it('toBeFloat() will test a value using is_float()', function () {
      expect(0.3)->toBeFloat();
      expect('0.3')->not->toBeFloat();
    });

    it('toBeLong() will test a value using is_long()', function () {
      expect(16777215)->toBeLong();
      expect('16777215')->not->toBeLong();
    });

    it('toBeNumeric() will test a value using is_numeric()', function () {
      expect('123')->toBeNumeric();
      expect('abc123')->not->toBeNumeric();
      expect('123xyz')->not->toBeNumeric();
    });

    it('toBeReal() will test a value using is_real()', function () {
      expect(42.0)->toBeReal();
      expect(42)->not->toBeReal();
    });

    it('toBeScalar() will test a value using is_scalar()', function () {
      expect('')->toBeScalar();
      expect(-1)->toBeScalar();
      expect(false)->toBeScalar();
      expect(null)->not->toBeScalar();
      expect(array())->not->toBeScalar();
      expect(new \stdClass())->not->toBeScalar();
    });

    it('toEndWith() will test if a value ends with certain text', function () {
      expect('abc')->toEndWith('c');
      expect('abc')->not->toEndWith('d');
    });

    it('toStartWith() will test if a value starts with certain text', function () {
      expect('xyz')->toStartWith('x');
      expect('xyz')->not->toStartWith('z');
    });

    it('toHaveKey() will test if a value has a certain key', function () {
      $foo = array('candy' => 'bar');

      expect($foo)->toHaveKey('candy');
      expect($foo)->not->toHaveKey('nothing');
    });
  });
});
