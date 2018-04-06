<?php

	namespace Bills;
	
	use Bills\models\Token;
	
	class TokenHelper {
		
		/**
		 * @var string
		 */
		private $secret;
		
		/**
		 * @param string $secret Used for creating tokens 
		 */
		public function __construct($secret)
		{
			$this->secret = $secret;
		}
	
		/**
		 * Reads the token from the Authorization header then attempts to verify it
		 *
		 * @return string $token if verified | integer 0 if expired | integer 0 if not verified
		 */
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
			$date = new \DateTime();
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