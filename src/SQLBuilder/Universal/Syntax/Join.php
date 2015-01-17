<?php
namespace SQLBuilder\Universal\Syntax;
use SQLBuilder\Universal\Syntax\Conditions;
use SQLBuilder\ArgumentArray;
use SQLBuilder\Driver\BaseDriver;
use SQLBuilder\Driver\MySQLDriver;
use SQLBuilder\ToSqlInterface;
use LogicException;
use SQLBuilder\MySQL\Traits\IndexHintTrait;

class Join implements ToSqlInterface
{
    use IndexHintTrait;

    public $conditions;

    public $alias;

    protected $joinType;

    public function __construct($table, $alias = NULL)
    {
        $this->table = $table;
        $this->alias = $alias;
        $this->conditions = new Conditions;
    }

    public function left() {
        $this->joinType = 'LEFT';
        return $this;
    }

    public function right() {
        $this->joinType = 'RIGHT';
        return $this;
    }

    public function inner() {
        $this->joinType = 'INNER';
        return $this;
    }

    public function on($conditionExpr = NULL, array $args = array())
    {
        if (is_string($conditionExpr)){
            $this->conditions->appendExpr($conditionExpr, $args);
        }
        return $this->conditions;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args) {
        $sql = '';

        if ($this->joinType) {
            $sql .= ' ' . $this->joinType;
        }

        $sql .= ' JOIN ' . $this->table;

        if ($this->alias) {
            $sql .= ' AS ' . $this->alias;
        }

        // $sql .= $this->buildIndexHintClause($driver, $args);

        if ($driver instanceof MySQLDriver) {
            if ($this->definedIndexHint($this->alias)) {
                $sql .= $this->buildIndexHintClauseByTableRef($this->alias, $driver, $args);
            } elseif ($this->definedIndexHint($this->table)) {
                $sql .= $this->buildIndexHintClauseByTableRef($this->table, $driver, $args);
            }
        }

        if ($this->conditions->hasExprs()) {
            $sql .= ' ON (' . $this->conditions->toSql($driver, $args) . ')';
        }
        return $sql;
    }

    public function _as($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    public function __call($m, $a) {
        if ($m == "as") {
            return $this->_as($a[0]);
        }
        throw new LogicException("Invalid method call: $m");
    }
}



