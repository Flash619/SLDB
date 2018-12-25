<?php
/*
Copyright 2017 Travis Truttschel (truttschel.travis@gmail.com)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

namespace SLDB;

use SLDB\MySQL\Database as MySQLDatabase;
use SLDB\Exception\InvalidConfigurationException;

class SLDB{

	/**
	* The configuration array for SLDB
	*/
	private $_config;

	/**
	* An array of valid configuration parameters for SDLB
	*/
	private $_valid_config_params;

	/**
	* An array of required configuration parameters for SLDB
	*/
	private $_required_config_params;

	/**
	* The active database object used for queries
	*/
	private $_database;

	/**
	* Class Constructor
	*/
	function __construct(array $config=array()){

		//Define member variables
		$this->_config = array();

		$params = array(
			'database_type',
			'database_name',
			'database_host',
			'database_user',
			'database_pass');

		$this->_valid_config_params = $params;
		$this->_required_config_params = $params;

		//Setup SLDB if a config is supplied
		if( empty($config) ){

			throw new InvalidConfigurationException();

		}else{

			$this->setConfig($config);

		}


	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}

	/**
	* Imports a configuration array to SLDB and verifies the accuracy of configuration keys
	* as well as sets up required classes/variables within SLDS to match the supplied
	* configuration options.
	* @param array
	* @throws InvalidConfigurationException
	*/
	private function setConfig(array $config){

		$matchedParams = array();

		//Loop through all $config as $k (key) $v (value)
		foreach( $config as $k => $v ){

			//IF $k exists within _required_config_params
			if( in_array( $k, $this->_required_config_params ) ){

				//IF $v is not set or null.
				if( empty($v) ){
					throw new InvalidConfigurationException();
				}else{
					$matchedParams[] = $k;
				}

			}

			//IF $k does not exist within _valid_config_params
			if( ! in_array( $k, $this->_valid_config_params ) ){
				throw new InvalidConfigurationException();
			}

		}

		//IF $matchedParams does not equal _required_config_params
		if( $matchedParams != $this->_required_config_params ){
			throw new InvalidConfigurationException();
		}

		//Initialize based on database type
		switch( strtolower( $config['database_type'] ) ){
			case 'mysql':
				$this->_database = new MySQLDatabase($this->_config);
				$config['database_type'] = MySQLDatabase::MYSQL;
				break;
			default:
				throw new InvalidConfigurationException("Database type not supported.");
		}

		//Save config for future reference
		$this->_config = $config;

	}

    /**
     * Returns the database used by this SLDB instance.
     * @return Database
     */
	public function getDatabase(){

		return $this->_database;

	}

    /**
     * Returns a new query object by calling the databases initQuery function.
     * This is shorthand for SLDB->getDatabase()->initQuery();
     * @return Query
     */
	public function initQuery(){

	    return $this->_database->initQuery();

    }

    /**
     * Executes the provided query on the database used by this instance.
     * This is shorthand for SLDB->getDatabase()->execute();
     * @param Query $query
     */
    public function execute(Query $query){

	    $this->_database->execute($query);

    }

}