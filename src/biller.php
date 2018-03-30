<?php

	function biller($secret) {
		
		// Get all billers
		if($_SERVER['REQUEST_METHOD'] === 'GET') {
			
			// Make sure token is valid
			$tokenHelper = new TokenHelper($secret);
			$id = $tokenHelper->getUserId();
			
			if($id != 0) {
				
				$refreshed_token = new Token($id, $secret, null);				
				
				// Get billers
				$dbmanager = new DBManager();
				$billers = $dbmanager->getBillers($id);					
				
				if($billers) {
					echo json_encode([
						'code' => 200, 
						'message' => 'OK',
						'billers' => $billers,
						'token' => $refreshed_token->getTokenString()
					]);				
				} else {
					echo json_encode([
						'code' => 404, 
						'message' => 'Not found',
						'billers' => '',
						'token' => $refreshed_token->getTokenString()
					]);					
				}				
				
			} else {
			
				echo json_encode(["code" => "401", "message" => "Unauthorized"]);
			
			}

		} 
		
		// Create new biller
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			// Make sure token is valid
			$tokenHelper = new TokenHelper($secret);
			$id = $tokenHelper->getUserId();
			
			if($id != 0) {
				
				$refreshed_token = new Token($id, $secret, null);				
				
				// Create biller
				$dbmanager = new DBManager();
				$create = $dbmanager->createBiller($id);					
				
				if($create) {
					echo json_encode(['code' => 201, 'message' => 'Biller created', "token" => $refreshed_token->getTokenString()]);			
				} else {
					echo json_encode(['code' => 500, 'message' => 'Biller not created', "token" => $refreshed_token->getTokenString()]);		
				}
				
			} else {
			
				echo json_encode(["code" => "401", "message" => "Unauthorized"]);
			
			}

		} 
		
		
	}

?>