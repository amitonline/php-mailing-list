<?php
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED)
error_reporting(E_ALL);

	require_once("Database.php");
	require_once("Table.php");

	echo("Starting..<br>");	
	$db = new Database("localhost", "urnexus", "root", "master");
	echo("status=" . $db->getLastError() . "<br>");

	$table = new Table($db, "zipcodes");
	$columns = $table->getColumns();
	var_dump($columns);
	echo("<br>");


	echo("Inserting new record<br>");
	$arrInsert = array();
	$arrInsert["zipcode"]="zzz";
	$arrInsert["type"]="mytype";
	$newId = $table->insert($arrInsert);
	echo("error=" . $table->getLastError() . "<br>");
	echo("Last inserted id is " . $newId.  "<br>");

	echo("Fetch new record<br>");
	$row = $table->fetch(array("zipcode", "primary_city"), array("id"=>$newId), array("="));
	var_dump($row);
	echo("<hr>");

	echo("Updating new record<br>");
	$arrUpdate= array("id"=>$newId, "zipcode"=>"updated", "type"=>"ZZ", "primary_city"=>"SOME CITY")
	;
	$table->update($arrUpdate, "id=" . $newId);
	echo("error=" . $table->getLastError() . "<br>");
	echo("Last updated id is " . $newId.  "<br>");

	echo("Fetch updated record<br>");
	$row = $table->fetch(null, array("id"=>$newId), array("="));
	var_dump($row);
	echo("<hr>");
	

	
	echo("Deleting new record<br>");
	if (!$table->delete('id=' . $newId))
		echo("Error:" . $table->getLastError() . "<br>");


	echo("Fetch multiple rows<br>");
	$rows = $table->fetchAll(array("zipcode", "primary_city"), array("id"=>4293), array(">"), 0, 10);
	var_dump($rows);
	echo("<hr>");
	echo("Closing down<br>");
	$db = null;

?>
