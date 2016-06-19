<?php
use SQLBuilder\Raw;
use SQLBuilder\Query\UpdateQuery;
use SQLBuilder\Query\DeleteQuery;
use SQLBuilder\Driver\MySQLDriver;
use SQLBuilder\Driver\PgSQLDriver;
use SQLBuilder\Driver\SQLiteDriver;
use SQLBuilder\Universal\Expr\FuncCallExpr;
use SQLBuilder\ToSqlInterface;
use SQLBuilder\ArgumentArray;
use SQLBuilder\Bind;

class FuncCallExprTest extends PHPUnit_Framework_TestCase
{
    public function testFuncCall()
    {
        $driver = new MySQLDriver;
        $args = new ArgumentArray;
        $func = new FuncCallExpr('COUNT', [ new Raw('*') ]);
        $sql = $func->toSql($driver, $args);
        $this->assertEquals('COUNT(*)', $sql);
    }
}

