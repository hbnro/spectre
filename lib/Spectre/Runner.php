<?php

namespace Spectre;

use \Clipper\Params;

class Runner
{
  private static $cc;
  private static $params;
  private static $reporters = array('TAP', 'JSON', 'Basic');

  public static function execute()
  {
    $xdebug = function_exists('xdebug_is_enabled') && xdebug_is_enabled();

    try {
      static::$params = new Params(array(
        'cover' => array('c', 'cover', Params::PARAM_NO_VALUE),
        'exclude' => array('x', 'exclude', Params::PARAM_MULTIPLE),
        'reporter' => array('r', 'reporter', Params::PARAM_REQUIRED),
      ));

      if ($xdebug && static::$params['cover']) {
        $filter = new \PHP_CodeCoverage_Filter;

        if (!empty(static::$params['exclude'])) {
          foreach (static::$params['exclude'] as $path) {
            if (is_dir($path)) {
              $filter->addDirectoryToBlacklist(realpath($path));
            } elseif (is_file($path)) {
              $filter->addFileToBlacklist(realpath($path));
            } else {
              throw new \Exception("The file or directory '$path' does not exists");
            }
          }
        }
      }

      $files = static::prepare();

      foreach ($files as $spec) {
        if (isset($filter)) {
          static::$cc []= static::instrument($spec, $filter);
        }

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

  private static function instrument($file, $filter)
  {
    $coverage = new \PHP_CodeCoverage(null, $filter);
    $coverage->start($file);

    return $coverage;
  }

  private static function report()
  {
    $output = new \PHP_CodeCoverage;

    foreach (static::$cc as $coverage) {
      $coverage->stop();
      $output->merge($coverage);
    }

    $html = new \PHP_CodeCoverage_Report_HTML;
    $html->process($output, 'coverage/html-report');

    $clover = new \PHP_CodeCoverage_Report_Clover;
    $clover->process($output, 'coverage/clover-report.xml');
  }

  private static function run()
  {
    $reporter = !empty(static::$params['reporter']) ? static::$params['reporter'] : 'Basic';

    if (!in_array($reporter, static::$reporters)) {
      throw new \Exception("Unknown '$reporter' reporter");
    }

    $data = \Spectre\Base::instance()->run();

    if (!$data) {
      echo "Missing specs\n";
      exit(1);
    } elseif (!empty(static::$cc)) {
      static::report();
    }

    $klass = "\\Spectre\\Report\\$reporter";
    $tap = new $klass($data);

    echo $tap;
    exit((int) (!!$tap->status));
  }
}
