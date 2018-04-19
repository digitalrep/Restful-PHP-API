<?php

	use PHPUnit\Framework\TestCase;
	use Bills\models\User;

	class UserTest extends TestCase {

		private $user;

		public function testUserGetBills() {

			$expectedBills = [
				(object)[
					"id" => "1",
					"user_id" => "1",
					"biller" => "Red Energy - Electricity",
					"category" => "Utilities",
					"amount" => "16789",
					"due" => "10/10/2017",
					"status" => "0"
				],
				(object)[
					"id" => "2",
					"user_id" => "1",
					"biller" => "Dodo",
					"category" => "Communications",
					"amount" => "7499",
					"due" => "23/11/2017",
					"status" => "0"
				],
				(object)[
					"id" => "3",
					"user_id" => "1",
					"biller" => "Red Energy - Gas",
					"category" => "Utilities",
					"amount" => "9965",
					"due" => "15/12/2017",
					"status" => "0"
				]
			];

			/*
			$expectedBills = [
				{"id":"1","amount":"16789","due":"10\/10\/2017","status":"0","biller":"Red Energy - Electricity","category":"Utilities"},
				{"id":"2","amount":"7499","due":"23\/11\/2017","status":"0","biller":"Dodo","category":"Communications"},
				{"id":"3","amount":"9965","due":"15\/12\/2017","status":"0","biller":"Red Energy - Gas","category":"Utilities"}
			];
			*/

			$this->user = new User(1);

			$this->assertEquals($expectedBills, $this->user->getBills());

		}

	}

?>
