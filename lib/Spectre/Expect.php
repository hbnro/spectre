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

    private $values = array(
                    'toBeTrue' => true,
                    'toBeFalse' => false,
                  );

    private function __construct()
    {
    }

    public static function that($value)
    {
        $test = new self();

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

        if (isset($this->values[$method]) || isset($this->types[$method])) {
            array_unshift($arguments, isset($this->values[$method]) ? $this->values[$method] : $this->types[$method]);

            $method = 'toBe'.(!isset($this->values[$method]) ? 'A' : '');
        }

        if (!isset($matchers[$method])) {
            throw new \Exception("Unknown '$method' matcher");
        }

        array_unshift($arguments, $this->expected);

        try {
            $test = call_user_func_array($matchers[$method], $arguments);

            if ($test instanceof self) {
                $test = $test->last_result;
            }
        } catch (\Exception $e) {
            $test = false;
        }

        $params = array_merge(array(
      'result' => null,
      'positive' => "Expected '{subject}' {verb} '{value}', but it {verb_past} not",
      'negative' => "Did not expect '{subject}' {verb} '{value}', but it {verb_past}",
    ), is_array($test) ? $test : array(
      'result' => (bool) $test,
    ));

    // value interpolation
    $verb = preg_replace_callback('/[A-Z]/', function ($match) {
      return ' '.strtolower($match[0]);
    }, $method);

        $verb_past = (stripos($verb, 'to be') === 0 ? 'was' : 'did');

        $value = implode(', ', \Spectre\Helpers::scalar(array_slice($arguments, 1)));
        $subject = implode('', \Spectre\Helpers::scalar(array($this->expected)));

        $repl = array(
      '{verb}' => trim($verb),
      '{verb_past}' => $verb_past,
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
