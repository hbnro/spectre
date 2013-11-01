<?php

describe('Spectre:', function () {
  describe('About locals:', function () {
    let('foo', 'bar');

    it('can set/read locals per scope', function ($foo) {
      expect($foo)->toBe('bar');
    });

    describe('But within another scope:', function () {
      it('would not exists those previous locals', function ($foo) {
        expect($foo)->toBeNull();
      });
    });
  });
});
