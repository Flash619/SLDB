<?php

namespace SLDB

use SLDB\Condition;

class OperatorType{
	const _AND_ = 1;
	const _OR_  = 2;
}

class Operator{

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

		if( $this->_validateConditions( new array( $condition ) ) ){

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

	private function _validateConditions($conditions){

		foreach( $conditions as $v ){

			if(! is_a( $v, Condition ) ){

				if(! is_a( $v, Operator ) ){

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