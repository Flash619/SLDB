 <?php

use PHPUnit\Framework\TestCase;
use SLDB\SLDB;
use SLDB\Base\Query;

class SLDBTest extends TestCase{

 	function testSLDBInitializeObject(){

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
 		$q  = $sldb->initQuery();

 		$this->AssertEquals($db::MYSQL,$sldb->getDatabase()->getType(),"SLDB failed to initialize correct database type.");
        $this->AssertEquals(NULL,$q->getType(),"Query was not initialized with NULL type.");

        $q = $sldb->initQuery()->delete('test_table');

        $this->AssertEquals(Query::DELETE,$q->getType(),"Query was not initialized with DELETE type.");

 	}

}