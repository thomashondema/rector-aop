<?php

use PhpParser\Node\Stmt\Nop;
use Rector\ValueObject\Application\File;
use RectorAop\Pointcut\AndPointcut;
use RectorAop\Pointcut\NotPointcut;
use RectorAop\Pointcut\PointcutContext;
use RectorAop\Pointcut\TruePointcut;

$node = new Nop();
$file = new File('/test.php', '<?php');
$context = new PointcutContext(null, null, null, null, null);

it('matches when all pointcuts match', function () use ($node, $file, $context) {
    $pointcut = new AndPointcut(new TruePointcut(), new TruePointcut());

    expect($pointcut->matches($node, $file, $context))->toBeTrue();
});

it('does not match when any pointcut fails', function () use ($node, $file, $context) {
    $pointcut = new AndPointcut(new TruePointcut(), new NotPointcut(new TruePointcut()));

    expect($pointcut->matches($node, $file, $context))->toBeFalse();
});

it('matches when no pointcuts are given', function () use ($node, $file, $context) {
    $pointcut = new AndPointcut();

    expect($pointcut->matches($node, $file, $context))->toBeTrue();
});
