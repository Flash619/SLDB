<?php

namespace SLDB\base;

use SLDB\Exception\InvalidOperatorArgumentsException;
use SLDB\Exception\InvalidQueryFieldException;
use SLDB\Exception\InvalidQueryTypeException;
use SLDB\Exception\InvalidQueryOperatorException;

/**
* This is the base query object, used and inherited by all other query objects. This object is designed to store parameters for queries and generate syntax and parameter arrays accordingly for its respective database type. This object is then passed to a Database::execute() function for execution of its internal syntax.
*/
class Query{

	// Constants used for query type identification.
	const SELECT  = 'QUERY_SELECT';
	const UPDATE  = 'QUERY_UPDATE';
	const INSERT  = 'QUERY_INSERT';
	const DELETE  = 'QUERY_DELETE';
	const CREATE  = 'QUERY_CREATE';
	const DROP    = 'QUERY_DROP';

    /**
     * @var string The type of database this query is designed for.
     */
	protected $_database_type;

    /**
     * @var string The primary table this query should use.
     */
	protected $_table;

    /**
     * @var array[Join] The tables this query should join.
     */
	protected $_join;

    /**
     * @var string The type for this query.
     */
	protected $_type;

    /**
     * @var array The fields this query should fetch.
     */
	protected $_fetch;

    /**
     * @var array The values this query should assign to fields.
     */
	protected $_set;

    /**
     * @var Operator The operator this query will use.
     */
	protected $_operator;

    /**
     * @var int The amount of rows this query should be limited to affecting.
     */
	protected $_limit;

    /**
     * @var int The amount of rows this query should be offset by.
     */
	protected $_offset;

    /**
     * @var string|NULL The syntax generated by this query.
     */
	protected $_syntax;

    /**
     * @var array The array of params this query should use in reference to the syntax this query has generated.
     */
	protected $_params;

    /**
     * @var int The number of rows returned from a select query.
     */
	protected $_rows_returned;

    /**
     * @var int The number of rows this query has affected.
     */
	protected $_rows_affected;

    /**
     * @var string|NULL The message collected from the database during execution.
     */
	protected $_message;

    /**
     * @var string|NULL The error collected from the database during execution.
     */
	protected $_error;

    /**
     * Query constructor.
     */
	function __construct(){

		$this->_database_type =  NULL;
		$this->_table         =  NULL;
		$this->_join          =  array();
		$this->_type          =  NULL;

		$this->_fetch         =  array();
		$this->_set           =  array();
		$this->_operator      =  NULL;
		$this->_limit         =  NULL;
		$this->_offset        =  NULL;

		$this->_syntax        =  NULL;
		$this->_params        =  array();

		$this->_rows_returned =  0;
		$this->_rows_affected =  0;
		$this->_message       =  NULL;
		$this->_error         =  NULL;

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}

	/**
	* Sets this query to be a select query using the fields provided. This function can be called multiple times for join
    * queries,
	* @param array $fields An array of field names to reference or retrieve.
	* @param string $table (Optional) Name of joined table who's fields should be set. If this value is not provided, it
    * will be assumed all fields belong to the primary selected table.
	* @return $this
    * @throws InvalidQueryFieldException
    * @throws InvalidQueryTypeException
	*/
	public function select(array $fields, string $table=NULL){

        if( $this->_table === NULL ){

            $this->setTable($table);

        }

        if( $table === NULL ){

			$table = $this->_table;

		}

        if( $this->_type === NULL ){

            $this->setType(Query::SELECT);

        }

		// Add all requested fields.
		foreach( $fields as $field ){

			// If field is empty of NULL throw exception.
			if( $field === NULL || empty( $field ) ){

				throw new InvalidQueryFieldException("Field name cannot be NULL or empty.");

			}

			$tableExists = false;

			// If table was never added to this query throw exception.
			if( array_key_exists( $table, $this->_fetch ) ){

			    $tableExists = true;

			}

			foreach( $this->_join as $k => $v ){

			    if( $v->getForeignTable() === $table ){

			        $tableExists = true;

                }

            }

			if(! $tableExists ){

                throw new InvalidQueryFieldException("Table '".$table."' does not exist within query.");

            }

			if( ! array_key_exists($table, $this->_fetch) ){

                $this->_fetch[ $table ] = array();

            }

			// If field already exists within table fetch array, skip.
			if( ! in_array( $field, $this->_fetch[ $table ] ) ){

				$this->_fetch[ $table ][] = $field;

			}

		}

		return $this;

	}

