<?php

use PHPUnit\Framework\TestCase;
use SLDB\MySQL\Database as Database;
use SLDB\MySQL\Query as Query;
use SLDB\MySQL\Join as Join;
use SLDB\MySQL\Condition;
use SLDB\MySQL\Operator;

class MySQLQueryTest extends TestCase{

 	function testInitializeObject(){

 		$db = new Database();
 		$query = $db->initQuery();

 		$this->AssertFalse((! is_a($query, 'SLDB\MySQL\Query')),"Failed to initialize MySQL Database object.");
 		$this->AssertEquals(NULL,$query->getType(),"Database queryInit() returned invalid object.");
 		$this->AssertEquals(Database::MYSQL,$query->getDatabaseType(),"Database queryInit() returned Query with invalid database type.");

 	}

 	function testMySQLSelectQuery(){

 		$query = new Query();

 		$query->select(array('id','name','quantity'),'test_table')->setOperator(new Operator(
 			Operator::AND_OPERATOR,
 			array(
 				new Condition('test_table','color',Condition::NOT_EQUAL_TO,'blue'),
 				new Condition('test_table','size',Condition::GREATER_THAN,'20'),
 			)
 		))->limit(15)->offset(15)->generate();

 		$a = "SELECT test_table.id,test_table.name,test_table.quantity FROM test_table WHERE color != ? AND size > ? LIMIT 15 OFFSET 15";

 		$b = $query->getSyntax();
 		$p = $query->getParams();

 		$this->AssertEquals($a,$b,"Generated query syntax did not match expected query syntax.");
 		$this->AssertEquals(2,count($p),"Param count did not equal expected return count.");
 		$this->AssertEquals("blue",$p[0],"Expected param did not match actual param returned.");
 		$this->AssertEquals("20",$p[1],"Expected param did not match actual param returned.");

 	}

 	function testMySQLSelectJoinQuery(){

 		$query = new Query();

 		$query->select(
 			array(
 				'id',
 				'name',
 				'quantity'
 			),
            'test_table'
 		)
 		->join(new Join(Join::INNER_JOIN,'test_table_b','id','test_table', 'id')) // Join test_table_b on test_table_b.id = test_table.id.
        ->join(new Join(Join::INNER_JOIN,'test_table_c','condition','test_table_b', 'condition')) // Join test_table_c on test_table_c.condition = test_table_b.condition.
 		->select(
 			array(
 				'id',
 				'color',
 				'size',
 				'condition',
 			),  'test_table_b' // Fetch from joined test_table_b 'b'.
 		)->select(
 			array(
 				'id',
 				'color',
 				'condition'
 			),  'test_table_c' // Fetch from joined test_table_c 'c'.
 		)->setOperator(new Operator(
 			Operator::AND_OPERATOR,
 			array(
 				new Condition('test_table_c','color',Condition::NOT_EQUAL_TO,'blue'),
 				new Condition('test_table_b','size',Condition::GREATER_THAN,'20'),
 				new Condition('test_table_c','condition',Condition::LIKE,'good') // test_table_c.condition LIKE good.
 			)
 		))->limit(15)->offset(15)->generate();

 		$a = "SELECT test_table.id,test_table.name,test_table.quantity,test_table_b.id,test_table_b.color,test_table_b.size,test_table_b.condition,test_table_c.id,test_table_c.color,test_table_c.condition FROM test_table INNER JOIN test_table ON test_table.id = test_table_b.id INNER JOIN test_table_b ON test_table_b.condition = test_table_c.condition  WHERE color != ? AND size > ? AND condition LIKE ? LIMIT 15 OFFSET 15";

 		$b = $query->getSyntax();
 		$p = $query->getParams();

 		$this->AssertEquals($a,$b,"Generated query syntax did not match expected query syntax.");
 		$this->AssertEquals(3,count($p),"Param count did not equal expected return count.");
 		$this->AssertEquals("blue",$p[0],"Expected param did not match actual param returned.");
 		$this->AssertEquals("20",$p[1],"Expected param did not match actual param returned.");

 	}

 	function testMySQLInsertQuery(){

 		$query = new Query();

 		$query->insert('test_table')->set(array(
 			'name'     => 'red and delicious',
 			'quantity' => 25,
 			'color'    => 'red',
 			'size'     => 'small',
 		))->generate();

 		$a = "INSERT INTO test_table (name,quantity,color,size) VALUES (?,?,?,?)";

 		$b = $query->getSyntax();
 		$p = $query->getParams();

 		$this->AssertEquals($a,$b,"Generated query syntax did not match expected query syntax.");
 		$this->AssertEquals(4,count($p),"Param count did not equal expected return count.");
 		$this->AssertEquals('red and delicious',$p[0],"Expected param did not match actual param returned.");

 	}

 	function testMySQLDeleteQuery(){

 		$query = new Query();

 		$query->delete('test_table')->setOperator(new Operator(
 			Operator::AND_OPERATOR,
 			array(
 				new Condition('test_table','color',Condition::EQUAL_TO,'blue'),
 				new Condition('test_table','size',Condition::EQUAL_TO,'small'),
 				new Condition('test_table','quantity',Condition::LESS_THAN,20),
 			)
 		))->limit(1)->generate();

 		$a = "DELETE FROM test_table WHERE color = ? AND size = ? AND quantity < ? LIMIT 1";

 		$b = $query->getSyntax();
 		$p = $query->getParams();

 		$this->AssertEquals($a,$b,"Generated query syntax did not match expected query syntax.");
 		$this->AssertEquals(3,count($p),"Param count did not equal expected return count.");
 		$this->AssertEquals('small',$p[1],"Expected param did not match actual param returned.");

 	}

 	function testMySQLUpdateQuery(){

 		$query = new Query();

 		$query->update('test_table')->set(array(
 			'color'    => 'red',
 			'quantity' => 20,
 		))->setOperator(new Operator(
 			Operator::AND_OPERATOR,
 			array(
 				new Condition('test_table','id',Condition::EQUAL_TO,10),
 			)
 		))->limit(1)->generate();

 		$a = "UPDATE test_table SET color = ?,quantity = ? WHERE id = ? LIMIT 1";

 		$b = $query->getSyntax();
 		$p = $query->getParams();

 		$this->AssertEquals($a,$b,"Generated query syntax did not match expected query syntax.");
 		$this->AssertEquals(3,count($p),"Param count did not equal expected return count.");
 		$this->AssertEquals(10,$p[2],"Expected param did not match actual param returned.");

 	}

}