<?php
namespace SLDB\Base;

/**
* This Database class is inherited by other Database class types and is used for general abstraction. This is more
* useful for someone who may be loading multiple databases with SLDB at once, or checking to see if a variable is
* a valid SLDB database object type. 
* @author Travis Truttschel
* @since 1.0.0
*/
class Database{

	//---------------------------------------------------------------
	// Protected member variables.
	//---------------------------------------------------------------

	/**
	* Whether this database is configed and ready.
	*/
	protected $_CONFIGURED;

	/**
	* What type of database this is.
	*/
	protected $_DATABASE_TYPE;

	/**
	* Class Constructor
	*/
	function __construct(){
		$this->_CONFIGURED    = false;
		$this->_DATABASE_TYPE = NULL;
		$this->_LAST_ERROR    = NULL;
	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}

	//---------------------------------------------------------------
	// Public Functions to be overriden by child classes.
	//---------------------------------------------------------------

	function select(array $columns,string $table,array $where,integer $limit=NULL){}

	function update(string $table,array $where,array $values,integer $limit=NULL){}
 
	function insert(string $table,array $row){}

	function create(string $table,array $fields){}

	function delete(string $table,array $where,integer $limit=NULL){}

	function drop(array $query){}

	//---------------------------------------------------------------
	// Public Functions
	//---------------------------------------------------------------	

	/**
	* Returns whether this database has been configured.
	* @author Travis Truttschel
	* @since 1.0.0
	* @return boolean
	*/
	function isConfigured(){ 		
		return $this->$_CONFIGURED; 
	}

	/**
	* Returns the type of database this database is using.
	* @author Travis Truttschel
	* @since 1.0.0
	* @return string || NULL
	*/
	function getDatabaseType(){
		return $this->$_DATABASE_TYPE;
	}

	//---------------------------------------------------------------
	// Protected functions
	//---------------------------------------------------------------	

	//---------------------------------------------------------------
	// Private functions
	//---------------------------------------------------------------	

	private function fatalQueryError($error){

		$result                    = array();
		$errors                    = array();
		$errors[]                  = "Failed to generate query syntax.";
		$result['errors']          = $errors;
		$result['rows']            = NULL;
		$result['rows_affected']   = NULL;

		return $result;

	}

}