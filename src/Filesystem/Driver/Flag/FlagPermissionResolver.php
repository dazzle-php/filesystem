<?php

namespace Dazzle\Filesystem\Driver\Flag;

class FlagPermissionResolver extends FlagResolverAbstract implements FlagResolverInterface
{
    /**
     * @var int|null
     */
    const DEFAULT_FLAG = null;

    /**
     * @var string
     */
    private $scope = '';

    /**
     * @var array
     */
    private $mapping = [
        'user' => [
            'w' => 128,
            'x' => 64,
            'r' => 256,
        ],
        'group' => [
            'w' => 16,
            'x' => 8,
            'r' => 32,
        ],
        'universe' => [
            'w' => 2,
            'x' => 1,
            'r' => 4,
        ],
    ];

    /**
     * @override
     * @inheritDoc
     */
    public function resolve($flag, $flags = null, $mapping = null)
    {
        $resultFlags = 0;
        $start = 0;

        foreach ([ 'universe', 'group', 'user' ] as $scope)
        {
            $this->scope = $scope;
            $start -= 3;
            $chunk = substr($flag, $start, 3);
            $resultFlags |= parent::resolve($chunk, $flags, $mapping);
        }

        return $resultFlags;
    }

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
        return $this->mapping[$this->scope];
    }
}
