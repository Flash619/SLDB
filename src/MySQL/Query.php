<?php

namespace SLDB\MySQL;

use SLDB\Base\Query    as BaseQuery;
use SLDB\Base\Database as BaseDatabase;
use SLDB\Operator;
use SLDB\Condition;

class Query extends BaseQuery{

	/**
	* Class Constructor
	*/
	function __construct(int $type=NULL){

		BaseQuery::__construct($type);

		$this->_database_type = BaseDatabase::MYSQL;

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){

		BaseQuery::__destruct();

	}

	protected function operatorToSyntax(Operator $operator){

		$result = array('syntax'=>'','params'=>array());
		$s = '';

		foreach( $operator->getConditions() as $v ){

			if( is_a( $v, 'SLDB\Operator' ) ){

				$subresult = $this->operatorToSyntax( $v );
				$s = $s.' ('.$subresult['syntax'].') ';
				$result['params'] = array_merge( $result['params'], $subresult['params'] );

				continue;

			}

			$ss = $v->getField();

			switch( $v->getType() ){
				case Condition::LIKE:
					$ss = $ss.' LIKE ';
					break;
				case Condition::NOT_LIKE:
					$ss = $ss.' NOT LIKE ';
					break;
				case Condition::EQUAL_TO:
					$ss = $ss.' = ';
					break;
				case Condition::NOT_EQUAL_TO:
					$ss = $ss.' != ';
					break;
				case Condition::GREATER_THAN:
					$ss = $ss.' > ';
					break;
				case Condition::LESS_THAN:
					$ss = $ss.' < ';
					break;
				case Condition::GREATER_OR_EQUAL_TO:
					$ss = $ss.' >= ';
					break;
				case Condition::LESS_OR_EQUAL_TO:
					$ss = $ss.' <= ';
					break;
			}

			$ss = $ss.'?';

			switch( $operator->getType() ){
				case Operator::AND_OPERATOR:
					$ss = $ss.' AND ';
					break;
				case Operator::OR_OPERATOR:
					$ss = $ss.' OR ';
					break;
			}

			$s = $s.$ss;

			$result['params'][] = $v->getValue();

		}

		switch( $operator->getType() ){
			case Operator::AND_OPERATOR:
				$s = rtrim($s,' AND ');
				break;
			case Operator::OR_OPERATOR:
				$s = rtrim($s,' OR ');
				break;
		}

		$result['syntax'] = $s;

		return $result;

	}

	protected function generateSelectSyntax(){

		$where = $this->operatorToSyntax( $this->_operator );

		$s = "SELECT ".implode(',', $this->_fields)." FROM ".$this->_table." WHERE ".$where['syntax'];

		if( $this->_limit !== NULL ){

			$s = $s.' LIMIT '.$this->_limit;

		}

		if( $this->_offset !== NULL ){

			$s = $s.' OFFSET '.$this->_offset;

		}

		$this->_params = $where['params'];
		$this->_syntax = $s;

	}

	protected function generateUpdateSyntax(){

		$where  = $this->operatorToSyntax( $this->_operator );
		$values = array('syntax'=>'','params'=>array());

		foreach( $this->_values as $k => $v ){

			$values['syntax']   = $values['syntax'].$k.' = '.'?,';
			$values['params'][] = $v;

		}

		$values['syntax'] = rtrim($values['syntax'],',');

		$s = "UPDATE ".$this->_table." SET ".$values['syntax']." WHERE ".$where['syntax'];

		if( $this->_limit !== NULL ){

			$s = $s.' LIMIT '.$this->_limit;

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

		$s = "INSERT INTO ".$this->_table." (".implode(',',$fields).") VALUES (".$vs.")";

		$this->_syntax = $s;
		$this->_params = $values;

	}

	protected function generateDeleteSyntax(){

		$where  = $this->operatorToSyntax( $this->_operator );

		$s = "DELETE FROM ".$this->_table." WHERE ".$where['syntax'];

		if( $this->_limit !== NULL ){

			$s = $s.' LIMIT '.$this->_limit;

		}

		$this->_params = $where['params'];
		$this->_syntax = $s;

	}

	protected function generateCreateSyntax(){}

	protected function generateDropSyntax(){}

}