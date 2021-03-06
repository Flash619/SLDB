<?php
namespace SLDB\Base;

/**
* This is the Base Query class inherited by all other Query classes.
* @author Travis Truttschel
* @since 1.0.0
*/
class Query{

	//---------------------------------------------------------------
	// Protected member variables.
	//---------------------------------------------------------------

	/**
	* Current database type.
	*/
	protected $_DATABASE_TYPE;

	/**
	* Class Constructor
	*/
	function __construct(){

		$this->_DATABASE_TYPE = NULL;

	}

	/**
	* Class Constructor
	*/
	function __destruct(){}

	//---------------------------------------------------------------
	// Standard functions to be overidden per child class.
	//---------------------------------------------------------------

	/**
	* Validates the user provided query array to insure accuracy. Returns true if the provided
	* array is valid, otherwise false will be returned.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param array
	* @return boolean
	*/
	protected function validateQueryArray(array $query){}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (columns), array (where), integer (limit)
	*/
	function generateSelectQuery(string $table,array $columns,array $where,int $limit=NULL,int $offset=NULL){}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (where), array (values), integer (limit)
	*/
	function generateUpdateQuery(string $table,array $where,array $values,int $limit=NULL){}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (row)
	*/
	function generateInsertQuery(string $table,array $row){}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (fields)
	*/
	function generateCreateQuery(string $table,array $fields){}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table), array (where), integer (limit)
	*/
	function generateDeleteQuery(string $table,array $where,int $limit=NULL){}

	/**
	* Populates $this->_QUERY with proper syntax. Sets query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param string (table)
	*/
	function generateDropQuery(string $table){}

	//---------------------------------------------------------------
	// Predefined functions used by child classes.
	//---------------------------------------------------------------	

	/**
	* Checks to see if an array has any empty values. This function can call itself if it finds
	* any nested arrays. Returns true if no values in the array(s) are empty, otherwaise false
	* will be returned.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param array
	* @return boolean
	*/
	protected function noEmptyValues(array $array){

		foreach( $array as $k =>$v ){
			if(empty($v)){
				return false;
			}
			if(is_array($v)){
				if(!$this->noEmptyValues($v)){
					return false;
				}
			}
		}

		return true;

	}


	//---------------------------------------------------------------
	// Public functions
	//---------------------------------------------------------------	

	/**
	* Returns the formatted raw query syntax for this query.
	* @author Travis Truttschel
	* @since 1.0.0
	* @return string || NULL
	*/
	function getQuerySyntax(){
		return $this->_QUERY;
	}

	/**
	* Returns the type of query stored within this query.
	* @author Travis Truttschel
	* @since 1.0.0
	* @return string || NULL
	*/
	function getQueryType(){
		return $this->_QUERY_TYPE;
	}

	/**
	* Returns the database type this query is designed for.
	* @author Travis Truttschel
	* @since 1.0.0
	* @return string || NULL
	*/
	function getDatabaseType(){
		return $this->_DATABASE_TYPE;
	}

}

class InvalidQueryTypeException extends \Exception {}
class InvalidQueryValuesException extends \Exception {}