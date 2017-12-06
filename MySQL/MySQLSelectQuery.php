<?php

namespace SLDB\MySQL;

use SLDB\Base\Query;

/**
* This is the MySQLSelectQuery class used for all MySQL SELECT queries.
* @author Travis Truttschel
* @since 1.0.0
*/
class MySQLSelectQuery extends Query{

	/**
	* Class Constructor
	*/
	function __construct(){

		Query::__construct();

	}

	/**
	* Class Destructor
	*/
	function __destruct(){}

}