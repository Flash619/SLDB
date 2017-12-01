<?php
namespace SLDB;

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
	private $_REQIORED_CONFIG_PARAMS;

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
		$this->_VALID_CONFIG_PARAMS = array(
			'database_type',
			'database_name',
			'database_host',
			'database_username',
			'database_password');
		$this->_REQIORED_CONFIG_PARAMS = array(
			'database_type',
			'database_name',
			'database_host',
			'database_username',
			'database_password');
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
	function __destruct(){

	}

	/**
	* Imports a configuration array to SLDB and verifies the accuracy of configuration keys
	* as well as sets up required classes/variables within SLDS to match the supplied
	* configuration options.
	* @author Travis Truttschel
	* @since 1.0.0
	* @param array
	* @throws InvalidConfigurationOptionException InvalidConfigurationValueException InvalidConfigurationExceiption
	*/
	function setConfig(array $config=array()){

		//Ignore empty arrays.
		if(empty($config)){
			return;
		}

		// --------------------------------------------------
		// Verify profided configuration validity
		// --------------------------------------------------

		//Loop through all $config as $k (key) $v (value)
		foreach($config as $k => $v){

			$valid = false;

			//Loop through all $_VALID_CONFIG_PARAMS as $p (value)
			foreach($_VALID_CONFIG_PARAMS as $p){

				//IF $k == $p then this parameter key is valid
				if($k == $p &&){

					//IF $v is empty AND $k does not exist in _REQIORED_CONFIG_PARAMS
					if(empty($v) && !array_key_exists($k, $this->_REQIORED_CONFIG_PARAMS)){

						//The value of this config array key is not valid
						throw new InvalidConfigurationValueException();

					}else{

						//This configuration key is valid
						$valid = true;

					}

				}

			}

			if(!$valid){
				throw new InvalidConfigurationOptionException();
			}

		}

		//Loop through all _REQIORED_CONFIG_PARAMS as $p (value)
		foreach($_REQIORED_CONFIG_PARAMS as $p){

			//IF $p does not exist in $config.
			if(! array_key_exists($p, $config)){

				//A required configuraation field is missing
				throw new InvalidConfigurationException();

			}

		}

		// --------------------------------------------------
		// Setup Required Member Objects & Save Configuration
		// --------------------------------------------------

		$this-$_CONFIG = $config;
		$this->initializeConfig();

	}

	/**
	* Sets up all member objects based on the configuration supplied.
	* @author Travis Truttschel
	* @since 1.0.0
	* @throws InvalidConfigurationException InvalidDatabaseTypeException
	*/
	protected function initializeConfig(){

		//IF _CONFIG is empty
		if(empty($this->_CONFIG)){

			//Invalid configuration is present
			throw new InvalidConfigurationException();

		}

		//Initialize based on database type
		switch(strtolower($this->_CONFIG['database_type'])){
			case 'mysql':
				$this->initializeMySQL();
				break;
			default:
				throw new InvalidDatabaseTypeException();
		}

	}

	/**
	* Sets up all member objects based for a MySQL type database. Called by initializeConfig.
	* @author Travis Truttschel
	* @since 1.0.0
	*/
	protected function initializeMySQL(){

		$this->_DATABASE = new MySQLDatabase();

	}

}

class InvalidConfigurationValueException extends Exception{}
class InvalidConfigurationOptionException extends Exception{}
class InvalidConfigurationException extends Exception{}
class InvalidDatabaseTypeException extends Exception{}