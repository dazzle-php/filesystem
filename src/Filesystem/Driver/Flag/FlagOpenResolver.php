<?php

namespace Dazzle\Filesystem\Driver\Flag;

class FlagOpenResolver extends FlagResolverAbstract implements FlagResolverInterface
{
    /**
     * @var int|null
     */
    const DEFAULT_FLAG = null;

    /**
     * @var array
     */
    private $mapping = [
        '+' => EIO_O_RDWR,
        'a' => EIO_O_APPEND,
        'c' => EIO_O_CREAT,
        'e' => EIO_O_EXCL,
        'f' => EIO_O_FSYNC,
        'n' => EIO_O_NONBLOCK,
        'r' => EIO_O_RDONLY,
        't' => EIO_O_TRUNC,
        'w' => EIO_O_WRONLY,
    ];

    /**
     * @override
     * @inheritDoc
     */
    protected function getFlags()
    {
        return static::DEFAULT_FLAG;
    }

    /**
     * @override
     * @inheritDoc
     */
    protected function getMapping()
    {
        return $this->mapping;
    }
}
