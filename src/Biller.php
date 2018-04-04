<?php

	class biller {
		
		private $secret;
		private $id;
		private $user;
		
		public function __construct($secret) {
		
			// Make sure token is valid
			$this->secret = $secret;
			$tokenHelper = new TokenHelper($this->secret);
			$this->id = $tokenHelper->getUserId();	
			$this->user = new User($this->id);
			
			if($this->id == 0) {
				
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
				
			} else {
			
				$method = $_SERVER['REQUEST_METHOD'];
				
				switch($method) {
					case 'GET':
						$this->getBiller();
						break;
					case 'POST':
						$this->postBiller();
						break;
					case 'PUT':
						$this->updateBiller();
						break;
					case 'PATCH':
						echo json_encode(["code" => 405, "message" => "Method not allowed"]);						
						break;
					case 'DELETE':
						$this->deleteBiller();					
						break;
				}
				
			}
			
		}
		
		private function getBiller() {
			
			$refreshed_token = new Token($this->id, $this->secret, null);	
			
			$billers = $this->user->getBillers();
			
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

		} 
		
		private function postBiller() {
			
			$refreshed_token = new Token($this->id, $this->secret, null);
			
			// Retrieve POST variables
			$category_id = $_REQUEST['category_id'];
			$name = $_REQUEST['name'];
			
			if($this->user->addBiller($category_id, $name)) {
				echo json_encode([
					'code' => 201, 
					'message' => 'Biller created',
					'token' => $refreshed_token->getTokenString()
				]);
			} else {
				echo json_encode([
					'code' => 424, 
					'message' => 'Biller not created',
					'token' => $refreshed_token->getTokenString()
				]);
			}		

		} 
		
		private function updateBiller() {
			
			$refreshed_token = new Token($this->id, $this->secret, null);
			
			// Get biller id from URI
			$sections = explode("/", $_SERVER['REQUEST_URI']);
			$biller_id = $sections[2];
				
			parse_str(file_get_contents("php://input"), $_PUT);
				
			// Retrieve PUT variables
			// All must be set as this is a PUT request
			$name = $_PUT['name'];
			$category_id = $_PUT['category_id'];
					
			if($this->user->updateBiller($biller_id, $category_id, $name)) {		
				echo json_encode([
					'code' => 200, 
					'message' => 'OK',
					'token' => $refreshed_token->getTokenString()
				]);
				
			} else {
				echo json_encode([
					'code' => 404, 
					'message' => 'Not found',
					'token' => $refreshed_token->getTokenString()
				]);
			}						
				
		}
		
		private function deleteBiller() {
		
			$refreshed_token = new Token($this->id, $this->secret, null);
			
			// Get category id from URI
			$sections = explode("/", $_SERVER['REQUEST_URI']);
			$biller_id = $sections[2];
				
			if($this->user->deleteBiller($biller_id)) {
				
				echo json_encode([
					'code' => 200, 
					'message' => 'OK',
					'token' => $refreshed_token->getTokenString()
				]);
					
			} else {
				
				echo json_encode([
					'code' => 404, 
					'message' => 'Not found',
					'token' => $refreshed_token->getTokenString()
				]);	
				
			}
		
		}
		
	}

?>