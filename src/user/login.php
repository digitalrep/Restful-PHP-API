<?php
	
	function login($secret) {
		
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
		
			$email = $_REQUEST['email'];
			$password = $_REQUEST['password'];

			$db = new PDO("sqlite:../src/database/database.sqlite");
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
			$stmt->execute([':email' => $email]);

			$data = [];
			
			while($row = $stmt->fetch(1)) {
				$data[] = [
					'id' => $row['id'],
					'name' => $row['name'],
					'email' => $row['email'],
					'password' => $row['password']
				];
			}

			$user = $data[0];
			
			if(!$user) {
				echo json_encode(['code' => 404, 'message' => 'User not found']);
			} else {
				if($user['email'] == password_verify($password, $user['password'])) {
					$date = new DateTime();
					$iat = date_timestamp_get($date);
					$token = new Token($user['id'], $secret, $iat);
					echo json_encode(['code' => 200, 'message' => 'User found', 'token' => $token->getTokenString()]);
				} else {
					echo json_encode(['code' => 401, 'message' => 'Unauthorized']);
				}
			}
		} else {
			echo json_encode(['code' => 405, 'message' => 'Method not allowed']);
		}
		
	}

?>