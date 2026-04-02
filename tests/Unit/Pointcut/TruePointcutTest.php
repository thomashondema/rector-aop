<?php

use PhpParser\Node\Stmt\Nop;
use Rector\ValueObject\Application\File;
use RectorAop\Pointcut\PointcutContext;
use RectorAop\Pointcut\TruePointcut;

it('always matches regardless of context', function () {
    $pointcut = new TruePointcut();
    $context = new PointcutContext(null, null, null, null, null);
    $file = new File('/test.php', '<?php');

    expect($pointcut->matches(new Nop(), $file, $context))->toBeTrue();
});
