<?php
/*
Copyright 2017 Travis Truttschel (truttschel.travis@gmail.com)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/


namespace SLDB;

use SLDB\Base\Database;
use SLDB\MySQL\MySQLDatabase;

/**
* SLDB Is a slim, lightweight database library for PHP. This main class works as a loader for the database as well
* as initializing queries against any loaded database configuration. 
* @author Travis Truttschel
* @version 1.0.0
* @since 1.0.0
* @param array (optional)
* @license MIT
*/
class SLDB{

	//---------------------------------------------------------------
	// Private member variables.
	//---------------------------------------------------------------

	/**
	* Whether SLDB has loaded a valid configuration array
	*/
	private $_CONFIGURED;

	/**
	* The configuration array for SLDB
	*/
	private $_CONFIG;

	/**
	* An array of valid configuration parameters for SDLB
	*/
	private $_VALID_CONFIG_PARAMS;

	/**
	* An array of required configuration parameters for SLDB
	*/
	private $_REQUIRED_CONFIG_PARAMS;

	/**
	* The active database object used for queries
	*/
	private $_DATABASE;

	/**
	* Class Constructor
	*/
	function __construct(array $config=array()){

		//Define member variables
		$this->_CONFIGURED   =   NULL;
		$this->_CONFIG       =   array();
		$params = array(
			'database_type',
			'database_name',
			'database_host',
			'database_username',
			'database_password');
		$this->_VALID_CONFIG_PARAMS = $params;
		$this->_REQUIRED_CONFIG_PARAMS = $params;
		$this->_DATABASE     =   NULL;

		//Setup SLDB if a config is supplied
		if(empty($config)){
			return;
		}else{
			$this->setConfig($config);
		}

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}

	//---------------------------------------------------------------
	// Private member functions.
	//---------------------------------------------------------------
	
	/**
	* Imports a configuration array to SLDB and verifies the accuracy of configuration keys
	* as well as sets up required classes/variables within SLDS to match the supplied
	* configuration options.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param array
	* @throws InvalidConfigurationOptionException InvalidConfigurationValueException InvalidConfigurationExceiption
	*/
	private function setConfig(array $config){

		// --------------------------------------------------
		// Verify profided configuration validity
		// --------------------------------------------------

		$matchedParams = array();

		//Loop through all $config as $k (key) $v (value)
		foreach($config as $k => $v){

			//IF $k exists within _REQUIRED_CONFIG_PARAMS
			if(in_array($k, $this->_REQUIRED_CONFIG_PARAMS)){

				//IF $v is not set or null.
				if(empty($v)){
					throw new InvalidConfigurationValueException();
				}else{
					$matchedParams[] = $k;
				}

			}

			//IF $k does not exist within _VALID_CONFIG_PARAMS
			if(!in_array($k, $this->_VALID_CONFIG_PARAMS)){
				throw new InvalidConfigurationOptionException();
			}

		}

		//IF $matchedParams does not equal _REQUIRED_CONFIG_PARAMS
		if($matchedParams != $this->_REQUIRED_CONFIG_PARAMS){
			throw new InvalidConfigurationException();
		}

		// --------------------------------------------------
		// Setup Required Member Objects & Save Configuration
		// --------------------------------------------------

		$this-$_CONFIG = $config;
		$this->initializeConfig();

	}

	/**
	* Sets up all member objects based on the configuration stored within SLDB.
	* @author Travis Truttschel
	* @since 1.0.0
	* @throws InvalidConfigurationException InvalidDatabaseTypeException
	*/
	private function initializeConfig(){

		//Initialize based on database type
		switch(strtolower($this->_CONFIG['database_type'])){
			case 'mysql':
				$this->_DATABASE = new MySQLDatabase();
				break;
			default:
				throw new InvalidDatabaseTypeException();
		}

	}

	//---------------------------------------------------------------
	// Public member functions.
	//---------------------------------------------------------------

	/**
	* Used for returning the database object to the user. This is
	* required otherwise the user has no way of accessing the
	* databases member query functions. 
	* @author Travis Truttschel
	* @since 1.0.0
	* @return SLDB\Base\Database Object || NULL
	*/
	function getDatabase(){

		return $this->_DATABASE;

	}

}

class InvalidConfigurationValueException extends Exception{}
class InvalidConfigurationOptionException extends Exception{}
class InvalidConfigurationException extends Exception{}
class InvalidDatabaseTypeException extends Exception{}