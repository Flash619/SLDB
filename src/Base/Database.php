<?php

namespace SLDB\Base;

use SLDB\Base\Query as BaseQuery;

class Database{

	const MYSQL      = 1;
	const POSTGRESQL = 2;
	const MONGODB    = 3;

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

	function initQuery(int $type=NULL){

		return new BaseQuery($this,$type);

	}

	function isConfigured(){

		if( $this->_config === NULL && ! is_array($this->_config) ){

			return false;

		}

		return true;

	}

	protected function setType(int $type){

		$this->$_type = $type;

	}


	function execute(BaseQuery &$query){}

}