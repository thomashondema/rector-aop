<?php

namespace RectorAop\Pointcut;

use PhpParser\Node;
use Rector\ValueObject\Application\File;

class HasParamTypePointcut implements Pointcut
{
    public function __construct(
        private readonly string $type,
    ) {}

    public function matches(Node $node, File $file, PointcutContext $context): bool
    {
        $method = $context->methodNode;
        if ($method === null) {
            return false;
        }

        foreach ($method->params as $param) {
            $type = $param->type;
            if ($type !== null && $type->__toString() === $this->type) {
                return true;
            }
        }

        return false;
    }
}
