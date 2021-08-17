<?php
	error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED);
	session_start();
	
	
	require_once("../includes/globals.php");

		// check for valid page referer
	$rDomain = getDomain($_SERVER["HTTP_REFERER"]);
	$thisDomain = $_SERVER['SERVER_NAME'];
	$thisDomain = str_replace("www.", "", $thisDomain);

	if (strtolower(trim($rDomain)) != strtolower(trim($thisDomain))) {
		exit("ERROR - Cross domain posting detected");
	}

	// check session
	if ($_SESSION["user_id"] < 1) {
		$userId = null;
	} else 
		$userId = $_SESSION["user_id"];

	$_SESSION["cookie_ok"] = 1;

	exit("");
?>
