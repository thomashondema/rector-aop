This is an example of a Rector rule that adds database transactions to methods that are annotated with `@db-transaction`. The rule checks for the presence of the annotation in the method's doc comment and wraps the method's statements in a closure that is passed to the `DB::transaction` method. This allows you to easily add transaction management to your Laravel application without having to manually modify each method.

```PHP
<?php

namespace RectorRules\Laravel;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use Rector\Rector\AbstractRector;

class AddDatabaseTransactions extends AbstractRector
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

        $docComment = $node->getDocComment();
        if ($docComment === null) {
            return null;
        }

        if (strpos($docComment->getText(), '@db-transaction') === false) {
            return null;
        }

        $closureUses = [];
        foreach ($node->params as $param) {
            if (! $param->var instanceof Node\Expr\Variable) {
                continue;
            }

            $closureUses[] = new Node\Expr\ClosureUse($param->var);
        }

        $closure = new Closure([
            'uses' => $closureUses,
            'stmts' => $node->stmts,
        ]);

        $transactionCall = new StaticCall(
            new FullyQualified('Illuminate\\Support\\Facades\\DB'),
            'transaction',
            [new Arg($closure)]
        );

        $isVoid = $node->returnType instanceof Node\Identifier
            && strtolower($node->returnType->toString()) === 'void';

        $node->stmts = $isVoid
            ? [new Expression($transactionCall)]
            : [new Return_($transactionCall)];

        return $node;
    }
}

```
