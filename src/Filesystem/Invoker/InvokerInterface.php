<?php

namespace Dazzle\Filesystem\Invoker;

use Dazzle\Promise\PromiseInterface;

interface InvokerInterface
{
    /**
     * Call the given function $func with the given $arg when appropriate for the concrete invoker.
     *
     * @param string $func
     * @param array $args
     * @return PromiseInterface
     */
    public function call($func, $args = []);

    /**
     * Return whether there are calls waiting to be called.
     *
     * @return bool
     */
    public function isEmpty();
}
