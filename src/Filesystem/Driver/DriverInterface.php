<?php

namespace Dazzle\Filesystem\Driver;

use Dazzle\Filesystem\Node\NodeInterface;
use Dazzle\Loop\LoopAwareInterface;

interface DriverInterface extends NodeInterface, LoopAwareInterface
{}
