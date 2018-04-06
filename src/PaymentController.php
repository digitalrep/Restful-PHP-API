<?php

	namespace Bills;
	
	use Bills\models\User;
	use Bills\models\Token;
	
	class PaymentController {
		
		private $secret;
		private $id;
		private $user;
		
		/**
		 * Takes a secret string and uses TokenHelper to verify it, 
		 * then obtains User id and creates User based on that id.
		 * If can't verify token, returns 401.
		 * Once User is verified, routes HTTP actions to function in file.
		 * 
		 * @param string $secret
		 */
		public function __construct($secret) {
			
			// Make sure jwt token is valid and retrieve user based on id
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
						$this->getPayment();
						break;
					case 'POST':
						$this->postPayment();
						break;
					case 'PUT':
						$this->putPayment();
						break;
					case 'PATCH':
						echo json_encode(["code" => 405, "message" => "Method not allowed"]);								
						break;
					case 'DELETE':
						$this->deletePayment();
						break;
				}
				
			}
			
		}
	
		/**
		 * Gets all payments for particular bill
		 *
		 * @return Payment[] 
		 */		
		private function getPayment() {
	
			$refreshed_token = new Token($this->id, $this->secret, null);
			
			// Get bill id from URI
			$sections = explode("/", $_SERVER['REQUEST_URI']);
			$bill_id = $sections[1];			
				
			$payments = $this->user->getPayments($bill_id);
			
			if($payments) {
				
				echo json_encode([
					'code' => 200, 
					'message' => 'OK',
					'payments' => $payments,
					'token' => $refreshed_token->getTokenString()
				]);
					
			} else {
				
				echo json_encode([
					'code' => 404, 
					'message' => 'Not found',
					'payments' => '',
					'token' => $refreshed_token->getTokenString()
				]);	
				
			}
		
		}
			
		/**
		 * Creates a payment 
		 *
		 * @param integer $_REQUEST['bill_id']
		 * @param integer $_REQUEST['amount']
		 * @param integer $_REQUEST['date'] (timestamp)
		 *
		 * @return boolean
		 */	
		private function postPayment() {
					
			$refreshed_token = new Token($this->id, $this->secret, null);
			
			// Retrieve POST variables
			$bill_id = $_REQUEST['bill_id'];
			$amount = str_replace(".", "", $_REQUEST['amount']); // Convert to integer **Require two spots after decimal on front end
			$date = $_REQUEST['date']; // Has to be unix timestamp format
						
			if($this->user->addPayment($bill_id, $amount, $date)) {
				echo json_encode([
					'code' => 201, 
					'message' => 'Payment created',
					'token' => $refreshed_token->getTokenString()
				]);
			} else {
				echo json_encode([
					'code' => 424, 
					'message' => 'Payment not created',
					'token' => $refreshed_token->getTokenString()
				]);
			}				
			
		} 
			
		/**
		 * Updates a payment 
		 *
		 * @param $_SERVER['REQUEST_URI'] (payment id)
		 * @param integer $_PUT['bill_id']
		 * @param integer $_PUT['amount']
		 * @param integer $_PUT['date'] (timestamp)
		 *
		 * @return boolean
		 */	
		private function putPayment() {
				
			$refreshed_token = new Token($this->id, $this->secret, null);
			
			// Get bill id from URI
			$sections = explode("/", $_SERVER['REQUEST_URI']);
			$payment_id = $sections[2];
				
			parse_str(file_get_contents("php://input"), $_PUT);
				
			// Retrieve PUT variables
			// All must be set as this is a PUT request
			$bill_id = $_PUT['bill_id'];
			$amount = str_replace(".", "", $_PUT['amount']);
			$date = $_PUT['date'];
				
			if($this->user->updatePayment($payment_id, $bill_id, $amount, $date)) {
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
					
		/**
		 * Delete payment
		 *
		 * @param integer $_SERVER['REQUEST_URI'] (payment id)
		 *
		 * @return boolean
		 */	
		private function deletePayment() {
		
			$refreshed_token = new Token($this->id, $this->secret, null);

			// Get payment id from URI
			$sections = explode("/", $_SERVER['REQUEST_URI']);
			$payment_id = $sections[2];
			
			echo $payment_id;
				
			if($this->user->deletePayment($payment_id)) {
				
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