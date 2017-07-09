<?php

namespace Dazzle\Filesystem\Invoker;

use Dazzle\Filesystem\Driver\DriverAbstract;
use Dazzle\Filesystem\Invoker\Queue\QueueCall;
use Dazzle\Loop\LoopInterface;
use Dazzle\Promise\Promise;
use SplQueue;

class InvokerQueue implements InvokerInterface
{
    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @var DriverAbstract
     */
    protected $driver;

    /**
     * @var SplQueue
     */
    protected $queue;

    /**
     * @param DriverAbstract $driver
     */
    public function __construct(DriverAbstract $driver)
    {
        $this->loop = $driver->getLoop();
        $this->driver = $driver;
        $this->queue = new SplQueue();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function call($func, $args = [])
    {
        $promise = new Promise();

        $this->queue->enqueue(new QueueCall($func, $args, $promise));

        if (!$this->queue->isEmpty())
        {
            $this->processQueue();
        }

        return $promise
            ->then(function(QueueCall $call) {
                return $this->driver->call($call->func, $call->args);
            })
            ->then(
                $this->getQueueHandler('Dazzle\Promise\Promise::doResolve'),
                $this->getQueueHandler('Dazzle\Promise\Promise::doReject')
            );
    }

    /**
     * @override
     * @inheritDoc
     */
    public function isEmpty()
    {
        return $this->queue->isEmpty();
    }

    /**
     * Process queue.
     */
    protected function processQueue()
    {
        $this->loop->onTick(function() {
            if ($this->queue->isEmpty())
            {
                return;
            }

            $call = $this->queue->dequeue();
            $call->promise->resolve($call);
        });
    }

    /**
     * Get queue handler.
     *
     * @param callable $func
     * @return callable
     */
    protected function getQueueHandler(callable $func)
    {
        return function($mixed) use($func) {
            if (!$this->queue->isEmpty())
            {
                $this->processQueue();
            }
            return $func($mixed);
        };
    }
}
