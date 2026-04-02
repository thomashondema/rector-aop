<?php

namespace RectorAop\Pointcut;

use Rector\Rector\AbstractRector;

abstract class AbstractPointcutRector extends AbstractRector
{
    use CanMatchPointcut;
    abstract protected function pointcut(): Pointcut;
}
