<?php

	$semsval = $_GET['semsval'];
	$type = $_GET['type'];
	$searchval = $_GET['searchval'];
	$typearray = array("EntryRequestTable.modulecode","moduletitle","day","requeststatus","period", "duration", "priority", "noofstudents","qualityroom","preferredrooms","semester", "noofrooms", "wheelchairaccess", "dataprojector", "doubleprojector", "visualiser", "videodvdbluray", "computer", "whiteboard", "chalkboard");
	
	
	$sql="SELECT * FROM EntryRequestTable INNER JOIN ModuleTable ON EntryRequestTable.ModuleCode=ModuleTable.ModuleCode ";
	if($semsval != '0' AND $type == ""){
		$sql .= "WHERE semester = '$semsval';";
		
	}
	else if($type != "" AND $semsval == '0'){
		$sql .= "WHERE $typearray[$type] LIKE '%$searchval%';";
	}
	else if($semsval != '0' AND $type != ""){
		$sql .= "WHERE $typearray[$type] LIKE '%$searchval%' AND semester = '$semsval';";	
	}

   include "DBquery.php";
   $JSON = json_encode($res->fetchAll());
   echo $JSON;


?>