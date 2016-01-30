<?php

namespace Spectre\Context;

class ProxyClass
{
    private $_fakeProps = array();

    public function __call($method, $arguments) {
        if (isset($this->_fakeProps[$method]) && is_callable($this->_fakeProps[$method])) {
            return call_user_func_array($this->_fakeProps[$method], $arguments);
        }

        if (substr($method, 0, 1) !== '_') {
            return call_user_func_array(array($this, '__' . $method), $arguments);
        }
    }

    public function __set($method, $value) {
        $this->_fakeProps[$method] = $value;
    }

    public function __get($property) {
        if (isset($this->_fakeProps[$property]) && !is_callable($this->_fakeProps[$property])) {
            return $this->_fakeProps[$property];
        }
    }

    public function __spy($method, $value = null) {
        $var = array();

        $this->_fakeProps[$method] = function () use (&$var, $value) {
            $var = func_get_args();
            return $value;
        };

        return function () use (&$var) {
            return $var;
        };
    }
}
