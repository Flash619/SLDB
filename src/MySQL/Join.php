<?php

namespace SLDB\MySQL;
use SLDB\Base\Join as BaseJoin;

class Join extends BaseJoin{

    protected $_primary_table;

    protected $_foreign_table;

    protected $_primary_key;

    protected $_foreign_key;

    protected $_type;

    function __construct(string $type = NULL, $foreign_table = NULL, $foreign_key = NULL, $primary_table = NULL, $primary_key = NULL){

        BaseJoin::__construct($type, $foreign_table, $foreign_key, $primary_table, $primary_key);

    }

    function getSyntax(){

        return str_replace('_',' ',$this->getType()) . ' ' . $this->getPrimaryTable() . ' ON ' . $this->getPrimaryTable() . '.' . $this->getPrimaryKey() . ' = ' . $this->getForeignTable() . '.' . $this->getForeignKey();

    }
}