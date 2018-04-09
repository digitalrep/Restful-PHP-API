<?php

	use PHPUnit\Framework\TestCase;
	use Bills\models\User;
	
	class UserTest extends TestCase {
		
		private $user;
		
		public function testUserGetBills() {
			
			$expectedBills = [
				array(
					"id" => 1,
					0 => 1,
					"user_id" => 1,
					1 => 1,
					"name" => "Red Energy - Electricity",
					2 => "Red Energy - Electricity",
					"category_id" => 1,
					3 => 1,
					"amount" => 16789,
					4 => 16789,
					"due" => 1525502122,
					5 => 1525502122,
					"status" => 0,
					6 => 0					
				),
				array(
					"id" => 2,
					0 => 2,
					"user_id" => 1,
					1 => 1,
					"name" => "Dodo",
					2 => "Dodo",
					"category_id" => 3,
					3 => 3,
					"amount" => 7499,
					4 => 7499,
					"due" => 1522737322,
					5 => 1522737322,
					"status" => 0,
					6 => 0	
				),
				array(
					"id" => 3,
					0 => 3,
					"user_id" => 1,
					1 => 1,
					"name" => "Red Energy - Gas",
					2 => "Red Energy - Gas",
					"category_id" => 1,
					3 => 1,
					"amount" => 9965,
					4 => 9965,
					"due" => 1522391722,
					5 => 1522391722,
					"status" => 0,
					6 => 0					
				)
			];
			
			$this->user = new User(1);
			
			$this->assertEquals($expectedBills, $this->user->getBills());
			
		}
		
	}

?>