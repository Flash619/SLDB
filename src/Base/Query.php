<?php

namespace SLDB\Base;

use SLDB\Exception\InvalidQueryFieldException;
use SLDB\Exception\InvalidQueryTypeException;
use SLDB\Exception\InvalidQueryOperatorException;
use SLDB\Base\Database as BaseDatabase;
use SLDB\Operator;

/**
* This is the base query object, used and inherited by all other query objects. This object is designed to store parameters for queries and generate syntax and parameter arrays accordignly for its respective database type. This object is then passed to a Database::execute() function for execution of its internal syntax.
*@author Travis Truttschel
*/
class Query{

	// Constants used for query type identification.
	const SELECT  = 1;
	const UPDATE  = 2;
	const INSERT  = 3;
	const DELETE  = 4;
	const CREATE  = 5;
	const DROP    = 6;

	/**
	* The type of database this query is designed for.
	*/
	protected $_database_type;

	/**
	* The table name this database should target during execution.
	*/
	protected $_table;

	/**
	* The table names this query should join during execution.
	*/
	protected $_joined_tables;

	/**
	* The type of query that this query is.
	*/
	protected $_type;

	/**
	* The fields this query should select or refer to during execution.
	*/
	protected $_fields;

	/**
	* The values this query should assign to fields during execution.
	*/
	protected $_values;

	/**
	* The operator this query should use during execution.
	*/
	protected $_operator;

	/**
	* The ammount of rows this query should be limited to during execution.
	*/
	protected $_limit;

	/**
	* The ammount of rows this query should be offset by during execution.
	*/
	protected $_offset;

	/**
	* The syntax this query should use during execution, generated via the Query::generate() function.
	*/
	protected $_syntax;

	/**
	* The array of params this query should use during execution in reference to the syntax this query has generated.
	*/
	protected $_params;

	/**
	* The rows returned from a select query during execution.
	*/
	protected $_rows_returned;

	/**
	* The number of rows this query has affected during execution.
	*/
	protected $_rows_affected;

	/**
	* The message collected from the database during execution.
	*/
	protected $_message;

	/**
	* The error collected from the database during execution.
	*/
	protected $_error;

	/**
	* Class Constructor
	*/
	function __construct(int $type=NULL){

		$this->_database_type =  NULL;
		$this->_table         =  NULL;
		$this->_joined_tables =  array();
		$this->_type          =  0;

		$this->_fields        =  array();
		$this->_values        =  array();
		$this->_operator      =  NULL;
		$this->_limit         =  NULL;
		$this->_offest        =  NULL;

		$this->_syntax        =  NULL;
		$this->_params        =  array();

		$this->_rows_returned =  0;
		$this->_rows_affected =  0;
		$this->_message       =  NULL;
		$this->_error         =  NULL;

		if($type !== NULL){

			$this->setType($type);

		}

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}

	/**
	* Sets the selected fields for this query to the field names in the provided array.
	* @param array $fields An array of field names to reference or retrieve.
	* @param string $table (Optional) Name of joined table whos fields should be set. If this value is not provided, it will be assumed all fields belong to the primary selected table.
	* @return SLDB\Base\Query This query.
	*/
	function setFields(array $fields,string $table=NULL){

		// Clear out all fields
		$this->_fields = array();

		// Re create field key for primary table if required.
		if( $this->_table !== NULL ){

			$this->setTable( $this->getTable() );

		}

		// Re create field key for joined tables if required.
		if( count( $this->_joined_tables ) > 0 ){

			$this->setJoinedTables( $this->getJoinedTables() );

		}

		// Add all requested fields.
		foreach( $fields as $field ){

			$this->addField( $field, $table );

		}

		return $this;

	}

