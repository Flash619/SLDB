<?php

namespace SLDB\MySQL

use SLDB\Base\Database as BaseDatabase;
use SLDB\MySQL\Query   as MySQLQuery;

class Database extends BaseDatabase{

	/**
	* Class Constructor
	*/
	function __construct(array $config=NULL){

		BaseDatabase::__construct($config);

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){

		BaseDatabase::__destruct();

	}

	function initQuery(int $type=NULL){

		return new MySQLQuery($this,$type);

	}

	function execute(MySQLQuery $query){

		$query->generate();

	}

}