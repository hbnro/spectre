<?php

namespace Spectre;

class Runner
{
  private static $cc;
  private static $cli;
  private static $reporters = array('TAP', 'JSON', 'Basic');

  public static function initialize($shell)
  {
    static::$cli = $shell;
  }

  public static function execute()
  {
    foreach (array_keys(static::watch()) as $spec) {
      require $spec;
    }

    return static::run();
  }

  public static function watch()
  {
    $files = array();

    foreach (static::$cli->params as $input) {
      if (is_dir($input)) {
        foreach (glob("$input/*-spec.php") as $one) {
          $files[realpath($one)] = filemtime($one);
        }
      } elseif (is_file($input)) {
        $files[realpath($input)] = filemtime($input);
      } else {
        throw new \Exception("The file or directory '$input' does not exists");
      }
    }

    return $files;
  }

  private static function run()
  {
    $start = microtime(true);
    $reporter = static::$cli->params['reporterOutput'];

    if ($reporter && !in_array($reporter, static::$reporters)) {
      throw new \Exception("Unknown '$reporter' reporter");
    }

    $xdebug = function_exists('xdebug_is_enabled') && xdebug_is_enabled();

    $shell = static::$cli;
    $error = 0;

    \Spectre\Base::log(function ($color = null, $tabs = null, $msg = null, $e = null) use ($shell, &$error) {
      if (!$msg) {
        $shell->writeln();
      } else {
        $indent = str_repeat('  ', $tabs);

        $shell->printf($color ? "$indent<c:$color>$msg</c>\n" : "$indent$msg\n");

        if ($e) {
          $error++;
          $shell->printf("$indent<c:red>$e</c>\n");
        }
      }
    });

    $shell->printf("\n<c:light_cyan>Running specs...</c>\n");

    if (static::$cli->params['codeCoverage']) {
      if (!$xdebug) {
        throw new \Exception('Xdebug is required for code coverage but is missing');
      }

      $cc = new \PHP_CodeCoverage(null, static::skip());

      $data = \Spectre\Base::run($cc);

      $html = new \PHP_CodeCoverage_Report_HTML;
      $html->process($cc, 'coverage/html-report');

      $clover = new \PHP_CodeCoverage_Report_Clover;
      $clover->process($cc, 'coverage/clover-report.xml');

      $shell->printf("<c:cyan>Saved code-coverage</c>\n");
    } else {
      $data = \Spectre\Base::run();
    }

    if ($reporter || static::$cli->params['reportFile']) {
      $file = static::$cli->params['reportFile'] ?: 'report.' . strtolower($reporter);
      $reporter = $reporter ?: strtoupper(substr($file, strrpos($file, '.') + 1));

      $klass = "\\Spectre\\Report\\$reporter";
      $tap = new $klass($data);
      $txt = (string) $tap;

      $shell->printf("<c:cyan>Saved $file</c>\n");

      file_put_contents($file, $txt);
    }

    $diff = round(microtime(true) - $start, 4);

    if ($error) {
      $shell->printf("<c:red>Done with errors ({$diff}s)</c>\n");
    } else {
      $shell->printf("<c:green>Done ({$diff}s)</c>\n");
    }

    return $error ? 1 : 0;
  }

  private static function skip()
  {
    $filter = new \PHP_CodeCoverage_Filter;
    $ignore = static::$cli->params['excludeSources'] ?: array();

    $ignore []= realpath(static::$cli->params->caller());
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
