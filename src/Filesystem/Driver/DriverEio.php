<?php

namespace Dazzle\Filesystem\Driver;

use Dazzle\Filesystem\Invoker\InvokerInterface;
use Dazzle\Filesystem\Invoker\InvokerStandard;
use Dazzle\Loop\LoopInterface;
use Dazzle\Promise\Promise;
use Dazzle\Promise\PromiseInterface;
use Dazzle\Throwable\Exception\Runtime\ExecutionException;
use Dazzle\Throwable\Exception\Runtime\UnexpectedValueException;

class DriverEio extends DriverAbstract implements DriverInterface
{
    /**
     * @var InvokerInterface
     */
    protected $invoker;

    /**
     * @var resource
     */
    protected $stream;

    /**
     * @var bool
     */
    protected $active = false;

    /**
     * @param LoopInterface $loop
     * @param array $options
     */
    public function __construct(LoopInterface $loop, $options = [])
    {
        eio_init();
        $this->loop = $loop;
        $this->options = $this->createConfiguration($options);
        $this->invoker = $this->createInvoker();
        $this->stream = eio_get_event_stream();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function stat($path)
    {
        return $this->invoker
            ->call('eio_lstat', [ $this->getPath($path) ])
            ->then([ $this, 'handleStat' ]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function chmod($path, $mode)
    {
        return $this->invoker
            ->call('eio_chmod', [ $this->getPath($path), decoct($mode) ])
            ->then([ $this, 'handleChmod' ]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function chown($path, $uid = -1, $gid = -1)
    {
        return $this->invoker
            ->call('eio_chown', [ $this->getPath($path), $uid, $gid ])
            ->then([ $this, 'handleChown' ]);
    }

    /**
     * @internal
     * @override
     * @inheritDoc
     */
    public function call($func, $args = [])
    {
        $loop = $this->loop;

        if ($loop->isRunning())
        {
            return $this->callDelayed($func, $args);
        }

        $promise = new Promise();
        $loop->onTick(function() use($func, $args, $promise) {
            $this
                ->callDelayed($func, $args)
                ->then(function($result) use($promise) {
                    return $promise->resolve($result);
                })
                ->failure(function($ex) use($promise) {
                    return $promise->reject($ex);
                });
        });

        return $promise;
    }

    /**
     * @param string $func
     * @param array $args
     * @return PromiseInterface
     */
    protected function callDelayed($func, $args = [])
    {
        $this->register();

        $promise = new Promise();

        $args[] = EIO_PRI_DEFAULT;
        $args[] = function($data, $result, $req) use($func, $args, $promise) {

            if ($result == -1)
            {
                $ex = new UnexpectedValueException(@eio_get_last_error($req));
                $ex->setContext($args);
                return $promise->reject($ex);
            }

            return $promise->resolve($result);
        };

        if (!$func(...$args))
        {
            $name = is_string($func) ? $func : get_class($func);
            $ex = new ExecutionException('Unknown error in response for "' . $name . '"');
            $ex->setContext($args);
            return $promise->reject($ex);
        };

        return $promise;
    }

    protected function register()
    {
        if ($this->active) {
            return;
        }

        $this->active = true;
        $this->loop->addReadStream($this->stream, [ $this, 'handleEvent' ]);
    }

    protected function unregister()
    {
        if (!$this->active) {
            return;
        }

        $this->active = false;
        $this->loop->removeReadStream($this->stream);
    }

    public function handleEvent()
    {
        if ($this->workPendingCount() == 0)
        {
            return;
        }

        while (eio_npending())
        {
            eio_poll();
        }

        if ($this->workPendingCount() == 0)
        {
            $this->unregister();
        }
    }

    public function workPendingCount()
    {
        return eio_nreqs() + eio_npending() + eio_nready();
    }

    /**
     * Get path.
     *
     * @param string $path
     * @return string
     */
    protected function getPath($path)
    {
        return $this->options['root'] . '/' . ltrim($path, '/');
    }

    /**
     * Create valid configuration for the driver.
     *
     * @param array $options
     * @return array
     */
    protected function createConfiguration($options = [])
    {
        return array_merge([
            'root' => '',
            'invoker.class' => InvokerStandard::class,
            'output.control' => false,
        ], $options);
    }

    /**
     * Create invoker for the driver.
     *
     * @return InvokerInterface
     */
    protected function createInvoker()
    {
        $invoker = class_exists($this->options['invoker.class'])
            ? $this->options['invoker.class']
            : InvokerStandard::class
        ;
        return new $invoker($this);
    }
}
