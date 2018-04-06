<?php

	/**
	 * Very simple PHP REST API
	 *
	 * Author: digitalrep@live.com.au
	 *
	 */
	 
	require_once __DIR__ . '/../vendor/autoload.php';
	 
	// Create DB tables. 
	include '../src/database/create_tables.php'; 
	
	// Parse URI
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