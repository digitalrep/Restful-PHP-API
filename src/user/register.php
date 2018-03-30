<?php
	
	function register($secret) {
		
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
		
			$dbmanager = new DBManager($secret);
			$registered = $dbmanager->register();

			if($registered) {
				echo json_encode(['code' => 200, 'message' => 'Registered']);
			} else {
				echo json_encode(['code' => 500, 'message' => 'Couldn\'t Register']);			
			}
			
		} else {
			echo json_encode(['code' => 405, 'message' => 'Method not allowed']);		
		}
		
	}

?>