<?php

namespace Spectre;

class Runner
{
  private static $cc;
  private static $params;
  private static $reporters = array('TAP', 'JSON', 'Basic');

  public static function execute(array $argv = array())
  {
    try {
      static::$params = new \Clipper\Params($argv);
      static::$params->parse(array(
        'file' => array('o', 'save', \Clipper\Params::PARAM_REQUIRED),
        'cover' => array('c', 'cover', \Clipper\Params::PARAM_NO_VALUE),
        'exclude' => array('x', 'exclude', \Clipper\Params::PARAM_MULTIPLE),
        'reporter' => array('r', 'reporter', \Clipper\Params::PARAM_REQUIRED),
      ));

      $files = static::prepare();

      foreach ($files as $spec) {
        require $spec;
      }

      static::run();
    } catch (\Exception $e) {
      echo $e->getMessage() . "\n";
      exit(1);
    }
  }

  private static function prepare()
  {
    $files = array();

    foreach (static::$params as $input) {
      if (is_dir($input)) {
        foreach (glob("$input/*-spec.php") as $one) {
          $files []= realpath($one);
        }
      } elseif (is_file($input)) {
        $files []= realpath($input);
      } else {
        throw new \Exception("The file or directory '$input' does not exists");
      }
    }

    return array_unique($files);
  }

  private static function run()
  {
    $start = microtime(true);
    $reporter = static::$params['reporter'];

    if ($reporter && !in_array($reporter, static::$reporters)) {
      throw new \Exception("Unknown '$reporter' reporter");
    }

    $xdebug = function_exists('xdebug_is_enabled') && xdebug_is_enabled();

    $shell = new \Clipper\Shell;
    $error = 0;

    \Spectre\Base::log(function ($test, $ok, $e) use ($shell, &$error) {
      $color = $ok ? 'green' : 'red';
      $result = $ok ? 'OK' : 'FAIL';

      $shell->printf("  <c:$color>* $test ... $result</c>\n");

      if ($e) {
        $error++;
        $shell->printf("    <c:red>$e</c>\n");
      }
    });

    $shell->printf("Spectre\n");
    $shell->printf("  <c:cyan>Running specs</c>\n");

    if ($xdebug && static::$params['cover']) {
      $cc = new \PHP_CodeCoverage(null, static::skip());

      $data = \Spectre\Base::run($cc);

      $html = new \PHP_CodeCoverage_Report_HTML;
      $html->process($cc, 'coverage/html-report');

      $clover = new \PHP_CodeCoverage_Report_Clover;
      $clover->process($cc, 'coverage/clover-report.xml');

      $shell->printf("  <c:cyan>Saved code-coverage</c>\n");
    } else {
      $data = \Spectre\Base::run();
    }


    if ($reporter || static::$params['file']) {
      $file = static::$params['file'] ?: 'report.' . strtolower($reporter);
      $reporter = $reporter ?: strtoupper(substr($file, strrpos($file, '.') + 1));

      $klass = "\\Spectre\\Report\\$reporter";
      $tap = new $klass($data);
      $txt = (string) $tap;

      $shell->printf("  <c:cyan>Saved $file</c>\n");

      file_put_contents($file, $txt);
    }


    $diff = round(microtime(true) - $start, 4);

    if ($error) {
      $shell->writeln("Done with errors ({$diff}s)");
    } else {
      $shell->writeln("Done ({$diff}s)");
    }

    exit((int) (!!$error));
  }

  private static function skip()
  {
    $filter = new \PHP_CodeCoverage_Filter;
    $ignore = static::$params['exclude'] ?: array();

    $ignore []= realpath(static::$params->caller());
    $ignore []= __FILE__; // TODO: how cover this?

    foreach ($ignore as $path) {
      if (is_dir($path)) {
        $filter->addDirectoryToBlacklist(realpath($path));
      } elseif (is_file($path)) {
        $filter->addFileToBlacklist(realpath($path));
      } else {
        throw new \Exception("The file or directory '$path' does not exists");
      }
    }

    return $filter;
  }
}
