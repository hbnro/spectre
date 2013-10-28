<?php

describe('Spectre:', function () {
  describe('About locals:', function () {
    local('foo', 'bar');
    $this->candy = 'does nothing';

    it('can set/read locals per scope', function ($candy, $foo) {
      expect($this->candy)->toBe('does nothing');
      expect($candy)->toBe('does nothing');
      expect($this->foo)->toBe('bar');
      expect($foo)->toBe('bar');
    });

    describe('But within another scope:', function () {
      it('would not exists those previous locals', function ($candy, $foo) {
        expect($this->candy)->toBeNull();
        expect($candy)->toBeNull();
        expect($this->foo)->toBeNull();
        expect($foo)->toBeNull();
      });
    });
  });

  describe('About matchers:', function () {
    $tests = [
      'toBe' => [1, 1],
      'toEqual' => [1, '1'],
      'toBeNull' => [null, null],
      'toBeTruthy' => [true, true],
      'toBeFalsy' => [false, false],
      'toContain' => ['candybar', 'andy'],
      'toBeEmpty' => [null],
      'toMatch' => ['bAr', '/[A-Z]/'],
      'toThrow' => [function () { throw new \Exception('unknown'); }],
    ];

    $length = sizeof($tests);

    describe("will handle correctly $length test matchers,", function () use ($tests) {
      foreach ($tests as $fn => $args) {
        $subject = array_shift($args);
        $callback = [expect($subject), $fn];

        $string_fn = preg_replace_callback('/[A-Z]/', function ($match) {
          return ' ' . strtolower($match[0]);
        }, $fn);

        $string_args = is_string($args[0]) ? preg_replace('/\s+/', '', var_export($args[0], 1)) : '';
        $string_subject = $subject instanceof \Closure ? '{closure}' : json_encode($subject);

        it(trim("expects $string_subject $string_fn $string_args"), function () use ($callback, $args) {
          call_user_func_array($callback, $args);
        });
      }
    });
  });
});
