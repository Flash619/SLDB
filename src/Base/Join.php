<?php

namespace SLDB\Base;

class Join{

    const LEFT_JOIN = 'LEFT_JOIN';
    const RIGHT_JOIN = 'RIGHT_JOIN';
    const INNER_JOIN = 'INNER_JOIN';
    const FULL_OUTER_JOIN = 'FULL_OUTER_JOIN';

    protected $_primary_table;

    protected $_foreign_table;

    protected $_primary_key;

    protected $_foreign_key;

    protected $_type;

    function __construct(string $type = NULL, $foreign_table = NULL, $foreign_key = NULL, $primary_table = NULL, $primary_key = NULL){

        $this->setPrimaryTable($primary_table);
        $this->setForeignTable($foreign_table);
        $this->setPrimaryKey($primary_key);
        $this->setForeignKey($foreign_key);
        $this->setType($type);

    }

    protected function setPrimaryTable(string $primary_table){

        $this->_primary_table = $primary_table;

    }

    protected function setForeignTable(string $foreign_table){

        $this->_foreign_table = $foreign_table;

    }

    protected function setPrimaryKey(string $primary_key){

        $this->_primary_key = $primary_key;

    }

    protected function setForeignKey(string $foreign_key){

        $this->_foreign_key = $foreign_key;

    }

    protected function setType(string $type){

        $this->_type = $type;

    }

    function getPrimaryTable(){

        return $this->_primary_table;

    }

    function getForeignTable(){

        return $this->_foreign_table;

    }

    function getPrimaryKey(){

        return $this->_primary_key;

    }

    function getForeignKey(){

        return $this->_foreign_key;

    }

    function getType(){

        return $this->_type;

    }

    function generateSyntax(){}
}