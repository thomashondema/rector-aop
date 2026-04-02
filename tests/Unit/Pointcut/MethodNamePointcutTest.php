<?php

use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Nop;
use Rector\ValueObject\Application\File;
use RectorAop\Pointcut\MethodNamePointcut;
use RectorAop\Pointcut\PointcutContext;

$node = new Nop();
$file = new File('/test.php', '<?php');

it('matches an exact method name', function () use ($node, $file) {
    $context = new PointcutContext(null, null, null, null, new ClassMethod('handle'));

    expect((new MethodNamePointcut('handle'))->matches($node, $file, $context))->toBeTrue();
});

it('matches a method name using a wildcard pattern', function () use ($node, $file) {
    $context = new PointcutContext(null, null, null, null, new ClassMethod('handleOrder'));

    expect((new MethodNamePointcut('handle*'))->matches($node, $file, $context))->toBeTrue();
});

it('does not match a different method name', function () use ($node, $file) {
    $context = new PointcutContext(null, null, null, null, new ClassMethod('process'));

    expect((new MethodNamePointcut('handle'))->matches($node, $file, $context))->toBeFalse();
});

it('returns false when no method node is present', function () use ($node, $file) {
    $context = new PointcutContext(null, null, null, null, null);

    expect((new MethodNamePointcut('handle'))->matches($node, $file, $context))->toBeFalse();
});
