<?php

	class bill {
		
		private $secret;
		
		public function __construct($secret) {
		
			$this->secret = $secret;
			
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
	
		private function getBill() {

			// Make sure token is valid
			$tokenHelper = new TokenHelper($this->secret);
			$id = $tokenHelper->getUserId();
				
			if($id != 0) {
					
				$refreshed_token = new Token($id, $this->secret, null);
					
				// Retrieve the bills
				$dbmanager = new DBManager();
				$dbmanager->getBills($id, $refreshed_token);
					
			} else {
				
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
				
			}
			
		}
			
		private function postBill() {
				
			// Make sure token is valid
			$tokenHelper = new TokenHelper($this->secret);
			$id = $tokenHelper->getUserId();
				
			if($id != 0) {
					
				$refreshed_token = new Token($id, $this->secret, null);
						
				// Create new bill
				$dbmanager = new DBManager();
				$dbmanager->createBill($id, $refreshed_token);	
					
			} else {
				
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
				
			}

		} 
			
		private function putBill() {
				
			// Make sure token is valid
			$tokenHelper = new TokenHelper($this->secret);
			$id = $tokenHelper->getUserId();
				
			if($id != 0) {
					
				$refreshed_token = new Token($id, $this->secret, null);
				
				// Patch bill
				$dbmanager = new DBManager();
				$dbmanager->updateBill($id, $refreshed_token);				
					
			} else {
					
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
					
			}
		}
			
		private function patchBill() {
				
			// Make sure token is valid
			$tokenHelper = new TokenHelper($this->secret);
			$id = $tokenHelper->getUserId();
				
			if($id != 0) {
					
				$refreshed_token = new Token($id, $this->secret, null);
					
				// Patch bill
				$dbmanager = new DBManager();
				$dbmanager->updateBillStatus($id, $refreshed_token);							
				
			} else {
					
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
					
			}
		}
		
		private function deleteBill() {
							
			// Make sure token is valid
			$tokenHelper = new TokenHelper($this->secret);
			$id = $tokenHelper->getUserId();
				
			if($id != 0) {

				$refreshed_token = new Token($id, $this->secret, null);

				// Delete bill
				$dbmanager = new DBManager();
				$dbmanager->deleteBill($id, $refreshed_token);	
					
			} else {
					
				echo json_encode(["code" => 401, "message" => "Unauthorized"]);
					
			}
		}	

	}

?>