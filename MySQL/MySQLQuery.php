<?php

namespace SLDB;

use SLDB\MySQL\MySQLCreateQuery;
use SLDB\MySQL\MySQLDatabase;
use SLDB\MySQL\MySQLDeleteQuery;
use SLDB\MySQL\MySQLDropQuery;
use SLDB\MySQL\MySQLInsertQuery;
use SLDB\MySQL\MySQLSelectQuery;
use SLDB\MySQL\MySQLUpdateQuery;

/**
* This is the MySQLQuery base class used for and inherited by all other MySQL Query classes.
* @author Travis Truttschel
* @since 1.0.0
*/
class MySQLQuery{

	//This class has no real configuration as configurable items
	//are passed during the function call and are stored temporarily
	//within memory during the query.

	/**
	* Class Constructor
	*/
	function __construct(){

	}

	/**
	* Class Destructor
	*/
	function __destruct(){

	}

}