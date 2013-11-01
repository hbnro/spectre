<?php

namespace Habanero\Spectre;

use Habanero\Spectre\Base as Spectre,
    Habanero\Clipper\Params as GetOpts;

class Runner
{
  private static $cc;
  private static $params;
  private static $reporters = array('TAP', 'JSON', 'Basic');

  public static function execute(array $argv = array())
  {
    try {
      static::$params = new GetOpts($argv);
      static::$params->parse(array(
        'cover' => array('c', 'cover', GetOpts::PARAM_NO_VALUE),
        'exclude' => array('x', 'exclude', GetOpts::PARAM_MULTIPLE),
        'reporter' => array('r', 'reporter', GetOpts::PARAM_REQUIRED),
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
    $reporter = !empty(static::$params['reporter']) ? static::$params['reporter'] : 'Basic';

    if (!in_array($reporter, static::$reporters)) {
      throw new \Exception("Unknown '$reporter' reporter");
    }


    $xdebug = function_exists('xdebug_is_enabled') && xdebug_is_enabled();

    if ($xdebug && static::$params['cover']) {
      $cc = new \PHP_CodeCoverage(null, static::skip());

      $data = Spectre::run($cc);

      $html = new \PHP_CodeCoverage_Report_HTML;
      $html->process($cc, 'coverage/html-report');

      $clover = new \PHP_CodeCoverage_Report_Clover;
      $clover->process($cc, 'coverage/clover-report.xml');
    } else {
      $data = Spectre::run();
    }

    $klass = "\\Habanero\\Spectre\\Report\\$reporter";
    $tap = new $klass($data);

    echo $tap;
    exit((int) (!!$tap->status));
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
