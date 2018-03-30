<?php

	class DBManager {
		
		/**
		 * @var PDO 
		 */
		private $db;

		public function __construct() {
			
			$this->db = new PDO("sqlite:../src/database/database.sqlite");
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		}
		
		/**
		 * Register new user 
		 *
		 * @params string $_REQUEST['name'] 
		 * @params string $_REQUEST['email'] 
		 * @params string $_REQUEST['password'] 
		 *
		 * @return JSON code 422 if bad entity | boolean true if registered | boolean false if db error
		 */
		function register() {
		
			$name = $_REQUEST['name'];
			$email = $_REQUEST['email'];
			$password = $_REQUEST['password'];
			
			if(strlen($name) < 3) {
				echo json_encode(['code' => 422, 'message' => 'Name too short']);	
				exit();
			}
			if(strlen($email) < 6) {
				echo json_encode(['code' => 422, 'message' => 'Email too short']);	
				exit();
			}
			if(strlen($password) < 6) {
				echo json_encode(['code' => 422, 'message' => 'Password too short']);	
				exit();
			}
			
			$insertQuery = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
			$stmt = $this->db->prepare($insertQuery);
			$stmt->bindParam(':name', $name);
			$stmt->bindParam(':email', $email);
			$pwd = password_hash($password, PASSWORD_DEFAULT);
			$stmt->bindParam(':password', $pwd);
			if($stmt->execute()) {
				return true;			
			} else {
				return false;	
			}
		
		}
		
		/**
		 * Login user 
		 *
		 * @params string $_REQUEST['email'] 
		 * @params string $_REQUEST['password'] 
		 *
		 * @return string $token if logged in | boolean false if not found | boolean false if bad password
		 */
		function login($secret) {

			// Get POST variables
			$email = $_REQUEST['email'];
			$password = $_REQUEST['password'];
			
			// We use email (it's unique)
			$stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
			$stmt->execute([':email' => $email]);		

			$data = [];
			
			// Not sure how to just fetch one row =/
			while($row = $stmt->fetch(1)) {
				$data[] = [
					'id' => $row['id'],
					'name' => $row['name'],
					'email' => $row['email'],
					'password' => $row['password']
				];
			}

			$user = $data[0];
			
			if(!$user) {
				return false; // user not found by email
			} else {
				if($user['email'] == password_verify($password, $user['password'])) {
					$date = new DateTime();
					$iat = date_timestamp_get($date);
					$token = new Token($user['id'], $secret, $iat);
					return $token; // everything good
				} else {
					return false; // wrong password
				}
			}			
			
		}
		
		/**
		 * Get bills for user
		 *
		 * @param integer $id user id
		 *
		 * @return array[] $bills Bill objects | boolean false if db error
		 */
		function getBills($id) {
			
			$query = "
			SELECT bills.id, bills.user_id, billers.name, billers.category, bills.amount, bills.due, bills.status 
			FROM bills 
			INNER JOIN billers 
			ON bills.biller_id = billers.id WHERE bills.user_id = :user_id";
			$stmt = $this->db->prepare($query);
			if(!$stmt) { print_r($this->db->errorInfo()); }
			$stmt->bindParam(':user_id', $id);
			
			if($stmt->execute()) {
				$bills = $stmt->fetchAll();
				return $bills;
			} else {
				return false;
			}
			
		}
		
		/**
		 * Get billers
		 *
		 * @param integer $id user id
		 *
		 * @return array[] $billers Biller objects | boolean false if db error
		 */
		function getBillers($id) {
			
			$query = "
			SELECT billers.id, billers.name, billers.category
			FROM billers";
			$stmt = $this->db->prepare($query);
			if(!$stmt) { print_r($this->db->errorInfo()); }
			
			if($stmt->execute()) {
				$billers = $stmt->fetchAll();
				return $billers;
			} else {
				return false;
			}
			
		}
		
		/**
		 * Create bill for user
		 *
		 * @param integer $id user id
		 *
		 * @return boolean true if created | boolean false if db error
		 */
		function createBill($id) {
			
			// Retrieve POST variables
			$user_id = $id;
			$biller_id = $_REQUEST['biller_id'];
			$amount = str_replace(".", "", $_REQUEST['amount']); // Convert to integer **Require two spots after decimal on front end
			$due = $_REQUEST['due']; // Has to be unix timestamp format
			$status = $_REQUEST['status']; // Integer 0 or 1
					
			$query = "INSERT INTO bills (user_id, biller_id, amount, due, status) VALUES (:user_id, :biller_id, :amount, :due, :status)";
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(':user_id', $user_id);
			$stmt->bindParam(':biller_id', $biller_id);
			$stmt->bindParam(':amount', $amount);
			$stmt->bindParam(':due', $due);
			$stmt->bindParam(':status', $status);
				
			if($stmt->execute()) {	
				return true;
			} else {
				return false;
			}
			
		}
		
		/**
		 * Create biller
		 *
		 * @param integer $id user id
		 *
		 * @return JSON 422 if bad entity | boolean true if created | boolean false if db error
		 */
		function createBiller($id) {
			
			// Retrieve POST variables
			$name = $_REQUEST['name'];
			$category = $_REQUEST['category'];
				
			// Check values
			if(strlen($name) < 3) {
				echo json_encode(['code' => 422, 'message' => 'Name too short']);	
				exit();
			}
			if(strlen($category) < 3) {
				echo json_encode(['code' => 422, 'message' => 'Category too short']);	
				exit();
			}
			
			$query = "INSERT INTO billers (name, category) VALUES (:name, :category)";
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(':name', $name);
			$stmt->bindParam(':category', $category);
				
			if($stmt->execute()) {	
				return true;
			} else {
				return false;
			}
			
		}
		
		/**
		 * Update bill for user
		 *
		 * @param integer $id user id
		 * @param input stream php://input for PUT vars
		 *
		 * @return boolean true if updated | boolean false if db error
		 */
		function updateBill($id) {
		
			// Get bill id from URI
			$sections = explode("/", $_SERVER['REQUEST_URI']);
			$bill_id = $sections[2];
				
			parse_str(file_get_contents("php://input"), $_PUT);
				
			// Retrieve PUT variables
			// All must be set as this is a PUT request
			$user_id = $id;
			$biller_id = $_PUT['biller_id'];
			$amount = str_replace(".", "", $_PUT['amount']);
			$due = $_PUT['due'];
			$status = $_PUT['status'];

			$query = "UPDATE bills 
			SET biller_id = :biller_id, amount = :amount, due = :due, status = :status
			WHERE id = :bill_id AND user_id = :user_id";
				
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(':biller_id', $biller_id);
			$stmt->bindParam(':bill_id', $bill_id);
			$stmt->bindParam(':user_id', $user_id);
			$stmt->bindParam(':amount', $amount);
			$stmt->bindParam(':due', $due);
			$stmt->bindParam(':status', $status);

			if($stmt->execute()) {			
				return true;
			} else {
				return false;
			}
		
		}
		
		/**
		 * Update bill for user
		 *
		 * @param integer $id user id
		 *
		 * @return boolean true if updated | boolean false if db error
		 */
		function updateBillStatus($id) { 
		
			// Get bill id from URI
			$sections = explode("/", $_SERVER['REQUEST_URI']);
			$bill_id = $sections[2];			

			$query = "UPDATE bills 
			SET status = 1
			WHERE id = :bill_id AND user_id = :user_id";
				
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(":bill_id", $bill_id);
			$stmt->bindParam(":user_id", $id);

			// Executes whether bill id is valid or not
			// How to send back appropriate json response if bill id invalid?
			if($stmt->execute()) {		
				return true;
			} else {
				return false;
			}
		
		}
		
		/**
		 * Delete bill for user
		 *
		 * @param integer $id user id
		 * @param $_SERVER['REQUEST_URI'] to get bill id
		 *
		 * @return true if updated | boolean false if db error
		 */
		function deleteBill($id) {
		
			$user_id = $id;
				
			// Get bill id from URI
			$sections = explode("/", $_SERVER['REQUEST_URI']);
			$bill_id = $sections[2];

			$query = "DELETE FROM bills WHERE id = :bill_id AND user_id = :user_id";
			$stmt = $this->db->prepare($query);
			
			// Executes whether bill id is valid or not
			// How to send back appropriate json response if bill id invalid?			
			if($stmt->execute(array(':bill_id' => $bill_id, ':user_id' => $user_id))) {
				return true;
			} else {
				return false;
			}
		
		}
		
	}

?>