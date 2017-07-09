<?php

namespace Dazzle\Filesystem\Invoker\Queue;

use Dazzle\Promise\PromiseInterface;

class QueueCall
{
    /**
     * @var string
     */
    public $func;

    /**
     * @var array
     */
    public $args;

    /**
     * @var PromiseInterface
     */
    public $promise;

    /**
     * @param string $func
     * @param array $args
     * @param PromiseInterface $promise
     */
    public function __construct($func, $args = [], PromiseInterface $promise)
    {
        $this->func = $func;
        $this->args = $args;
        $this->promise = $promise;
    }
}
