<?php

namespace Dazzle\Filesystem\Driver\Flag;

interface FlagResolverInterface
{
    /**
     * Resolve flags.
     *
     * @param string $flagString
     * @param null|int $flags
     * @param null|array $mapping
     * @return int
     */
    public function resolve($flagString, $flags = null, $mapping = null);
}
