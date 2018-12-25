<?php

namespace SLDB\Base;

class Database{

	const MYSQL      = 'MYSQL';
	const POSTGRESQL = 'POSTGRESQL';
	const MONGODB    = 'MONGODB';

    /**
     * @var string|NULL The type for this database.
     */
	protected $_type;

    /**
     * @var array The config for this database.
     */
	protected $_config;

	/**
	* Class Constructor
	*/
	function __construct(array $config=NULL){

		$this->_config = $config;
		$this->_type = NULL;

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}

	function getType(){

		return $this->_type;

	}

    /**
     * Initializes a new query.
     * @return Query
     */
	function initQuery(){

		return new Query();

	}

    /**
     * Returns true if this database has been configured, otherwise false.
     * @return bool
     */
	function isConfigured(){

		if( $this->_config === NULL && ! is_array($this->_config) ){

			return false;

		}

		return true;

	}

    /**
     * Sets the database type for this database.
     * @param string $type
     * @return $this
     */
	protected function setType(string $type){

		$this->_type = $type;
		return $this;

	}

    /**
     * Executes the provided query on this database.
     * @param Query $query
     */
	function execute(Query &$query){}

}