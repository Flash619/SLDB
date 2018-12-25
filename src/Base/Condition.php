<?php

namespace SLDB\Base;

/**
 * This class is designed to work with the SLDB\Operator class. Conditions are stored within operators, and conditions tell SLDB what rows queries should apply to based on field value comparisons. These conditions are then generated into query syntax within SLDB\Base\Query objects and used during execution.
 */
class Condition
{

    // Constants used for condition type identification.
    const LIKE = 'LIKE';
    const NOT_LIKE = 'NOT_LIKE';
    const EQUAL_TO = 'EQUAL_TO';
    const NOT_EQUAL_TO = 'NOT_EQUAL_TO';
    consT GREATER_THAN = 'GREATER_THAN';
    const LESS_THAN = 'LESS_THAN';
    const GREATER_OR_EQUAL_TO = 'GREATER_OR_EQUAL_TO';
    const LESS_OR_EQUAL_TO = 'LESS_OR_EQUAL_TO';

    /**
     * @var string The table this condition should apply to. Useful only in cases of joined tables.
     */
    protected $_table;

    /**
     * @var string The field this condition should apply to.
     */
    protected $_field;

    /**
     * @var string The type of condition that should apply to this condition.
     */
    protected $_type;

    /**
     * @var string The value this condition should use as reference when making comparisons.
     */
    protected $_value;

    /**
     * @var string|NULL The syntax generated by this condition.
     */
    protected $_syntax;

    /**
     * Condition constructor.
     * @param string|NULL $table Table this conditions field belongs to.
     * @param string|NULL $field Field this condition applies to.
     * @param string|NULL $type Type of condition to apply.
     * @param string|NULL $value The value this field must validate to depending on the provided condition type.
     */
    function __construct(string $table = NULL, string $field = NULL, string $type = NULL, string $value = NULL)
    {

        $this->setTable($table);
        $this->setField($field);
        $this->setType($type);
        $this->setValue($value);
        $this->_syntax = NULL;

    }

    /**
     * Class Deconstructor
     */
    function __destruct()
    {
    }

    /**
     * Returns the table name this condition should apply to.
     * @return string The table name this condition should apply to.
     */
    public function getTable()
    {

        return $this->_table;

    }

    /**
     * Sets the table name this condition should apply to.
     * @param string $table The table this condition should apply to.
     * @return $this
     */
    protected function setTable(string $table = NULL)
    {

        $this->_table = $table;
        return $this;

    }

    /**
     * Returns the field name this condition should apply to.
     * @return string The field name this condition should apply to.
     */
    public function getField()
    {

        return $this->_field;

    }

    /**
     * Sets the field name this condition should apply to.
     * @param string $field The field name this condition should apply to.
     * @return $this
     */
    protected function setField(string $field = NULL)
    {

        $this->_field = $field;
        return $this;

    }

    /**
     * Returns the type of condition that should apply to this condition.
     * @return int The type of condition that should apply to this condition.
     */
    public function getType()
    {

        return $this->_type;

    }

    /**
     * Sets the type of condition that should apply to this condition.
     * @param string $type The type of condition that should apply to this condition.
     * @return $this
     */
    protected function setType(string $type = NULL)
    {

        $this->_type = $type;
        return $this;

    }

    /**
     * Returns the value this condition should use as reference when making comparisons.
     * @return string The value that this condition should use as reference when making comparisons.
     */
    public function getValue()
    {

        return $this->_value;

    }

    /**
     * Sets the value this condition should use as reference when making comparisons.
     * @param string $value The value this condition should use when making comparisons.
     * @return $this
     */
    protected function setValue(string $value = NULL)
    {

        $this->_value = $value;
        return $this;

    }

    /**
     * Returns the syntax generated by this condition. If generate has not been called
     * this will return NULL.
     * @return string Syntax.
     */
    public function getSyntax()
    {

        return $this->_syntax;

    }

    /**
     * Generates the syntax for this condition, hydrating this conditions
     * syntax property.
     * @return $this
     */
    public function generate()
    {

        return $this;

    }


}