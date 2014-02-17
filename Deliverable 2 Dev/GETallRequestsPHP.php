This file is used for retrieving all details for both writing the view requests
table and supplying the data for the search to work. Instead of using 
DBquery.php we had to connect directly with the database as the 
real_escape_string could not function without it. This php file was used to 
search through three different tables depending on the column and value
that we wanted to search by.              
					Matt									
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
	
	$typearray = array("EntryRequestTable.modulecode","moduletitle","day");
	array_push($typearray,"requeststatus","period","duration","priority");
	array_push($typearray,"noofstudents","qualityroom","preferredrooms");
	array_push($typearray,"semester","noofrooms","wheelchairaccess");
	array_push($typearray,"dataprojector","doubleprojector");
	array_push($typearray,"visualiser","videodvdbluray","computer");
	array_push($typearray,"whiteboard","chalkboard");
	$username = $_GET['username'];
	$year = $_GET["year"];
	
	$sql="SELECT * FROM EntryRequestTable "
	$sql .= "INNER JOIN ModuleTable "
	$sql .= "ON EntryRequestTable.ModuleCode=ModuleTable.ModuleCode "
	$sql .= "INNER JOIN DepartmentTable "
	$sql .= "ON ModuleTable.departmentid=DepartmentTable.departmentid "
	$sql .= "INNER JOIN UserTable "
	$sql .= "ON DepartmentTable.departmentid=UserTable.departmentid "
	$sql .= "WHERE UserTable.username='$username' AND year = $year ";

	if($type != "" AND $type != "20"){
		$sql .= "AND $typearray[$type] LIKE '%$searchval%' ";
	}
	if($searchval != ""){
		$sql .= "AND (EntryRequestTable.modulecode LIKE '%$searchval%' "
		$sql .= "OR moduletitle LIKE '%$searchval%' OR day LIKE '%$searchval%' "
		$sql .= "OR requeststatus LIKE '%$searchval%' OR period LIKE '%$searchval%' "
		$sql .= "OR duration LIKE '%$searchval%' OR priority LIKE '%$searchval%' " 
		$sql .= "OR noofstudents LIKE '%$searchval%' "
		$sql .= "OR qualityroom LIKE '%$searchval%' "
		$sql .= "OR preferredrooms LIKE '%$searchval%' "
		$sql .= "OR noofrooms LIKE '%$searchval%' "
		$sql .= "OR wheelchairaccess LIKE '%$searchval%' "
		$sql .= "OR dataprojector LIKE '%$searchval%' "
		$sql .= "OR doubleprojector LIKE '%$searchval%' "
		$sql .= "OR visualiser LIKE '%$searchval%' "
		$sql .= "OR videodvdbluray LIKE '%$searchval%' "
		$sql .= "OR computer LIKE '%$searchval%' OR whiteboard LIKE '%$searchval%' "
		$sql .= "OR chalkboard LIKE '%$searchval%') "; 
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