<?php

namespace Dazzle\Filesystem;

use Dazzle\Filesystem\Driver\DriverInterface;
use Dazzle\Loop\LoopInterface;

class Disk implements DiskInterface
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @override
     * @inheritDoc
     */
    public function setLoop(LoopInterface $loop = null)
    {
        $this->driver->setLoop($loop);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function getLoop()
    {
        return $this->driver->getLoop();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function access($path, $mode = 0755)
    {
        return $this->driver->access($path, $mode);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function append($path, $data = '')
    {
        return $this->driver->append($path, $data);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function chmod($path, $mode)
    {
        return $this->driver->chmod($path, $mode);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function chown($path, $uid = -1, $gid = -1)
    {
        return $this->driver->chown($path, $uid, $gid);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function exists($path)
    {
        return $this->driver->exists($path);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function link($srcPath, $dstPath)
    {
        return $this->driver->link($srcPath, $dstPath);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function ls($path)
    {
        return $this->driver->ls($path);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function mkdir($path, $mode = 0755)
    {
        return $this->driver->mkdir($path, $mode);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function prepend($path, $data = '')
    {
        return $this->driver->prepend($path, $data);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function readlink($path)
    {
        return $this->driver->readlink($path);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function realpath($path)
    {
        return $this->driver->realpath($path);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function rename($srcPath, $dstPath)
    {
        return $this->driver->rename($srcPath, $dstPath);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function rmdir($path)
    {
        return $this->driver->rmdir($path);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function stat($path)
    {
        return $this->driver->stat($path);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function symlink($srcPath, $dstPath)
    {
        return $this->driver->symlink($srcPath, $dstPath);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function truncate($path, $len = 0)
    {
        return $this->driver->truncate($path, $len);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function unlink($path)
    {
        return $this->driver->unlink($path);
    }
}