	/**
	* Adds a field name provided to the list of field names to be referenced or retrieved in this query.
	* @param string $fields Field name to reference or retrieve.
	* @param string $table (Optional) Name of joined table this field belongs to. If this value is not provided, it will be assumed the field belongs to the primary selected table.
	* @return SLDB\Base\Query This query.
	*/
	function addField(string $field,string $table=NULL){

		if( $table === NULL ){

			$table = $this->_table;

		}

		if( $table === NULL ){

			throw new InvalidQueryFieldException("Unable to add field to table 'NULL'.");

		}

		if(! array_key_exists( $table, $this->_fields ) ){

			throw new InvalidQueryFieldException("The table name provided '".$table."' does not exist within this query.");

		}

		$this->_fields[$table][] = $field;

		return $this;
		
	}

	/**
	* Adds a value provided to the list of values to be assigned in this query.
	* @param string $field The field this value should be assigned to.
	* @param string $value The value that should be assigned to this field.
	* @return SLDB\Base\Query This query.
	*/
	function addValue(string $field, string $value){

		$this->_values[$field] = $value;
		return $this;

	}

	/**
	* Adds as an array of values to the list of values to be assigned in this query.
	* @param array $values An array of field names and values to add to the list of values to be assigned.
	* @return SLDB\Base\Query This query.
	*/
	function addValues(array $values){

		$this->_values = array_merge($this->_values, $values);
		return $this;

	}

	/**
	* Adds a joined table to this query. This is useful for MySQL or PostgreSQL when joining multiple tables for a single query.
	* @param string $table Table name to join.
	* @return SLDB\Base\Query This query.
	*/
	function addJoinedTable(string $table){

		if( $this->_table != $table ){

			if( ! in_array( $table, $this->_joined_tables ) ){

				$this->_joined_tables[] = $table;

			}

			if( ! array_key_exists( $table, $this->_fields ) ){

				$this->_fields[$table] = array();

			}

		}

		return $this;

	}

	/**
	* Sets the tables to join for this query. This is useful for MySQL or PostgreSQL when joining multiple tables for a single query.
	* @param array $joined_tables Tables name to join.
	* @return SLDB\Base\Query This query.
	*/
	function setJoinedTables(array $joined_tables){

		$this->_joined_tables = array();

		foreach( $joined_tables as $table ){

			$this->addJoinedTable( $table );

		}

		return $this;

	}

	/**
	* Sets the values to be assigned in this query to the provided array of field names and values.
	* @param array $values An array of field names and values to be assigned in this query.
	* @return SLDB\Base\Query This query.
	*/
	function setValues(array $values){

		$this->_values = $values;
		return $this;

	}

	/**
	* Sets the operator in this query to the operator provided.
	* @param SLDB\Operator $operator The operator to use in this query.
	* @return SLDB\Base\Query This query.
	*/
	function setOperator(Operator $operator){

		$this->_operator = $operator;
		return $this;

	}

	/**
	* Sets the limit of rows this query should affect. This value is only honored if the database and query
	* type supports limits.
	* @param int $limit The number of rows this query should affect.
	* @return SLDB\Base\Query This query.
	*/
	function setLimit(int $limit){

		$this->_limit = $limit;
		return $this;

	}

	/**
	* Sets the ammount of rows that this query should be offset by. This value is only honored if the database and query type supports offsets. 
	* @param int $offset The ammount of rows this query should be offset by.
	* @return SLDB\Base\Query This query.
	*/
	function setOffset(int $offset){

		$this->_offset = $offset;
		return $this;

	}

	/**
	* Sets what type of query this is. This value is one of SELECT, INSERT, UPDATE, DELETE, CREATE, DROP constants stored within the SLDB\Base\Query object.
	* @param int $type The SLDB\Base\Query constant that referrs to this queries type.
	* @return SLDB\Base\Query This query.
	*/
	function setType(int $type){

		$this->_type = $type;
		return $this;

	}

	/**
	* Sets the table or collection this query should target.
	* @param string The name of the table or collection this query should target.
	* @return SLDB\Base\Query This query.
	*/
	function setTable(string $table){

		if( $this->_table !== NULL ){

			unset( $this->_fields[ $this->_table ] );

		}

		$this->_table = $table;
		$this->_fields[ $table ] = array();
		return $this;

	}

	/**
	* Returns the array of rows returned from this query. This is only useful for select queries.
	* @return array Array of returned rows from this query.
	*/
	function getRowsReturned(){

		return $this->_rows_returned;

	}

