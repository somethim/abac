<?php

namespace zennit\ABAC\Strategies\Operators\Arithmetic;

use zennit\ABAC\Strategies\Contracts\ArithmeticOperatorInterface;

class GreaterThanEqualsOperator implements ArithmeticOperatorInterface
{
    public function evaluate(mixed $values, mixed $against): bool
    {
        return $values >= $against;
    }
}