<?php

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
