<?php

namespace RectorAop\Pointcut;

use PhpParser\Node;
use Rector\ValueObject\Application\File;

class NotPointcut implements Pointcut
{
    public function __construct(private readonly Pointcut $inner) {}

    public function matches(Node $node, File $file, PointcutContext $context): bool
    {
        return ! $this->inner->matches($node, $file, $context);
    }
}
