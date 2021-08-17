<?php
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED)
error_reporting(E_ALL);

	require_once("Database.php");

	echo("Starting..<br>");	
	$db = new Database("localhost", "urnexus", "root", "master");
	echo("status=" . $db->getLastError() . "<br>");

	echo("Inserting new record<br>");
	$sql = "insert into zipcodes (zipcode, type) values (:zipcode, :type)";
	$db->query($sql);
	$db->bind(':zipcode', 'zzz');
	$db->bind(':type', 'mytype');
	$db->execute();
	echo("Last inserted id is" . $db->lastInsertId() . "<br>");

	echo("Retrieving single row<br>");
	$sql = "select * from zipcodes where zipcode=:zipcode";
	$db->query($sql);
	$db->bind(":zipcode", "zzz");
	$row = $db->single();
	var_dump($row);

	echo("Retrieving multiple rows<br>");
	$sql = " select * from zipcodes limit 0,20";
	$db->query($sql);
	$resultset = $db->resultset();
	var_dump($resultset);

	echo("Deleting inserted row<br>");
	$sql = "delete from zipcodes where zipcode = :zipcode";
	$db->query($sql);
	$db->bind(":zipcode", "zzz");
	$db->execute();

	echo("Closing down<br>");
	$db = null;

?>
