<?php

describe('Code coverage', function () {
  it('Helpers::secs() should parse the time into readable time-format', function () {
    expect(\Spectre\Helpers::secs(3600))->toBe('1:00:00');
  });
});
