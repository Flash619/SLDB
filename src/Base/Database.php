<?php

namespace SLDB\Base;

use SLDB\Base\Query as BaseQuery;

class Database{

	const MYSQL      = 'MYSQL';
	const POSTGRESQL = 'POSTGRESQL';
	const MONGODB    = 'MONGODB';

	protected $_type;
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

	function initQuery(string $type=NULL){

		return new BaseQuery($this,$type);

	}

	function isConfigured(){

		if( $this->_config === NULL && ! is_array($this->_config) ){

			return false;

		}

		return true;

	}

	protected function setType(int $type){

		$this->_type = $type;
		return $this;

	}


	function execute(BaseQuery &$query){}

}