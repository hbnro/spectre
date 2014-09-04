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

    it('toBeLessThan() is for mathematical comparisons', function () {
      $pi = 3.1415926;
      $e = 2.78;

      expect($e)->toBeLessThan($pi);
      expect($pi)->not->toBeLessThan($e);
    });

    it('toBeGreaterThan() is for mathematical comparisons', function () {
      $pi = 3.1415926;
      $e = 2.78;

      expect($pi)->toBeGreaterThan($e);
      expect($e)->not->toBeGreaterThan($pi);
    });

    it('toWarn() is for testing if a function raises a warning', function () {
      $foo = function () {
        return 1 + 2;
      };
      $bar = function () {
        return $a + 1;
      };

      expect($foo)->not->toWarn();
      expect($bar)->toWarn(E_NOTICE);
    });

    // TODO:
    it('toBeEmpty()');
    it('toBeString()');
    it('toBeInteger()');
    it('toBeArray()');
    it('toBeInstanceOf()');
    it('toBeLike()');
    it('toBeBoolean()');
    it('toBeObject()');
    it('toBeCallable()');
    it('toBeDouble()');
    it('toBeFloat()');
    it('toBeLong()');
    it('toBeNumeric()');
    it('toBeReal()');
    it('toBeScalar()');
    it('toEndWith()');
    it('toStartWith()');
    it('toThrow()');
    it('toPrint()');
    it('toHaveKey()');
    it('toHaveLength()');
  });
});
