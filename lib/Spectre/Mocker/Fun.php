<?php

namespace Spectre\Mocker;

class Fun
{
    private static $proxies = array();

    private $function;
    private $callback;
    private $installed;

    public static function factory($namespace, $className = null)
    {
        if (!$className) {
            $className = $namespace;
            $namespace = '';
        }

        $qualified = implode('\\', array($namespace, $className));

        static::$proxies[$qualified] = new static($qualified);

        return static::$proxies[$qualified];
    }

    public static function invoke($qualified, array $arguments)
    {
        $parts = explode('\\', $qualified);
        $name = end($parts);

        return call_user_func_array(array(static::$proxies[$qualified]->callback, $name), $arguments);
    }

    public function __construct($qualified)
    {
        if (!function_exists($qualified)) {
            $parts = explode('\\', $qualified);
            $fun = array_pop($parts);
            $ns = implode('\\', $parts);

            $this->function = $fun;

            $this->callback = \Spectre\Mocker\Stub::factory($qualified)
                ->methods($fun)
                ->getMock();

            $template = new \Text_Template(implode(DIRECTORY_SEPARATOR, array(__DIR__, 'fn.tpl')));
            $template->setVar(array(
                'namespace' => $ns ? "namespace $ns;" : '',
                'fallback' => $fun,
            ));

            $code = $template->render();

            eval($code);
        }
    }

    public function __call($method, array $arguments)
    {
        if (!$this->installed) {
            $this->installed = true;

            if ($method === 'expects') {
                return $this->callback
                    ->expects($arguments[0])
                    ->method($this->function);
            }

            $this->callback->method($this->function);
        }

        return call_user_func_array(array($this->callback, $method), $arguments);
    }
}
