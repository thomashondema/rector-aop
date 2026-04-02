<?php

use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Nop;
use Rector\ValueObject\Application\File;
use RectorAop\Pointcut\AttributePointcut;
use RectorAop\Pointcut\PointcutContext;

$node = new Nop();
$file = new File('/test.php', '<?php');

it('matches when the method has the attribute', function () use ($node, $file) {
    $attrGroup = new AttributeGroup([new Attribute(new Name('MyAttribute'))]);
    $method = new ClassMethod('handle', ['attrGroups' => [$attrGroup]]);
    $context = new PointcutContext(null, null, null, null, $method);

    expect((new AttributePointcut('MyAttribute'))->matches($node, $file, $context))->toBeTrue();
});

it('matches when the class has the attribute', function () use ($node, $file) {
    $class = new Class_('MyClass');
    $class->attrGroups = [new AttributeGroup([new Attribute(new Name('MyAttribute'))])];
    $context = new PointcutContext(null, null, null, $class, null);

    expect((new AttributePointcut('MyAttribute'))->matches($node, $file, $context))->toBeTrue();
});

it('does not match when no attribute is present', function () use ($node, $file) {
    $context = new PointcutContext(null, null, null, null, new ClassMethod('handle'));

    expect((new AttributePointcut('MyAttribute'))->matches($node, $file, $context))->toBeFalse();
});

it('matches by short class name when attribute uses a fully qualified name', function () use ($node, $file) {
    $attrGroup = new AttributeGroup([new Attribute(new Name('App\Attributes\MyAttribute'))]);
    $method = new ClassMethod('handle', ['attrGroups' => [$attrGroup]]);
    $context = new PointcutContext(null, null, null, null, $method);

    expect((new AttributePointcut('MyAttribute'))->matches($node, $file, $context))->toBeTrue();
});
