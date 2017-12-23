<?php

namespace SLDB;

use SLDB\Condition;

class Operator{

	const AND_OPERATOR = 1;
	const OR_OPERATOR  = 2;

	private $_type;

	private $_conditions;

	/**
	* Class Constructor
	*/
	function __construct(int $type=NULL,array $conditions=NULL){

		if( $type !== NULL ){

			$this->_type = $type;

		}else{

			throw new InvalidOperatorArgumentsException();

		}

		if( $conditions !== NULL && count( $conditions ) > 0 ){

			if( $this->_validateConditions($conditions) ){

				$this->_conditions = $conditions;

			}

		}else{

			throw new InvalidOperatorArgumentsException();

		}

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}

	function addCondition($condition){

		if( $this->_validateConditions( array( $condition ) ) ){

			$this->_conditions[] = $condition;

		}

	}

	function addConditions(array $conditions ){

		if( $this->_validateConditions( $conditions ) ){

			array_merge( $this->_conditions, $conditions );

		}

	}

	function getConditions(){

		return $this->_conditions;

	}

	function getType(){

		return $this->_type;

	}

	private function _validateConditions($conditions){

		foreach( $conditions as $v ){

			if(! is_a( $v, 'SLDB\Condition' ) ){

				if(! is_a( $v, 'SLDB\Operator' ) ){

					return false;

				}

				if(! $this->_validateConditions( $v ) ){

					return false;

				}

			}

		}

		return true;

	}

}
class InvalidOperatorArgumentsException extends \Exception{}