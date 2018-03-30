<?php

	function bill($secret) {
		
		// Get all bills for user 
		if($_SERVER['REQUEST_METHOD'] === 'GET') {		

			// Make sure token is valid
			$tokenHelper = new TokenHelper($secret);
			$id = $tokenHelper->getUserId();
			
			if($id != 0) {
				
				$refreshed_token = new Token($id, $secret, null);
				
				// Retrieve the bills
				$dbmanager = new DBManager();
				$dbmanager->getBills($id, $refreshed_token);
				
			} else {
			
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
			
			}
		
		}
		
		// Create new bill 
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			// Make sure token is valid
			$tokenHelper = new TokenHelper($secret);
			$id = $tokenHelper->getUserId();
			
			if($id != 0) {
				
				$refreshed_token = new Token($id, $secret, null);
					
				// Create new bill
				$dbmanager = new DBManager();
				$dbmanager->createBill($id, $refreshed_token);	
				
			} else {
			
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
			
			}

		} 
		
		// Update bill
		if($_SERVER['REQUEST_METHOD'] === 'PUT') {
			
			// Make sure token is valid
			$tokenHelper = new TokenHelper($secret);
			$id = $tokenHelper->getUserId();
			
			if($id != 0) {
				
				$refreshed_token = new Token($id, $secret, null);
				
				// Patch bill
				$dbmanager = new DBManager();
				$dbmanager->updateBill($id, $refreshed_token);				
				
			} else {
				
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
				
			}
		}
		
		// Update bill status to paid = true
		if($_SERVER['REQUEST_METHOD'] === 'PATCH') {
			
			// Make sure token is valid
			$tokenHelper = new TokenHelper($secret);
			$id = $tokenHelper->getUserId();
			
			if($id != 0) {
				
				$refreshed_token = new Token($id, $secret, null);
				
				// Patch bill
				$dbmanager = new DBManager();
				$dbmanager->updateBillStatus($id, $refreshed_token);							
				
			} else {
				
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
				
			}
		}
		
		// Delete bill by bill id
		if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
						
			// Make sure token is valid
			$tokenHelper = new TokenHelper($secret);
			$id = $tokenHelper->getUserId();
			
			if($id != 0) {

				$refreshed_token = new Token($id, $secret, null);

				// Delete bill
				$dbmanager = new DBManager();
				$dbmanager->deleteBill($id, $refreshed_token);	
				
			} else {
				
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
				
			}
		}
		
	}

?>