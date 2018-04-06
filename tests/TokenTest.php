<?php

	use PHPUnit\Framework\TestCase;
	use Bills\models\Token;
	
	class TokenTest extends TestCase {
		
		private $secret = "rvMuQ1MJ002IeWcl09TT4grwUxz41sSR";
		private $token;
		
		public function testTokenProperlyCreated() {

			$id = 1;
			$iat = 1522392567;
			
			// Generated @ https://jwt.io/
			$token_string = "eyJhbGciIDogIkhTMjU2IiwgInR5cCIgOiAiSldUIn0=.eyJ1c2VyX2lkIiA6IDEsICJpYXQiIDogMTUyMjM5MjU2N30=.MpfJihwPacxAq73WqSXpyn267KWgmmhG9bXBqMGkQ=";

			$this->token = new Token($id, $this->secret, $iat);
			$this->assertEquals($token_string, $this->token->getTokenString());
			
		}
		
	}

?>