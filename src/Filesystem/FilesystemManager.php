<?php

namespace Dazzle\Filesystem;

class FilesystemManager implements FilesystemManagerInterface
{
    /**
     * @var FilesystemInterface[]
     */
    protected $filesystems;

    /**
     * @param FilesystemInterface[] $filesystems
     */
    public function __construct($filesystems = [])
    {
        $this->mountFilesystems($filesystems);
    }

    /**
     *
     */
    public function __destruct()
    {
        unset($this->filesystems);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function mountFilesystems($filesystems)
    {
        foreach ($filesystems as $prefix=>$filesystem)
        {
            $this->mountFilesystem($prefix, $filesystem);
        }
    }

    /**
     * @override
     * @inheritDoc
     */
    public function existsFilesystem($prefix)
    {
        return isset($this->filesystems[$prefix]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function mountFilesystem($prefix, FilesystemInterface $filesystem)
    {
        $this->filesystems[$prefix] = $filesystem;
    }

    /**
     * @override
     * @inheritDoc
     */
    public function unmountFilesystem($prefix)
    {
        unset($this->filesystems[$prefix]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function getFilesystem($prefix)
    {
        if (!$this->existsFilesystem($prefix))
        {
            return null;
        }

        return $this->filesystems[$prefix];
    }
}
