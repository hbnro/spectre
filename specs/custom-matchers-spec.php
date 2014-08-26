<?php


namespace My\Custom\Tests;

class MyCustomMatcher extends \Spectre\Matchers\Base
{
  public function execute($value) {
    return $this->expected === $value;
  }
}

\Spectre\Base::addMatcher(MyCustomMatcher::class);
\Spectre\Base::addMatcher(MyCustomMatcher::class, 'toBeCustomValue');

describe('Spectre', function () {
  describe('Custom matchers:', function () {
    it('should validate custom matchers', function () {
      expect('foo')->toBeCustomValue('foo');
      expect('bar')->MyCustomMatcher('bar');
    });
  });
});