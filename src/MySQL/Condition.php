<?php

namespace SLDB\MySQL;

use SLDB\Base\Condition as BaseCondition;

/**
 * This class is designed to work with the SLDB\Operator class. Conditions are stored within operators, and conditions tell SLDB what rows queries should apply to based on field value comparisons. These conditions are then generated into query syntax within SLDB\Base\Query objects and used during execution.
 */
class Condition extends BaseCondition
{

    /**
     * Condition constructor.
     * @param string|NULL $table Table this conditions field belongs to.
     * @param string|NULL $field Field this condition applies to.
     * @param string|NULL $type Type of condition to apply.
     * @param string|NULL $value The value this field must validate to depending on the provided condition type.
     */
    function __construct(string $table = NULL, string $field = NULL, string $type = NULL, string $value = NULL)
    {

        BaseCondition::__construct($table, $field, $type, $value);

    }

    /**
     * Generates the syntax for this condition, hydrating this conditions
     * syntax property.
     */
    public function generate()
    {

        switch ($this->getType()) {
            case BaseCondition::EQUAL_TO:
                $this->_syntax = $this->getField() . ' = ?';
                return $this;
            case BaseCondition::GREATER_OR_EQUAL_TO:
                $this->_syntax = $this->getField() . ' >= ?';
                return $this;
            case BaseCondition::GREATER_THAN:
                $this->_syntax = $this->getField() . ' > ?';
                return $this;
            case BaseCondition::LESS_OR_EQUAL_TO:
                $this->_syntax = $this->getField() . ' <= ?';
                return $this;
            case BaseCondition::LESS_THAN:
                $this->_syntax = $this->getField() . ' < ?';
                return $this;
            case BaseCondition::LIKE:
                $this->_syntax = $this->getField() . ' LIKE ?';
                return $this;
            case BaseCondition::NOT_EQUAL_TO:
                $this->_syntax = $this->getField() . ' != ?';
                return $this;
            case BaseCondition::NOT_LIKE:
                $this->_syntax = $this->getField() . ' NOT LIKE ?';
                return $this;
        }

    }

}