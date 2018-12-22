<?php

namespace SLDB;

use SLDB\Condition;

/**
* The operator class is used by queries to determine which rows a query should apply to based on field value comparison within stored conditions. Each condition stored within this operator holds a field, value, and condition type. When all conditions match a particular row, that row is affected or selected by the active query. Operators can store a limitless number of conditions. Conditions can also be nested oeprators as well.
* When the query is generated, all operators and conditions are parsed into syntax and parameter arrays for the database to use during the query execution. Translation of operators to syntax takes place within the SLDB Query objects of their respective database types.
* @author Travis Truttschel
*/
class Operator{

	// Constants used for operator type identification.
	const AND_OPERATOR = 'AND_OPERATOR';
	const OR_OPERATOR  = 'OR_OPERATOR';

	/**
	* The type of operator that this operator is.
	*/
	private $_type;

	/**
	* Conditions stored within this operator.
	*/
	private $_conditions;

	/**
	* Class Constructor
	*/
	function __construct(string $type=NULL,array $conditions=NULL){

		if( $type !== NULL ){

			$this->_type = $type;

		}else{

			throw new InvalidOperatorArgumentsException();

		}

		if( $conditions !== NULL && count( $conditions ) > 0 ){

			if( $this->_validateConditions($conditions) ){

				$this->_conditions = $conditions;

			}

		}else{

			throw new InvalidOperatorArgumentsException();

		}

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}

	/**
	* Adds a condition to this operator.
	* @param SLDB\Condition SLDB\Operator $condition Condition or operator to add as a condition to this operator.
	* @return SLDB\Operator This operator.
	* @throws SLDB\InvalidOperatorArgumentsException If the suplied condition is not valid.
	*/
	function addCondition($condition){

		if( $this->_validateConditions( array( $condition ) ) ){

			$this->_conditions[] = $condition;

		}

		return $this;

	}

	/**
	* Adds a array of conditions to the stored conditions within this operator.
	* @param array $conditions Conditions to add to this operator.
	* @return SLDB\Operator This operator.
	* @throws SLDB\InvalidOperatorArgumentsException If the supplied conditions are not valid.
	*/
	function addConditions(array $conditions){

		if( $this->_validateConditions( $conditions ) ){

			array_merge( $this->_conditions, $conditions );

		}

		return $this;

	}

	/**
	* Sets the conditions within this operator the the array of supplied conditions.
	* @param array $conditions Conditions to use within this operator.
	* @return SLDB\Operator This operator.
	* @throws SLDB\InvalidOperatorArgumentsException If the supplied conditions are not valid.
	*/
	function setConditions(array $conditions){

		if( $this->_validateConditions( $conditions ) ){

			$this->_conditions = $conditions;

		}

		return $this;

	}

	/**
	* Returns the array of conditions stored within this operator.
	* @return array Conditions within this operator.
	*/
	function getConditions(){

		return $this->_conditions;

	}

	/**
	* Returns the type of operator this operator is.
	* @return int Operator type.
	*/
	function getType(){

		return $this->_type;

	}

	/**
	* Validates this operator and nested operators insuring that all tables and fields within this operator, are found within the table and field arrays provided to this function.
	* @param string $primary_table The primary table to be used in a query with this operator.
	* @param array $joined_tables The tables this function should use as reference when validating table and field names.
	* @param array $fields The fields this function should use as reference when validating table and field names.
	* @return boolean True if this operator is valid, otherwise False.
	*/
	function validate(string $table,array $joins,array $fields){

        $tableExists = false;

		foreach( $this->_conditions as $condition ){

			if( is_a( $condition, 'SLDB\Operator' ) ){

				if( ! $this->_validate( $joins, $fields ) ){

					return false;

				}

				continue;

			}

			// Make sure that the Table exists within $tables and $fields if the Table is not NULL. If the table is NULL, the Query object will assume this condition
			// uses the $primary_table as the reference table during execution.

            if( $condition->getTable() === $table ) {

                $tableExists = true;

            }

            foreach( $joins as $k => $v ) {

                if ( $v->getForeignTable() === $condition->getTable() ) {

                    $tableExists = true;

                }

            }

            if(! $tableExists ) {
                throw new InvalidOperatorArgumentsException("Condition table '" . $condition->getTable() . "' does not exist within query.");
                return false;
            }

		}

		return true;

	}

	/**
	* Validates conditions supplied, including nested operators/conditions recursively.
	* @param array $conditions Conditions to verify.
	* @throws SLDB\InvalidOperatorArgumentsException If the supplied conditions are not valid.
	*/
	private function _validateConditions(array $conditions){

		foreach( $conditions as $v ){

			if(! is_a( $v, 'SLDB\Condition' ) ){

				if(! is_a( $v, 'SLDB\Operator' ) ){

					throw new InvalidOperatorArgumentsException();

				}

				if(! $this->_validateConditions( $v ) ){

					throw new InvalidOperatorArgumentsException();

				}

			}

		}

		return true;

	}

}
class InvalidOperatorArgumentsException extends \Exception{}