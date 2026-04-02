<?php

use PhpParser\Modifiers;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Nop;
use Rector\ValueObject\Application\File;
use RectorAop\Pointcut\PointcutContext;
use RectorAop\Pointcut\PublicMethodPointcut;

$node = new Nop();
$file = new File('/test.php', '<?php');

it('matches a public method', function () use ($node, $file) {
    $context = new PointcutContext(null, null, null, null, new ClassMethod('handle', ['flags' => Modifiers::PUBLIC]));

    expect((new PublicMethodPointcut())->matches($node, $file, $context))->toBeTrue();
});

it('does not match a protected method', function () use ($node, $file) {
    $context = new PointcutContext(null, null, null, null, new ClassMethod('handle', ['flags' => Modifiers::PROTECTED]));

    expect((new PublicMethodPointcut())->matches($node, $file, $context))->toBeFalse();
});

it('does not match a private method', function () use ($node, $file) {
    $context = new PointcutContext(null, null, null, null, new ClassMethod('handle', ['flags' => Modifiers::PRIVATE]));

    expect((new PublicMethodPointcut())->matches($node, $file, $context))->toBeFalse();
});

it('does not match when no method node is present', function () use ($node, $file) {
    $context = new PointcutContext(null, null, null, null, null);

    expect((new PublicMethodPointcut())->matches($node, $file, $context))->toBeFalse();
});
