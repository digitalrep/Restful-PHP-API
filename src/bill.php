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
				$bills = $dbmanager->getBills($id);
				
				if(!$bills == 0) {
					echo json_encode([
						'code' => 200, 
						'message' => 'OK',
						'bills' => $bills,
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
		
		// Create new bill 
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			// Make sure token is valid
			$tokenHelper = new TokenHelper($secret);
			$id = $tokenHelper->getUserId();
			
			if($id != 0) {
				
				$refreshed_token = new Token($id, $secret, null);
					
				// Create new bill
				$dbmanager = new DBManager();
				$insert = $dbmanager->createBill($id);
					
				if($insert) {
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
			$tokenHelper = new TokenHelper($secret);
			$id = $tokenHelper->getUserId();
			
			if($id != 0) {
				
				$refreshed_token = new Token($id, $secret, null);
				
				// Patch bill
				$dbmanager = new DBManager();
				$patch = $dbmanager->updateBill($id);	
				
				if($patch) {
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
			$tokenHelper = new TokenHelper($secret);
			$id = $tokenHelper->getUserId();
			
			if($id != 0) {
				
				$refreshed_token = new Token($id, $secret, null);
				
				// Patch bill
				$dbmanager = new DBManager();
				$patch = $dbmanager->updateBillStatus($id);				
				
				if($patch) {
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
			$tokenHelper = new TokenHelper($secret);
			$id = $tokenHelper->getUserId();
			
			if($id != 0) {

				$refreshed_token = new Token($id, $secret, null);

				// Delete bill
				$dbmanager = new DBManager();
				$delete = $dbmanager->deleteBill($id);
				
				if($delete) {			
					
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