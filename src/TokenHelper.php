<?php

	class TokenHelper {
		
		private $secret;
		
		public function __construct($secret)
		{
			$this->secret = $secret;
		}
	
		function getUserId() {
			
			// Get raw token string
			$headers = getallheaders();
			$raw_token = $headers['Authorization'];			
			$token_body = explode('Bearer: ', $raw_token);
			$parts = explode('.', $token_body[1]);
				
			// Decode header and payload
			$header = base64_decode($parts[0]);
			$payload = base64_decode($parts[1]);
			
			// Decode payload variables
			$id = json_decode($payload)->{'user_id'};
			$issued = json_decode($payload)->{'iat'};
				
			// Get current timestamp
			$date = new DateTime();
			$now = date_timestamp_get($date);
				
			// Check that token hasn't expired yet
			if($now - $issued > 600) {
				return 0;
			}

			// Create new token with variables extracted from raw token 
			// and check that they match
			$token = new Token($id, $this->secret, $issued);
				
			if($token->getTokenString() == $token_body[1]) {	
				return $id;
			} else {
				return 0;
			}
			
		}
		
	}
	
?>