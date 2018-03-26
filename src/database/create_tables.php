<?php

	$db = new SQLite3('../src/database/database.sqlite');
	
	if(!$db) {
		echo $db->lastErrorMsg();
	} 
	
	//$db->exec("PRAGMA foreign_keys = ON;");
		
	$createTablesSql =
<<<EOF
	CREATE TABLE IF NOT EXISTS users (
		id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
		name TEXT NOT NULL,
		email TEXT NOT NULL UNIQUE,
		password TEXT NOT NULL
	);
EOF;

	if(!$db->exec($createTablesSql)) {
		echo $db->lastErrorMsg();
		echo json_encode(['code' => 500, 'message' => 'Users table not created']);	
	} 

	$createTablesSql =
<<<EOF
	CREATE TABLE IF NOT EXISTS billers (
		id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
		name TEXT NOT NULL UNIQUE,
		category TEXT NOT NULL
	);
EOF;

	if(!$db->exec($createTablesSql)) {
		echo $db->lastErrorMsg();
		echo json_encode(['code' => 500, 'message' => 'Billers table not created']);	
	}

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

	if(!$db->exec($createTablesSql)) {
		echo $db->lastErrorMsg();
		echo json_encode(['code' => 500, 'message' => 'Bills table not created']);	
	} 
	
	$db->close();
	


?>