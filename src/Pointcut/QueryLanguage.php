<?php

namespace RectorAop\Pointcut;

function within(string $pattern): Pointcut
{
    return new WithinNamespacePointcut($pattern);
}

function method(string $pattern): Pointcut
{
    return new MethodNamePointcut($pattern);
}

function attribute(string $class): Pointcut
{
    return new AttributePointcut($class);
}

function public_(): Pointcut
{
    return new PublicMethodPointcut;
}

function and_(Pointcut ...$pointcuts): Pointcut
{
    return new AndPointcut(...$pointcuts);
}

function not_(Pointcut $pointcut): Pointcut
{
    return new NotPointcut($pointcut);
}
