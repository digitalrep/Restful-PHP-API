<?php

	class Token {
		
		private $token_string;
		
		public function __construct($id, $secret, $iat) {
			$this->token_string = $this->createToken($id, $secret, $iat);
		}
		
		public function getTokenString() {
			return $this->token_string;
		}

		private function createToken($id, $secret, $iat) {
			
			$header = '{"alg" : "HS256", "typ" : "JWT"}';
			$date = new DateTime();
			$now = date_timestamp_get($date);
			if($iat == null) { $iat = $now; }
			$payload = '{"user_id" : ' . $id . ', "iat" : ' . $iat . '}';
			$unsignedToken = base64_encode($header) . "." . base64_encode($payload);		
			$signature = hash_hmac('sha256', $unsignedToken, $secret, true);
			$encodedSignature = base64_encode($signature);
			$token = base64_encode($header) . "." . base64_encode($payload) . "." . $encodedSignature;
			$cleaned_token = str_replace("/", "", $token);
			return $cleaned_token;
		
		}
	
	}
	
?>