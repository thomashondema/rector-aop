<?php

use PhpParser\Node\Stmt\Nop;
use Rector\ValueObject\Application\File;
use RectorAop\Pointcut\NotPointcut;
use RectorAop\Pointcut\OrPointcut;
use RectorAop\Pointcut\PointcutContext;
use RectorAop\Pointcut\TruePointcut;

$node = new Nop();
$file = new File('/test.php', '<?php');
$context = new PointcutContext(null, null, null, null, null);

it('matches when at least one pointcut matches', function () use ($node, $file, $context) {
    $pointcut = new OrPointcut(new NotPointcut(new TruePointcut()), new TruePointcut());

    expect($pointcut->matches($node, $file, $context))->toBeTrue();
});

it('does not match when no pointcuts match', function () use ($node, $file, $context) {
    $false = new NotPointcut(new TruePointcut());
    $pointcut = new OrPointcut($false, $false);

    expect($pointcut->matches($node, $file, $context))->toBeFalse();
});

it('does not match when no pointcuts are given', function () use ($node, $file, $context) {
    $pointcut = new OrPointcut();

    expect($pointcut->matches($node, $file, $context))->toBeFalse();
});
