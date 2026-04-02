<?php

use PhpParser\Node\Stmt\Nop;
use Rector\ValueObject\Application\File;
use RectorAop\Pointcut\PathPointcut;
use RectorAop\Pointcut\PointcutContext;

$node = new Nop();
$file = new File('/test.php', '<?php');

it('matches an exact file path', function () use ($node, $file) {
    $context = new PointcutContext('/app/Services/OrderService.php', null, null, null, null);

    expect((new PathPointcut('/app/Services/OrderService.php'))->matches($node, $file, $context))->toBeTrue();
});

it('matches a file path using a wildcard pattern', function () use ($node, $file) {
    $context = new PointcutContext('/app/Services/OrderService.php', null, null, null, null);

    expect((new PathPointcut('/app/Services/*'))->matches($node, $file, $context))->toBeTrue();
});

it('does not match a path outside the pattern', function () use ($node, $file) {
    $context = new PointcutContext('/app/Models/Order.php', null, null, null, null);

    expect((new PathPointcut('/app/Services/*'))->matches($node, $file, $context))->toBeFalse();
});

it('does not match when the file path is null', function () use ($node, $file) {
    $context = new PointcutContext(null, null, null, null, null);

    expect((new PathPointcut('/app/Services/*'))->matches($node, $file, $context))->toBeFalse();
});
