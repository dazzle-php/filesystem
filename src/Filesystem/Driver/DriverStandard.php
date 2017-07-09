<?php

namespace Dazzle\Filesystem\Driver;

use Dazzle\Filesystem\Driver\Flag\FlagResolverInterface;
use Dazzle\Filesystem\Invoker\InvokerInterface;
use Dazzle\Filesystem\Invoker\InvokerStandard;
use Dazzle\Loop\LoopInterface;
use Dazzle\Promise\Promise;
use DateTimeImmutable;
use Error;
use Exception;

class DriverStandard extends DriverAbstract implements DriverInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var InvokerInterface
     */
    protected $invoker;

    /**
     * @var FlagResolverInterface
     */
    protected $flagPermission;

    /**
     * @param LoopInterface $loop
     * @param array $options
     */
    public function __construct(LoopInterface $loop, $options = [])
    {
        $this->loop = $loop;
        $this->options = $this->createConfiguration($options);
        $this->invoker = $this->createInvoker();
        $this->flagPermission = $this->createFlagPermissionResolver();
    }

    /**
     * @override
     * @inheritDoc
     */
    public function access($path, $mode = 0755)
    {
        // TODO
    }

    /**
     * @override
     * @inheritDoc
     */
    public function append($path, $data = '')
    {
        // TODO
    }

    /**
     * @override
     * @inheritDoc
     */
    public function chmod($path, $mode)
    {
        return $this->invoker->call('chmod', [ $this->getPath($path), decoct($mode) ]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function chown($path, $uid = -1, $gid = -1)
    {
        return $this->invoker->call('chown', [ $this->getPath($path), $uid, $gid ]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function exists($path)
    {
        return $this->stat($path)
            ->then(
                function() { return true; },
                function() { return false; }
            );
    }

    /**
     * @override
     * @inheritDoc
     */
    public function link($srcPath, $dstPath)
    {
        return $this->invoker->call('link', [ $this->getPath($srcPath), $this->getPath($dstPath) ]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function ls($path)
    {
        // TODO
    }

    /**
     * @override
     * @inheritDoc
     */
    public function mkdir($path, $mode = 0755)
    {
        return $this->invoker->call('mkdir', [ $this->getPath($path), decoct($this->flagPermission->resolve($mode)) ]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function prepend($path, $data = '')
    {
        // TODO
    }

    /**
     * @override
     * @inheritDoc
     */
    public function readlink($path)
    {
        return $this->invoker->call('readlink', [ $this->getPath($path) ]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function realpath($path)
    {
        return $this->invoker->call('realpath', [ $this->getPath($path) ]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function rename($srcPath, $dstPath)
    {
        return $this->invoker->call('rename', [ $this->getPath($srcPath), $this->getPath($dstPath) ]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function rmdir($path)
    {
        return $this->invoker->call('rmdir', [ $this->getPath($path) ]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function stat($path)
    {
        return $this->invoker
            ->call('stat', [ $this->getPath($path) ])
            ->then(function($info) {
                return $info ? array_filter($info, function($infoKey) {
                    return !is_numeric($infoKey);
                }, ARRAY_FILTER_USE_KEY) : $info;
            })
            ->then(function($info) {
                if ($info)
                {
                    $info['atime'] && $info['atime'] = new DateTimeImmutable('@' . $info['atime']);
                    $info['mtime'] && $info['mtime'] = new DateTimeImmutable('@' . $info['mtime']);
                    $info['ctime'] && $info['ctime'] = new DateTimeImmutable('@' . $info['ctime']);
                }
                return $info;
            });
    }

    /**
     * @override
     * @inheritDoc
     */
    public function symlink($srcPath, $dstPath)
    {
        return $this->invoker->call('symlink', [ $this->getPath($srcPath), $this->getPath($dstPath) ]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function truncate($path, $len = 0)
    {
        // TODO
    }

    /**
     * @override
     * @inheritDoc
     */
    public function unlink($path)
    {
        return $this->invoker->call('unlink', [ $this->getPath($path) ]);
    }

    /**
     * @internal
     * @override
     * @inheritDoc
     */
    public function call($func, $args = [])
    {
        try
        {
            return Promise::doResolve(@$func(...$args));
        }
        catch (Error $ex)
        {
            return Promise::doReject($ex);
        }
        catch (Exception $ex)
        {
            return Promise::doReject($ex);
        }
    }

    /**
     * Get path.
     *
     * @param string $path
     * @return string
     */
    protected function getPath($path)
    {
        return $this->options['root'] . '/' . ltrim($path, '/');
    }

    /**
     * Create valid configuration for the driver.
     *
     * @param array $options
     * @return array
     */
    protected function createConfiguration($options = [])
    {
        return array_merge([
            'root' => '',
            'invoker.class' => InvokerStandard::class,
        ], $options);
    }

    /**
     * Create invoker for the driver.
     *
     * @return InvokerInterface
     */
    protected function createInvoker()
    {
        $invoker = class_exists($this->options['invoker.class'])
            ? $this->options['invoker.class']
            : InvokerStandard::class
        ;
        return new $invoker($this);
    }
}
