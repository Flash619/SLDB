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
	* The last known error recieved from the database engine in PHP
	*/
	protected $_LAST_ERROR;

	/**
	* Class Constructor
	*/
	function __construct(){
		$this->_CONFIGURED    = false;
		$this->_DATABASE_TYPE = NULL;
		$this->_LAST_ERROR    = NULL;
	}

	/**
	* Class Destructor
	*/
	function __destruct(){}

	//---------------------------------------------------------------
	// Standard functions to be overidden per child class.
	//---------------------------------------------------------------

	function select(array $columns,string $table,array $where,integer $limit=NULL){}
 
	function insert(string $table,array $row){}

	function create(){}

	function delete(string $table,array $where,integer $limit=NULL){}

	function drop(array $query){}


	//---------------------------------------------------------------
	// Predefined functions used by child classes.
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

}