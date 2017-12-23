 <?php

 use PHPUnit\Framework\TestCase;
 use SLDB\MySQL\Database as Database;
 use SLDB\MySQL\Query as Query;
 use SLDB\Condition;
 use SLDB\Operator;

 class MySQLQueryTest extends TestCase{

 	public function testInitializeObject(){

 		$db = new Database();
 		$query = $db->initQuery(Query::SELECT);

 		$this->AssertFalse((! is_a($query, 'SLDB\MySQL\Query')),"Failed to initialize MySQL Database object.");
 		$this->AssertEquals(Query::SELECT,$query->getType());

 	}

 	public function testMySQLSelectQuery(){

 		$query = new Query(Query::SELECT);
 		$query->setTable('test_table');
 		$query->setFields(array('id','name','quantity'));
 		$query->setOperator(new Operator(
 			Operator::AND_OPERATOR,
 			array(
 				new Condition('color',Condition::NOT_EQUAL_TO,'blue'),
 				new Condition('size',Condition::GREATER_THAN,'20'),
 			)
 		));
 		$query->setLimit(15);
 		$query->setOffset(15);

 		$query->generate();

 		$a = "SELECT id,name,quantity FROM test_table WHERE color != ? AND size > ? LIMIT 15 OFFSET 15";

 		$b = $query->getSyntax();
 		$p = $query->getParams();

 		$this->AssertEquals($a,$b,"Generated query syntax did not match expected query syntax.");
 		$this->AssertEquals(2,count($p),"Param count did not equal expected return count.");
 		$this->AssertEquals("blue",$p[0],"Expected param did not match actual param returned.");
 		$this->AssertEquals("20",$p[1],"Expected param did not match actual param returned.");

 	}
/*
 	public function testMySQLInsertQuery(){

 		$query = new MySQLQuery();

 		$a = "INSERT INTO test_table (name,quantity,color,size) VALUES (?,?,?,?)";

 		$b = $query->generateInsertQuery(
 			"test_table",
 			array(
 				"name" => "red and delicious",
 				"quantity" => 25,
 				"color" => "red",
 				"size" => "small",
 			)
 		);

 		$this->AssertEquals($a,$b['syntax'],"Generated query syntax did not match expected query syntax.");
 		$this->AssertEquals(4,count($b['params']),"Param count did not equal expected return count.");

 	}

 	public function testMySQLDeleteQuery(){

 		$query = new MySQLQuery();

 		$a = "DELETE FROM test_table WHERE color = ? AND size = ? AND quantity < ? LIMIT ?";

 		$b = $query->generateDeleteQuery(
 			"test_table",
 			array(
 				"color" => "blue",
 				"size" => "small",
 				"quantity" => "[<]20",
 			),
 			1
 		);

 		$this->AssertEquals($a,$b['syntax'],"Generated query syntax did not match expected query syntax.");
 		$this->AssertEquals(4,count($b['params']),"Param count did not equal expected return count.");

 	}

 	public function testMySQLUpdateQuery(){

 		$query = new MySQLQuery();

 		$a = "UPDATE test_table SET color = ? AND quantity = ? WHERE id = ? LIMIT ?";

 		$b = $query->generateUpdateQuery(
 			"test_table",
 			array(
 				"id" => 10,
 			),
 			array(
 				"color" => "red",
 				"quantity" => "20",
 			),
 			1
 		);

 		$this->AssertEquals($a,$b['syntax'],"Generated query syntax did not match expected query syntax.");
 		$this->AssertEquals(4,count($b['params']),"Param count did not equal expected return count.");

 	}
*/
 }