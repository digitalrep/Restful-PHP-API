<?php

	use PHPUnit\Framework\TestCase;
	use PHPUnit\DbUnit\TestCaseTrait;
	
	class DBManagerTest extends TestCase {
		
		use TestCaseTrait;
		
		private $pdo;
		
		final public function getConnection() {
			
			$this->pdo = new PDO("sqlite:" . __DIR__ . DIRECTORY_SEPARATOR . "../src/database/database.sqlite");
			
			$this->pdo->exec("DROP TABLE IF EXISTS users");
			$this->pdo->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, email TEXT NOT NULL UNIQUE, password TEXT NOT NULL)");
			$this->pdo->exec("DROP TABLE IF EXISTS categories");
			$this->pdo->exec("CREATE TABLE IF NOT EXISTS categories (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, user_id INTEGER NOT NULL, category_name TEXT NOT NULL, FOREIGN KEY (user_id) REFERENCES users(id))");
			$this->pdo->exec("DROP TABLE IF EXISTS billers");	
			$this->pdo->exec("CREATE TABLE IF NOT EXISTS billers (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, user_id INTEGER NOT NULL, category_id INTEGER NOT NULL, name TEXT NOT NULL UNIQUE, FOREIGN KEY (user_id) REFERENCES users(id), FOREIGN KEY (category_id) REFERENCES categories(id))");
			$this->pdo->exec("DROP TABLE IF EXISTS bills");	
			$this->pdo->exec("CREATE TABLE IF NOT EXISTS bills (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, user_id INTEGER NOT NULL, biller_id INTEGER NOT NULL, amount INTEGER NOT NULL, due INTEGER NOT NULL, status INTEGER NOT NULL, FOREIGN KEY (biller_id) REFERENCES billers(id), FOREIGN KEY (user_id) REFERENCES users(id))");
						
			return $this->createDefaultDBConnection($this->pdo, "database.sqlite");

		}
		
		public function testUsers() {
			
			// Isn't working
			// Gonna switch to MySQL and see if I can get that working
			/*
			There were 2 errors:

			1) DBManagerTest::testUsers
			PHPUnit\DbUnit\Operation\Exception: COMPOSITE[TRUNCATE] operation failed on query:
							DELETE FROM "table"
						 using args: Array
			(
			)
			 [SQLSTATE[HY000]: General error: 1 no such table: table]

			2) DBManagerTest::testBillers
			PHPUnit\DbUnit\Operation\Exception: COMPOSITE[TRUNCATE] operation failed on query:
							DELETE FROM "table"
						 using args: Array
			(
			)
			 [SQLSTATE[HY000]: General error: 1 no such table: table]
			*/
		
			$expected = $this->createFlatXMLDataSet('database.xml')->getTable('users');
			$queryTable = $this->getConnection()->createQueryTable("users", "SELECT * FROM users");
			$this->assertTablesEqual($expected, $queryTable);
		
		}
		
		public function testBillers() {
		
			$this->assertEquals(3, $this->getConnection()->getRowCount('billers'), "What am I meant to put here?");
		
		}
		
		public function getDataSet() {
			
			return $this->createFlatXMLDataSet(__DIR__ . DIRECTORY_SEPARATOR . 'database.xml');
		
		}
		
	}

?>