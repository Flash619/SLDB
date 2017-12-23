<?php

namespace SLDB\MySQL

use SLDB\Base\Query as BaseQuery;
use SLDB\Base\Database as BaseDatabase;
use SLDB\DatabaseType;

class Query extends BaseQuery{

	/**
	* Class Constructor
	*/
	function __construct(int $type=NULL){

		BaseQuery::__construct($type);

		$this->_database_type = DatabaseType:MYSQL;

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){

		BaseQuery::__destruct();

	}

	protected function operatorToSyntax(Operator $operator){

		$result = array('syntax'=>'','params'=>array());

		foreach( $operator->getConditions() as $v ){

			if( is_a( $v, Operator ) ){

				$subresult = $this->operatorToSyntax( $v );
				$s = $s.' ('.$subresult['syntax'].') ';
				$result['params'] = array_merge( $result['params'], $subresult['params'] );

				continue;

			}

			$ss = $v->getField();

			switch( $v->getType() ){
				case ConditionType::LIKE:
					$ss = $ss.' LIKE ';
					break;
				case ConditionType::NOT_LIKE:
					$ss = $ss.' NOT LIKE ';
					break;
				case ConditionType::EQUAL_TO:
					$ss = $ss.' = ';
					break;
				case ConditionType::NOT_EQUAL_TO:
					$ss = $ss.' != ';
					break;
				case ConditionType::GREATER_THAN:
					$ss = $ss.' > ';
					break;
				case ConditionType::LESS_THAN:
					$ss = $ss.' < ';
					break;
				case ConditionType::GREATER_OR_EQUAL_TO:
					$ss = $ss.' >= ';
					break;
				case ConditionType::LESS_OR_EQUAL_TO:
					$ss = $ss.' <= ';
					break;
			}

			$ss = $ss.'?';

			switch( $operator->getType() ){
				case OperatorType::_AND_:
					$ss = $ss.' AND ';
					break;
				case OperatorType::_OR_:
					$ss = $ss.' OR ';
					break;
			}

			$s  = $s.$ss;

			$result['params'][] = $v->getValue();

		}

		switch( $operator->getType() ){
			case OperatorType::_AND_:
				$s = rtrim($s,' AND ');
				break;
			case OperatorType::_OR_:
				$s = rtrim($s,' OR ');
				break;
		}

		$result['syntax'] = $s;

		return $result;

	}

	protected function valuesToSyntax(array $values){

		$result = array('syntax'=>'','params'=>array());
		$s = '';

		foreach( $values as $k => $v ){

			$s=$k.' = '.'?, ';
			$result['params'] = $v;

		}

		$result['syntax'] = $s;

		return $result;

	}

	protected function generateSelectSyntax(){

		$where = $this->operatorToSyntax( $this->_operator );

		$s = "SELECT ".implode(',', $this->_fields)." FROM ".$this->_table." WHERE ".$where['syntax'];

		if( $this->_limit !== NULL ){

			$s = $s.'LIMIT '.$this->_limit;

		}

		if( $this->_offset !== NULL ){

			$s = $s.'OFFSET '.$this->_offset;

		}

		$this->_params = $where['params'];
		$this->_syntax = $s;

	}

	protected function generateUpdateSyntax(){

		$where  = $this->operatorToSyntax( $this->_operator );
		$values = $this->valuesToSyntax( $this->_values );

		$s = "UPDATE ".$this->_table." SET ".$values['syntax']." WHERE ".$where['syntax'];

		if( $this->_limit !== NULL ){

			$s = $s.'LIMIT '.$this->_limit;

		}

		$this->_params = array_merge($values['params'],$where['params']);
		$this->_syntax = $s;

	}

	protected function generateInsertSyntax(){

		$fields = array();
		$values = array();
		$vs     = '';

		foreach( $this->_values as $k => $v ){

			$fields[] = $k;
			$values[] = $v;
			$vs       = $vs.'?,';
			
		}

		$vs = rtrim($vs,',');

		$s = "INSERT INTO ".$this->_table." ".implode(',',$fields)." VALUES (".$vs.")";

		$this->_syntax = $s;
		$this->_params = $values;

	}

	protected function generateDeleteSyntax(){

		$where  = $this->operatorToSyntax( $this->_operator );

		$s = "DELETE FROM ".$this->_table." WHERE ".$where['syntax'];

		if( $this->_limit !== NULL ){

			$s = $s.'LIMIT '.$this->_limit;

		}

		$this->_params = $where['params'];
		$this->_syntax = $s;

	}

	protected function generateCreateSyntax(){}

	protected function generateDropSyntax(){}

}