	/**
	* Sets the values to be assigned in this query to the provided array of field names and values.
	* @param array $values An array of field names and values to be assigned in this query.
	* @return $this
	*/
	public function set(array $values){

		$this->_set = $values;
		return $this;

	}

    /**
     * Sets this query to be a delete query, deleting from the table provided.
     * @param string $table
     * @return $this
     * @throws InvalidQueryTypeException
     */
	public function delete($table=NULL){

        if( $table !== NULL ){

            $this->setTable($table);

        }

	    $this->setType(Query::DELETE);

        return $this;

    }

    /**
     * Sets this query to be a insert query, inserting into the table provided.
     * @param null $table
     * @return $this
     * @throws InvalidQueryTypeException
     */
    public function insert($table=NULL){

	    if( $table !== NULL ){

	        $this->setTable($table);

        }

	    $this->setType(Query::INSERT);

        return $this;

    }

    /**
     * Sets this query to be a update query, updating the table provided.
     * @param null $table
     * @return $this
     * @throws InvalidQueryTypeException
     */
    public function update($table=NULL){

        if( $table !== NULL ){

            $this->setTable($table);

        }

        $this->setType(Query::UPDATE);

        return $this;

    }

	/**
	* Sets the tables to join for this query. This is useful for MySQL or PostgreSQL when joining multiple tables for a single query.
	* @param Join $join Tables name to join.
	* @return $this
	*/
	public function join(Join $join){

	    $this->_join[] = $join;

		return $this;

	}

    /**
     * Sets the type for this query.
     * @param string $type
     * @throws InvalidQueryTypeException
     */
	protected function setType(string $type){

	    if( $this->_type !== NULL ){

	        throw new InvalidQueryTypeException('Query type is already set.');

        }

	    $this->_type = $type;

    }

    /**
     * Sets the primary table used for this query.
     * @param string $table
     * @return $this
     */
    public function setTable(string $table){

        // If we are switching tables, clear out the old table.
        if( $this->_table !== NULL ){

            unset( $this->_fetch[ $this->_table ] );

        }

        $this->_table = $table;
        $this->_fetch[ $table ] = array();
        return $this;

    }

	/**
	* Sets the operator in this query to the operator provided.
	* @param Operator|Condition $where The operator to use in this query.
	* @return $this
    * @throws InvalidQueryOperatorException
    * @throws InvalidOperatorArgumentsException
	*/
	public function where($where){

        if( is_a($where, 'SLDB\Base\Condition') ){

            $where = $this->initOperator(Operator::AND_OPERATOR,array(new Condition($where->getTable(), $where->getField(), $where->getType(), $where->getValue())));

        }else if(! is_a($where, 'SLDB\Base\Operator') ){

            throw new InvalidQueryOperatorException('Query::where expects parameter 1 to be a Condition or Operator.');

        }

		$this->_operator = $where;

		return $this;

	}

	/**
	* Sets the limit of rows this query should affect. This value is only honored if the database and query
	* type supports limits.
	* @param int $limit The number of rows this query should affect.
	* @return $this
	*/
	public function limit(int $limit){

		$this->_limit = $limit;
		return $this;

	}

	/**
	* Sets the ammount of rows that this query should be offset by. This value is only honored if the database and query type supports offsets. 
	* @param int $offset The ammount of rows this query should be offset by.
	* @return $this
	*/
	public function offset(int $offset){

		$this->_offset = $offset;
		return $this;

	}

	/**
	* Sets what type of query this is. This value is one of SELECT, INSERT, UPDATE, DELETE, CREATE, DROP constants stored within the SLDB\Base\Query object.
	* @param string $type The Query constant that referrs to this queries type.
	* @return $this
	*/
	public function type(string $type){

		// Query type validation is handled by the Query::generate() function within the primary switch statement.

		$this->_type = $type;
		return $this;

	}

	/**
	* Returns the array of rows returned from this query. This is only useful for select queries.
	* @return int
	*/
	public function getRowsReturned(){

		return $this->_rows_returned;

	}

	/**
	* Returns the number of rows affected by this query. 
	* @return int
	*/
	public function getRowsAffected(){

		return $this->_rows_affected;

	}

	/**
	* Returns the error stored within this query if an error was encounted during execution.
	* @return string|NULL
	*/
	public function getError(){

		return $this->_error;

	}

