<?php

use PhpParser\Node\Stmt\Nop;
use Rector\ValueObject\Application\File;
use RectorAop\Pointcut\NotPointcut;
use RectorAop\Pointcut\PointcutContext;
use RectorAop\Pointcut\TruePointcut;

$node = new Nop();
$file = new File('/test.php', '<?php');
$context = new PointcutContext(null, null, null, null, null);

it('inverts a matching pointcut to false', function () use ($node, $file, $context) {
    $pointcut = new NotPointcut(new TruePointcut());

    expect($pointcut->matches($node, $file, $context))->toBeFalse();
});

it('inverts a non-matching pointcut to true', function () use ($node, $file, $context) {
    $pointcut = new NotPointcut(new NotPointcut(new TruePointcut()));

    expect($pointcut->matches($node, $file, $context))->toBeTrue();
});
