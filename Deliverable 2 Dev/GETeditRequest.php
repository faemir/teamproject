<?php

	$id = $_GET["id"];
	$sql = "SELECT * FROM EntryRequestTable JOIN RoomBooking WHERE EntryRequestTable.requestid = $id AND RoomBooking.requestid = $id";
	include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>