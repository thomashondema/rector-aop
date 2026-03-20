<?php

namespace RectorAop\Pointcut;

use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;

class PointcutContext
{
    public function __construct(
        public readonly ?string $filePath,
        public readonly ?string $namespace,
        public readonly ?string $className,
        public readonly ?ClassLike $classNode,
        public readonly ?ClassMethod $methodNode,
    ) {}
}
