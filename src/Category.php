<?php

	class category {
		
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
						$this->getCategory();
						break;
					case 'POST':
						$this->postCategory();
						break;
					case 'PUT':
						$this->updateCategory();	
						break;
					case 'PATCH':
						echo json_encode(["code" => 405, "message" => "Method not allowed"]);					
						break;
					case 'DELETE':
						$this->deleteCategory();
						break;
				}
				
			}
			
		}
		
		private function getCategory() {
			
			$refreshed_token = new Token($this->id, $this->secret, null);	
			
			$categories = $this->user->getCategories();
			
			if($categories) {
				
				echo json_encode([
					'code' => 200, 
					'message' => 'OK',
					'categories' => $categories,
					'token' => $refreshed_token->getTokenString()
				]);
					
			} else {
				
				echo json_encode([
					'code' => 404, 
					'message' => 'Not found',
					'categories' => '',
					'token' => $refreshed_token->getTokenString()
				]);	
				
			}

		} 
		
		private function postCategory() {
			
			$refreshed_token = new Token($this->id, $this->secret, null);
			
			// Retrieve POST variables
			$name = $_REQUEST['name'];
			
			if($this->user->addCategory($name)) {
				
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
		
		private function updateCategory() {
			
			$refreshed_token = new Token($this->id, $this->secret, null);
			
			// Get category id from URI
			$sections = explode("/", $_SERVER['REQUEST_URI']);
			$category_id = $sections[2];
				
			parse_str(file_get_contents("php://input"), $_PUT);
				
			// Retrieve PUT variables
			// All must be set as this is a PUT request
			$name = $_PUT['name'];
					
			if($this->user->updateCategoryName($category_id, $name)) {		
				echo json_encode([
					'code' => 200, 
					'message' => 'OK',
					'token' => $refreshed_token->getTokenString()
				]);
				
			} else {
				echo json_encode([
					'code' => 404, 
					'message' => 'Not found PATCH',
					'token' => $refreshed_token->getTokenString()
				]);
			}						
				
		}
		
		private function deleteCategory() {
		
			$refreshed_token = new Token($this->id, $this->secret, null);
			
			// Get category id from URI
			$sections = explode("/", $_SERVER['REQUEST_URI']);
			$category_id = $sections[2];
				
			if($this->user->deleteCategory($category_id)) {
				
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