This is used to get all the details about a specific entry by using the
request id as a reference. It then returns these details back to the 
website for the details tab. 
					Matt
<?php

    $id = $_GET["id"];
	

	$sql="SELECT * ";
	$sql.= "FROM EntryRequestTable INNER JOIN ModuleTable "; 
	$sql.= "ON EntryRequestTable.modulecode=ModuleTable.modulecode "; 
	$sql.= "LEFT JOIN RoomBooking "; 
	$sql.= "ON EntryRequestTable.requestid=RoomBooking.requestid ";
	$sql.= "WHERE EntryRequestTable.requestid='$id'";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>