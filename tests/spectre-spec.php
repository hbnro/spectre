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

  describe('About matchers:', function () {
    $tests = array(
      'toBe' => array(1, 1),
      'toEqual' => array(1, '1'),
      'toBeNull' => array(null, null),
      'toBeTruthy' => array(true, true),
      'toBeFalsy' => array(false, false),
      'toContain' => array('candybar', 'andy'),
      'toBeEmpty' => array(null),
      'toMatch' => array('bAr', '/[A-Z]/'),
      'toThrow' => array(function () { throw new \Exception('unknown'); }),
    );

    $length = sizeof($tests);

    describe("will handle correctly $length test matchers,", function () use ($tests) {
      foreach ($tests as $fn => $args) {
        $subject = array_shift($args);
        $callback = array(expect($subject), $fn);

        $string_fn = preg_replace_callback('/[A-Z]/', function ($match) {
          return ' ' . strtolower($match[0]);
        }, $fn);

        $string_args = '';
        $string_subject = $subject instanceof \Closure ? '{closure}' : json_encode($subject);

        if (!empty($args[0]) && is_string($args[0])) {
          $string_args = preg_replace('/\s+/', '', var_export($args[0], 1));
        }

        it(trim("expects $string_subject $string_fn $string_args"), function () use ($callback, $args) {
          call_user_func_array($callback, $args);
        });
      }
    });
  });
});
