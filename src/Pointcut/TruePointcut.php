<?php

namespace RectorAop\Pointcut;

use PhpParser\Node;
use Rector\ValueObject\Application\File;

class TruePointcut implements Pointcut
{
    public function matches(Node $node, File $file, PointcutContext $context): bool
    {
        return true;
    }
}
