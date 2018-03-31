<?php

	class biller {
		
		private $secret;
		
		public function __construct($secret) {
		
			$this->secret = $secret;
			
			$method = $_SERVER['REQUEST_METHOD'];
			
			switch($method) {
				case 'GET':
					$this->getBiller();
					break;
				case 'POST':
					$this->postBiller();
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
		
		private function getBiller() {
			
			// Make sure token is valid
			$tokenHelper = new TokenHelper($this->secret);
			$id = $tokenHelper->getUserId();
			
			if($id != 0) {
				
				$refreshed_token = new Token($id, $this->secret, null);				
				
				// Get billers
				$dbmanager = new DBManager();
				$dbmanager->getBillers($id, $refreshed_token);								
				
			} else {
			
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
			
			}

		} 
		
		private function postBiller() {
			
			// Make sure token is valid
			$tokenHelper = new TokenHelper($this->secret);
			$id = $tokenHelper->getUserId();
			
			if($id != 0) {
				
				$refreshed_token = new Token($id, $this->secret, null);				
				
				// Create biller
				$dbmanager = new DBManager();
				$dbmanager->createBiller($id, $refreshed_token);					
				
			} else {
			
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
			
			}

		} 
		
	}

?>