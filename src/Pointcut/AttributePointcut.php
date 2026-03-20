<?php

namespace RectorAop\Pointcut;

use PhpParser\Node;
use Rector\ValueObject\Application\File;

class AttributePointcut implements Pointcut
{
    public function __construct(
        private readonly string $attributeClass,
    ) {}

    public function matches(Node $node, File $file, PointcutContext $context): bool
    {
        $targets = array_filter([$context->methodNode, $context->classNode]);

        foreach ($targets as $target) {
            foreach ($target->attrGroups ?? [] as $attrGroup) {
                foreach ($attrGroup->attrs as $attr) {
                    $name = $attr->name->toString();
                    if ($name === $this->attributeClass || str_ends_with($name, '\\' . ltrim($this->attributeClass, '\\'))) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
