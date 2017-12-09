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
	* @return boolean (true if creation of query syntax succeeded, otherwise false)
	*/
	function generateSelectQuery(string $table,array $columns,array $where,integer $limit=NULL){

		// Call parent class function first for back end stuff.
		Query::generateSelectQuery($table,$columns,$where,$limit);

		return true;

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (where), array (values), integer (limit)
	* @return boolean (true if creation of query syntax succeeded, otherwise false)
	*/
	function generateUpdateQuery(string $table,array $where,array $values,integer $limit=NULL){

		// Call parent class function first for back end stuff.
		Query::generateUpdateQuery($table,$where,$values,$limit);

		return true;

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (row)
	* @return boolean (true if creation of query syntax succeeded, otherwise false)
	*/
	function generateInsertQuery(string $table,array $row){

		// Call parent class function first for back end stuff.
		Query::generateInsertQuery($table,$row);

		return true;

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (fields)
	* @return boolean (true if creation of query syntax succeeded, otherwise false)
	*/
	function generateCreateQuery(string $table,array $fields){

		// Call parent class function first for back end stuff.
		Query::generateCreateQuery($table,$fields);

		return true;

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table)
	* @return boolean (true if creation of query syntax succeeded, otherwise false)
	*/
	function generateDeleteQuery(string $table,array $where,integer $limit=NULL){

		// Call parent class function first for back end stuff.
		Query::generateDeleteQuery($table,$where,$limit);

		return true;

	}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (where), integer (limit)
	* @return boolean (true if creation of query syntax succeeded, otherwise false)
	*/
	function generateDropQuery(string $table){

		// Call parent class function first for back end stuff.
		Query::generateDropQuery($table);

		return true;

	}

}