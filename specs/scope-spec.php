<?php

describe('Spectre:', function () {
  describe('About scopes:', function () {
    it('(inception)', function () {
      describe('invisible spec', function () {
        let('candy', 'bar');
        it('baz', function () {});

        expect(1)->toBe(1);
        expect(0)->not->toBe(1);

        try { expect(1)->toBe(0); } catch (\Exception $e) {}
        try { expect(1)->not->toBe(1); } catch (\Exception $e) {}

        expect(function () { expect(0)->buzz; })->toThrow();
      });
    });
  });
});
