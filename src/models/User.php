<?php

	namespace Bills\models;

	use Bills\DBManager;
	
	class User {
	
		/**
		 * @var integer
		 */
		private $id;
		/**
		 * @var DBManager
		 */
		private $dbmanager;
		
		/**
		 * @param integer $id used to identify User
		 */
		public function __construct($id) {
			
			$this->id = $id;
			$this->dbmanager = new DBManager();
			
		}
		
		/**
		 * Gets all bills created by User
		 *
		 * @return Bill[] 
		 */		
		public function getBills() {
		
			return $this->dbmanager->getBills($this->id);
		
		}
		
		/**
		 * Gets all billers created by User
		 *
		 * @return Biller[] 
		 */	
		public function getBillers() {
		
			return $this->dbmanager->getBillers($this->id);
		
		}
		
		/**
		 * Gets all categories created by User
		 *
		 * @return Category[] 
		 */	
		public function getCategories() {
		
			return $this->dbmanager->getCategories($this->id);
		
		}
		
		/**
		 * Creates a bill 
		 *
		 * @param integer $biller_id
		 * @param integer $amount
		 * @param integer $due (timestamp)
		 * @param integer $status (1 or 0)
		 *
		 * @return boolean
		 */	
		public function addBill($biller_id, $amount, $due, $status) {

			return $this->dbmanager->createBill($this->id, $biller_id, $amount, $due, $status);
		
		}
		
		/**
		 * Creates a biller
		 *
		 * @param integer $category_id
		 * @param string $name
		 *
		 * @return boolean
		 */	
		public function addBiller($category_id, $name) {

			return $this->dbmanager->createBiller($this->id, $category_id, $name);
		
		}
		
		/**
		 * Creates a category
		 *
		 * @param string $name
		 *
		 * @return boolean
		 */	
		public function addCategory($name) {

			return $this->dbmanager->createCategory($this->id, $name);
		
		}
		
		/**
		 * Updates biller
		 *
		 * @param integer $biller_id
		 * @param integer $category_id
		 * @param string $name
		 *
		 * @return boolean
		 */	
		public function updateBiller($biller_id, $category_id, $name) {
		
			return $this->dbmanager->updateBiller($this->id, $biller_id, $category_id, $name);					
		
		}
		
		/**
		 * Updates bill
		 *
		 * @param integer $bill_id
		 * @param integer $biller_id
		 * @param integer $amount
		 * @param integer $due (timestamp)
		 * @param integer $status (1 or 0)
		 *
		 * @return boolean
		 */	
		public function updateBill($bill_id, $biller_id, $amount, $due, $status) {
		
			return $this->dbmanager->updateBill($this->id, $bill_id, $biller_id, $amount, $due, $status);			
		
		}
		
		/**
		 * Change bill status
		 *
		 * @param integer $bill_id
		 *
		 * @return boolean
		 */	
		public function changeBillStatus($bill_id) {
		
			return $this->dbmanager->updateBillStatus($this->id, $bill_id);			
		
		}
		
		/**
		 * Deletes bill
		 *
		 * @param integer $bill_id
		 *
		 * @return boolean
		 */	
		public function deleteBill($bill_id) {
		
			return $this->dbmanager->deleteBill($this->id, $bill_id);					
		
		}
		
		/**
		 * Deletes biller
		 *
		 * @param integer $bill_id
		 *
		 * @return boolean
		 */	
		public function deleteBiller($biller_id) {
		
			return $this->dbmanager->deleteBiller($this->id, $biller_id);					
		
		}
		
		/**
		 * Updates category
		 *
		 * @param integer $category_id
		 * @param string $name
		 *
		 * @return boolean
		 */	
		public function updateCategoryName($category_id, $name) {
		
			return $this->dbmanager->patchCategory($this->id, $category_id, $name);					
		
		}
		
		/**
		 * Deletes category
		 *
		 * @param integer $category_id
		 *
		 * @return boolean
		 */	
		public function deleteCategory($category_id) {
		
			return $this->dbmanager->deleteCategory($this->id, $category_id);					
		
		}
		
		// Payments 
		
		/**
		 * Gets all payments for bill
		 *
		 * @return Payment[] 
		 */	
		public function getPayments($bill_id) {
		
			return $this->dbmanager->getPayments($this->id, $bill_id);
		
		}
		
		/**
		 * Creates a payment 
		 *
		 * @param integer $bill_id
		 * @param integer $amount
		 * @param integer $date (timestamp)
		 *
		 * @return boolean
		 */	
		public function addPayment($bill_id, $amount, $date) {

			return $this->dbmanager->createPayment($this->id, $bill_id, $amount, $date);
		
		}
		
		/**
		 * Updates payment
		 *
		 * @param integer $payment_id
		 * @param integer $bill_id
		 * @param integer $amount
		 * @param string $date
		 *
		 * @return boolean
		 */	
		public function updatePayment($payment_id, $bill_id, $amount, $date) {
		
			return $this->dbmanager->updatePayment($this->id, $payment_id, $bill_id, $amount, $date);					
		
		}
		
		/**
		 * Deletes payment
		 *
		 * @param integer $payment_id
		 *
		 * @return boolean
		 */	
		public function deletePayment($payment_id) {
		
			return $this->dbmanager->deletePayment($this->id, $payment_id);					
		
		}
	
	}
	
?>