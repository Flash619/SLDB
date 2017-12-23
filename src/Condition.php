<?php

namespace SLDB;

class Condition{

	const LIKE                = 1;
	const NOT_LIKE            = 2;
	const EQUAL_TO            = 3;
	const NOT_EQUAL_TO        = 4;
	consT GREATER_THAN        = 5;
	const LESS_THAN           = 6;
	const GREATER_OR_EQUAL_TO = 7;
	const LESS_OR_EQUAL_TO    = 8;

	private $_field;
	private $_type;
	private $_value;

	/**
	* Class Constructor
	*/
	function __construct(string $field=NULL,int $type=NULL,string $value=NULL){

		$this->setField($field);
		$this->setType($type);
		$this->setValue($value);

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}

	function getField(){
		return $this->_field;
	}

	function getType(){
		return $this->_type;
	}

	function getValue(){
		return $this->_value;
	}

	function setField(string $field=NULL){
		$this->_field = $field;
	}

	function setType(int $type=NULL){
		$this->_type = $type;
	}

	function setValue(string $value=NULL){
		$this->_value = $value;
	}

}

/*
$myshit = SLDB->select('mytable',new array('id','somefield'),new Condition('id',ConditionType::LIKE,'butts'));

$query = new SelectQuery('mytable');

$query->addField('id');
$query->addField('somefield');
$query->addFields(array('anotherfield','price');

$query->addCondition(new Condition('id',ConditionType::LIKE,'butts'))

$query->execute();

$MyValueArray = $query->fetchValues();
*/