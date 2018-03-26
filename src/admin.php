<?php

	function admin($secret) {
		
		if($_SERVER['REQUEST_METHOD'] === 'GET') {
			
			$id = checkToken($secret);
			if($id != 0) {
				
				// Controller Operations
				
				
				// Return refreshed token
				$refreshed_token = new Token($id, $secret, null);
				echo json_encode(["code" => "200", "token" => $refreshed_token->getTokenString()]);				
				
			} else {
			
				echo json_encode(["code" => "401", "message" => "Unauthorized"]);
			
			}

		} else {
			
			echo json_encode(['code' => 405, 'message' => 'Method not allowed']);
		
		}
		
	}

?>