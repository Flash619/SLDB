<?php

namespace SLDB;

class Join{

	protected $_table;
	protected $_local_field;
	protected $_foreign_field;
	protected $_foreign_table;

	/**
	* Class Constructor
	*/
	function __construct(string $table=NULL,string $local_field=NULL,string $foreign_field=NULL,string $foreign_table=NULL){

		$this->_table         = $table;
		$this->_local_field   = $local_field;
		$this->_foreign_field = $foreign_field;
		$this->_foreign_table = $foreign_table;

	}

	/**
	* Class Deconstructor
	*/
	function __destruct(){}

}