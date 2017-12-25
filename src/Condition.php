<?php

namespace SLDB;

/**
* This class is designed to work with the SLDB\Operator class. Conditions are stored within operators, and conditions tell SLDB what rows queries should apply to based on field value comparisons. These conditions are then generated into query syntax within SLDB\Base\Query objects and used during execution.
*@author Travis Truttschel
*/
class Condition{

	// Constants used for condition type identification.
	const LIKE                = 1;
	const NOT_LIKE            = 2;
	const EQUAL_TO            = 3;
	const NOT_EQUAL_TO        = 4;
	consT GREATER_THAN        = 5;
	const LESS_THAN           = 6;
	const GREATER_OR_EQUAL_TO = 7;
	const LESS_OR_EQUAL_TO    = 8;

	/**
	* The table this condition should apply to. Useful only in cases of joined tables.
	*/
	private $_table;

	/**
	* The field this condition should apply to.
	*/
	private $_field;

	/**
	* The type of condition that should apply to this condition.
	*/
	private $_type;

	/**
	* The value this condition should use as reference when making comparisons.
	*/
	private $_value;

	/**
	* Class Constructor
	*/
	function __construct(string $field=NULL,int $type=NULL,string $value=NULL,string $table=NULL){

		$this->setTable($table);
		$this->setField($field);
		$this->setType($type);
		$this->setValue($value);

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}

	/**
	* Returns the table name this condition should apply to.
	* @return string The table name this condition should apply to.
	*/
	function getTable(){

		return $this->_table;

	}

	/**
	* Returns the field name this condition should apply to.
	* @return string The field name this condition should apply to.
	*/
	function getField(){

		return $this->_field;

	}

	/**
	* Returns the type of condition that should apply to this condition.
	* @return int The type of condition that should apply to this condition.
	*/
	function getType(){

		return $this->_type;

	}

	/**
	* Returns the value this condition should use as reference when making comparisons.
	* @return string The value that this condition should use as reference when making comparisons.
	*/
	function getValue(){

		return $this->_value;

	}

	/**
	* Sets the table name this condition should apply to.
	* @param string $table The table this condition should apply to.
	* @return SLDB\Condition This condition.
	*/
	function setTable(string $table){

		$this->_table = $table;
		return $this;

	}

	/**
	* Sets the field name this condition should apply to.
	* @param string $field The field name this condition should apply to.
	* @return SLDB\Condition This condition.
	*/
	function setField(string $field=NULL){

		$this->_field = $field;
		return $this;

	}

	/**
	* Sets the type of condition that should apply to this condition.
	* @param int $type The type of condition that should apply to this condition.
	* @return SLDB\Condition This condition.
	*/
	function setType(int $type=NULL){

		$this->_type = $type;
		return $this;

	}

	/**
	* Sets the value this condition should use as reference when making comparisons.
	* @param string $value The value this condition should use when making comparisons.
	* @return SLDB\Condition This condition.
	*/
	function setValue(string $value=NULL){

		$this->_value = $value;
		return $this;

	}

}