	/**
	* Returns the database output message collected during execution if one was stored. 
	* @return string|NULL
	*/
	public function getMessage(){

		return $this->_message;

	}

	/**
	* Returns the type of query this query is. This value is compared using the defined type constants within SLDB\Base\Query.
	* @return string
	*/
	public function getType(){

		return $this->_type;

	}

	/**
	* Returns the database type that this query is designed to be compatible with. This value is compared using the defined constants within SLDB\Base\Database.
	* @return string
	*/
	public function getDatabaseType(){

		return $this->_database_type;

	}

	/**
	* Returns the table or collection name this query is set to target. 
	* @return string
	*/
	public function getTable(){

		return $this->_table;

	}

	/**
	* Returns the joined tables for this query as an array.
	* @return array[Join]
	*/
	public function getJoinedTables(){

		return $this->_join;

	}

	/**
	* Returns the syntax generated by this query after using the Query::generate() function. 
	* @return string
	*/
	public function getSyntax(){

		return $this->_syntax;

	}

	/**
	* Returns an array of parameters to be bound during execution in reference to the query syntax generated by this query.
	* @return array
	*/
	public function getParams(){

		return $this->_params;

	}

	/**
	* Returns the array of field names and values stored within this query to be assigned during execution.
	* @return array
	*/
	public function getValues(){

		return $this->_set;

	}

	/**
	* Returns the operator to be used during execution of this query.
	* @return Operator
	*/
	public function getOperator(){

		return $this->_operator;

	}

	/**
	* Returns true if this query has a error stored from during execution. Otherwise false is returned. 
	* @return boolean
	*/
	public function hasError(){

		if( $this->_error !== NULL ){

			return true;

		}

		return false;

	}

	/**
	* Returns true if this query has a message stored from during execution. Otherwise false is returned. 
	* @return boolean
	*/
	public function hasMessage(){

		if( $this->_message !== NULL ){

			return true;

		}

		return false;
	}

    /**
     * Initializes and returns a new operator of the appropriate database type for this query.
     * @param string|NULL $type The type of comparison this operator uses.
     * @param array|NULL $conditions The conditions this operator will compare.
     * @return Operator
     * @throws InvalidOperatorArgumentsException
     */
	public function initOperator(string $type=NULL,array $conditions=NULL){

	    return new Operator($type,$conditions);

    }

    /**
     * Initializes and returns a new condition of the appropriate database type for this query.
     * @param string|NULL $table Table this conditions field belongs to.
     * @param string|NULL $field Field this condition applies to.
     * @param string|NULL $type Type of condition to apply.
     * @param string|NULL $value The value this field must validate to depending on the provided condition type.
     * @return Condition
     */
    public function initCondition($table,$field,$type,$value){

        return new Condition($table,$field,$type,$value);

    }

    /**
     * Generates the syntax for this query based on all parameters stored within this query. This function will also
     * populate the internal params array to be used during execution in reference to the syntax string stored within
     * this query.
     * @throws InvalidQueryOperatorException
     * @throws InvalidQueryTypeException
     */
    public function generate(){

    	// Not all query types use operators.
    	if( $this->_type !== self::INSERT && $this->_type !== self::CREATE ){

    		// Validate the operator and pass the error along to the stack.
    		try{

    			$this->_operator->validate( $this->_table, $this->_join, $this->_fetch );

    		}catch( \Exception $e ){

    			throw new InvalidQueryOperatorException("Failed to validate operator. ( ".$e->getMessage()." )");

    		}

    	}

    	// Generate query syntax based on query type.
		switch($this->_type){
			case self::SELECT:
				$this->generateSelectSyntax();
				return $this;
			case self::UPDATE:
				$this->generateUpdateSyntax();
                return $this;
			case self::INSERT:
				$this->generateInsertSyntax();
                return $this;
			case self::DELETE:
				$this->generateDeleteSyntax();
                return $this;
			case self::CREATE:
				$this->generateCreateSyntax();
                return $this;
			case self::DROP:
				$this->generateDropSyntax();
                return $this;
			default:
				throw new InvalidQueryTypeException();
		}

	}

	// Functions to override for child classes.

	protected function generateSelectSyntax(){}

	protected function generateUpdateSyntax(){}

	protected function generateInsertSyntax(){}

	protected function generateDeleteSyntax(){}

	protected function generateCreateSyntax(){}

	protected function generateDropSyntax(){}

}