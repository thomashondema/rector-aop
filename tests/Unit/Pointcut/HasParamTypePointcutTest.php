<?php

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Nop;
use Rector\ValueObject\Application\File;
use RectorAop\Pointcut\HasParamTypePointcut;
use RectorAop\Pointcut\PointcutContext;

$node = new Nop();
$file = new File('/test.php', '<?php');

it('matches when the method has a parameter with the given type', function () use ($node, $file) {
    $param = new Param(new Variable('request'), null, new Name('Request'));
    $method = new ClassMethod('handle', ['params' => [$param]]);
    $context = new PointcutContext(null, null, null, null, $method);

    expect((new HasParamTypePointcut('Request'))->matches($node, $file, $context))->toBeTrue();
});

it('does not match when the parameter has a different type', function () use ($node, $file) {
    $param = new Param(new Variable('response'), null, new Name('Response'));
    $method = new ClassMethod('handle', ['params' => [$param]]);
    $context = new PointcutContext(null, null, null, null, $method);

    expect((new HasParamTypePointcut('Request'))->matches($node, $file, $context))->toBeFalse();
});

it('returns false when the method has no parameters', function () use ($node, $file) {
    $context = new PointcutContext(null, null, null, null, new ClassMethod('handle'));

    expect((new HasParamTypePointcut('Request'))->matches($node, $file, $context))->toBeFalse();
});

it('returns false when no method node is present', function () use ($node, $file) {
    $context = new PointcutContext(null, null, null, null, null);

    expect((new HasParamTypePointcut('Request'))->matches($node, $file, $context))->toBeFalse();
});
