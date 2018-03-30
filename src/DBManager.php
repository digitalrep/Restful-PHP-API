<?php

	class DBManager {
		
		private $db;

		public function __construct() {
			
			$this->db = new PDO("sqlite:../src/database/database.sqlite");
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		}
		
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