<?php

	function bill($secret) {
		
		// Get all bills for user 
		if($_SERVER['REQUEST_METHOD'] === 'GET') {		

			// Make sure token is valid
			$id = checkToken($secret);
			if($id != 0) {
				
				$refreshed_token = new Token($id, $secret, null);
				
				// Retrieve the bills
				$db = new PDO("sqlite:../src/database/database.sqlite");
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$query = "SELECT bills.id, bills.user_id, billers.name, billers.category, bills.amount, bills.due, bills.status FROM bills INNER JOIN billers ON bills.biller_id = billers.id WHERE bills.user_id = :user_id";
				$stmt = $db->prepare($query);
				//if(!$stmt) { print_r($db->errorInfo()); }
				$stmt->bindParam(':user_id', $id);
				if($stmt->execute()) {
					$bills = $stmt->fetchAll();
					echo json_encode([
						'code' => 200, 
						'message' => 'OK',
						'bills' => $bills,
						'token' => $refreshed_token->getTokenString()]);			
				} else {
					echo json_encode([
						'code' => 404, 
						'message' => 'Not found',
						'bills' => '',
						'token' => $refreshed_token->getTokenString()]);		
				}
	
				
			} else {
			
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
			
			}
		
		}
		
		// Create new bill 
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			// Make sure token is valid
			$id = checkToken($secret);
			if($id != 0) {
				
				$refreshed_token = new Token($id, $secret, null);
					
				// Create new bill
					
				// Retrieve POST variables
				$user_id = $id;
				$biller_id = $_REQUEST['biller_id'];
				$amount = str_replace(".", "", $_REQUEST['amount']); // Convert to integer **Require two spots after decimal on front end
				$due = $_REQUEST['due']; // Has to be unix timestamp format
				$status = $_REQUEST['status']; // Integer 0 or 1
					
				// Create Bill in database
				$db = new PDO("sqlite:../src/database/database.sqlite");
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					
				if(!$db) {
					echo $db->lastErrorMsg();
				} 
					
				$query = "INSERT INTO bills (user_id, biller_id, amount, due, status) VALUES (:user_id, :biller_id, :amount, :due, :status)";
				$stmt = $db->prepare($query);
				$stmt->bindParam(':user_id', $user_id);
				$stmt->bindParam(':biller_id', $biller_id);
				$stmt->bindParam(':amount', $amount);
				$stmt->bindParam(':due', $due);
				$stmt->bindParam(':status', $status);
				
				if($stmt->execute()) {
					echo json_encode([
						'code' => 201, 
						'message' => 'Bill created',
						'token' => $refreshed_token->getTokenString()]);			
				} else {
					echo json_encode([
						'code' => 424, 
						'message' => 'Bill not created',
						'token' => $refreshed_token->getTokenString()]);		
				}		
				
				
			} else {
			
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
			
			}

		} 
		
		// Update bill
		if($_SERVER['REQUEST_METHOD'] === 'PUT') {
			// Make sure token is valid
			$id = checkToken($secret);
			if($id != 0) {
				
				$refreshed_token = new Token($id, $secret, null);
				
				// Get bill id from URI
				$sections = explode("/", $_SERVER['REQUEST_URI']);
				$bill_id = $sections[2];
				
				parse_str(file_get_contents("php://input"), $_PUT);
				
				// Retrieve PUT variables
				// All must be set as this is a PUT request
				$user_id = $id;
				$biller_id = $_PUT['biller_id'];
				$amount = str_replace(".", "", $_PUT['amount']);
				$due = $_PUT['due'];
				$status = $_PUT['status'];
				
				echo "raw status: " . $_PUT['status'];
				echo "(int) status: " . (int)$_PUT['status'];
				
				// Update Bill in database
				$db = new PDO("sqlite:../src/database/database.sqlite");
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				if(!$db) {
					echo $db->lastErrorMsg();
				} 				

				$query = "UPDATE bills 
				SET biller_id = :biller_id, amount = :amount, due = :due, status = :status
				WHERE id = :bill_id AND user_id = :user_id";
				
				$stmt = $db->prepare($query);
				$stmt->bindParam(':biller_id', $biller_id);
				$stmt->bindParam(':bill_id', $bill_id);
				$stmt->bindParam(':user_id', $user_id);
				$stmt->bindParam(':amount', $amount);
				$stmt->bindParam(':due', $due);
				$stmt->bindParam(':status', $status);

				if($stmt->execute()) {
					echo json_encode([
						'code' => 200, 
						'message' => 'OK',
						'token' => $refreshed_token->getTokenString()]);			
				} else {
					echo json_encode([
						'code' => 404, 
						'message' => 'Not found',
						'token' => $refreshed_token->getTokenString()]);		
				}				
				
			} else {
				
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
				
			}
		}
		
		// Update bill status to paid = true
		if($_SERVER['REQUEST_METHOD'] === 'PATCH') {
			// Make sure token is valid
			$id = checkToken($secret);
			if($id != 0) {
				
				$refreshed_token = new Token($id, $secret, null);
				
				// Get bill id from URI
				$sections = explode("/", $_SERVER['REQUEST_URI']);
				$bill_id = $sections[2];
				
				// Update Bill status in database
				$db = new PDO("sqlite:../src/database/database.sqlite");
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				if(!$db) {
					echo $db->lastErrorMsg();
				} 				

				$query = "UPDATE bills 
				SET status = 1
				WHERE id = :bill_id AND user_id = :user_id";
				
				$stmt = $db->prepare($query);
				$stmt->bindParam(":bill_id", $bill_id);
				$stmt->bindParam(":user_id", $id);

				if($stmt->execute()) {
					echo json_encode([
						'code' => 200, 
						'message' => 'OK',
						'token' => $refreshed_token->getTokenString()]);			
				} else {
					echo json_encode([
						'code' => 404, 
						'message' => 'Not found',
						'token' => $refreshed_token->getTokenString()]);		
				}				
				
			} else {
				
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
				
			}
		}
		
		// Delete bill by bill id
		if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
						
			// Make sure token is valid
			$id = checkToken($secret);
			if($id != 0) {

				$refreshed_token = new Token($id, $secret, null);
				$user_id = $id;
				
				// Get bill id from URI
				$sections = explode("/", $_SERVER['REQUEST_URI']);
				$bill_id = $sections[2];
				
				// Create Bill in database
				$db = new PDO("sqlite:../src/database/database.sqlite");
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				if(!$db) {
					echo $db->lastErrorMsg();
				} 
				
				// Make sure this bill belongs to this user before deleting it
				$query = "SELECT * FROM bills WHERE id = :bill_id AND user_id = :user_id";
				$stmt = $db->prepare($query);
				$stmt->execute(array(':bill_id' => $bill_id, ':user_id' => $user_id));
				$bill = $stmt->fetch();
				
				if($bill) {
					$query = "DELETE FROM bills WHERE id = :bill_id AND user_id = :user_id";
					$stmt = $db->prepare($query);
					$stmt->execute(array(':bill_id' => $bill_id, ':user_id' => $user_id));					
					
					echo json_encode([
						'code' => 200, 
						'message' => 'OK',
						'token' => $refreshed_token->getTokenString()
					]);		
					
				} else {
					echo json_encode([
						'code' => 404, 
						'message' => 'Not found',
						'bills' => '',
						'token' => $refreshed_token->getTokenString()
					]);	
				}		
				
				
			} else {
				
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
				
			}
		}
		
	}

?>