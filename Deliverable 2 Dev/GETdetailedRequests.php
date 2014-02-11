<?php

    $id = $_GET["id"];
	
	$sql="SELECT EntryRequestTable.requestid, EntryRequestTable.year, EntryRequestTable.duration, EntryRequestTable.noofstudents, EntryRequestTable.noofrooms, EntryRequestTable.qualityroom
	FROM EntryRequestTable INNER JOIN ModuleTable 
	ON EntryRequestTable.modulecode=ModuleTable.modulecode WHERE EntryRequestTable.requestid='$id'";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>