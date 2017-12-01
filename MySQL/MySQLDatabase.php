<?php

namespace SLDB;

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

}