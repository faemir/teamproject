<?php
	require_once 'MDB2.php';
	include "LTF.php"; //to provide $username,$password
	
	$host='co-project.lboro.ac.uk'; //accesses database
	$dbName='team04';//login
	$dsn = "mysql://$username:$password@$host/$dbName"; 
	
	$db =& MDB2::connect($dsn); 
	if(PEAR::isError($db)){ 
	   die($db->getMessage());
	}


	$semsval = $_GET['semsval'];
	$type = $_GET['type'];
	$searchval = mysql_real_escape_string($_GET['searchval']);
	$typearray = array("EntryRequestTable.modulecode","moduletitle","day","requeststatus","period", "duration", "priority", "noofstudents","qualityroom","preferredrooms","semester", "noofrooms", "wheelchairaccess", "dataprojector", "doubleprojector", "visualiser", "videodvdbluray", "computer", "whiteboard", "chalkboard");
	$username = $_GET['username'];
	
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
	$db->setFetchMode(MDB2_FETCHMODE_ASSOC);
	
	$res =& $db->query($sql);
	if(PEAR::isError($res)){
		die($res->getMessage());
	} 
	$JSON = json_encode($res->fetchAll());
   
   echo $JSON;
?>