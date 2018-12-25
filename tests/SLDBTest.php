<?php

use PHPUnit\Framework\TestCase;
use SLDB\Base\Condition;
use SLDB\Base\Operator;
use SLDB\Base\Query;
use SLDB\SLDB;

class SLDBTest extends TestCase
{

    function testSLDBInitializeObject()
    {

        $sldb = new SLDB(
            array(
                'database_type' => 'mysql',
                'database_name' => 'dbname',
                'database_host' => 'dbhost',
                'database_user' => 'dbuser',
                'database_pass' => 'dbpass',
            )
        );

        $db = $sldb->getDatabase();
        $q = $sldb->initQuery();

        $this->AssertEquals($db::MYSQL, $sldb->getDatabase()->getType(), "SLDB failed to initialize correct database type.");
        $this->AssertEquals(NULL, $q->getType(), "Query was not initialized with NULL type.");

    }

    function testSLDBInitFunctions()
    {

        $sldb = new SLDB(
            array(
                'database_type' => 'mysql',
                'database_name' => 'dbname',
                'database_host' => 'dbhost',
                'database_user' => 'dbuser',
                'database_pass' => 'dbpass',
            )
        );

        $q = $sldb->initQuery()->delete('test_table');

        $this->AssertEquals(Query::DELETE, $q->getType(), "Query was not initialized with DELETE type.");

        $c = $sldb->initCondition('test_table', 'color', Condition::NOT_EQUAL_TO, 'blue');
        $o = $sldb->initOperator(Operator::OR_OPERATOR, array($c));

        $this->AssertEquals(true, is_a($c, 'SLDB\MySQL\Condition'), "Condition was not initialized with the correct database type.");
        $this->AssertEquals(true, is_a($o, 'SLDB\MySQL\Operator'), "Operator was not initialized with the correct database type.");

    }

}