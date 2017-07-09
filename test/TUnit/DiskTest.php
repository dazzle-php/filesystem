<?php

namespace Dazzle\Filesystem\Test\TUnit;

use Dazzle\Filesystem\Disk;
use Dazzle\Filesystem\DiskInterface;
use Dazzle\Filesystem\Driver\DriverInterface;
use Dazzle\Filesystem\Test\TUnit;

class DiskTest extends TUnit
{
    /**
     *
     */
    public function testApiConstructor_CreatesInstance()
    {
        $disk = $this->createDisk();
        $this->assertInstanceOf(DiskInterface::class, $disk);
    }

    /**
     *
     */
    public function testApiDestructor_DoesNotThrowException()
    {
        $disk = $this->createDisk();
        unset($disk);
    }

    /**
     *
     */
    public function testApiSetLoop_InvokesMethodOnDriver()
    {
        $this->checkApiCall('setLoop', []);
    }

    /**
     *
     */
    public function testApiGetLoop_InvokesMethodOnDriver()
    {
        $this->checkApiCall('getLoop', []);
    }

    /**
     *
     */
    public function testApiAccess_InvokesMethodOnDriver()
    {
        $this->checkApiCall('access', [ 'path', 0666 ]);
    }

    /**
     *
     */
    public function testApiAppend_InvokesMethodOnDriver()
    {
        $this->checkApiCall('append', [ 'path', 'text' ]);
    }

    /**
     *
     */
    public function testApiChmod_InvokesMethodOnDriver()
    {
        $this->checkApiCall('chmod', [ 'path', 0666 ]);
    }

    /**
     *
     */
    public function testApiChown_InvokesMethodOnDriver()
    {
        $this->checkApiCall('chown', [ 'path', 1000, 1000 ]);
    }

    /**
     *
     */
    public function testApiExists_InvokesMethodOnDriver()
    {
        $this->checkApiCall('exists', [ 'path' ]);
    }

    /**
     *
     */
    public function testApiLink_InvokesMethodOnDriver()
    {
        $this->checkApiCall('link', [ 'src_path', 'dst_path' ]);
    }

    /**
     *
     */
    public function testApiLs_InvokesMethodOnDriver()
    {
        $this->checkApiCall('ls', [ 'path' ]);
    }

    /**
     *
     */
    public function testApiMkdir_InvokesMethodOnDriver()
    {
        $this->checkApiCall('mkdir', [ 'path', 0666 ]);
    }

    /**
     *
     */
    public function testApiPrepend_InvokesMethodOnDriver()
    {
        $this->checkApiCall('prepend', [ 'path', 'text' ]);
    }

    /**
     *
     */
    public function testApiReadlink_InvokesMethodOnDriver()
    {
        $this->checkApiCall('readlink', [ 'path' ]);
    }

    /**
     *
     */
    public function testApiRealpath_InvokesMethodOnDriver()
    {
        $this->checkApiCall('realpath', [ 'path' ]);
    }

    /**
     *
     */
    public function testApiRename_InvokesMethodOnDriver()
    {
        $this->checkApiCall('rename', [ 'src_path', 'dst_path' ]);
    }

    /**
     *
     */
    public function testApiRmdir_InvokesMethodOnDriver()
    {
        $this->checkApiCall('rmdir', [ 'path' ]);
    }

    /**
     *
     */
    public function testApiStat_InvokesMethodOnDriver()
    {
        $this->checkApiCall('stat', [ 'path' ]);
    }

    /**
     *
     */
    public function testApiSymlink_InvokesMethodOnDriver()
    {
        $this->checkApiCall('symlink', [ 'src_path', 'dst_path' ]);
    }

    /**
     *
     */
    public function testApiTruncate_InvokesMethodOnDriver()
    {
        $this->checkApiCall('truncate', [ 'path', 5 ]);
    }

    /**
     *
     */
    public function testApiUnlink_InvokesMethodOnDriver()
    {
        $this->checkApiCall('unlink', [ 'path' ]);
    }

    /**
     * @param string $func
     * @param array $args
     */
    public function checkApiCall($func, $args = [])
    {
        $driver = $this->createDriver();
        $driver
            ->expects($this->once())
            ->method($func)
            ->with(...$args);

        $disk = $this->createDisk($driver);
        $disk->$func(...$args);
    }

    /**
     * @param string[]|null $methods
     * @return DriverInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    public function createDriver($methods = [])
    {
        return $this->getMock(DriverInterface::class, $methods, [], '', false);
    }

    /**
     * @param DriverInterface $driver
     * @return DiskInterface
     */
    public function createDisk(DriverInterface $driver = null)
    {
        $driver = $driver ? $driver : $this->createDriver();
        return new Disk($driver);
    }
}
