<?php

namespace Dazzle\Filesystem\Driver\Flag;

abstract class FlagResolverAbstract
{
    /**
     * @return int
     */
    abstract protected function getFlags();

    /**
     * @return array
     */
    abstract protected function getMapping();

    /**
     * @see FlagResolverInterface::resolve
     */
    public function resolve($flagString, $flags = null, $mapping = null)
    {
        if ($flags === null)
        {
            $flags = $this->getFlags();
        }

        if ($mapping === null)
        {
            $mapping = $this->getMapping();
        }

        foreach (str_split($flagString) as $flag)
        {
            if (isset($mapping[$flag]))
            {
                $flags |= $mapping[$flag];
            }
        }

        return $flags;
    }
}
