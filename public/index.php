<?php

	/**
	 * Very simple PHP REST API
	 *
	 */
	 
	 
	// Create DB tables. 
	include '../src/database/create_tables.php';
	
	// Models
	include '../src/models/token.php';
	
	// Unprotected 
	include '../src/user/register.php';
	include '../src/user/login.php';
	
	// Helpers
	include '../src/token_helpers.php';
	
	// Protected
	include '../src/admin.php'; 
	include '../src/bill.php';
	include '../src/biller.php';
	
	// Parse URI
	//$action = preg_replace("/[^A-Za-z0-9 ]/", '', $_SERVER['REQUEST_URI']);
	$actions = explode("/", $_SERVER['REQUEST_URI']);
	
	// JWT Secret
	$secret = "rvMuQ1MJ002IeWcl09TT4grwUxz41sSR";
	
	// 'Router' 
	if(function_exists($actions[1])) {
		call_user_func_array($actions[1], array($secret));
	}

?>