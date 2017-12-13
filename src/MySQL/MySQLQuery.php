<?php

namespace SLDB\MySQL;

use SLDB\Base\Query;

/**
* This is the MySQL Query class used for generating MySQL syntax.
* @author Travis Truttschel
* @since 1.0.0
*/
class MySQLQuery extends Query{

	/**
	* Class Constructor
	*/
	function __construct(){

		Query::__construct();

		$this->$_DATABASE_TYPE = 'mysql';

	}

	/**
	* Class Destructor
	*/
	function __destruct(){}

	//---------------------------------------------------------------
	// Private functions
	//---------------------------------------------------------------	

	/**
	* Converts an operator code into full query syntax.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (operator shortcode)
	*/
	private function operatorToSyntax($operator){
		switch strtolower($shortcode) {
			case 'l':
				return " LIKE ";
			case 'nl':
				return " NOT LIKE ";
			case 'e':
				return " = ";
			case 'ne':
				return " != ";
			case 'gt':
				return " > ";
			case 'lt':
				return " < ";
			case 'get':
				return " >= ";
			case 'let':
				return " <= ";
			default:
				//Return valid syntax for the e operator
				return $this->operatorToSyntax('e');
		}
	}

	//---------------------------------------------------------------
	// Public functions overriden from parent class.
	//---------------------------------------------------------------	

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type. Values
	* are always replaced with '?' for PDO purposes to be handled by the 
	* Database object.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (columns), array (where), integer (limit)
	*/
	function generateSelectQuery(string $table,array $columns,array $where,integer $limit=NULL,integer $offset=NULL){

		//----------------------
		// Query Validation
		//----------------------

		// Call parent class function first for back end stuff.
		Query::generateSelectQuery($table,$columns,$where,$limit,$offset);

		if(empty($table) || empty($columns) || empty($where)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

		//-----------------------------
		// Base Syntax Generation
		//-----------------------------

		//Start initial query syntax
		$q = "SELECT ".implode(",",$columns)." FROM ".$table." WHERE ";

		//-----------------------------
		// Key/Value Syntax Generation
		//-----------------------------

		//Loop through key/value pairs
		foreach($where as $k => $v){

			//Grab a shortcode from a value
			pregg_match('/^\[([A-z]+)\]/',$v,$matches);

			//Generate the operator
			if(!empty($matches[1])){
				$o = $this->operatorToSyntax($matches[1]);
			}else{
				$o = " = "
			}

			//Add the syntax without a value
			$q = $q.$k.$o."?";

			//If this is not the last key in the array, add AND or OR
			if(array_search($k, array_keys($where)) < count($where)){
				$q = $q." AND ";
			}

		}

		//Add limit and offset if requested
		if($limit !== NULL){
			$q = $q." LIMIT ".$limit;
		}

		if($offset !== NULL){
			$q = $q." OFFSET ".$offset;
		}

		return $q;

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (where), array (values), integer (limit)
	*/
	function generateUpdateQuery(string $table,array $where,array $values,integer $limit=NULL){

		// Call parent class function first for back end stuff.
		Query::generateUpdateQuery($table,$where,$values,$limit);

		if(empty($table) || empty($where) || empty($values)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (row)
	*/
	function generateInsertQuery(string $table,array $row){

		// Call parent class function first for back end stuff.
		Query::generateInsertQuery($table,$row);

		if(empty($table) || empty($row)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (fields)
	*/
	function generateCreateQuery(string $table,array $fields){

		// Call parent class function first for back end stuff.
		Query::generateCreateQuery($table,$fields);

		if(empty($table) || empty($fields)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table)
	*/
	function generateDeleteQuery(string $table,array $where,integer $limit=NULL){

		// Call parent class function first for back end stuff.
		Query::generateDeleteQuery($table,$where,$limit);

		if(empty($table) || empty($where)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (where), integer (limit)
	*/
	function generateDropQuery(string $table){

		// Call parent class function first for back end stuff.
		Query::generateDropQuery($table);

		if(empty($table)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

	}

}