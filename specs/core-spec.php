<?php

// based on the jasmine test suite

namespace My\Custom\Tests;

class MyCustomMatcher extends \Spectre\Matchers\Base
{
  public function execute($value) {
    return $this->expected === $value;
  }
}

\Spectre\Base::addMatcher('\\My\\Custom\\Tests\\MyCustomMatcher');
\Spectre\Base::addMatcher('\\My\\Custom\\Tests\\MyCustomMatcher', 'toBeCustomValue');

describe('About describe()', function () {
  let('a', true);

  it('contains spec with an expectation', function ($a) {
    expect($a)->toBe(true);
  });

  describe('it() is just a function', function () {
    it('and so is a spec', function ($a) {
      expect($a)->toBe(true);
    });

    it('can have more than one expectation', function () {
      $foo = 0;
      $foo += 1;

      expect($foo)->toEqual(1);
      expect(true)->toEqual(true);
    });
  });

  describe('nested inside another describe()', function () {
    it('is just another function', function () {
      expect(1)->toBeTruthy();
    });
  });

  describe('with beforeEach() and afterEach()', function () {
    let('foo', new \stdClass());

    beforeEach(function ($foo) {
      if (!isset($foo->bar)) {
        $foo->bar = 0;
      }

      $foo->bar += 1;
    });

    afterEach(function ($foo) {
      $foo->bar = 0;
    });

    it('can share and reset some state', function ($foo) {
      expect($foo->bar)->toEqual(1);
    });

    it('can have more than one expectation', function ($foo) {
      expect($foo->bar)->toEqual(1);
      expect(true)->not->toEqual(false);
    });
  });
});

describe('About expect()', function () {
  it('receive a single value for test', function () {
    expect(function () {
      expect();
    })->toWarn('Undefined variable: value');
  });

  describe('included matchers', function () {
    let('a', '3');
    let('b', 4);
    let('c', 5);

    it('toBe() compares with ===', function ($a, $b, $c) {
      expect(($a * $a) + ($b * $b))->tobe($c * $c);
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
  });
});


describe('Pending specs', function () {
  xit("can be declared 'xit'", function () {
    expect(true)->toBe(false);
  });

  it("can be declared with 'it' but without a function");
});

describe('Custom matchers', function () {
  it('should validate custom matchers', function () {
    expect('foo')->toBeCustomValue('foo');
    expect('bar')->MyCustomMatcher('bar');
  });
});

describe('Isolation tests', function () {
  it('can negate individual tests', function () {
    // inception
    describe('invisible spec', function () {
      it('baz', function () {});
    });

    describe('invisible spec', function () {
      let('candy', 'bar');

      expect(1)->toBe(1);
      expect(0)->not->toBe(1);

      try { expect(1)->toBe(0); } catch (\Exception $e) {}
      try { expect(1)->not->toBe(1); } catch (\Exception $e) {}

      expect(function () { expect(0)->buzz; })->toThrow();
    });
  });

  it('should be self-contained', function () {
    $scope = new \Spectre\Spec\Base;

    $it = function($desc, $test) use ($scope) { $scope->push($desc, $test); };
    $let = function($key, $value) use ($scope) { $scope->local($key, $value); };
    $describe = function($desc, $cases) use ($scope) { $scope->add($desc, $cases); };

    $describe('x', function () use ($it, $let) {
      $let('a', 'b');
      $it('y', function ($a) {
        expect(1)->toBe(1);
        expect($a)->toBe('b');
      });
    });

    $describe('z', function () {});

    expect(json_encode($scope->run()))->toBe('{"groups":{"x":{"results":{"y":[]}}}}');
  });
});
