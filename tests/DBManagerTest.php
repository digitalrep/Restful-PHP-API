<?php

	use PHPUnit\Framework\TestCase;
	use PHPUnit\DbUnit\TestCaseTrait;
	use PHPUnit\DbUnit\Operation\Truncate;
	
	class DBManagerTest extends TestCase {
		
		use TestCaseTrait;
		
		private $pdo;
		
		final public function getConnection() {
			
			//[SQLSTATE[HY000]: General error: 8 attempt to write a readonly database]
			//$this->pdo = new PDO("sqlite:" . __DIR__ . DIRECTORY_SEPARATOR . "../src/database/database.sqlite");
			//return $this->createDefaultDBConnection($this->pdo, "database.sqlite");
			
			$this->pdo = new PDO('mysql:host=localhost;dbname=bills', 'bills_admin', '5y9_uio345');
			return $this->createDefaultDBConnection($this->pdo, "bills");

		}
		
		public function testUsersInserted() {
		
			$expected = $this->createXMLDataSet(__DIR__ . DIRECTORY_SEPARATOR . 'database.xml')->getTable('users');
			$queryTable = $this->getConnection()->createQueryTable("users", "SELECT * FROM users");
			$this->assertTablesEqual($expected, $queryTable);
		
		}
		
		public function testBillersInserted() {
		
			$this->assertEquals(8, $this->getConnection()->getRowCount('billers'), "Billers does not contain 8 rows");
		
		}
		
		public function testPaymentsInserted() {
		
			$this->assertEquals(3, $this->getConnection()->getRowCount('payments'), "Payments does not contain 3 rows");
		
		}
		
		public function testCategoriesInserted() {
		
			$this->assertEquals(4, $this->getConnection()->getRowCount('categories'), "Categories does not contain 4 rows");
		
		}
		
		public function testBillsInserted() {
		
			$this->assertEquals(3, $this->getConnection()->getRowCount('bills'), "Bills does not contain 3 rows");
		
		}
		
		public function getDataSet() {
			
			return $this->createXMLDataSet(__DIR__ . DIRECTORY_SEPARATOR . 'database.xml');
		
		}
		
		public function tearDown() {
		
			// This removes *all* data from database, even existing data
			
			//$truncateOperation = new Truncate();
			//$truncateOperation->execute($this->getConnection(), $this->getDataSet());
			
		}
		
	}

?>