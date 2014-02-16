<?php

    $id = $_GET["id"];
	

	$sql="SELECT *
	FROM EntryRequestTable INNER JOIN ModuleTable 
	ON EntryRequestTable.modulecode=ModuleTable.modulecode 
	LEFT JOIN  ConfirmedBooking ON EntryRequestTable.requestid= ConfirmedBooking.requestid
	WHERE EntryRequestTable.requestid='$id' ORDER BY day, period";

    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>