<?php

namespace SLDB\MySQL

use SLDB\Base\Query as BaseQuery;
use SLDB\MySQL\Database as MySQLDatabase;

class Query extends BaseQuery{

	/**
	* Class Constructor
	*/
	function __construct(MySQLDatabase $database=NULL,int $type=NULL){

		BaseQuery::__construct($database,$type);

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){

		BaseQuery::__destruct();

	}

}
