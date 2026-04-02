<?php

namespace RectorAop\Pointcut;

use PhpParser\Node;
use Rector\ValueObject\Application\File;

class WithinNamespacePointcut implements Pointcut
{
    public function __construct(
        private readonly string $pattern,
    ) {}

    public function matches(Node $node, File $file, PointcutContext $context): bool
    {
        $subject = $context->className ?? $context->namespace ?? '';

        return fnmatch(str_replace('\\', '\\', $this->pattern), $subject);
    }
}
