<?php
	
	$type = $_GET['type'];
	$searchval = $_GET['searchval'];
	$typearray = array("modulecode","moduletitle","day","requeststatus","period", "duration", "priority", "noofstudents","qualityroom","preferredrooms","semester", "noofrooms", "wheelchairaccess", "dataprojector", "doubleprojector", "visualiser", "videodvdbluray", "computer", "whiteboard", "chalkboard");
	
	
	$sql="SELECT * FROM EntryRequestTable INNER JOIN ModuleTable ON EntryRequestTable.ModuleCode=ModuleTable.ModuleCode ";
	if($type != ""){
		$sql .= "WHERE $typearray[$type] LIKE '%$searchval%' ;";
	}

    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>