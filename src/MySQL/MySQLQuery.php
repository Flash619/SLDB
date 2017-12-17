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

	private function keyValuesToSyntax(array $values){

		$s = '';

		//Loop through key/value pairs
		foreach($values as $k => $v){

			//Grab a shortcode from a value
			preg_match('/^\[(.*)\]/',$v,$matches);

			//Generate the operator
			if(!empty($matches[1])){
				$o = $this->operatorToSyntax($matches[1]);
			}else{
				$o = " = ";
			}

			//Add the syntax without a value
			$s = $s.$k.$o."? AND ";

		}

		$s = rtrim($s,' AND ');

		return $s;

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

		$result = array();
		$result['params'] = array();
		$result['syntax'] = "";

		//-----------------------------
		// Query Validation
		//-----------------------------

		//vCall parent class function first for back end stuff.
		Query::generateSelectQuery($table,$columns,$where,$limit,$offset);

		if(empty($table) || empty($columns) || empty($where)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

		//-----------------------------
		// Base Syntax Generation
		//-----------------------------

		// Start initial query syntax
		$q = "SELECT ".implode(",",$columns)." FROM ".$table." WHERE ";

		//Loop through key value pairs generating syntax.
		$q = $q.$this->keyValuesToSyntax($where);

		//Add all values in order to the returning params array for PDO.
		foreach( $where as $k => $v ){ $result['params'][] = $v; }

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

		// Call parent class function first for back end stuff.
		Query::generateUpdateQuery($table,$where,$values,$limit);

		$result = array();
		$result['params'] = array();
		$result['syntax'] = "";

		//-----------------------------
		// Query Validation
		//-----------------------------

		if(empty($table) || empty($where) || empty($values)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

		//-----------------------------
		// Base Syntax Generation
		//-----------------------------

		$q = "UPDATE ".$table." SET ";

		//Loop through key value pairs generating syntax.
		$q = $q.$this->keyValuesToSyntax($values);
		$q = $q." WHERE ";
		$q = $q.$this->keyValuesToSyntax($where);

		//Add all values in order to the returning params array for PDO.
		foreach( $values as $k => $v ){ $result['params'][] = $v; }
		foreach( $where as $k => $v ){ $result['params'][] = $v; }

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

		// Call parent class function first for back end stuff.
		Query::generateInsertQuery($table,$values);

		$result = array();
		$result['params'] = array();
		$result['syntax'] = "";

		//-----------------------------
		// Query Validation
		//-----------------------------


		if(empty($table) || empty($values)){
			throw new InvalidQueryValuesException("Required values not met.");
		}
		
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

		// Call parent class function first for back end stuff.
		Query::generateCreateQuery($table,$fields);

		$result = array();
		$result['params'] = array();
		$result['syntax'] = "";

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
	function generateDeleteQuery(string $table,array $where,int $limit=NULL){

		$result = array();
		$result['params'] = array();
		$result['syntax'] = "";

		// Call parent class function first for back end stuff.
		Query::generateDeleteQuery($table,$where,$limit);
         
  		//-----------------------------
		// Query Validation
		//-----------------------------

		if(empty($table) || empty($where)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

        	 //-----------------------------
		// Base Syntax GenerationW
		//-----------------------------

		$q = "DELETE FROM ".$table." WHERE ".$this->keyValuesToSyntax($where);
	
		foreach($where as $k => $v){

			$result['params'][] = $v;

    		}

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

		$result = array();
		$result['params'] = array();
		$result['syntax'] = "";

		// Call parent class function first for back end stuff.
		Query::generateDropQuery($table);

		if(empty($table)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

	}

}
