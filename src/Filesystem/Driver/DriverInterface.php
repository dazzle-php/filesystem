<?php

namespace Dazzle\Filesystem\Driver;

use Dazzle\Loop\LoopAwareInterface;
use Dazzle\Promise\PromiseInterface;

interface DriverInterface extends LoopAwareInterface
{
    /**
     * Test permissions for the node specified by $path.
     *
     * @param string $path
     * @param int $mode
     * @return PromiseInterface
     */
    public function access($path, $mode = 0755);

    /**
     * Append data to a file.
     *
     * @param string $path
     * @param string $data
     * @return PromiseInterface
     */
    public function append($path, $data = '');

    /**
     * Change the mode of the node.
     *
     * @param string $path
     * @param int $mode
     * @return PromiseInterface
     */
    public function chmod($path, $mode);

    /**
     * Change the owner of the node.
     *
     * @param string $path
     * @param int $uid
     * @param int $gid
     * @return PromiseInterface
     */
    public function chown($path, $uid = -1, $gid = -1);

    /**
     * Check if the node at $path exists.
     *
     * @param string $path
     * @return PromiseInterface
     */
    public function exists($path);

    /**
     * Create a hard link to $srcPath at $dstPath.
     *
     * @param string $srcPath
     * @param string $dstPath
     * @return PromiseInterface
     */
    public function link($srcPath, $dstPath);

    /**
     * List contents of the directory.
     *
     * @param string $path
     * @return PromiseInterface
     */
    public function ls($path);

    /**
     * Create new directory.
     *
     * @param string $path
     * @param int $mode
     * @return PromiseInterface
     */
    public function mkdir($path, $mode = 0755);

    /**
     * Prepend data to a file.
     *
     * @param string $path
     * @param string $data
     * @return PromiseInterface
     */
    public function prepend($path, $data = '');

    /**
     * Read value of a symbolic link at $path.
     *
     * @param string $path
     * @return PromiseInterface
     */
    public function readlink($path);

    /**
     * Get the canonicalized absolute pathname to $path.
     *
     * @param string $path
     * @return PromiseInterface
     */
    public function realpath($path);

    /**
     * Change the name of the node.
     *
     * @param string $srcPath
     * @param string $dstPath
     * @return PromiseInterface
     */
    public function rename($srcPath, $dstPath);

    /**
     * Remove directory.
     *
     * @param string $path
     * @return PromiseInterface
     */
    public function rmdir($path);

    /**
     * Stat the node, returning detailed information about it, such as the file, c/m/a-time, mode, g/u-id, and more.
     *
     * @param string $path
     * @return PromiseInterface
     */
    public function stat($path);

    /**
     * Create a symbolic link to $srcPath at $dstPath.
     *
     * @param string $srcPath
     * @param string $dstPath
     * @return PromiseInterface
     */
    public function symlink($srcPath, $dstPath);

    /**
     * Truncate the node to a size of precisely $len bytes.
     *
     * @param string $path
     * @param int $len
     * @return PromiseInterface
     */
    public function truncate($path, $len = 0);

    /**
     * Unlink the node.
     *
     * @param string $path
     * @return PromiseInterface
     */
    public function unlink($path);
}
