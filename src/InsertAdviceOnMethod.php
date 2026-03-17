<?php

namespace RectorAop;

use PhpParser\Node;
use PhpParser\Node\Expr;
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

        $originalStatements = $node->stmts;

        $arguments = [];
        $closureParams = [];
        foreach ($node->params as $param) {
            if (! $param->var instanceof Expr\Variable) {
                continue;
            }

            $arguments[] = new Node\Arg($param->var, false, $param->variadic);
            $closureParams[] = clone $param;
        }

        $isVoid = $node->returnType instanceof Identifier
            && strtolower($node->returnType->toString()) === 'void';

        $node->stmts = [];

        $node = $this->before($node, $arguments);

        $closure = new Expr\Closure([
            'static' => $node->isStatic(),
            'params' => $closureParams,
            'stmts' => $originalStatements,
        ]);

        $call = new Expr\FuncCall($closure, $arguments);

        if (! $isVoid) {
            $resultVariable = new Expr\Variable('result');
            $call = new Expr\Assign($resultVariable, $call);
        }

        $node = $this->around($node, $arguments, $call, $isVoid ? null : $resultVariable);

        $node = $this->after($node, $isVoid ? null : $resultVariable);

        if (! $isVoid) {
            $node->stmts[] = new Node\Stmt\Return_($resultVariable);
        }

        return $node;
    }

    /**
     * @param  Node\Arg[]  $arguments
     */
    protected function before(ClassMethod $method, array $arguments): ClassMethod
    {
        return $method;
    }

    /**
     * @param  Node\Arg[]  $arguments
     */
    protected function around(ClassMethod $method, array $arguments, Expr $call, ?Expr\Variable $resultVariable): ClassMethod
    {
        $method->stmts[] = new Node\Stmt\Expression($call);

        return $method;
    }

    protected function after(ClassMethod $method, ?Expr\Variable $resultVariable): ClassMethod
    {
        return $method;
    }
}
