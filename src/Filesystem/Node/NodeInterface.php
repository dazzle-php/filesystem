<?php

namespace Dazzle\Filesystem\Node;

use Dazzle\Promise\PromiseInterface;

interface NodeInterface
{
    /**
     * Stat the node, returning detailed information about it, such as the file, c/m/a-time, mode, g/u-id, and more.
     *
     * @param string $path
     * @return PromiseInterface
     */
    public function stat($path);

    /**
     * Change the node mode.
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
}
