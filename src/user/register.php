<?php
	
	function register($secret) {
		
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
		
			$db = new PDO("sqlite:../src/database/database.sqlite");
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$name = $_REQUEST['name'];
			$email = $_REQUEST['email'];
			$password = $_REQUEST['password'];
			
			if(strlen($name) < 3) {
				echo json_encode(['code' => 422, 'message' => 'Name too short']);	
				exit();
			}
			if(strlen($email) < 6) {
				echo json_encode(['code' => 422, 'message' => 'Email too short']);	
				exit();
			}
			if(strlen($password) < 6) {
				echo json_encode(['code' => 422, 'message' => 'Password too short']);	
				exit();
			}
			
			$insertQuery = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
			$stmt = $db->prepare($insertQuery);
			$stmt->bindParam(':name', $name);
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
			if($stmt->execute()) {
				echo json_encode(['code' => 201, 'message' => 'User created']);			
			} else {
				echo json_encode(['code' => 500, 'message' => 'User not created']);		
			}
			$db->close();
			
		} else {
			echo json_encode(['code' => 405, 'message' => 'Method not allowed']);		
		}
		
	}

?>