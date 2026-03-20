<?php

namespace RectorAop\Pointcut;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;

/** TODO: move this to a trait */
abstract class AbstractPointcutRector extends AbstractRector
{
    abstract protected function pointcut(): Pointcut;

    protected function matchesPointcut(Node $node): bool
    {
        $context = new PointcutContext(
            filePath: $this->file->getFilePath(),
            namespace: $this->getNamespace(),
            className: $this->resolveClassName($node),
            classNode: $this->betterNodeFinder->findParentType($node, ClassLike::class),
            methodNode: $node instanceof ClassMethod ? $node : $this->betterNodeFinder->findParentType($node, ClassMethod::class),
        );

        return $this->pointcut()->matches($node, $this->file, $context);
    }

    private function getNamespace(): ?string
    {
        $namespace = $this->file->getNewStmts()[0] ?? null;

        return method_exists($namespace, 'name') && $namespace->name ? $namespace->name->toString() : null;
    }

    private function resolveClassName(Node $node): ?string
    {
        $classLike = $this->betterNodeFinder->findParentType($node, ClassLike::class);

        if ($classLike === null || $classLike->name === null) {
            return null;
        }

        $namespace = $this->getNamespace();

        return $namespace ? $namespace . '\\' . $classLike->name->toString() : $classLike->name->toString();
    }
}
