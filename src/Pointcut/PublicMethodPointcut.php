<?php

namespace RectorAop\Pointcut;

use PhpParser\Node;
use Rector\ValueObject\Application\File;

class PublicMethodPointcut implements Pointcut
{
    public function matches(Node $node, File $file, PointcutContext $context): bool
    {
        return $context->methodNode?->isPublic() ?? false;
    }
}
