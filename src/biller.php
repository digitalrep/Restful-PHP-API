<?php

	function biller($secret) {
		
		// Create new biller
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			// Make sure token is valid
			$id = checkToken($secret);
			if($id != 0) {
				
				$refreshed_token = new Token($id, $secret, null);				
				
				// Retrieve POST variables
				$name = $_REQUEST['name'];
				$category = $_REQUEST['category'];
				
				// Check values
				if(strlen($name) < 3) {
					echo json_encode(['code' => 422, 'message' => 'Name too short']);	
					exit();
				}
				if(strlen($category) < 3) {
					echo json_encode(['code' => 422, 'message' => 'Category too short']);	
					exit();
				}
				
				// Create Bill in database
				$db = new SQLite3('../src/database/database.sqlite');
				
				if(!$db) {
					echo $db->lastErrorMsg();
				} 
				
				$query = "INSERT INTO billers (name, category) VALUES (:name, :category)";
				$stmt = $db->prepare($query);
				$stmt->bindParam(':name', $name);
				$stmt->bindParam(':category', $category);
			
				if($stmt->execute()) {
					echo json_encode(['code' => 201, 'message' => 'Biller created', "token" => $refreshed_token->getTokenString()]);			
				} else {
					echo json_encode(['code' => 500, 'message' => 'Biller not created', "token" => $refreshed_token->getTokenString()]);		
				}
				$db->close();	
				
			} else {
			
				echo json_encode(["code" => "401", "message" => "Unauthorized"]);
			
			}

		} else {
			
			echo json_encode(['code' => 405, 'message' => 'Method not allowed']);
		
		}
		
		
	}

?>