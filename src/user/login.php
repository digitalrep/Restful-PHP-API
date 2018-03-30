<?php
	
	function login($secret) {
		
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			$dbmanager = new DBManager($secret);
			$token = $dbmanager->login($secret);

			if(!$token) {
				echo json_encode(['code' => 401, 'message' => 'Couldn\'t log you in']);
			} else {
				echo json_encode(['code' => 200, 'message' => 'Logged in', 'token' => $token->getTokenString()]);
			}
			
		} 
		
	}

?>