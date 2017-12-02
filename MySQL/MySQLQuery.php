<?php

namespace SLDB\MySQL;

use SLDB\Base\Query;

/**
* This is the MySQLQuery base class used for and inherited by all other MySQL Query classes.
* @author Travis Truttschel
* @since 1.0.0
*/
class MySQLQuery extends Query{

	//This class has no real configuration as configurable items
	//are passed during the function call and are stored temporarily
	//within memory during the query.

	/**
	* Class Constructor
	*/
	function __construct(string $type,array $query){

		//Type setting & validation take place in the parent class.
		Query::__construct($type,$query);

	}

	/**
	* Class Destructor
	*/
	function __destruct(){

	}

}