<?php

namespace RectorAop;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;

class InsertPointcutOnMethod extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    public function refactor(Node $node)
    {
        if (! $node instanceof ClassMethod) {
            return null;
        }

        if ($node->stmts === null) {
            return null;
        }

        $methodName = $this->getName($node->name);
        if ($methodName === null) {
            return null;
        }

        $shimMethod = clone $node;

        $pointcutName = $methodName . 'Pointcut';
        $node->name = new Identifier($pointcutName);

        $arguments = [];
        foreach ($shimMethod->params as $param) {
            if (! $param->var instanceof Node\Expr\Variable) {
                continue;
            }

            $arguments[] = new Node\Arg($param->var, false, $param->variadic);
        }

        $isVoid = $shimMethod->returnType instanceof Identifier
            && strtolower($shimMethod->returnType->toString()) === 'void';

        $shimMethod->stmts = [];

        $shimMethod = $this->before($shimMethod, $arguments);

        $call = $shimMethod->isStatic()
            ? new Node\Expr\StaticCall(new Node\Name('self'), $pointcutName, $arguments)
            : new Node\Expr\MethodCall(new Node\Expr\Variable('this'), $pointcutName, $arguments);

        if (! $isVoid) {
            $resultVariable = new Node\Expr\Variable('result');
            $call = new Node\Expr\Assign($resultVariable, $call);
        }

        $shimMethod = $this->around($shimMethod, $arguments, $call, $isVoid ? null : $resultVariable);

        $shimMethod = $this->after($shimMethod, $isVoid ? null : $resultVariable);

        if (! $isVoid) {
            $shimMethod->stmts[] = new Node\Stmt\Return_($resultVariable);
        }

        return [$shimMethod, $node];
    }

    /**
     * @param  ClassMethod  $method
     * @param  Node\Arg[]  $arguments
     * @return ClassMethod
     */
    protected function before(Node $method, array $arguments): Node
    {
        return $method;
    }

    protected function around(Node $method, array $arguments, $call, ?Node\Expr\Variable $resultVariable): Node
    {
        $method->stmts[] = new Node\Stmt\Expression($call);

        return $method;
    }

    protected function after(Node $method, ?Node\Expr\Variable $resultVariable): Node
    {
        return $method;
    }
}
