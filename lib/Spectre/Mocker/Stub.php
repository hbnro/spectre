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
            'mockClassName' => '',
            'callOriginalConstructor' => true,
            'callOriginalClone' => true,
            'callAutoload' => true,
            'cloneArguments' => false,
            'callOriginalMethods' => false,
        ),
        'getMockClass' => array(
            'methods' => array(),
            'arguments' => array(),
            'mockClassName' => '',
            'callOriginalConstructor' => false,
            'callOriginalClone' => true,
            'callAutoload' => true,
            'cloneArguments' => false,
        ),
        'getMockForAbstractClass' => array(
            'arguments' => array(),
            'mockClassName' => '',
            'callOriginalConstructor' => true,
            'callOriginalClone' => true,
            'callAutoload' => true,
            'mockedMethods' => array(),
            'cloneArguments' => false,
        ),
        'getMockForTrait' => array(
            'arguments' => array(),
            'mockClassName' => '',
            'callOriginalConstructor' => true,
            'callOriginalClone' => true,
            'callAutoload' => true,
            'mockedMethods' => array(),
            'cloneArguments' => false,
        ),
        'getObjectForTrait' => array(
            'arguments' => array(),
            'mockClassName' => '',
            'callOriginalConstructor' => true,
            'callOriginalClone' => true,
            'callAutoload' => true,
            'cloneArguments' => false,
        ),
    );

    public function __construct($className = null)
    {
        $this->className = $className;
    }

    public function __call($method, $arguments = array())
    {
        if (isset(static::$factories[$method])) {
            $className = isset($arguments[0]) ? $arguments[0] : $this->className;

            return $this->build($method, $className);
        }

        $this->attributes[$method] = $arguments;

        return $this;
    }

    public static function factory($namespace, $className = null)
    {
        if (!static::$builder) {
            static::$builder = new \PHPUnit_Framework_MockObject_Generator();
        }

        if (!$className) {
            $className = $namespace;
            $namespace = '';
        }

        return new static(implode('\\', array($namespace, $className)));
    }

    private function build($type, $name)
    {
        $args = array($name);

        foreach (static::$factories[$type] as $key => $val) {
            if (!isset($this->attributes[$key])) {
                $args [] = $val;
            } elseif (is_array($val)) {
                $args [] = is_array($this->attributes[$key][0]) ? $this->attributes[$key][0] : $this->attributes[$key];
            } else {
                $args [] = $this->attributes[$key][0];
            }
        }

        $mock = @call_user_func_array(array(static::$builder, $type), $args);

        if (isset(\Spectre\Base::$node->mocks)) {
            \Spectre\Base::$node->mocks [] = $mock;
        }

        return $mock;
    }
}
