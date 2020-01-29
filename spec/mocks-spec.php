<?php

namespace Spectre\Test;

class Foo
{
    public function bar()
    {
        return $this->baz().'!';
    }

    public function baz()
    {
        return 'buzz';
    }

    public function bazzinga()
    {
        return phpversion();
    }
}

describe('Mocker', function () {
    it('can mock instance methods', function () {
        $stub = spy(__NAMESPACE__, 'Foo')
            ->methods('baz')
            ->callOriginalMethods(false)
            ->getMock();

        $stub->expects(exactly(1))
            ->method('baz')
            ->will(returnValue('foo'));

        expect($stub->bar())->toEqual('foo!');
    });

    it('can mock global functions', function () {
        $stub = fun(__NAMESPACE__, 'phpversion')
            ->expects(exactly(2))
            ->will(returnValue(42));

        $x = new Foo();

        expect(phpversion())->toEqual(42);
        expect($x->bazzinga())->toEqual(42);
    });
});
