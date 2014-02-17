<?php

    $id = $_GET["id"];
	

	$sql="SELECT *
	FROM EntryRequestTable INNER JOIN ModuleTable 
	ON EntryRequestTable.modulecode=ModuleTable.modulecode 
	LEFT JOIN RoomBooking ON EntryRequestTable.requestid=RoomBooking.requestid
	WHERE EntryRequestTable.requestid='$id'";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>