<?php

namespace Habanero\Spectre;

use Habanero\Spectre\Helpers as Dump;

class Expect
{
  private $result;
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

    $test = $this->callback($method, $arguments);

    // value interpolation
    $verb = preg_replace_callback('/[A-Z]/', function ($match) {
      return ' ' . strtolower($match[0]);
    }, $method);

    $repl = array(
      '{verb}' => trim($verb),
      '{value}' => join(', ', Dump::scalar($arguments)),
      '{subject}' => join('', Dump::scalar(array($this->expected))),
    );

    // reporting
    if ($this->negative ? $this->result : !$this->result) {
        throw new \Exception(strtr($this->negative ? $test->negative : $test->positive, $repl));
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

  private function callback($method, array $arguments)
  {
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
    $klass = "\\Habanero\\Spectre\\Matchers\\$method";

    $matcher = new $klass($this->expected);
    $this->result = call_user_func_array(array($matcher, 'execute'), $arguments);

    return $matcher;
  }
}
