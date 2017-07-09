<?php

/**
 * ---------------------------------------------------------------------------------------------------------------------
 * DESCRIPTION
 * ---------------------------------------------------------------------------------------------------------------------
 * This file contains the example of using exists() with Filesystem.
 *
 * ---------------------------------------------------------------------------------------------------------------------
 * USAGE
 * ---------------------------------------------------------------------------------------------------------------------
 * To run this example in CLI from project root use following syntax
 *
 * $> php ./example/node_exists.php
 *
 * Following flags are supported to test example with different configurations:
 *
 * --driver  : define driver to use, default: standard, supported: [ standard, eio ]
 * --invoker : define invoker to use, default: standard, supported: [ standard, queue ]
 *
 * Ex:
 * $> php ./example/node_exists.php --driver=standard --invoker=standard
 *
 * ---------------------------------------------------------------------------------------------------------------------
 */

require_once __DIR__ . '/bootstrap/autoload.php';

use Dazzle\Filesystem\Filesystem;
use Dazzle\Loop\Model\SelectLoop;
use Dazzle\Loop\Loop;
use Dazzle\Promise\Promise;

$loop = new Loop(
    new SelectLoop()
);
$fsm = new Filesystem(
    new $driver($loop, [ 'root' => __DIR__ . '/data', 'invoker.class' => $invoker ])
);

$process = function() use($loop, $fsm) {
    $files = [
        '_file.txt', // this file should exist
        '_file_that_does_not_exist.txt', // this file should not exist
    ];
    $promises = [];

    foreach ($files as $file)
    {
        $promises[] = $fsm
            ->exists($file)
            ->then(function($result) use($file) {
                if ($result) {
                    echo "File [$file] exists!" . PHP_EOL;
                } else {
                    echo "File [$file] does not exist!" . PHP_EOL;
                }
            })
            ->failure(function($ex) {
                echo (string) $ex . PHP_EOL;
            });
    }

    Promise::all($promises)
        ->done(function() use($loop) {
            $loop->stop();
        });
};

$loop->onStart($process);
$loop->start();
