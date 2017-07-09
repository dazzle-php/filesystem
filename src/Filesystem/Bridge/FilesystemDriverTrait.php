<?php

namespace Dazzle\Filesystem\Bridge;

use Dazzle\Filesystem\Driver\DriverInterface;

trait FilesystemDriverTrait
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @see DriverInterface::stat
     */
    public function stat($path)
    {
        return $this->driver->stat($path);
    }

    /**
     * @see DriverInterface::chmod
     */
    public function chmod($path, $mode)
    {
        return $this->driver->chmod($path, $mode);
    }

    /**
     * @see DriverInterface::chown
     */
    public function chown($path, $uid = -1, $gid = -1)
    {
        return $this->driver->chown($path, $uid, $gid);
    }
}
