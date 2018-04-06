<?php

	//use Bills\user\LoginController;

	/**
	 * Very simple PHP REST API
	 *
	 * Author: digitalrep@live.com.au
	 *
	 */
	 
	require_once __DIR__ . '/../vendor/autoload.php';
	 
	// Create DB tables. 
	include '../src/database/create_tables.php'; 
	
	// Models
	//include '../src/models/Token.php';
	//include '../src/models/User.php';
	
	// Helpers
	//include '../src/TokenHelper.php';
	//include '../src/DBManager.php';
	
	// Unprotected Routes
	//include '../src/user/RegisterController.php';
	//include '../src/user/LoginController.php';
	
	// Protected Routes
	//include '../src/BillController.php';
	//include '../src/BillerController.php';
	//include '../src/CategoryController.php';
	//include '../src/PaymentController.php';
	
	// Parse URI
	//$action = preg_replace("/[^A-Za-z0-9 ]/", '', $_SERVER['REQUEST_URI']);
	$actions = explode("/", $_SERVER['REQUEST_URI']);
	
	// JWT Secret
	$secret = "rvMuQ1MJ002IeWcl09TT4grwUxz41sSR";
	
	// Router
	if($actions[1] != '') {
		$name = ucfirst($actions[1]) . "Controller";
		$controller = "Bills\\" . $name;
		$object = new $controller($secret);
	}

?>