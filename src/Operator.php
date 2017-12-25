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
	const AND_OPERATOR = 1;
	const OR_OPERATOR  = 2;

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
	function __construct(int $type=NULL,array $conditions=NULL){

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
	* @param array $tables The tables this function should use as reference when validating table and field names.
	* @param array $fields The fields this function should use as reference when validating table and field names.
	* @return boolean True if this operator is valid, otherwise False.
	*/
	function validate(array $primary_table,array $tables=NULL,array $fields=NULL){

		foreach( $this->_conditions as $condition ){

			if( is_a( $v, 'SLDB\Operator' ) ){

				if( ! $this->_validate( $tables, $fields ) ){

					return false;

				}

			}

			// Make sure that the Table exists within $tables and $fields if the Table is not NULL.

			// If the conditions table is not in $tables, or the conditions table is not a key in fields, return false if the conditions table is not NULL.
			if( ( ! in_array( $condition->getTable(), $tables ) || ! array_key_exists( $condition->getTable(), $fields ) ) && $condition->getTable() !== NULL ){

				return false;

			}

			// If the table is NULL, make sure that the field for the condition exists within the proper field array for the Primary Table.

			// If the conditions table is NULL and the conditions field is not found under $primary_table field array, return false.
			if( $condition->getTable() === NULL && ! in_array( $condition->getField(), $fields[$primary_table] ) ){

				return false;

			}

			// If the table is not NULL, make sure that the field for the condition exists within the proper field array for the joined Table.

			// If the conditions table is not NULL and the conditions field is not in the conditions table field array, return false.
			if( $condition->getTable() !== NULL && ! in_array( $condition->getField(), $fields[ $condition->getTable() ] ) ){

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