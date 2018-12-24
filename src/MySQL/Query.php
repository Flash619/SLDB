<?php

namespace SLDB\mysql;

use SLDB\Base\Query    as BaseQuery;
use SLDB\Base\Database as BaseDatabase;

class Query extends BaseQuery{

	/**
	* Class Constructor
	*/
	function __construct(string $type=NULL){

		BaseQuery::__construct($type);

		$this->_database_type = BaseDatabase::MYSQL;

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){

		BaseQuery::__destruct();

	}

	protected function generateSelectSyntax(){

		$where = $this->_operator->generate()->getSyntax();

		$s = "SELECT ".implode(',', $this->_fetch[$this->_table])." FROM ".$this->_table . ' ';

		if( count($this->_join) !== 0 ){

            foreach( $this->_join as $k => $v ){

                $s = $s . $v->getSyntax() . ' ';

            }

        }

		$s = $s . "WHERE ".$where;

		if( $this->_limit !== NULL ){

			$s = $s.' LIMIT '.$this->_limit;

		}

		if( $this->_offset !== NULL ){

			$s = $s.' OFFSET '.$this->_offset;

		}

		$this->_params = $this->_operator->getParams();
		$this->_syntax = $s;

	}

	protected function generateUpdateSyntax(){

		$where  = $this->_operator->generate()->getSyntax();
		$values = array('syntax'=>'','params'=>array());

		foreach( $this->_set as $k => $v ){

			$values['syntax']   = $values['syntax'].$k.' = '.'?,';
			$values['params'][] = $v;

		}

		$values['syntax'] = rtrim($values['syntax'],',');

		$s = "UPDATE ".$this->_table." SET ".$values['syntax']." WHERE ".$where;

		if( $this->_limit !== NULL ){

			$s = $s.' LIMIT '.$this->_limit;

		}

		$this->_params = array_merge($values['params'],$this->_operator->getParams());
		$this->_syntax = $s;

	}

	protected function generateInsertSyntax(){

		$fields = array();
		$values = array();
		$vs     = '';

		foreach( $this->_set as $k => $v ){

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

		$where  = $this->_operator->generate()->getSyntax();

		$s = "DELETE FROM ".$this->_table." WHERE ".$where;

		if( $this->_limit !== NULL ){

			$s = $s.' LIMIT '.$this->_limit;

		}

		$this->_params = $this->_operator->getParams();
		$this->_syntax = $s;

	}

	protected function generateCreateSyntax(){}

	protected function generateDropSyntax(){}

}