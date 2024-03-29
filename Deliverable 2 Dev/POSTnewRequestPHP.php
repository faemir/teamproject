This script posts a new request entry into EntryRequestTable. A connection 
with the database is established at the beginning for the use of 
mysql_real_escape_string() function before a query is made later on in script.
the editbool value decides whether request is new or its an edit of a existing
request.
	Josh, Callum
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
	
	$editBool = $_GET["editBool"];
	$editRequestId = $_GET["editrequestid"];
	$year = $_GET["year"]; 
    $modulecode = $_GET["modulecode"]; 
    $priority = $_GET["priority"];
    $semester = $_GET["semester"];
    $day = $_GET["day"];
	$period = $_GET["period"];
    $duration = $_GET["duration"];
    $weekid = $_GET["weekid"];
    $noofstudents = $_GET["noofstudents"];
    $noofrooms = $_GET["noofrooms"];
    $preferredroom = $_GET["preferredroom"];
    
    $requeststatus = "pending";
    
    $qualityroom =  $_GET["qualityroom"];
    $wheelchair = $_GET["wheelchair"];
    $dataprojector = $_GET["dataprojector"];
    $doubleprojector = $_GET["doubleprojector"];
    $visualiser = $_GET["visualiser"];
    $videodvdbluray = $_GET["videodvdbluray"];
    $computer = $_GET["computer"];
    $whiteboard = $_GET["whiteboard"];
    $chalkboard = $_GET["chalkboard"];
	$nearestroom = $_GET["nearestroom"];
	$other = mysql_real_escape_string($_GET["other"]);
	
	if($editBool == 'true'){

		$sql = "UPDATE EntryRequestTable ";
		$sql .= "SET Year=$year,ModuleCode='$modulecode',Priority=$priority,";
		$sql .= "Semester=$semester,Day='$day',Period=$period,";
		$sql .= "Duration=$duration,WeekID=$weekid,NoOfStudents=$noofstudents";
		$sql .= ",NoOfRooms=$noofrooms,PreferredRooms=$preferredroom,";
		$sql .= "RequestStatus='$requeststatus',QualityRoom=$qualityroom,";
		$sql .= "WheelChairAccess=$wheelchair,DataProjector=$dataprojector,";
		$sql .= "DoubleProjector=$doubleprojector,Visualiser=$visualiser,";
		$sql .= "VideoDVDBluray=$videodvdbluray,Computer=$computer,";
		$sql .= "WhiteBoard=$whiteboard,ChalkBoard=$chalkboard,";
		$sql .= "NearestRoom=$nearestroom,Other='$other' WHERE"; 
		$sql .= "RequestID=$editRequestId;";

	}
	else{
		$sql = "INSERT INTO EntryRequestTable(Year,ModuleCode,Priority,";
		$sql .= "Semester,Day,Period,Duration,";
		$sql .= "WeekID,NoOfStudents,NoOfRooms,PreferredRooms,";
		$sql .= "RequestStatus,QualityRoom,WheelchairAccess,DataProjector,";
		$sql .= "DoubleProjector,Visualiser,VideoDVDBluray,Computer,";
		$sql .= "WhiteBoard,ChalkBoard,NearestRoom,Other) VALUES ";
		$sql .= "($year,'$modulecode',$priority,$semester,'$day',";
		$sql .= "$period,$duration,$weekid,$noofstudents,$noofrooms,";
		$sql .= "$preferredroom,'$requeststatus',$qualityroom,$wheelchair,";
		$sql .= "$dataprojector,$doubleprojector,$visualiser,$videodvdbluray,";
		$sql .= "$computer,$whiteboard,$chalkboard,$nearestroom,'$other');";
	}

	$db->setFetchMode(MDB2_FETCHMODE_ASSOC);
	$res =& $db->query($sql);
	if(PEAR::isError($res)){
		die($res->getMessage());
	} 

?>