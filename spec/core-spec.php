<?php

// based on the jasmine test suite

describe('About let()', function () {
  let('global', 'VALUE');
  let('dummy', new \stdClass());

  it('should share context locally', function ($global) {
    expect($global)->toBe('VALUE');
  });

  describe('inner tests', function () {
    let('local', 'TEST');

    it('should share the upper context too', function ($global, $local) {
      expect($global)->toBe('VALUE');
      expect($local)->toBe('TEST');
    });
  });

  describe('sibling tests', function () {
    it('should not share their siblings context', function ($global, $local) {
      expect($global)->toBe('VALUE');
      expect($local)->not->toBe('TEST');
    });
  });

  describe('callbacks inside tests', function ($dummy) {
    it('should share context locally', function ($dummy) {
      expect(function ($global, $local) {
        echo $local ?: $global;
      })->toPrint('VALUE');
    });
  });

  describe('objects within inner tests', function ($dummy) {
    before(function ($dummy) {
      $dummy->foo = 'bar';
    });

    it('should share objects by reference', function ($dummy) {
      expect($dummy->foo)->toBe('bar');
    });
  });
});

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

  describe('with before() and after()', function () {
    let('foo', new \stdClass());

    before(function ($foo) {
      $foo->value = 'bar';
    });

    after(function ($foo) {
      unset($foo->value);
    });

    it('should setup and tear-down', function ($foo) {
      expect($foo->value)->toBe('bar');
    });
  });
});

describe('Pending specs', function () {
  xit("can be declared 'xit'", function () {
    expect(true)->toBe(false);
  });

  it("can be declared with 'it' but without a function");
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

    $scope->customMatchers('toFTW', function () {
      return 1;
    });

    try {
      $scope->customMatchers('FAIL', null);
    } catch (\Exception $e) {}

    $it = function($desc, $test = null) use ($scope) { $scope->push($desc, $test); };
    $let = function($key, $value = null) use ($scope) { $scope->local($key, $value); };
    $describe = function($desc, $cases) use ($scope) { $scope->add($desc, $cases); };

    $expect = function($value) { return \Spectre\Expect::that($value); };

    $before = function ($block) use ($scope) { $scope->prepend($block); };
    $beforeEach = function ($block) use ($scope) { $scope->prepend($block, true); };

    $after = function ($block) use ($scope) { $scope->append($block); };
    $afterEach = function ($block) use ($scope) { $scope->append($block, true); };

    $after(function () {});
    $afterEach(function () {});

    $before(function () {});
    $beforeEach(function () {});

    $describe('x', function () use ($it, $let, $expect) {
      $let('test', $expect);
      $let('a', 'b');
      $it('y', function ($a, $test) {
        $test(1)->toBe(1);
        $test($a)->toBe('b');
        $test(function ($test) {
          $test(null)->toFTW();
          $test(null)->toRawwwr();
        })->toThrow();
      });
      $it('x');
    });

    $describe('z', function () {});

    $scope->log(function() {});

    expect(json_encode($scope->run()))->toBe('{"groups":{"x":{"results":{"y":[],"x":[]}}}}');
  });
});
