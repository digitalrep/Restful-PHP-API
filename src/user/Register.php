<?php
	
	function register($secret) {
		
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
		
			$dbmanager = new DBManager($secret);
			$dbmanager->register();
			
		}
		
	}

?>