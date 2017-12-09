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
	* Current stored query string
	*/
	protected $_QUERY;

	/**
	* Last stored query error
	*/
	protected $_ERROR;

	/**
	* Stored result rows
	*/
	protected $_ROWS;

	/**
	* Total affected rows from last query
	*/
	protected $_ROWS_AFFECTED;

	/**
	* Result array to be returned.
	*/
	protected $_RESULT;

	/**
	* Class Constructor
	*/
	function __construct(){

		$this->$_ERROR          =  NULL;
		$this->$_QUERY          =  NULL;
		$this->$_ROWS           =  NULL;
		$this->$_ROWS_AFFECTED  =  NULL;

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}


	//---------------------------------------------------------------
	// Standard functions to be overidden per query child class.
	//---------------------------------------------------------------

	/**
	* Validates the user provided query array to insure accuracy. Returns true if the provided
	* array is valid, otherwise false will be returned.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param array
	* @return boolean
	*/
	protected function validateQuery(array $query){}

	/**
	* Generates the result array to be returned to the user. This function is overriden and called
	* by specific query types to provide more dynamic result values depending on query type.
	* @author Travis Truttschel
	* @since 1.0.0
	* @return array
	*/
	protected function generateResultArray(){

		$this->$_RESULT                     = array();
		$this->$_RESULT['rows']             = $this->$_ROWS;
		$this->$_RESULT['error']            = $this->$_ERROR;

	}

	/**
	* Runs the supplied query and returns true on success, false on failure.
	* @author Travis Truttschel
	* @since 1.0.0
	* @return boolean
	*/
	function commit(){}


	//---------------------------------------------------------------
	// Predefined functions used by query child classes.
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
	private function noEmptyValues(array $array){

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

	/**
	* Returns the previous database error.
	* @author Travis Truttschel
	* @since 1.0.0
	* @return string || NULL
	*/
	function getError(){
		return $this->_ERROR;
	}

	/**
	* Returns the resulting rows from a select query.
	* @author Travis Truttschel
	* @since 1.0.0
	* @return array || NULL
	*/
	function getRows(){
		return $this->_ROWS;
	}

	/**
	* Returns total number of rows affected by the last query.
	* @author Travis Truttschel
	* @since 1.0.0
	* @return integer || NULL
	*/
	function getRowsAffected(){
		return $this->_ROWS_AFFECTED;
	}

	/**
	* Returns the formatted raw query syntax for this query.
	* @author Travis Truttschel
	* @since 1.0.0
	* @return string || NULL
	*/
	function getQuerySyntax(){
		return $this->_QUERY;
	}

}

class InvalidQueryTypeException extends Exception {}
class InvalidQueryException extends Exception {}