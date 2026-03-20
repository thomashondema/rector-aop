<?php

namespace RectorAop\Pointcut;

use PhpParser\Node;
use Rector\ValueObject\Application\File;

class MethodNamePointcut implements Pointcut
{
    public function __construct(
        private readonly string $pattern,
    ) {}

    public function matches(Node $node, File $file, PointcutContext $context): bool
    {
        $method = $context->methodNode;
        if ($method === null) {
            return false;
        }

        return fnmatch($this->pattern, $method->name->toString());
    }
}
