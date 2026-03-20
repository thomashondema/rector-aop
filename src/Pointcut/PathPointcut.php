<?php

namespace RectorAop\Pointcut;

use PhpParser\Node;
use Rector\ValueObject\Application\File;

class PathPointcut implements Pointcut
{
    public function __construct(
        private readonly string $pattern,
    ) {}

    public function matches(Node $node, File $file, PointcutContext $context): bool
    {
        return fnmatch($this->pattern, $context->filePath ?? '');
    }
}
