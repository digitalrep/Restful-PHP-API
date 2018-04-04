<?php

	class DBManager {
		
		/**
		 * @var PDO 
		 */
		private $db;

		public function __construct() {
			
			$this->db = new PDO('mysql:host=localhost;dbname=bills', 'bills_admin', '5y9_uio345');
			//$this->db = new PDO("sqlite:../src/database/database.sqlite");
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		}
		
		/**
		 * Get bills for user
		 *
		 * @param integer $id user id
		 *
		 * @return array[] $bills Bill objects | boolean false if db error
		 */
		public function getBills($id) {
		
			$query = "
			SELECT bills.id, bills.user_id, billers.name, billers.category_id, bills.amount, bills.due, bills.status 
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
				return null;
			}			
		
		}
		
		/**
		 * Create bill for user
		 *
		 * @param integer $id user id
		 *
		 * @return boolean true if created | boolean false if db error
		 */
		function createBill($user_id, $biller_id, $amount, $due, $status) {
					
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
		 * Update bill for user
		 *
		 * @param integer $id user id
		 * @param input stream php://input for PUT vars
		 *
		 * @return boolean true if updated | boolean false if db error
		 */
		function updateBill($id, $bill_id, $biller_id, $amount, $due, $status) {
			
			$query = "SELECT * FROM bills WHERE id = :bill_id AND user_id = :user_id LIMIT 1";
			
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(":bill_id", $bill_id);
			$stmt->bindParam(":user_id", $id);
			$stmt->execute();
			
			$bill = $stmt->fetch(1);
			
			if(!$bill) {		
			
				return false;
			
			} else {

				$query = "UPDATE bills 
				SET biller_id = :biller_id, amount = :amount, due = :due, status = :status
				WHERE id = :bill_id AND user_id = :user_id";
					
				$stmt = $this->db->prepare($query);
				$stmt->bindParam(':biller_id', $biller_id);
				$stmt->bindParam(':bill_id', $bill_id);
				$stmt->bindParam(':user_id', $id);
				$stmt->bindParam(':amount', $amount);
				$stmt->bindParam(':due', $due);
				$stmt->bindParam(':status', $status);

				if($stmt->execute()) {		
					return true;
				} else {
					return false;
				}
				
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
			
			$query = "SELECT * FROM bills WHERE id = :bill_id AND user_id = :user_id LIMIT 1";
			
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(":bill_id", $bill_id);
			$stmt->bindParam(":user_id", $id);
			$stmt->execute();
			
			$bill = $stmt->fetch(1);
			
			if(!$bill) {		
				return false;
			} else {

				$status = (int)!$bill->status;

				$query = "UPDATE bills 
				SET status = :status
				WHERE id = :bill_id AND user_id = :user_id";
					
				$stmt = $this->db->prepare($query);
				$stmt->bindParam(":status", $status);
				$stmt->bindParam(":bill_id", $bill_id);
				$stmt->bindParam(":user_id", $id);

				if($stmt->execute()) {		
					return true;
				} else {
					return false;
				}
				
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
		function deleteBill($id, $bill_id) {
		
			$user_id = $id;
			
			$query = "SELECT * FROM bills WHERE id = :bill_id AND user_id = :user_id LIMIT 1";
			
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(":bill_id", $bill_id);
			$stmt->bindParam(":user_id", $user_id);
			$stmt->execute();
			
			$bill = $stmt->fetch(1);
			
			if(!$bill) {		
			
				return false;
			
			} else {

				$query = "DELETE FROM bills WHERE id = :bill_id AND user_id = :user_id";
				$stmt = $this->db->prepare($query);
					
				if($stmt->execute(array(':bill_id' => $bill_id, ':user_id' => $user_id))) {
					return true;
				} else {
					return false;	
				}
					
				
			}
		
		}
		
		/**
		 * Create biller
		 *
		 * @param integer $id user id
		 *
		 * @return JSON 422 if bad entity | boolean true if created | boolean false if db error
		 */
		function createBiller($id, $category_id, $name) {
			
			// Check values
			if(strlen($name) < 3) {
				echo json_encode(['code' => 422, 'message' => 'Name too short']);	
				exit();
			}
			
			$query = "INSERT INTO billers (user_id, category_id, name) VALUES (user_id, :category_id, :name)";
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(':user_id', $id);
			$stmt->bindParam(':category_id', $category_id);
			$stmt->bindParam(':name', $name);
				
			if($stmt->execute()) {	
			
				return true;

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
			SELECT billers.id, billers.name, categories.category_name
			FROM billers
			INNER JOIN categories
			ON billers.category_id = categories.id WHERE billers.user_id = :user_id";
			$stmt = $this->db->prepare($query);
			if(!$stmt) { print_r($this->db->errorInfo()); }
			$stmt->bindParam(':user_id', $id);
			
			if($stmt->execute()) {
				$billers = $stmt->fetchAll();
				return $billers;
			} else {
				return false;
			}
			
		}
		
		/**
		 * Get categories
		 *
		 * @param integer $id user id
		 *
		 * @return array[] $categories Category objects | boolean false if db error
		 */
		function getCategories($id) {
			
			$query = "
			SELECT categories.id, categories.category_name
			FROM categories
			WHERE user_id = :user_id";
			$stmt = $this->db->prepare($query);
			if(!$stmt) { print_r($this->db->errorInfo()); }
			$stmt->bindParam(':user_id', $id);
			
			if($stmt->execute()) {
				$categories = $stmt->fetchAll();
				return $categories;
			} else {
				return false;
			}
			
		}
		
		/**
		 * Create category
		 *
		 * @param integer $id user id
		 *
		 * @return JSON 422 if bad entity | boolean true if created | boolean false if db error
		 */
		function createCategory($id, $name) {
			
			// Check values
			if(strlen($name) < 3) {
				echo json_encode(['code' => 422, 'message' => 'Name too short']);	
				exit();
			}
			
			$query = "INSERT INTO categories (user_id, category_name) VALUES (:id, :name)";
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(':id', $id);
			$stmt->bindParam(':name', $name);
				
			if($stmt->execute()) {	
			
				return true;
				
			} else {
				
				return false;

			}
			
		}
		
		/**
		 * Update category name for user
		 *
		 * @param integer $id user id
		 * @param string $name category name
		 *
		 * @return boolean true if updated | boolean false if db error
		 */
		function patchCategory($id, $category_id, $name) { 
			
			$query = "SELECT * FROM categories WHERE id = :category_id AND user_id = :user_id LIMIT 1";
			
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(":category_id", $category_id);
			$stmt->bindParam(":user_id", $id);
			$stmt->execute();
			
			$bill = $stmt->fetch(1);
			
			if(!$bill) {		
				return false;
			} else {

				$query = "UPDATE categories 
				SET category_name = :name
				WHERE id = :category_id AND user_id = :user_id";
					
				$stmt = $this->db->prepare($query);
				$stmt->bindParam(":name", $name);
				$stmt->bindParam(":category_id", $category_id);
				$stmt->bindParam(":user_id", $id);

				if($stmt->execute()) {		
					return true;
				} else {
					return false;
				}
				
			}			
			
		}
		
		/**
		 * Update biller for user
		 *
		 * @param integer $id user id
		 * @param string $name name
		 * @param interger $category_id category id
		 *
		 * @return boolean true if updated | boolean false if db error
		 */
		function updateBiller($id, $biller_id, $category_id, $name) { 
			
			$query = "SELECT * FROM billers WHERE id = :biller_id AND user_id = :user_id LIMIT 1";
			
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(":biller_id", $biller_id);
			$stmt->bindParam(":user_id", $id);
			$stmt->execute();
			
			$biller = $stmt->fetch(1);
			
			if(!$biller) {		
				return false;
			} else {

				$query = "UPDATE billers 
				SET name = :name, category_id = :category_id
				WHERE id = :biller_id AND user_id = :user_id";
					
				$stmt = $this->db->prepare($query);
				$stmt->bindParam(":name", $name);
				$stmt->bindParam(":category_id", $category_id);
				$stmt->bindParam(":biller_id", $biller_id);
				$stmt->bindParam(":user_id", $id);

				if($stmt->execute()) {		
					return true;
				} else {
					return false;
				}
				
			}			
			
		}
		
		/**
		 * Delete category for user
		 *
		 * @param integer $id user id
		 * @param integer $category_id category id
		 *
		 * @return true if deleted | boolean false if not found or db error
		 */
		function deleteCategory($id, $category_id) {
		
			$user_id = $id;
			
			$query = "SELECT * FROM categories WHERE id = :id AND user_id = :user_id LIMIT 1";
			
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(":id", $category_id);
			$stmt->bindParam(":user_id", $user_id);
			$stmt->execute();
			
			$category = $stmt->fetch(1);
			
			if(!$category) {		
			
				return false;
			
			} else {

				$query = "DELETE FROM categories WHERE id = :id AND user_id = :user_id";
				$stmt = $this->db->prepare($query);
					
				if($stmt->execute(array(':id' => $category_id, ':user_id' => $user_id))) {
					return true;
				} else {
					return false;	
				}
					
				
			}
		
		}
		
		/**
		 * Delete category for user
		 *
		 * @param integer $id user id
		 * @param integer $biller_id biller id
		 *
		 * @return true if deleted | boolean false if not found or db error
		 */
		function deleteBiller($id, $biller_id) {
		
			$user_id = $id;
			
			$query = "SELECT * FROM billers WHERE id = :id AND user_id = :user_id LIMIT 1";
			
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(":id", $biller_id);
			$stmt->bindParam(":user_id", $user_id);
			$stmt->execute();
			
			$category = $stmt->fetch(1);
			
			if(!$category) {		
			
				return false;
			
			} else {

				$query = "DELETE FROM billers WHERE id = :id AND user_id = :user_id";
				$stmt = $this->db->prepare($query);
					
				if($stmt->execute(array(':id' => $biller_id, ':user_id' => $user_id))) {
					return true;
				} else {
					return false;	
				}
					
				
			}
		
		}
		
		/**
		 * Register new user 
		 *
		 * @param string $_REQUEST['name'] 
		 * @param string $_REQUEST['email'] 
		 * @param string $_REQUEST['password'] 
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
			
			try {
				
				$insertQuery = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
				$stmt = $this->db->prepare($insertQuery);
				$stmt->bindParam(':name', $name);
				$stmt->bindParam(':email', $email);
				$pwd = password_hash($password, PASSWORD_DEFAULT);
				$stmt->bindParam(':password', $pwd);
				$stmt->execute();
				
				if($stmt->rowCount() > 0) {
					echo json_encode(['code' => 200, 'message' => 'Registered']);		
				} else {
					echo json_encode(['code' => 500, 'message' => 'Couldn\'t Register']);	
				}
				
			} catch (Exception $e) {
				
				echo json_encode(['code' => 500, 'message' => 'Couldn\'t Register - email address already registered']);
				
			}
		
		}
		
		/**
		 * Login user 
		 *
		 * @param string $_REQUEST['email'] 
		 * @param string $_REQUEST['password'] 
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
				
				echo json_encode(['code' => 401, 'message' => 'Couldn\'t log you in']); // user not found by email
			
			} else {
				
				if($user['email'] == password_verify($password, $user['password'])) {
				
					$date = new DateTime();
					$iat = date_timestamp_get($date);
					$token = new Token($user['id'], $secret, $iat);
					echo json_encode(['code' => 200, 'message' => 'Logged in', 'token' => $token->getTokenString()]); // everything good
				
				} else {
				
					echo json_encode(['code' => 401, 'message' => 'Couldn\'t log you in']); // wrong password
	
				}
			}			
			
		}
		
	}

?>