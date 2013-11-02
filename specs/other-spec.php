<?php

describe('Spectre:', function () {
  describe('Other tests:', function () {
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
});
