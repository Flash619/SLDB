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

    /**
     * Initializes a new query.
     * @param string|NULL $type Type of query to initialize.
     * @return Query
     */
	function initQuery(string $type=NULL){

		return new MySQLQuery($type);

	}

    /**
     * Executes the provided query on this database.
     * @param BaseQuery $query
     * @throws \SLDB\Exception\InvalidQueryOperatorException
     * @throws \SLDB\Exception\InvalidQueryTypeException
     */
	function execute(BaseQuery &$query){

		if( ! is_a( $query, MySQLQuery ) ){
			throw new \Exception("Query supplied does not match database type.");
		}

		$query->generate();

	}

}