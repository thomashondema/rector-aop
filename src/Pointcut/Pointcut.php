<?php

namespace RectorAop\Pointcut;

namespace RectorAop\Pointcut;

use PhpParser\Node;
use Rector\ValueObject\Application\File;

interface Pointcut
{
    public function matches(Node $node, File $file, PointcutContext $context): bool;
}
