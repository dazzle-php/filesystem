<?php

namespace Dazzle\Filesystem\Test\TUnit;

use Dazzle\Filesystem\DiskInterface;
use Dazzle\Filesystem\Driver\DriverInterface;
use Dazzle\Filesystem\Filesystem;

class FilesystemTest extends DiskTest
{
    /**
     * @param DriverInterface $driver
     * @return DiskInterface
     */
    public function createDisk(DriverInterface $driver = null)
    {
        $driver = $driver ? $driver : $this->createDriver();
        return new Filesystem($driver);
    }
}
