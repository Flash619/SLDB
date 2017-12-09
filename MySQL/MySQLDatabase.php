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

		Database::__construct();

		$this->_CONFIGURED = false;
		$this->_DATABASE_TYPE = 'mysql';

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}

	//---------------------------------------------------------------
	// Public functions
	//---------------------------------------------------------------	

	function select(string $table,array $columns,array $where,integer $limit=NULL){

		$query  = new MySQLQuery();
		$errors = array();
		$result = array();

		try{
			$query->generateSelectQuery($table,$columns,$where,$limit);
		}catch(Exception e){
			return $this->fatalQueryError(e->getMessage());
		}

		//TODO PDO stuff.

		return $result;
	}

	function update(string $table,array $where,array $values,integer $limit=NULL){

		$query = new MySQLQuery();
		$errors = array();
		$result = array();

		try{
			$query->generateUpdateQuery($table,$where,$values,$limit);
		}catch(Exception e){
			return $this->fatalQueryError($e->getMessage());
		}

		//TODO PDO stuff.

		return $result;
		
	}

	function insert(string $table,array $row){

		$query = new MySQLQuery();
		$errors = array();
		$result = array();

		try{
			$query->generateInsertQuery($table,$row);
		}catch(Exception e){
			return $this->fatalQueryError(e->getMessage());
		}

		//TODO PDO stuff.

		return $result;
		
	}

	function create(string $table,array $fields){

		$query = new MySQLQuery();
		$errors = array();
		$result = array();

		try{
			$query->generateCreateQuery($table,$fields);
		}catch(Exception e)
			return $this->fatalQueryError(e->getMessage());
		}

		//TODO PDO stuff.

		return $result;
		
	}

	function delete(string $table,array $where,integer $limit=NULL){

		$query = new MySQLQuery();
		$errors = array();
		$result = array();

		try{
			$query->generateDeleteQuery($table,$where,$limit);
		}catch(Exception e){
			return $this->fatalQueryError($e->getMessage());
		}

		//TODO PDO stuff.

		return $result;
		
	}

	function drop(string $table){

		$query = new MySQLQuery();
		$errors = array();
		$result = array();

		try{
			$query->generateDropQuery($table);
		}catch(Exception e)
			return $this->fatalQueryError($e->getMessage());
		}

		//TODO PDO stuff.

		return $result;
		
	}

	//---------------------------------------------------------------
	// Protected functions
	//---------------------------------------------------------------	

	//---------------------------------------------------------------
	// Private functions
	//---------------------------------------------------------------		

}