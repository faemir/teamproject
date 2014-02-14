<?php	
	$editBool = $_GET["editBool"];
	$requestid = $_GET["requestid"];
	$room = $_GET["room"];
	$modulecode = $_GET["modulecode"];
	$sql = "INSERT INTO RoomBooking (RequestID, RoomID, ModuleCode) VALUES ($requestid,'$room','$modulecode');";
	include "DBquery.php";
?>