<?php

describe('Spectre:', function () {
  describe('About scopes:', function () {
    it('(inception)', function () {
      describe('invisible spec', function () {
        let('candy', 'bar');
        it('baz', function () {});
      });
    });
  });
});
