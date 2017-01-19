<?php

namespace SQLBuilder\Universal\Syntax;

use SQLBuilder\ToSqlInterface;
use SQLBuilder\ArgumentArray;
use SQLBuilder\Driver\BaseDriver;
use Exception;

class Distinct implements ToSqlInterface
{
    protected $expr;

    public function __construct($expr)
    {
        $this->expr = $expr;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        if ($this->expr instanceof ToSqlInterface) {
            return 'DISTINCT '.$this->expr->toSql($driver, $args);
        } elseif (is_string($this->expr)) {
            return 'DISTINCT '.$this->expr;
        } else {
            throw new Exception('Unsupported expression type');
        }
    }
}
