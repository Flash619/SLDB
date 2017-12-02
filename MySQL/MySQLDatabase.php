<?php

namespace SLDB\MySQL;

use SLDB\Base\Database;
use SLDB\MySQL\MySQLQuery;

/**
* This is the MySQLDatabase class used for all MySQL Database activities.
* @author Travis Truttschel
* @since 1.0.0
*/
class MySQLDatabase extends Database{

	/**
	* Class Constructor
	*/
	function __construct(){
		$this->_CONFIGURED = false;
		$this->_DATABASE_TYPE = NULL;
	}

	/**
	* Class Destructor
	*/
	function __destruct(){

	}

	function select(array $query=array()){

	}

	function insert(array $query=array()){
		
	}

	function create(array $query=array()){
		
	}

	function delete(array $query=array()){
		
	}

	function drop(array $query=array()){
		
	}

}