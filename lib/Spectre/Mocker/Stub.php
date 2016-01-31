<?php

namespace Spectre\Mocker;

class Stub
{
    private $type = null;
    private $name = null;

    private $arguments = array();
    private $attributes = array();

    private static $builder;

    private static $factories = array(
        'getMock' => array(
            'methods' => array(),
            'arguments' => array(),
            'class' => '',
            'constructor' => true,
            'clone' => true,
            'autoload' => true,
            'cloneArgs' => false,
            'callMethods' => false,
        ),
        'getMockClass' => array(
            'methods' => array(),
            'arguments' => array(),
            'class' => '',
            'constructor' => false,
            'clone' => true,
            'autoload' => true,
            'cloneArgs' => false,
        ),
        'getMockForAbstractClass' => array(
            'arguments' => array(),
            'class' => '',
            'constructor' => true,
            'clone' => true,
            'autoload' => true,
            'mockedMethods' => array(),
            'cloneArgs' => false,
        ),
        'getMockForTrait' => array(
            'arguments' => array(),
            'class' => '',
            'constructor' => true,
            'clone' => true,
            'autoload' => true,
            'mockedMethods' => array(),
            'cloneArgs' => false,
        ),
        'getObjectForTrait' => array(
            'arguments' => array(),
            'trait' => '',
            'constructor' => true,
            'clone' => true,
            'autoload' => true,
            'cloneArgs' => false,
        ),
    );

    public static function __callStatic($method, $arguments)
    {
        if (isset(static::$factories[$method])) {
            return new static($method, $arguments[0]);
        }
    }

    public function __construct($type, $className)
    {
        $this->type = $type;
        $this->name = $className;
    }

    public function __call($method, $arguments = array())
    {
        $this->attributes[$method] = $arguments;

        return $this;
    }

    public function build()
    {
        if (!static::$builder) {
            static::$builder = new \PHPUnit_Framework_MockObject_Generator();
        }

        $args = array();
        $args [] = $this->name;

        foreach (static::$factories[$this->type] as $key => $val) {
            if (!isset($this->attributes[$key])) {
                $args [] = $val;
            } elseif (is_array($val)) {
                $args [] = is_array($this->attributes[$key][0]) ? $this->attributes[$key][0] : $this->attributes[$key];
            } else {
                $args [] = $this->attributes[$key][0];
            }
        }

        return call_user_func_array(array(static::$builder, $this->type), $args);
    }
}
