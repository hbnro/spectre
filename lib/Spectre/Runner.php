<?php

namespace Spectre;

class Runner
{
    private static $cc;
    private static $cli;
    private static $reporters = array('TAP', 'JSON');

    public static function initialize($shell)
    {
        static::$cli = $shell;
    }

    public static function execute()
    {
        $filter = static::$cli->params->filterSpecs ? '<('.static::$cli->params->filterSpecs.')>' : false;

        foreach (array_keys(static::watch()) as $spec) {
            if (preg_match('/-spec\.php$/', $spec)) {
                if ($filter && !@preg_match($filter, $spec)) {
                    continue;
                }

                require $spec;
            }
        }

        return static::run();
    }

    public static function watch()
    {
        return static::ls(static::$cli->params);
    }

    private static function run()
    {
        $start = microtime(true);
        $xdebug = function_exists('xdebug_is_enabled') && xdebug_is_enabled();
        $shell = static::$cli;
        $error = 0;

        \Spectre\Base::log(function () use ($shell, &$error) {
            @list($color, $tabs, $msg, $e) = func_get_args();

            if (!$msg) {
                $shell->writeln();
            } else {
                $indent = str_repeat('  ', $tabs);

                $shell->printf($color ? "$indent<c:$color>$msg</c>\n" : "$indent$msg\n");

                if ($e) {
                    ++$error;
                    $debug = implode("\n$indent  ", explode("\n", $e));
                    $shell->printf("$indent  $debug\n");
                }
            }
        });

        $shell->printf("\n<c:light_cyan>Running specs...</c>\n");

        if (static::$cli->params->codeCoverage) {
            if (!$xdebug) {
                throw new \Exception('Xdebug is required for code coverage but is missing');
            }

            $cc = new \PHP_CodeCoverage(null, static::skip());

            $data = \Spectre\Base::run($cc);

            $html = new \PHP_CodeCoverage_Report_HTML();
            $html->process($cc, 'coverage/html-report');

            $clover = new \PHP_CodeCoverage_Report_Clover();
            $clover->process($cc, 'coverage/clover-report.xml');

            $shell->printf("<c:cyan>Saved code-coverage</c>\n");
        } else {
            $data = \Spectre\Base::run();
        }

        if (static::$cli->params->reportOutput || static::$cli->params->reportFile) {
            $file = static::$cli->params->reportFile ?: 'report.'.strtolower(static::$cli->params->reportOutput);
            $reporter = static::$cli->params->reportOutput ?: strtoupper(substr($file, strrpos($file, '.') + 1));

            if (!in_array($reporter, static::$reporters)) {
                throw new \Exception("Unknown '$reporter' reporter");
            }

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
        $filter = new \PHP_CodeCoverage_Filter();
        $ignore = static::$cli->params->excludeSources ?: array();

        $ignore [] = realpath(static::$cli->params->getCommand());
        $ignore [] = __FILE__; // TODO: how cover this?

    foreach ($ignore as $path) {
        if (is_dir($path)) {
            $filter->addDirectoryToBlacklist(realpath($path));
        } elseif (is_file($path)) {
            $filter->addFileToBlacklist(realpath($path));
        } else {
            throw new \Exception("The file or directory '$path' does not exist");
        }
    }

        return $filter;
    }

    private static function ls($set)
    {
        $files = array();

        foreach ($set as $input) {
            if (is_dir($input)) {
                foreach (glob("$input/*") as $one) {
                    if (preg_match('/\.php$/', $one)) {
                        $files[realpath($one)] = filemtime($one);
                    } else {
                        $files = array_merge($files, static::ls(glob("$one/*")));
                    }
                }
            } elseif (is_file($input)) {
                $files[realpath($input)] = filemtime($input);
            } else {
                throw new \Exception("The file or directory '$input' does not exist");
            }
        }

        return $files;
    }
}
