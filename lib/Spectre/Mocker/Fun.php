<?php

namespace Spectre\Mocker;

class Fun
{
    private static $proxies = array();

    private $is_defined;
    private $function;
    private $callback;

    public static function factory($namespace, $function = null)
    {
        if (!$function) {
            $function = $namespace;
            $namespace = '';
        }

        $qualified = implode('\\', array($namespace, $function));

        static::$proxies[$qualified] = new static($qualified);

        return static::$proxies[$qualified];
    }

    public static function invoke($qualified, array $arguments)
    {
        $parts = explode('\\', $qualified);
        $function = end($parts);

        return call_user_func_array(array(static::$proxies[$qualified]->callback, $function), $arguments);
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
        $callback = $this->callback;

        if (!$this->is_defined) {
            $this->is_defined = true;

            if ($method === 'expects') {
                return call_user_func_array(array($this->callback, $method), $arguments)
                    ->method($this->function);
            }

            $callback = $this->callback->method($this->function);
        }

        return call_user_func_array(array($callback, $method), $arguments);
    }
}
