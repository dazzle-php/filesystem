<?php

namespace Dazzle\Filesystem\Driver;

use Dazzle\Filesystem\Driver\Flag\FlagOpenResolver;
use Dazzle\Filesystem\Driver\Flag\FlagPermissionResolver;
use Dazzle\Filesystem\Driver\Flag\FlagResolverInterface;
use Dazzle\Loop\LoopAwareTrait;
use Dazzle\Promise\PromiseInterface;

abstract class DriverAbstract
{
    use LoopAwareTrait;

    /**
     * @internal
     * @param string $func
     * @param array $args
     * @return PromiseInterface
     */
    abstract public function call($func, $args = []);

    /**
     * @return FlagResolverInterface
     */
    protected function createFlagPermissionResolver()
    {
        return new FlagPermissionResolver();
    }

    /**
     * @return FlagResolverInterface
     */
    protected function createFlagOpenResolver()
    {
        return new FlagOpenResolver();
    }
}
