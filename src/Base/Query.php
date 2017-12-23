<?php

namespace SLDB\Base;

use SLDB\Base\Database as BaseDatabase;
use SLDB\DatabaseTYpe;
use SLDB\Operator;
use SLDB\QueryType;

class Query{

	protected $_database_type;
	protected $_table;
	protected $_type;

	protected $_fields;
	protected $_field_values;
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
	function __construct(,int $type=NULL){

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
		
	}


	function addField(string $field){

		$this->_fields[] = $field;
		
	}

	function addValue(string $field, string $value){

		$this->_field_values[$field] = $value;

	}

	function setValues(array $field_values){

		$this->_field_values = $field_values;

	}

	function setOperator(Operator $operator){

		$this->_operator[] = $operator;

	}

	function setLimit(int $limit){

		$this->_limit = $limit;

	}

	function setOffset(int $offset){

		$this->_offset = $offset;

	}

	function setType(int $type){

		$this->_type = $type;

	}

	function setTable(string $table){

		$this->_table = $table;

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
			QueryType::SELECT:
				$this->generateSelectSyntax();
				break;
			QueryType::UPDATE:
				$this->generateUpdateSyntax();
				break;
			QueryType::INSERT:
				$this->generateInsertSyntax();
				break;
			QueryType::DELETE:
				$this->generateDeleteSyntax();
				break;
			QueryType::CREATE:
				$this->generateCreateSyntax();
				break;
			QueryType::DROP:
				$this->generateDropSyntax();
				break;
			default:
				throw new InvalidQueryTypeException();
		}

	}

	protected function operatorToSyntax(Operator $operator){}

	protected function valuesToSyntax(array $values){}

	protected function generateSelectSyntax(){}

	protected function generateUpdateSyntax(){}

	protected function generateInsertSyntax(){}

	protected function generateDeleteSyntax(){}

	protected function generateCreateSyntax(){}

	protected function generateDropSyntax(){}

}
class InvalidQueryTypeException extends \Exception{}
class InvalidOperatorException extends \Exception{}