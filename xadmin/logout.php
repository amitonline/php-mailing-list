<?php
	error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED);
	session_start();
	$redirect = "index.php";

    $_SESSION["admin_id"] = null;	
	session_destroy();
	
	header("Location:" . $redirect);
	exit;
?>
