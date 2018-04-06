<?php
	
	namespace Bills; //  no namespace here causes index not to be able to find it
	
	//use \Bills\DBManager;
	
	class LoginController {
		
		/* @var string */
		private $secret;
		
		/**
		 * Verifies user based on email and password, 
		 * then returns fresh jwt token
		 * 
		 * @param string $secret
		 */
		public function __construct($secret) {
			
			$this->secret = $secret;
			
			$method = $_SERVER['REQUEST_METHOD'];
			
			switch($method) {
				case 'GET':
					echo json_encode(["code" => 405, "message" => "Method not allowed"]);
					break;
				case 'POST':
					//$dbmanager = new Bills\DBManager(); // no use statement Uncaught Error: Class 'Bills\\Bills\\DBManager' not found 
					//$dbmanager = new DBManager(); // no use statement Uncaught Error: Class 'Bills\\DBManager' not found 
					//$dbmanager = new DBManager(); // use \Bills\DBManager Uncaught Error: Class 'Bills\\DBManager' not found 
					$dbmanager = new DBManager();
					$dbmanager->login($secret);
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

?>