<?php

use PhpParser\Node\Stmt\Nop;
use Rector\ValueObject\Application\File;
use RectorAop\Pointcut\PointcutContext;
use RectorAop\Pointcut\WithinNamespacePointcut;

$node = new Nop();
$file = new File('/test.php', '<?php');

it('matches an exact class name', function () use ($node, $file) {
    $context = new PointcutContext(null, null, 'User', null, null);

    expect((new WithinNamespacePointcut('User'))->matches($node, $file, $context))->toBeTrue();
});

it('matches a class name using a wildcard suffix pattern', function () use ($node, $file) {
    $context = new PointcutContext(null, null, 'OrderService', null, null);

    expect((new WithinNamespacePointcut('*Service'))->matches($node, $file, $context))->toBeTrue();
});

it('falls back to namespace when class name is null', function () use ($node, $file) {
    $context = new PointcutContext(null, 'Services', null, null, null);

    expect((new WithinNamespacePointcut('Services'))->matches($node, $file, $context))->toBeTrue();
});

it('does not match when class name is outside the pattern', function () use ($node, $file) {
    $context = new PointcutContext(null, null, 'OrderService', null, null);

    expect((new WithinNamespacePointcut('*Repository'))->matches($node, $file, $context))->toBeFalse();
});
