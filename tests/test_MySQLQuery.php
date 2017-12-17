 <?php

 use PHPUnit\Framework\TestCase;
 use SLDB\MySQL\MySQLQuery;

 class TestMySQLQuery extends TestCase{

 	public function testInitializeObject(){

 		$query = new MySQLQuery();

 		$this->AssertFalse((!$query instanceof MySQLQuery),"Failed to initialize MySQL Database object.");

 	}

 	public function testMySQLSelectQuery(){

 		$query = new MySQLQuery();

 		$a = "SELECT id,name,quantity FROM test_table WHERE color != ? AND size > ? LIMIT ? OFFSET ?";

 		$b = $query->generateSelectQuery(
 			"test_table",
 			array(
 				"id",
 				"name",
 				"quantity",
 			),
 			array(
 				"color" => "[!=]blue",
 				"size" => "[>]20",
 			),
 			25,
 			10
 		);

 		$this->AssertEquals($a,$b['syntax']);
 		$this->AssertEquals(4,count($b['params']));

 	}

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

 		$this->AssertEquals($a,$b['syntax']);
 		$this->AssertEquals(4,count($b['params']));

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

 		$this->AssertEquals($a,$b['syntax']);
 		$this->AssertEquals(4,count($b['params']));

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

 		$this->AssertEquals($a,$b['syntax']);
 		$this->AssertEquals(4,count($b['params']));

 	}

 }