<?php

namespace Spectre;

class Expect
{
  private $expected;
  private $negative;

  private $scalars = array(
                      'array', 'bool',
                      'callable', 'double',
                      'float', 'int', 'integer', 'long',
                      'null', 'numeric', 'object', 'real',
                      'resource', 'scalar', 'string',
                    );

  private $alias = array(
                    'boolean' => 'bool',
                  );

  private function __construct() {}

  public static function that($value)
  {
    $test = new self;
    $test->expected = $value;

    return $test;
  }

  public function __call($method, array $arguments)
  {
    // TODO: custom matchers

    // is_* matching
    @list(, $type) = explode('Be', $method);

    $type = strtolower($type);
    $type = isset($this->alias[$type]) ? $this->alias[$type] : $type;

    if (in_array(strtolower($type), $this->scalars)) {
      array_unshift($arguments, $type);

      $method = 'Is';
    }

    // default matchers
    $method = ucfirst($method);
    $klass = "\\Spectre\\Matchers\\$method";

    $test = new $klass($this->expected);
    $result = call_user_func_array(array($test, 'execute'), $arguments);

    // value interpolation
    $verb = preg_replace_callback('/[A-Z]/', function ($match) {
      return ' ' . strtolower($match[0]);
    }, $method);

    $repl = array(
      '{verb}' => trim($verb),
      '{value}' => \Spectre\Base::scalar($arguments),
      '{subject}' => \Spectre\Base::scalar(array($this->expected)),
    );

    // reporting
    if ($this->negative ? $result : !$result) {
      if ($this->negative) {
        throw new \Exception(strtr($test->negative, $repl));
      } else {
        throw new \Exception(strtr($test->positive, $repl));
      }
    }
  }

  public function __get($key)
  {
    if ($key === 'not') {
      $this->negative = !$this->negative;
    } else {
      throw new \Exception("The '$key' operator is not implemented");
    }

    return $this;
  }
}
