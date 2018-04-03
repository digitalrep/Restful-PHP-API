<?php

	class bill {
		
		private $secret;
		private $id;
		private $database;
		
		public function __construct($secret, $database) {
		
			$this->secret = $secret;
			$this->database = $database;
			
			// Make sure token is valid
			$tokenHelper = new TokenHelper($this->secret);
			$this->id = $tokenHelper->getUserId();			
			
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
				
			// Retrieve the bills
			$dbmanager = new DBManager($this->database);
			$dbmanager->getBills($this->id, $refreshed_token);
		
		}
			
		private function postBill() {
					
			$refreshed_token = new Token($this->id, $this->secret, null);
						
			// Create new bill
			$dbmanager = new DBManager($this->database);
			$dbmanager->createBill($this->id, $refreshed_token);			
			
		} 
			
		private function putBill() {
				
			$refreshed_token = new Token($this->id, $this->secret, null);
				
			// Patch bill
			$dbmanager = new DBManager($this->database);
			$dbmanager->updateBill($this->id, $refreshed_token);					
			
		}
			
		private function patchBill() {
			
			$refreshed_token = new Token($this->id, $this->secret, null);
					
			// Patch bill
			$dbmanager = new DBManager($this->database);
			$dbmanager->updateBillStatus($this->id, $refreshed_token);							
				
		}
		
		private function deleteBill() {
		
			$refreshed_token = new Token($this->id, $this->secret, null);

			// Delete bill
			$dbmanager = new DBManager($this->database);
			$dbmanager->deleteBill($this->id, $refreshed_token);	
			
		}	

	}

?>