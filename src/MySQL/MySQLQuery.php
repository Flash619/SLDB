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

		$this->_DATABASE_TYPE = 'mysql';

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
	private function operatorToSyntax(string $operator){
		switch (strtolower($operator)) {
			case 'l':
			case '%':
				return " LIKE ";
			case 'nl':
			case '!%':
				return " NOT LIKE ";
			case 'e':
			case '=':
				return " = ";
			case 'ne':
			case '!=':
				return " != ";
			case 'gt':
			case '>':
				return " > ";
			case 'lt':
			case '<':
				return " < ";
			case 'get':
			case '>=':
				return " >= ";
			case 'let':
			case '<=':
				return " <= ";
			default:
				//Return valid syntax for the e operator
				return $this->operatorToSyntax('e');
		}
	}

	private function keyValuesToSyntaxArray(array $values){

		$r = array(
			'syntax' => '',
			'params' => array()
		);

		//Loop through key/value pairs
		foreach($values as $k => $v){

			$hasOperator = false;

			//Grab a shortcode from a value
			preg_match('/^\[(.*)\]/',$v,$matches);

			//Generate the operator
			if(!empty($matches[1])){
				$hasOperator = true;
				$o = $this->operatorToSyntax($matches[1]);
			}else{
				$o = " = ";
			}

			//Add the syntax without a value
			$r['syntax'] = $r['syntax'].$k.$o."? AND ";

			//If the value has an operator, remove it before storing the value.
			if($hasOperator){
				preg_match('/^\[.*\](.*)/',$v,$matches);
				$r['params'][] = $matches[1];
			}else{
				$r['params'][] = $v;
			}

		}

		$r['syntax'] = rtrim($r['syntax'],' AND ');

		return $r;

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
	function generateSelectQuery(string $table,array $columns,array $where,int $limit=NULL,int $offset=NULL){

		//-----------------------------
		// Query Validation
		//-----------------------------

		if(empty($table) || empty($columns) || empty($where)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

		//-----------------------------
		// Variable Initialization
		//-----------------------------

		//Call parent class function first for back end stuff.
		Query::generateSelectQuery($table,$columns,$where,$limit,$offset);

		// Setup resulting arrays.
		$result = array();
		$result['params'] = array();
		$result['syntax'] = "";

		// Convert $where to key value syntax array.
		$where = $this->keyValuesToSyntaxArray($where);

		// Append where params to result params.
		$result['params'] = $where['params'];

		//-----------------------------
		// Base Syntax Generation
		//-----------------------------

		// Start initial query syntax
		$q = "SELECT ".implode(",",$columns)." FROM ".$table." WHERE ".$where['syntax'];

		// Add limit and/or offset if requested
		if($limit !== NULL){
			$q = $q." LIMIT ?";
			$result['params'][] = $limit;
		}

		if($offset !== NULL){
			$q = $q." OFFSET ?";
			$result['params'][] = $offset;
		}

		$result['syntax'] = $q;

		return $result;

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (where), array (values), integer (limit)
	*/
	function generateUpdateQuery(string $table,array $where,array $values,int $limit=NULL){

		//-----------------------------
		// Query Validation
		//-----------------------------

		if(empty($table) || empty($where) || empty($values)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

		//-----------------------------
		// Variable Initialization
		//-----------------------------

		// Call parent class function first for back end stuff.
		Query::generateUpdateQuery($table,$where,$values,$limit);

		// Setup resulting arrays.
		$result = array();
		$result['params'] = array();
		$result['syntax'] = "";

		// Convert $where and $value to key value syntax arrays.
		$values = $this->keyValuesToSyntaxArray($values);
		$where = $this->keyValuesToSyntaxArray($where);

		// Append value and where params to result params.
		$result['params'] = array_merge($values['params'],$where['params']);

		//-----------------------------
		// Base Syntax Generation
		//-----------------------------

		$q = "UPDATE ".$table." SET ";

		//Loop through key value pairs generating syntax.
		$q = $q.$values['syntax'];
		$q = $q." WHERE ";
		$q = $q.$where['syntax'];

		// Add limit and/or offset if requested
		if($limit !== NULL){
			$q = $q." LIMIT ?";
			$result['params'][] = $limit;
		}

		$result['syntax'] = $q;

		return $result;

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (row)
	*/
	function generateInsertQuery(string $table,array $values){

		//-----------------------------
		// Query Validation
		//-----------------------------

		if(empty($table) || empty($values)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

		//-----------------------------
		// Variable Initialization
		//-----------------------------

		// Call parent class function first for back end stuff.
		Query::generateInsertQuery($table,$values);

		// Setup resulting arrays.
		$result = array();
		$result['params'] = array();
		$result['syntax'] = "";
		
        //-----------------------------
		// Base Syntax Generation
		//-----------------------------

		$q = "INSERT INTO ".$table." (";

		foreach($values as $k => $v){

			$q = $q.$k.",";

		}

		$q = rtrim($q,',');

		$q = $q.") VALUES (";

		foreach($values as $k => $v){

			$q = $q."?".",";
			$result['params'][] = $v;

		}

		$q = rtrim($q,',');

		$q = $q.")";

		$result['syntax'] = $q;

		return $result;

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (fields)
	*/
	function generateCreateQuery(string $table,array $fields){

		//-----------------------------
		// Query Validation
		//-----------------------------

		if(empty($table) || empty($fields)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

		//-----------------------------
		// Variable Initialization
		//-----------------------------

		// Call parent class function first for back end stuff.
		Query::generateCreateQuery($table,$fields);

		$result = array();
		$result['params'] = array();
		$result['syntax'] = "";

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table)
	*/
	function generateDeleteQuery(string $table,array $where,int $limit=NULL){

  		//-----------------------------
		// Query Validation
		//-----------------------------

		if(empty($table) || empty($where)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

		//-----------------------------
		// Variable Initialization
		//-----------------------------

		// Call parent class function first for back end stuff.
		Query::generateDeleteQuery($table,$where,$limit);

		// Setup resulting arrays.
		$result = array();
		$result['params'] = array();
		$result['syntax'] = "";

		// Convert $where to key value syntax array.
		$where = $this->keyValuesToSyntaxArray($where);

		// Append where params to result params.
		$result['params'] = $where['params'];

        //-----------------------------
		// Base Syntax Generation
		//-----------------------------

		$q = "DELETE FROM ".$table." WHERE ".$where['syntax'];

		// Add limit and/or offset if requested
		if($limit !== NULL){
			$q = $q." LIMIT ?";
			$result['params'][] = $limit;
		}

		$result['syntax'] = $q;
		
		return $result;

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (where), integer (limit)
	*/
	function generateDropQuery(string $table){

  		//-----------------------------
		// Query Validation
		//-----------------------------

		if(empty($table)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

		//-----------------------------
		// Variable Initialization
		//-----------------------------

		// Call parent class function first for back end stuff.
		Query::generateDropQuery($table);

		$result = array();
		$result['params'] = array();
		$result['syntax'] = "";

	}

}
