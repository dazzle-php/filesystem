<?php

/**
 * ---------------------------------------------------------------------------------------------------------------------
 * DESCRIPTION
 * ---------------------------------------------------------------------------------------------------------------------
 * This file contains the example of using symlink() with Filesystem.
 *
 * ---------------------------------------------------------------------------------------------------------------------
 * USAGE
 * ---------------------------------------------------------------------------------------------------------------------
 * To run this example in CLI from project root use following syntax
 *
 * $> php ./example/node_symlink.php
 *
 * Following flags are supported to test example with different configurations:
 *
 * --driver  : define driver to use, default: standard, supported: [ standard, eio ]
 * --invoker : define invoker to use, default: standard, supported: [ standard, queue ]
 *
 * Ex:
 * $> php ./example/node_symlink.php --driver=standard --invoker=standard
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
    new $driver($loop, [ 'root' => __DIR__, 'invoker.class' => $invoker ])
);

$process = function() use($loop, $fsm) {
    $srcPath = 'node_readlink.php';
    $dstPath = 'node_readlink.link.php';

    Promise::doResolve()
        ->then(function() use($fsm, $srcPath, $dstPath) {
            return $fsm->symlink($srcPath, $dstPath);
        })
        ->then(function($result) use($fsm, $dstPath) {
            echo 'Symlink has been created with status=' . var_export($result, true) . PHP_EOL;
            return $fsm->unlink($dstPath);
        })
        ->failure(function($ex) {
            echo (string) $ex . PHP_EOL;
        })
        ->done(function() use($loop) {
            $loop->stop();
        });
};

$loop->onStart($process);
$loop->start();
