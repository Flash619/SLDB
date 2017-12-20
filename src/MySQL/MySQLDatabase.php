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

	function select(string $table,array $columns,array $where,integer $limit=NULL,integer $offset=NULL){

		$query  = new MySQLQuery();
		$syntax = '';
		$errors = array();
		$result = array();
		
		try {
			$syntax = $query->generateSelectQuery($table,$columns,$where,$limit,$offset);
		}catch (Exception $e){
			return $this->fatalQueryError($e->getMessage());
		}

		//TODO PDO stuff.

		return $result;
	}

	function update(string $table,array $where,array $values,integer $limit=NULL){

		$query = new MySQLQuery();
		$syntax = '';
		$errors = array();
		$result = array();

		try{
			$syntax = $query->generateUpdateQuery($table,$where,$values,$limit);
		}catch(Exception $e){
			return $this->fatalQueryError($e->getMessage());
		}

		//TODO PDO stuff.

		return $result;
		
	}

	function insert(string $table,array $values){

		$query = new MySQLQuery();
		$syntax = '';
		$errors = array();
		$result = array();

		try{
			$syntax = $query->generateInsertQuery($table,$values);
		}catch(Exception $e){
			return $this->fatalQueryError($e->getMessage());
		}

		//TODO PDO stuff.

		return $result;
		
	}

	function create(string $table,array $fields){

		$query = new MySQLQuery();
		$syntax = '';
		$errors = array();
		$result = array();

		try{
			$syntax = $query->generateCreateQuery($table,$fields);
		}catch(Exception $e){
			return $this->fatalQueryError($e->getMessage());
		}

		//TODO PDO stuff.

		return $result;
		
	}

	function delete(string $table,array $where,integer $limit=NULL){

		$query = new MySQLQuery();
		$syntax = '';
		$errors = array();
		$result = array();

		try{
			$syntax = $query->generateDeleteQuery($table,$where,$limit);
		}catch(Exception $e){
			return $this->fatalQueryError($e->getMessage());
		}

		//TODO PDO stuff.

		return $result;
		
	}

	function drop(string $table){

		$query = new MySQLQuery();
		$syntax = '';
		$errors = array();
		$result = array();

		try{
			$syntax = $query->generateDropQuery($table);
		}catch(Exception $e){
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
