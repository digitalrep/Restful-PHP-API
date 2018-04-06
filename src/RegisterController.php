<?php
	
	namespace Bills;
	
	class RegisterController {
		
		/**
		 * Registers a new user 
		 */
		public function __construct() {
		
			$this->secret = $secret;
			
			$method = $_SERVER['REQUEST_METHOD'];
			
			switch($method) {
				case 'GET':
					echo json_encode(["code" => 405, "message" => "Method not allowed"]);
					break;
				case 'POST':
					$dbmanager = new DBManager();
					$dbmanager->register();
					break;
				case 'PUT':
					echo json_encode(["code" => 405, "message" => "Method not allowed"]);
					break;
				case 'PATCH':
					echo json_encode(["code" => 405, "message" => "Method not allowed"]);						
					break;
				case 'DELETE':
					echo json_encode(["code" => 405, "message" => "Method not allowed"]);						
					break;
			}
			
		}
		
	}

?>