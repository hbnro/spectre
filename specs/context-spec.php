<?php

describe('About context', function () {
    let('global', 'VALUE');

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
});