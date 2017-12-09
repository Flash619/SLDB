<?php

namespace SLDB\MySQL;

use SLDB\Base\Database;
use SLDB\MySQL\MySQLSelectQuery;
use SLDB\MySQL\MySQLInsertQuery;
use SLDB\MySQL\MySQLCreateQuery;
use SLDB\MySQL\MySQLDeleteQuery;
use SLDB\MySQL\MySQLDropQuery;


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
		$this->_DATABASE_TYPE = 'MySQL';
	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}

	function select(array $columns,string $table,array $where,integer $limit=NULL){

		//Validation takes place in the object itself.
		$query = new MySQLSelectQuery($columns,$table,$where,$limit);

		if(!$query->commit()){

		}

		//TODO Running & Post Validation


	}

	function insert(string $table,array $row){

		//Validation takes place in the object itself.
		$query = new MySQLInsertQuery($table,$row);
		
		//TODO Running & Post Validation
		
	}

	function create(){

		//NOT YET IMPLEMENTED
		
	}

	function delete(string $table,array $where,integer $limit=NULL){

		//Validation takes place in the object itself.
		$query = new MySQLDeleteQuery($table,$where,$limit);
		
		//TODO Running & Post Validation
		
	}

	function drop(array $query){

		//NOT YET IMPLEMENTED
		
	}

}