<?php

class Foo {
    public function bar() {
        return $this->baz().'!';
    }

    public function baz() {
        return 'buzz';
    }
}

describe('Mocker', function () {
    it('can mock instance methods', function () {
        $stub = spy('Foo')
            ->methods('baz')
            ->callOriginalMethods(false)
            ->getMock();

        $stub->expects(exactly(1))
            ->method('baz')
            ->will(returnValue('foo'));

        expect($stub->bar())->toEqual('foo!');
    });
});
