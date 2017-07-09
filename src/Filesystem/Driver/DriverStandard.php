<?php

namespace Dazzle\Filesystem\Driver;

use Dazzle\Filesystem\Invoker\InvokerInterface;
use Dazzle\Filesystem\Invoker\InvokerStandard;
use Dazzle\Loop\LoopInterface;
use Dazzle\Promise\Promise;
use Error;
use Exception;

class DriverStandard extends DriverAbstract implements DriverInterface
{
    /**
     * @var InvokerInterface
     */
    protected $invoker;

    /**
     * @param LoopInterface $loop
     * @param array $options
     */
    public function __construct(LoopInterface $loop, $options = [])
    {
        $this->loop = $loop;
        $this->options = $this->createConfiguration($options);
        $this->invoker = $this->createInvoker();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function stat($path)
    {
        return $this->invoker
            ->call('stat', [ $this->getPath($path) ])
            ->then(function($stat) {
                return $stat ? array_filter($stat, function($statKey) {
                    return !is_numeric($statKey);
                }, ARRAY_FILTER_USE_KEY) : $stat;
            })
            ->then([ $this, 'handleStat' ]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function chmod($path, $mode)
    {
        return $this->invoker
            ->call('chmod', [ $this->getPath($path), decoct($mode) ])
            ->then([ $this, 'handleChmod' ]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function chown($path, $uid = -1, $gid = -1)
    {
        return $this->invoker
            ->call('chown', [ $this->getPath($path), $uid, $gid ])
            ->then([ $this, 'handleChown' ]);
    }

    /**
     * @internal
     * @override
     * @inheritDoc
     */
    public function call($func, $args = [])
    {
        try
        {
            return Promise::doResolve(@$func(...$args));
        }
        catch (Error $ex)
        {
            return Promise::doReject($ex);
        }
        catch (Exception $ex)
        {
            return Promise::doReject($ex);
        }
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
