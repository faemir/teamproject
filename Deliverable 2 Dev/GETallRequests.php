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
	if($semsval != '0'){
		$sql .= "AND semester = '$semsval';";
		
	}
	if($type != ""){
		$sql .= "AND $typearray[$type] LIKE '%$searchval%';";
	}
	
	

   include "DBquery.php";
   $JSON = json_encode($res->fetchAll());
   echo $JSON;


?>