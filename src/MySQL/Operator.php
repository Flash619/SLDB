<?php

namespace SLDB\MySQL;

use SLDB\Base\Operator as BaseOperator;

/**a
 * The operator class is used by queries to determine which rows a query should apply to based on field value comparison within stored conditions. Each condition stored within this operator holds a field, value, and condition type. When all conditions match a particular row, that row is affected or selected by the active query. Operators can store a limitless number of conditions. Conditions can also be nested oeprators as well.
 * When the query is generated, all operators and conditions are parsed into syntax and parameter arrays for the database to use during the query execution. Translation of operators to syntax takes place within the SLDB Query objects of their respective database types.
 */
class Operator extends BaseOperator
{

    /**
     * Class Constructor
     */
    function __construct(string $type = NULL, array $conditions = NULL)
    {

        BaseOperator::__construct($type, $conditions);

    }

    /**
     * Generates the syntax for this operator, hydrating this operators syntax and params
     * properties.
     */
    public function generate()
    {

        $s = '';

        foreach ($this->_conditions as $v) {

            if (is_a($v, 'SLDB\Base\Operator')) {

                $v->generate();
                $s = $s . ' (' . $v->getSyntax() . ') ';
                $this->_params = array_merge($this->_params, $v->getParams());

                continue;

            }

            $ss = $v->generate()->getSyntax();

            switch ($this->getType()) {
                case Operator::AND_OPERATOR:
                    $ss = $ss . ' AND ';
                    break;
                case Operator::OR_OPERATOR:
                    $ss = $ss . ' OR ';
                    break;
            }

            $s = $s . $ss;

            $this->_params[] = $v->getValue();

        }

        switch ($this->getType()) {
            case Operator::AND_OPERATOR:
                $s = rtrim($s, ' AND ');
                break;
            case Operator::OR_OPERATOR:
                $s = rtrim($s, ' OR ');
                break;
        }

        $this->_syntax = $s;

        return $this;
    }

}