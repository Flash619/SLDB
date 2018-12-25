<?php

namespace SLDB\MySQL;

use SLDB\Base\Database as BaseDatabase;
use SLDB\Base\Query as BaseQuery;
use SLDB\Exception\InvalidQueryOperatorException;

class Query extends BaseQuery
{

    /**
     * Class Constructor
     */
    function __construct()
    {

        BaseQuery::__construct();
        $this->_database_type = BaseDatabase::MYSQL;

    }

    /**
     * Class Deconstructor
     */
    function __destruct()
    {

        BaseQuery::__destruct();

    }

    /**
     * Sets the operator in this query to the operator provided.
     * @param Operator|Condition $where The operator to use in this query.
     * @return $this
     * @throws InvalidQueryOperatorException
     */
    public function where($where)
    {

        if (is_a($where, 'SLDB\Base\Condition')) {

            $where = $this->initOperator(Operator::AND_OPERATOR, array(new Condition($where->getTable(), $where->getField(), $where->getType(), $where->getValue())));

        } else if (!is_a($where, 'SLDB\Base\Operator')) {

            throw new InvalidQueryOperatorException('Query::where expects parameter 1 to be a Condition or Operator.');

        }

        $this->_operator = $where;

        return $this;

    }

    /**
     * Initializes and returns a new operator of the appropriate database type for this query.
     * @param $args
     * @return Operator
     */
    public function initOperator(...$args)
    {

        return new Operator(...$args);

    }

    /**
     * Initializes and returns a new condition of the appropriate database type for this query.
     * @param $args
     * @return Condition
     */
    public function initCondition(...$args)
    {

        return new Condition(...$args);

    }

    protected function generateSelectSyntax()
    {

        $where = $this->_operator->generate()->getSyntax();

        $s = "SELECT ";

        foreach ($this->_fetch as $k => $v) {

            foreach ($v as $f) {

                $s .= $k . '.' . $f . ',';

            }

        }

        $s = rtrim($s, ',');
        $s .= ' FROM ' . $this->_table;

        if (count($this->_join) !== 0) {

            $s .= ' ';

            foreach ($this->_join as $k => $v) {

                $s = $s . $v->getSyntax() . ' ';

            }

        }

        $s .= " WHERE " . $where;

        if ($this->_limit !== NULL) {

            $s .= ' LIMIT ' . $this->_limit;

        }

        if ($this->_offset !== NULL) {

            $s .= ' OFFSET ' . $this->_offset;

        }

        $this->_params = $this->_operator->getParams();

        $this->_syntax = $s;

    }

    protected function generateUpdateSyntax()
    {

        $where = $this->_operator->generate()->getSyntax();
        $values = array('syntax' => '', 'params' => array());

        foreach ($this->_set as $k => $v) {

            $values['syntax'] = $values['syntax'] . $k . ' = ' . '?,';
            $values['params'][] = $v;

        }

        $values['syntax'] = rtrim($values['syntax'], ',');

        $s = "UPDATE " . $this->_table . " SET " . $values['syntax'] . " WHERE " . $where;

        if ($this->_limit !== NULL) {

            $s .= ' LIMIT ' . $this->_limit;

        }

        $this->_params = array_merge($values['params'], $this->_operator->getParams());
        $this->_syntax = $s;

    }

    protected function generateInsertSyntax()
    {

        $fields = array();
        $values = array();
        $vs = '';

        foreach ($this->_set as $k => $v) {

            $fields[] = $k;
            $values[] = $v;
            $vs = $vs . '?,';

        }

        $vs = rtrim($vs, ',');

        $s = "INSERT INTO " . $this->_table . " (" . implode(',', $fields) . ") VALUES (" . $vs . ")";

        $this->_syntax = $s;
        $this->_params = $values;

    }

    protected function generateDeleteSyntax()
    {

        $where = $this->_operator->generate()->getSyntax();

        $s = "DELETE FROM " . $this->_table . " WHERE " . $where;

        if ($this->_limit !== NULL) {

            $s .= ' LIMIT ' . $this->_limit;

        }

        $this->_params = $this->_operator->getParams();
        $this->_syntax = $s;

    }

    protected function generateCreateSyntax()
    {
    }

    protected function generateDropSyntax()
    {
    }

}