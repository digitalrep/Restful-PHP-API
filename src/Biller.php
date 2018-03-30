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
				$dbmanager->getBillers($id, $refreshed_token);								
				
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
				$dbmanager->createBiller($id, $refreshed_token);					
				
			} else {
			
				echo json_encode(["code" => "401", "message" => "Unauthorized"]);
			
			}

		} 
		
		
	}

?>