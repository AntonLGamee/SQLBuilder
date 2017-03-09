<?php

namespace SQLBuilder\Universal\Expr;

use SQLBuilder\Driver\BaseDriver;
use SQLBuilder\ToSqlInterface;
use SQLBuilder\ArgumentArray;

/**
 * @codeCoverageIgnore
 */
class UnaryExpr implements ToSqlInterface
{
    public $op;

    public $operand;

    public function __construct($op, $operand)
    {
        $this->op = $op;
        $this->operand = $operand;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        return $this->op.' '.$driver->deflate($this->operand, $args);
    }

    public static function __set_state(array $array)
    {
        return new self($array['op'], $array['operand']);
    }
}
