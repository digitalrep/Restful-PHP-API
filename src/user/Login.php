<?php
	
	function login($secret) {
		
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			$dbmanager = new DBManager($secret);
			$dbmanager->login($secret);
			
		} 
		
	}

?>