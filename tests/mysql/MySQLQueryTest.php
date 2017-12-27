<?php

use PHPUnit\Framework\TestCase;
use SLDB\MySQL\Database as Database;
use SLDB\MySQL\Query as Query;
use SLDB\Condition;
use SLDB\Operator;

class MySQLQueryTest extends TestCase{

 	function testInitializeObject(){

 		$db = new Database();
 		$query = $db->initQuery(Query::SELECT);

 		$this->AssertFalse((! is_a($query, 'SLDB\MySQL\Query')),"Failed to initialize MySQL Database object.");
 		$this->AssertEquals(Query::SELECT,$query->getType(),"Database queryInit() returned invalid object.");
 		$this->AssertEquals(Database::MYSQL,$query->getDatabaseType(),"Database queryInit() returned Query with invalid database type.");

 	}

 	function testMySQLSelectQuery(){

 		$query = new Query(Query::SELECT);

 		$query->use('test_table')->fetch(array('id','name','quantity'))->setOperator(new Operator(
 			Operator::AND_OPERATOR,
 			array(
 				new Condition('color',Condition::NOT_EQUAL_TO,'blue'),
 				new Condition('size',Condition::GREATER_THAN,'20'),
 			)
 		))->limit(15)->offset(15)->generate();

 		$a = "SELECT id,name,quantity FROM test_table WHERE color != ? AND size > ? LIMIT 15 OFFSET 15";

 		$b = $query->getSyntax();
 		$p = $query->getParams();

 		$this->AssertEquals($a,$b,"Generated query syntax did not match expected query syntax.");
 		$this->AssertEquals(2,count($p),"Param count did not equal expected return count.");
 		$this->AssertEquals("blue",$p[0],"Expected param did not match actual param returned.");
 		$this->AssertEquals("20",$p[1],"Expected param did not match actual param returned.");

 	}

 	function testMySQLSelectJoinQuery(){

 		$query = new Query(Query::SELECT);

 		$query->use('test_table') // Use test_table as primary table 'a'.
 		->fetch(
 			array(
 				'id',
 				'name',
 				'quantity'
 			) // From primary test_table 'a'.
 		)
 		->join('test_table_b','id','id') // Join test_table_b 'b' on b.id = a.id. 
 		->join('test_table_c','condition','condition','test_table_b') // Join test_table_c 'c' on c.condition = b.condition.
 		->fetch(
 			array(
 				'id',
 				'color',
 				'size',
 				'condition',
 			),  'test_table_b' // Fetch from joined test_table_b 'b'.
 		)->fetch(
 			array(
 				'id',
 				'color',
 				'condition'
 			),  'test_table_c' // Fetch from joined test_table_c 'c'.
 		)->setOperator(new Operator(
 			Operator::AND_OPERATOR,
 			array(
 				new Condition('color',Condition::NOT_EQUAL_TO,'blue'),
 				new Condition('size',Condition::GREATER_THAN,'20'),
 				new Condition('condition',Condition::LIKE,'good','test_table_c') // c.condition LIKE good.
 			)
 		))->limit(15)->offset(15)->generate();

 		$a = "SELECT id,name,quantity FROM test_table WHERE color != ? AND size > ? LIMIT 15 OFFSET 15";

 		$b = $query->getSyntax();
 		$p = $query->getParams();

 		$this->AssertEquals($a,$b,"Generated query syntax did not match expected query syntax.");
 		$this->AssertEquals(2,count($p),"Param count did not equal expected return count.");
 		$this->AssertEquals("blue",$p[0],"Expected param did not match actual param returned.");
 		$this->AssertEquals("20",$p[1],"Expected param did not match actual param returned.");

 	}

 	function testMySQLInsertQuery(){

 		$query = new Query(Query::INSERT);

 		$query->use('test_table')->set(array(
 			'name'     => 'red and delicious',
 			'quantity' => 25,
 			'color'    => 'red',
 		))->setSingle('size','small')->generate();

 		$a = "INSERT INTO test_table (name,quantity,color,size) VALUES (?,?,?,?)";

 		$b = $query->getSyntax();
 		$p = $query->getParams();

 		$this->AssertEquals($a,$b,"Generated query syntax did not match expected query syntax.");
 		$this->AssertEquals(4,count($p),"Param count did not equal expected return count.");
 		$this->AssertEquals('red and delicious',$p[0],"Expected param did not match actual param returned.");

 	}

 	function testMySQLDeleteQuery(){

 		$query = new Query(Query::DELETE);

 		$query->use('test_table')->setOperator(new Operator(
 			Operator::AND_OPERATOR,
 			array(
 				new Condition('color',Condition::EQUAL_TO,'blue'),
 				new Condition('size',Condition::EQUAL_TO,'small'),
 				new Condition('quantity',Condition::LESS_THAN,20),
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

 		$query = new Query(Query::UPDATE);

 		$query->use('test_table')->set(array(
 			'color'    => 'red',
 			'quantity' => 20,
 		))->setOperator(new Operator(
 			Operator::AND_OPERATOR,
 			array(
 				new Condition('id',Condition::EQUAL_TO,10),
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