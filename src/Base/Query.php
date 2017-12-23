<?php

namespace SLDB\Base;

use SLDB\Base\Database as BaseDatabase;
use SLDB\Operator;

class Query{

	const SELECT  = 1;
	const UPDATE  = 2;
	const INSERT  = 3;
	const DELETE  = 4;
	const CREATE  = 5;
	const DROP    = 6;

	protected $_database_type;
	protected $_table;
	protected $_type;

	protected $_fields;
	protected $_values;
	protected $_operator;
	protected $_limit;
	protected $_offset;

	protected $_syntax;
	protected $_params;

	protected $_rows_returned;
	protected $_rows_affected;
	protected $_message;
	protected $_error;

	/**
	* Class Constructor
	*/
	function __construct(int $type=NULL){

		$this->_database_type = '';
		$this->_table         = '';
		$this->_type          =  0;

		$this->_fields        =  array();
		$this->_values        =  array();
		$this->_operator      =  NULL;
		$this->_limit         =  NULL;
		$this->_offest        =  NULL;

		$this->_syntax        = '';
		$this->_params        = array();

		$this->_rows_returned =  0;
		$this->_rows_affected =  0;
		$this->_message       =  NULL;
		$this->_error         =  NULL;

		if($type !== NULL){

			$this->setType($type);

		}

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}

	function setFields(array $fields){

		$this->_fields = $fields;
		return $this;
		
	}


	function addField(string $field){

		$this->_fields[] = $field;
		return $this;
		
	}

	function addValue(string $field, string $value){

		$this->_values[$field] = $value;
		return $this;

	}

	function addValues(array $values){

		$this->_values = array_merge($this->_values, $values);
		return $this;

	}

	function setValues(array $values){

		$this->_values = $values;
		return $this;

	}

	function setOperator(Operator $operator){

		$this->_operator = $operator;
		return $this;

	}

	function setLimit(int $limit){

		$this->_limit = $limit;
		return $this;

	}

	function setOffset(int $offset){

		$this->_offset = $offset;
		return $this;

	}

	function setType(int $type){

		$this->_type = $type;
		return $this;

	}

	function setTable(string $table){

		$this->_table = $table;
		return $this;

	}

	function getRowsReturned(){

		return $this->_rows_returned;

	}

	function getRowsAffected(){

		return $this->_rows_affected;

	}

	function getError(){

		return $this->_error;

	}

	function getMessage(){

		return $this->_message;

	}

	function getType(){

		return $this->_type;

	}

	function getDatabaseType(){

		return $this->_database_type;

	}

	function getTable(){

		return $this->_table;

	}

	function getSyntax(){

		return $this->_syntax;

	}

	function getParams(){

		return $this->_params;

	}

	function getValues(){

		return $this->_values;

	}

	function getOperator(){

		return $this->_operator;

	}

	function hasError(){

		if( $this->_error !== NULL ){

			return true;

		}

		return false;

	}

	function hasMessage(){

		if( $this->_message !== NULL ){

			return true;

		}

		return false;
	}

    function generate(){

		switch($this->_type){
			case self::SELECT:
				$this->generateSelectSyntax();
				break;
			case self::UPDATE:
				$this->generateUpdateSyntax();
				break;
			case self::INSERT:
				$this->generateInsertSyntax();
				break;
			case self::DELETE:
				$this->generateDeleteSyntax();
				break;
			case self::CREATE:
				$this->generateCreateSyntax();
				break;
			case self::DROP:
				$this->generateDropSyntax();
				break;
			default:
				throw new InvalidQueryTypeException();
		}

	}

	protected function operatorToSyntax(Operator $operator){}

	protected function generateSelectSyntax(){}

	protected function generateUpdateSyntax(){}

	protected function generateInsertSyntax(){}

	protected function generateDeleteSyntax(){}

	protected function generateCreateSyntax(){}

	protected function generateDropSyntax(){}

}
class InvalidQueryTypeException extends \Exception{}
class InvalidOperatorException extends \Exception{}