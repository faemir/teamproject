<?php

	$semsval = $_GET['semsval'];
	$type = $_GET['type'];
	$searchval = $_GET['searchval'];
	$typearray = array("EntryRequestTable.modulecode","moduletitle","day","requeststatus","period", "duration", "priority", "noofstudents","qualityroom","preferredrooms","semester", "noofrooms", "wheelchairaccess", "dataprojector", "doubleprojector", "visualiser", "videodvdbluray", "computer", "whiteboard", "chalkboard");
	$username = 'admin';
	//$username = $_session['username'];
	
	$sql="SELECT * FROM EntryRequestTable INNER JOIN ModuleTable ON EntryRequestTable.ModuleCode=ModuleTable.ModuleCode
	INNER JOIN DepartmentTable ON ModuleTable.departmentid=DepartmentTable.departmentid
	INNER JOIN UserTable ON DepartmentTable.departmentid=UserTable.departmentid
	WHERE UserTable.username='$username' ";

	if($type != "" AND $type != "20"){
		$sql .= "AND $typearray[$type] LIKE '%$searchval%' ";
	}
	if($searchval != ""){
		$sql .= "AND (EntryRequestTable.modulecode LIKE '%$searchval%' OR moduletitle LIKE '%$searchval%' OR day LIKE '%$searchval%' OR requeststatus LIKE '%$searchval%' OR period LIKE '%$searchval%' OR duration LIKE '%$searchval%' OR priority LIKE '%$searchval%' OR noofstudents LIKE '%$searchval%' OR qualityroom LIKE '%$searchval%' OR preferredrooms LIKE '%$searchval%' OR noofrooms LIKE '%$searchval%' OR wheelchairaccess LIKE '%$searchval%' OR dataprojector LIKE '%$searchval%' OR doubleprojector LIKE '%$searchval%' OR visualiser LIKE '%$searchval%' OR videodvdbluray LIKE '%$searchval%' OR computer LIKE '%$searchval%' OR whiteboard LIKE '%$searchval%' OR chalkboard LIKE '%$searchval%') "; 
	}
	if($semsval != '0'){
		$sql .= "AND semester = $semsval ";
		
	}
   include "DBquery.php";
   $JSON = json_encode($res->fetchAll());
   echo $JSON;



?>