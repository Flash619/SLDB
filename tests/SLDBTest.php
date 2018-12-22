 <?php

use PHPUnit\Framework\TestCase;
use SLDB\SLDB;


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

 		$this->AssertEquals($db::MYSQL,$sldb->getDatabase()->getType(),"SLDB failed to initialize correct database type.");

 	}

}