<?php

describe('Spectre:', function () {
  describe('About locals:', function () {
    local('foo', 'bar');

    it('can set/read locals per scope', function ($foo) {
      expect($foo)->toBe('bar');
    });

    describe('But within another scope:', function () {
      it('would not exists those previous locals', function ($foo) {
        expect($foo)->toBeNull();
      });
    });
  });


  describe('About php-spec matchers:', function () {
    $tests = array(
      // TODO: improve this
      'toContain' => array(array(1, 2, 3), 2),
      'toBeLike' => array(1, '1'),
      'toBe' => array(1, 1),
      'toEqual' => array(1, 1),
      'toBeNull' => array(),
      'toBeString' => array(''),
      'toBeBoolean' => array(!0),
      'toBeObject' => array(new \stdClass),
      'toBeArray' => array(array()),
      'toBeInteger' => array(1),
      'toBeCallable' => array(function () {}),
      'toBeDouble' => array(1.0),
      'toBeFloat' => array(1.0),
      'toBeLong' => array(1),
      'toBeNumeric' => array('1.0'),
      'toBeReal' => array(1.0),
      'toBeScalar' => array('0'),
      'toEndWith' => array('abc', 'c'),
      'toStartWith' => array('def', 'd'),
      'toMatch' => array('bAr', '/[A-Z]/'),
      'toBeAnInstanceOf' => array(new \stdClass, 'stdClass'),
      'toThrow' => array(function () { throw new \Exception('unknown'); }),
    );


    foreach ($tests as $fn => $args) {
      $subject = array_shift($args);
      $callback = array(expect($subject), $fn);

      it(trim("testing $fn() matcher"), function () use ($callback, $args) {
        call_user_func_array($callback, $args);
      });
    }
  });
});
