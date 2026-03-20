<?php

namespace RectorAop\Pointcut;

use PhpParser\Node;
use Rector\ValueObject\Application\File;

class OrPointcut implements Pointcut
{
    /** @var Pointcut[] */
    private array $pointcuts;

    public function __construct(Pointcut ...$pointcuts)
    {
        $this->pointcuts = $pointcuts;
    }

    public function matches(Node $node, File $file, PointcutContext $context): bool
    {
        foreach ($this->pointcuts as $pointcut) {
            if ($pointcut->matches($node, $file, $context)) {
                return true;
            }
        }

        return false;
    }
}
