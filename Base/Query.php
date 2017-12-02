<?php
namespace SLDB\Base;

/**
* This is the Base Query class inherited by all other Query classes.
* @author Travis Truttschel
* @since 1.0.0
*/
class Query{

	/**
	* Current query type
	*/
	private $_QUERY_TYPE;

	/**
	* Known query types
	*/
	private $_QUERY_TYPES;

	/**
	* Current stored query array;
	*/
	private $_QUERY;

	/**
	* Class Constructor
	*/
	function __construct(string $type,array $query){

		//Set known query types
		$_QUERY_TYPES = array(
			'select',
			'insert',
			'update',
			'create',
			'drop',
			'delete',
		);

		//Validate query type
		if(! in_array($type,$this->_QUERY_TYPES)){
			throw new InvalidQueryTypeException();
		}

		$this->_QUERY_TYPE = $type;

		//Validate query
		if(!$this->validateQuery($query)){
			throw new InvalidQueryException();
		}

		$this->_QUERY = $query;

	}

	/**
	* Class Constructor
	*/
	function __destruct(){

	}

	/**
	* Validates the user provided query array to insure accuracy. Returns true if the provided
	* array is valid, otherwise false will be returned.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param array
	* @return boolean
	*/
	protected function validateQuery(array $query){

		switch($this->$_QUERY_TYPE){

			//--------------------------
			//SELECT query validation
			//--------------------------

			case 'select':
				if(!$this->noEmptyValues($query)){ return false; }
				//First value is table name, Second value is fields, Third value is selectors.
				//OPTIONAL Fourth value is limit.
				if(!is_string($query[0]) || !is_array($query[1]) || is_array($query[2])){ return false; }
				return true;

			//--------------------------
			//INSERT query validation
			//--------------------------

			case 'insert':
				//First value is table name, Second value is field/value pairs.
				if(!is_string($query[0]) || !is_array($query[1])){ return false; }
				return true;

			//--------------------------
			//UPDATE query validation
			//--------------------------

			case 'update':
				//First value is table name, Second value is fields/value pairs, Third value is field/value pairs.
				//OPTIONAL Fourth value is limit.
				if(!is_string($query[0]) || !is_array($query[1]) || !is_array($query[2])){ return false; }
				return true;

			//--------------------------
			//CREATE query validation
			//--------------------------

			case 'create':
				//TODO this type of query is not yet supported.
				throw new InvalidQueryTypeException();
				return true;

			//--------------------------
			//DROP query validation
			//--------------------------

			case 'drop':
				//TODO this type of query is not yet supported.
				throw new InvalidQueryTypeException();
				return true;

			//--------------------------
			//DELETE query validation
			//--------------------------

			case 'delete':
				//First value is table name, Second value is fields/value pairs.
				//OPTIONAL Fourth value is limit.
				if(!is_string($query[0]) || !is_array($query[1])){ return false; }
				return true;
			default: return false;
		}

	}

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

}

class InvalidQueryTypeException extends Exception {}
class InvalidQueryException extends Exception {}