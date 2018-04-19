<?php
	
	namespace Bills; 
	
	use Bills\models\User;
	use Bills\models\Token;
	
	class RefreshController {
		
		/* @var string */
		private $secret;
		
		/**
		 * Verifies user based on email and password, 
		 * then returns fresh jwt token
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
						$dbmanager = new DBManager();
						$dbmanager->refresh($user_id, $this->secret);
						break;
					case 'POST':
						echo json_encode(["code" => 405, "message" => "Method not allowed"]);
						break;
					case 'PUT':
						echo json_encode(["code" => 405, "message" => "Method not allowed"]);
						break;
					case 'PATCH':
						echo json_encode(["code" => 405, "message" => "Method not allowed"]);						
						break;
					case 'DELETE':
						echo json_encode(["code" => 405, "message" => "Method not allowed"]);						
						break;
				}
			}
		}
		
	}

?>