	/**
	* Returns the number of rows affected by this query. 
	* @return int Number of rows affected by this query.
	*/
	function getRowsAffected(){

		return $this->_rows_affected;

	}

	/**
	* Returns the error stored within this query if an error was encounted during execution.
	* @return string or NULL Error stored within this query if one exists.
	*/
	function getError(){

		return $this->_error;

	}

	/**
	* Returns the database output message collected during execution if one was stored. 
	* @return string or NULL Database message during execution if one exists. 
	*/
	function getMessage(){

		return $this->_message;

	}

	/**
	* Returns the type of query this query is. This value is compared using the defined type constants within SLDB\Base\Query.
	* @return int Type of query.
	*/
	function getType(){

		return $this->_type;

	}

	/**
	* Returns the database type that this query is designed to be compatible with. This value is compared using the defined constants within SLDB\Base\Database.
	* @return int Type of database this query is designed for.
	*/
	function getDatabaseType(){

		return $this->_database_type;

	}

	/**
	* Returns the table or collection name this query is set to target. 
	* @return string Table or collection name.
	*/
	function getTable(){

		return $this->_table;

	}

	/**
	* Returns the joined tables for this query as an array.
	* @return array Tables joined to this query.
	*/
	function getJoinedTables(){

		return $this->_joined_tables;

	}

	/**
	* Returns the syntax generated by this query after using the Query::generate() function. 
	* @return string Syntax generated.
	*/
	function getSyntax(){

		return $this->_syntax;

	}

	/**
	* Returns an array of parameters to be bound during execution in reference to the query syntax generated by this query.
	* @return array Params to be bound during execution.
	*/
	function getParams(){

		return $this->_params;

	}

	/**
	* Returns the array of field names and values stored within this query to be assigned during execution.
	* @return array Field names and values to be assigned during execution of this query.
	*/
	function getValues(){

		return $this->_values;

	}

	/**
	* Returns the operator to be used during execution of this query.
	* @return SLDB\Operator Operator to be used during execution of this query.
	*/
	function getOperator(){

		return $this->_operator;

	}

	/**
	* Returns true if this query has a error stored from during execution. Otherwise false is returned. 
	* @return boolean True if an error is stored within this query, otherwise false.
	*/
	function hasError(){

		if( $this->_error !== NULL ){

			return true;

		}

		return false;

	}

	/**
	* Returns true if this query has a message stored from during execution. Otherwise false is returned. 
	* @return boolean True if an message is stored within this query, otherwise false.
	*/
	function hasMessage(){

		if( $this->_message !== NULL ){

			return true;

		}

		return false;
	}

	/**
	* Generates the syntax for this query based on all parameters stored within this query. This function will also popluate the internal params array to be used during execution in reference to the syntax string stored within this query.
	*/
    function generate(){

    	// Not all query types use operators.
    	if( $this->_type !== self::INSERT && $this->_type !== self::CREATE ){

    		if( ! $this->_operator->validate( $this->_table, $this->_joined_tables, $this->_fields ) ){

    			throw new InvalidQueryOperatorException("Operator table or field not valid for this query.");

    		}

    	}

		switch($this->_type){
			case self::SELECT:
				$this->generateSelectSyntax();
				break;
			case self::UPDATE:
				$this->generateUpdateSyntax();
				break;
			case self::INSERT:
				$this->generateInsertSyntax();
				break;
			case self::DELETE:
				$this->generateDeleteSyntax();
				break;
			case self::CREATE:
				$this->generateCreateSyntax();
				break;
			case self::DROP:
				$this->generateDropSyntax();
				break;
			default:
				throw new InvalidQueryTypeException();
		}

	}

	protected function operatorToSyntax(Operator $operator){}

	protected function generateSelectSyntax(){}

	protected function generateUpdateSyntax(){}

	protected function generateInsertSyntax(){}

	protected function generateDeleteSyntax(){}

	protected function generateCreateSyntax(){}

	protected function generateDropSyntax(){}

}