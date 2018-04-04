<?php

	class bill {
		
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
						$this->getBill();
						break;
					case 'POST':
						$this->postBill();
						break;
					case 'PUT':
						$this->putBill();
						break;
					case 'PATCH':
						$this->patchBill();
						break;
					case 'DELETE':
						$this->deleteBill();
						break;
				}
				
			}
			
		}
	
		private function getBill() {
	
			$refreshed_token = new Token($this->id, $this->secret, null);
				
			$bills = $this->user->getBills();
			
			if($bills) {
				
				echo json_encode([
					'code' => 200, 
					'message' => 'OK',
					'bills' => $bills,
					'token' => $refreshed_token->getTokenString()
				]);
					
			} else {
				
				echo json_encode([
					'code' => 404, 
					'message' => 'Not found',
					'bills' => '',
					'token' => $refreshed_token->getTokenString()
				]);	
				
			}
		
		}
			
		private function postBill() {
					
			$refreshed_token = new Token($this->id, $this->secret, null);
			
			// Retrieve POST variables
			$biller_id = $_REQUEST['biller_id'];
			$amount = str_replace(".", "", $_REQUEST['amount']); // Convert to integer **Require two spots after decimal on front end
			$due = $_REQUEST['due']; // Has to be unix timestamp format
			$status = $_REQUEST['status']; // Integer 0 or 1
						
			if($this->user->addBill($biller_id, $amount, $due, $status)) {
				echo json_encode([
					'code' => 201, 
					'message' => 'Bill created',
					'token' => $refreshed_token->getTokenString()
				]);
			} else {
				echo json_encode([
					'code' => 424, 
					'message' => 'Bill not created',
					'token' => $refreshed_token->getTokenString()
				]);
			}				
			
		} 
			
		private function putBill() {
				
			$refreshed_token = new Token($this->id, $this->secret, null);
			
			// Get bill id from URI
			$sections = explode("/", $_SERVER['REQUEST_URI']);
			$bill_id = $sections[2];
				
			parse_str(file_get_contents("php://input"), $_PUT);
				
			// Retrieve PUT variables
			// All must be set as this is a PUT request
			$biller_id = $_PUT['biller_id'];
			$amount = str_replace(".", "", $_PUT['amount']);
			$due = $_PUT['due'];
			$status = $_PUT['status'];
				
			if($this->user->updateBill($bill_id, $biller_id, $amount, $due, $status)) {
				echo json_encode([
					'code' => 200, 
					'message' => 'OK',
					'token' => $refreshed_token->getTokenString()
				]);
			} else {
				echo json_encode([
					'code' => 404, 
					'message' => 'Not found PUT',
					'token' => $refreshed_token->getTokenString()
				]);
			}				
			
		}
			
		private function patchBill() {
			
			$refreshed_token = new Token($this->id, $this->secret, null);
			
			// Get bill id from URI
			$sections = explode("/", $_SERVER['REQUEST_URI']);
			$bill_id = $sections[2];
					
			if($this->user->changeBillStatus($bill_id)) {		
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
		
		private function deleteBill() {
		
			$refreshed_token = new Token($this->id, $this->secret, null);

			// Get bill id from URI
			$sections = explode("/", $_SERVER['REQUEST_URI']);
			$bill_id = $sections[2];
				
			if($this->user->deleteBill($bill_id)) {
				
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