<?php

namespace Dazzle\Filesystem;

use Dazzle\Filesystem\Bridge\FilesystemDriverTrait;
use Dazzle\Filesystem\Driver\DriverInterface;

class Filesystem implements FilesystemInterface
{
    use FilesystemDriverTrait;

    /**
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }
}
