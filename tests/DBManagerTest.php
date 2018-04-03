<?php

	use PHPUnit\Framework\TestCase;
	use PHPUnit\DbUnit\TestCaseTrait;
	
	class DBManagerTest extends TestCase {
		
		use TestCaseTrait;
		
		private $pdo;
		
		final public function getConnection() {
			
			//To test sqlite database, make owner of this file www-data:www-data
			$this->pdo = new PDO("sqlite:" . __DIR__ . DIRECTORY_SEPARATOR . "../src/database/database.sqlite");
			return $this->createDefaultDBConnection($this->pdo, "database.sqlite");
			
			//$this->pdo = new PDO('mysql:host=localhost;dbname=bills', 'bills_admin', '5y9_uio345');
			//return $this->createDefaultDBConnection($this->pdo, "bills");

		}
		
		public function testUsers() {
		
			$expected = $this->createXMLDataSet(__DIR__ . DIRECTORY_SEPARATOR . 'database.xml')->getTable('users');
			$queryTable = $this->getConnection()->createQueryTable("users", "SELECT * FROM users");
			$this->assertTablesEqual($expected, $queryTable);
		
		}
		
		public function testBillers() {
		
			$this->assertEquals(3, $this->getConnection()->getRowCount('billers'), "Billers does not contain 3 Billers");
		
		}
		
		public function getDataSet() {
			
			return $this->createXMLDataSet(__DIR__ . DIRECTORY_SEPARATOR . 'database.xml');
		
		}
		
	}

?>