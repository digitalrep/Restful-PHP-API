<?php

	/**
	 * Very simple PHP REST API
	 *
	 * Author: digitalrep@live.com.au
	 *
	 */

	 
	// Create DB tables. 
	include '../src/database/create_tables.php'; 
	
	// Models
	include '../src/models/Token.php';
	include '../src/models/User.php';
	
	// Helpers
	include '../src/TokenHelper.php';
	include '../src/DBManager.php';
	
	// Unprotected 
	include '../src/user/Register.php';
	include '../src/user/Login.php';
	
	// Protected
	include '../src/Bill.php';
	include '../src/Biller.php';
	include '../src/Category.php';
	
	// Parse URI
	//$action = preg_replace("/[^A-Za-z0-9 ]/", '', $_SERVER['REQUEST_URI']);
	$actions = explode("/", $_SERVER['REQUEST_URI']);
	
	// JWT Secret
	$secret = "rvMuQ1MJ002IeWcl09TT4grwUxz41sSR";
	
	// Router
	if($actions[1] != '') {
		$object = new $actions[1]($secret);
	}

?>