<?php

describe('Spectre:', function () {
  // TODO: do intensive testing!
  describe('About matchers:', function () {
    $tests = array(
      'toEqual' => array(1, '1'),
      '!toEqual' => array(1, 2),
      'toBe' => array(1, 1),
      '!toBe' => array(1, '1'),
      'toMatch' => array('bAz', '/A/'),
      '!toMatch' => array('FoO', '/f/'),
      'toBeNull' => array(),
      '!toBeNull' => array(1),
      'toBeTruthy' => array('1'),
      '!toBeTruthy' => array(),
      'toBeFalsy' => array(),
      '!toBeFalsy' => array('2'),
      'toContain' => array('candy', 'andy'),
      '!toContain' => array(array(1, 2, 3), 4),
      'toBeLessThan' => array(2, 3),
      '!toBeLessThan' => array(3, 2),
      'toBeGreaterThan' => array(array(1, 2), array()),
      '!toBeGreaterThan' => array(array(), array(2, 1)),
      'toBeEmpty' => array(),
      '!toBeEmpty' => array(array(1)),
      'toBeString' => array(''),
      '!toBeString' => array(),
      'toBeInteger' => array(1),
      '!toBeInteger' => array('1'),
      'toBeArray' => array(array()),
      '!toBeArray' => array(),
      'toBeInstanceOf' => array(new \stdClass, 'stdClass'),
      '!toBeInstanceOf' => array(1, '1'),
      'toBeLike' => array(2, '2'),
      '!toBeLike' => array(2, 1),
      'toBeBoolean' => array(!0),
      '!toBeBoolean' => array(),
      'toBeObject' => array(new \stdClass),
      '!toBeObject' => array(),
      'toBeCallable' => array('strlen'),
      '!toBeCallable' => array(),
      'toBeDouble' => array(1.0),
      '!toBeDouble' => array('1.0'),
      'toBeFloat' => array(2.0),
      '!toBeFloat' => array('2.0'),
      'toBeLong' => array(3),
      '!toBeLong' => array('3'),
      'toBeNumeric' => array('13.20'),
      '!toBeNumeric' => array(),
      'toBeReal' => array(4.0),
      '!toBeReal' => array('4.0'),
      'toBeScalar' => array(true),
      '!toBeScalar' => array(),
      'toEndWith' => array('foo', 'o'),
      '!toEndWith' => array('bar', 'baz'),
      'toStartWith' => array('abc', 'a'),
      '!toStartWith' => array('def', 'f'),
      'toThrow' => array(function () { throw new \Exception; }),
      '!toThrow' => array(function () {}),
    );

    foreach ($tests as $fn => $args) {
      $subject = array_shift($args);

      if (substr($fn, 0, 1) === '!') {
        $callback = array(expect($subject)->not, substr($fn, 1));
        $kall = 'NOT ' . substr($fn, 1);
      } else {
        $callback = array(expect($subject), $fn);
        $kall = $fn;
      }

      it("testing $kall() matcher", function () use ($callback, $args) {
        call_user_func_array($callback, $args);
      });
    }
  });
});
