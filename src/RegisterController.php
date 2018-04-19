<?php

	namespace Bills;

	class RegisterController {

		/* @var string */
		private $secret;

		/**
		 * Registers a new user
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
					$dbmanager = new DBManager();
					$dbmanager->register($secret);
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
