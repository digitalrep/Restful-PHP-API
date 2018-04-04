<?php

	class User {
	
		private $id;
		private $dbmanager;
		
		public function __construct($id) {
			
			$this->id = $id;
			$this->dbmanager = new DBManager2();
			
		}
		
		public function getBills() {
		
			return $this->dbmanager->getBills($this->id);
		
		}
		
		public function getBillers() {
		
			return $this->dbmanager->getBillers($this->id);
		
		}
		
		public function getCategories() {
		
			return $this->dbmanager->getCategories($this->id);
		
		}
		
		public function addBill($biller_id, $amount, $due, $status) {

			return $this->dbmanager->createBill($this->id, $biller_id, $amount, $due, $status);
		
		}
		
		public function addBiller($category_id, $name) {

			return $this->dbmanager->createBiller($this->id, $category_id, $name);
		
		}
		
		public function updateBiller($biller_id, $category_id, $name) {
		
			return $this->dbmanager->updateBiller($this->id, $biller_id, $category_id, $name);					
		
		}
		
		public function addCategory($name) {

			return $this->dbmanager->createCategory($this->id, $name);
		
		}
		
		public function updateBill($bill_id, $biller_id, $amount, $due, $status) {
		
			return $this->dbmanager->updateBill($this->id, $bill_id, $biller_id, $amount, $due, $status);			
		
		}
		
		public function changeBillStatus($bill_id) {
		
			return $this->dbmanager->updateBillStatus($this->id, $bill_id);			
		
		}
		
		public function deleteBill($bill_id) {
		
			return $this->dbmanager->deleteBill($this->id, $bill_id);					
		
		}
		
		public function deleteBiller($biller_id) {
		
			return $this->dbmanager->deleteBiller($this->id, $biller_id);					
		
		}
		
		public function updateCategoryName($category_id, $name) {
		
			return $this->dbmanager->patchCategory($this->id, $category_id, $name);					
		
		}
		
		public function deleteCategory($category_id) {
		
			return $this->dbmanager->deleteCategory($this->id, $category_id);					
		
		}
	
	}
	
?>