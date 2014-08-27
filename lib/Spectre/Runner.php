<?php

namespace Spectre;

class Runner
{
  public static $status = 0;

  private static $cc;
  private static $params;
  private static $reporters = array('TAP', 'JSON', 'Basic');

  public static function execute(array $argv = array())
  {
    static::$params = new \Clipper\Params($argv);
    static::$params->parse(array(
      'cover' => array('c', 'cover', \Clipper\Params::PARAM_NO_VALUE),
      'exclude' => array('x', 'exclude', \Clipper\Params::PARAM_MULTIPLE),
      'reporter' => array('r', 'reporter', \Clipper\Params::PARAM_REQUIRED),
    ));

    $files = static::prepare();

    foreach ($files as $spec) {
      require $spec;
    }

    static::run();
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

    \Spectre\Base::log(function ($color, $msg, $e = null) use ($shell, &$error) {
      $shell->printf($color ? "  <c:$color>$msg</c>\n" : "  $msg\n");

      if ($e) {
        $error++;
        $shell->printf("      <c:red>$e</c>\n");
      }
    });

    $shell->printf("Spectre\n");
    $shell->printf("  <c:cyan>Running specs</c>\n");

    if (static::$params['cover']) {
      if (!$xdebug) {
        throw new \Exception("Xdebug is required for code coverage but is missing");
      }

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
      $shell->printf("<c:red>Done with errors ({$diff}s)</c>\n");
    } else {
      $shell->printf("<c:green>Done ({$diff}s)</c>\n");
    }

    static::$status = $error ? 1 : 0;
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
