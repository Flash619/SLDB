<?php

namespace SLDB\MySQL;

use SLDB\Base\Database as BaseDatabase;
use SLDB\Base\Query    as BaseQuery;
use SLDB\MySQL\Query   as MySQLQuery;

class Database extends BaseDatabase{

	/**
	* Class Constructor
	*/
	function __construct(array $config=NULL){

		BaseDatabase::__construct($config);
		$this->_type = self::MYSQL;

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){

		BaseDatabase::__destruct();

	}

	function initQuery(int $type=NULL){

		return new MySQLQuery($type);

	}

	function execute(BaseQuery &$query){

		if( ! is_a( $query, MySQLQuery ) ){
			throw new \Exception("Query supplied does not match database type.");
		}

		$query->generate();

	}

}