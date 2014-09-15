<?php

namespace Spectre;

class Expect
{
  private $last_result;

  private $expected;
  private $negative;

  private $types = array(
                    'toBeArray' => 'array',
                    'toBeBool' => 'bool',
                    'toBeBoolean' => 'bool',
                    'toBeCallable' => 'callable',
                    'toBeDouble' => 'double',
                    'toBeFloat' => 'float',
                    'toBeInt' => 'int',
                    'toBeInteger' => 'integer',
                    'toBeLong' => 'long',
                    'toBeNull' => 'null',
                    'toBeNumeric' => 'numeric',
                    'toBeObject' => 'object',
                    'toBeReal' => 'real',
                    'toBeResource' => 'resource',
                    'toBeScalar' => 'scalar',
                    'toBeString' => 'string',
                  );

  private function __construct() {}

  public static function that($value)
  {
    $test = new self;

    if ($value instanceof \Closure) {
      $test->expected = function () use ($value) {
        Helpers::invoke($value, Base::$node);
      };
    } else {
      $test->expected = $value;
    }

    return $test;
  }

  public function __call($method, array $arguments)
  {
    $matchers = Base::customMatchers();

    if (isset($this->types[$method])) {
      array_unshift($arguments, $this->types[$method]);

      $method = 'toBeA';
    }

    if (!isset($matchers[$method])) {
      throw new \Exception("Unknown '$method' matcher");
    }

    array_unshift($arguments, $this->expected);

    try {
      $test = call_user_func_array($matchers[$method], $arguments);

      if ($test instanceof Expect) {
        $test = $test->last_result;
      }
    } catch (\Exception $e) {
      $test = false;
    }

    $params = array_merge(array(
      'result' => null,
      'positive' => "Expected '{subject}' {verb} '{value}', but it does not",
      'negative' => "Not expected '{subject}' {verb} '{value}', but it does",
    ), is_array($test) ? $test : array(
      'result' => !!$test,
    ));

    // value interpolation
    $verb = preg_replace_callback('/[A-Z]/', function ($match) {
      return ' ' . strtolower($match[0]);
    }, $method);

    $value = join(', ', \Spectre\Helpers::scalar(array_slice($arguments, 1)));
    $subject = join('', \Spectre\Helpers::scalar(array($this->expected)));

    $repl = array(
      '{verb}' => trim($verb),
      '{value}' => "<debug>$value</debug>",
      '{subject}' => "<debug>$subject</debug>",
    );

    $this->last_result = $this->negative ^ $params['result'];

    // reporting
    if (!$this->last_result) {
      $message = strtr($this->negative ? $params['negative'] : $params['positive'], $repl);
      $message = preg_replace('/<debug>\s*<\/debug>/s', '', $message);

      throw new \Exception($message);
    }

    $this->negative = false;

    return $this;
  }

  public function __get($key)
  {
    switch ($key) {
      case 'and';
      break;

      case 'not';
        $this->negative = !$this->negative;
      break;

      default:
        throw new \Exception("The '$key' operator is not implemented");
    }

    return $this;
  }
}
