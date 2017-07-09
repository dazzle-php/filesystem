<?php

namespace Dazzle\Filesystem\Driver;

use Dazzle\Loop\LoopAwareTrait;
use Dazzle\Promise\PromiseInterface;
use Dazzle\Throwable\Exception\Runtime\ReadException;
use DateTimeImmutable;

abstract class DriverAbstract
{
    use LoopAwareTrait;

    /**
     * @var array
     */
    protected $options;

    /**
     * @internal
     * @param string $func
     * @param array $args
     * @return PromiseInterface
     */
    abstract public function call($func, $args = []);

    /**
     * Handle stat command.
     *
     * @internal
     */
    public function handleStat($info)
    {
        if (!$info && $this->options['output.control'])
        {
            throw new ReadException('Function stat() failed on given node!');
        }
        $info['atime'] && $info['atime'] = new DateTimeImmutable('@' . $info['atime']);
        $info['mtime'] && $info['mtime'] = new DateTimeImmutable('@' . $info['mtime']);
        $info['ctime'] && $info['ctime'] = new DateTimeImmutable('@' . $info['ctime']);
        return $info;
    }

    /**
     * Handle stat command.
     *
     * @internal
     */
    public function handleChmod($info)
    {
        if (!$info && $this->options['output.control'])
        {
            throw new ReadException('Function chmod() failed on given node!');
        }
        return $info;
    }

    /**
     * Handle stat command.
     *
     * @internal
     */
    public function handleChown($stat)
    {
        if (!$stat && $this->options['output.control'])
        {
            throw new ReadException('Function stat() failed on given node!');
        }
        return $stat;
    }
}
