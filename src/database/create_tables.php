<?php

		$db = new PDO('mysql:host=localhost;dbname=bills', 'bills_admin', '5y9_uio345');	
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// Users
		
		try {
			$sql = 	"CREATE TABLE IF NOT EXISTS users (
				id INT(5) AUTO_INCREMENT PRIMARY KEY NOT NULL,
				name VARCHAR(50) NOT NULL,
				email VARCHAR(250) NOT NULL,
				password VARCHAR(250) NOT NULL,
				UNIQUE(email)
			)";
			$db->exec($sql);
		} catch(Exception $e) {
			echo $e->getMessage();
		}
		
		// Categories
		
		try {
			$sql = 	"CREATE TABLE IF NOT EXISTS categories (
				id INT(5) AUTO_INCREMENT PRIMARY KEY NOT NULL,
				user_id INT(5) NOT NULL,
				category_name VARCHAR(250) NOT NULL,
				UNIQUE(category_name),
				FOREIGN KEY(user_id) REFERENCES users(id)
			)";
			$db->exec($sql);
		} catch(Exception $e) {
			echo $e->getMessage();
		}		
		
		// Billers
		
		try {
			$sql = 	"CREATE TABLE IF NOT EXISTS billers (
				id INT(5) AUTO_INCREMENT PRIMARY KEY NOT NULL,
				user_id INT(5) NOT NULL,
				category_id INT(5) NOT NULL,
				name VARCHAR(250) NOT NULL,
				UNIQUE(name),
				FOREIGN KEY(user_id) REFERENCES users(id),
				FOREIGN KEY(category_id) REFERENCES categories(id)
			)";
			$db->exec($sql);
		} catch(Exception $e) {
			echo $e->getMessage();
		}	
		
		// Bills
		
		try {
			$sql = 	"CREATE TABLE IF NOT EXISTS bills (
				id INT(5) AUTO_INCREMENT PRIMARY KEY NOT NULL,
				user_id INT(5) NOT NULL,
				biller_id INT(5) NOT NULL,
				amount INT(8) NOT NULL,
				due INT(10) NOT NULL,
				status INT(1) NOT NULL,
				FOREIGN KEY(user_id) REFERENCES users(id),
				FOREIGN KEY(biller_id) REFERENCES billers(id)
			)";
			$db->exec($sql);
		} catch(Exception $e) {
			echo $e->getMessage();
		}	
		
	/* sqlite 
		
		$db = new PDO("sqlite:../src/database/database.sqlite");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	// Users
		
	$createTablesSql =
<<<EOF
	CREATE TABLE IF NOT EXISTS users (
		id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
		name TEXT NOT NULL,
		email TEXT NOT NULL UNIQUE,
		password TEXT NOT NULL
	);
EOF;

	try {
		$db->exec($createTablesSql);
	} catch(Exception $e) {
		echo $e->getMessage();
	}

	// Categories
	
	$createTablesSql =
<<<EOF
	CREATE TABLE IF NOT EXISTS categories (
		id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
		user_id INTEGER NOT NULL,
		category_name TEXT NOT NULL,
		FOREIGN KEY (user_id) REFERENCES users(id)
	);
EOF;

	try {
		$db->exec($createTablesSql);
	} catch(Exception $e) {
		echo $e->getMessage();
	}

	// Billers
	
	$createTablesSql =
<<<EOF
	CREATE TABLE IF NOT EXISTS billers (
		id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
		user_id INTEGER NOT NULL,
		category_id INTEGER NOT NULL,
		name TEXT NOT NULL UNIQUE,
		FOREIGN KEY (user_id) REFERENCES users(id),
		FOREIGN KEY (category_id) REFERENCES categories(id)
	);
EOF;

	try {
		$db->exec($createTablesSql);
	} catch(Exception $e) {
		echo $e->getMessage();
	}

	// Bills
	
	$createTablesSql =
<<<EOF
	CREATE TABLE IF NOT EXISTS bills(
		id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
		user_id INTEGER NOT NULL,
		biller_id INTEGER NOT NULL,
		amount INTEGER NOT NULL,
		due INTEGER NOT NULL,
		status INTEGER NOT NULL,
		FOREIGN KEY (biller_id) REFERENCES billers(id),
		FOREIGN KEY (user_id) REFERENCES users(id)
	);
EOF;

	try {
		$db->exec($createTablesSql);
	} catch(Exception $e) {
		echo $e->getMessage();
	}		
		
	*/


	


?>