<?php

namespace Dazzle\Filesystem\Invoker;

use Dazzle\Filesystem\Driver\DriverAbstract;
use Dazzle\Loop\LoopInterface;

class InvokerStandard implements InvokerInterface
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
     * @param DriverAbstract $driver
     */
    public function __construct(DriverAbstract $driver)
    {
        $this->loop = $driver->getLoop();
        $this->driver = $driver;
    }

    /**
     * @override
     * @inheritDoc
     */
    public function call($func, $args = [])
    {
        return $this->driver->call($func, $args);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function isEmpty()
    {
        return true;
    }
}
