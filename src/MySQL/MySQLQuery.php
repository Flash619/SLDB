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
	// Public functions overriden from parent class.
	//---------------------------------------------------------------	

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (columns), array (where), integer (limit)
	*/
	function generateSelectQuery(string $table,array $columns,array $where,integer $limit=NULL){

		// Call parent class function first for back end stuff.
		Query::generateSelectQuery($table,$columns,$where,$limit);

		if(empty($table) || empty($columns) || empty($where)){
			throw new InvalidQueryValuesException("Required values not met.");
		}

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