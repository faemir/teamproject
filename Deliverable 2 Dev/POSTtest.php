<?php
    //all variables -----------------------------------------//
    // strings need to be passed as string with ' '.
    
    $year = $_GET["year"]; 
    $modulecode = $_GET["modulecode"]; 
    $priority = $_GET["priority"];
    $semester = $_GET["semester"];
    $day = $_GET["day"];
    $duration = $_GET["duration"];
    
    $weekid = 0; //if not empty then normal, else if empty write new row to weekstable
    
    $noofstudents = $_GET["noofstudents"];
    $noofrooms = $_GET["noofrooms"];
    $preferredroom = $_GET["preferredroom"];
    
    $requeststatus = "pending";
    
    $qualityroom = $_GET["qualityroom"];
    $wheelchair = $_GET["wheelchair"];
    $dataprojector = $_GET["dataprojector"];
    $doubleprojector = $_GET["doubleprojector"];
    $visualiser = $_GET["visualiser"];
    $videodvdbluray = $_GET["videodvdbluray"];
    $computer = $_GET["computer"];
    $whiteboard = $_GET["whiteboard"];
    $chalkboard = $_GET["chalkboard"];
    
    $roomsarray= $_GET["roomsarray"];
    
    //parts of DBQuery.php to set up connection---------------//
    require_once 'MDB2.php';
	include "LTF.php"; //to provide $username,$password
	
	$host='co-project.lboro.ac.uk'; //accesses database
	$dbName='team04';//login
	$dsn = "mysql://$username:$password@$host/$dbName"; 
	
	$db =& MDB2::connect($dsn); 
	if(PEAR::isError($db)){ 
	   die($db->getMessage());
	}

    $db->setFetchMode(MDB2_FETCHMODE_ASSOC);
    //--------------------------------------------------------//
    
    //entry request table entry
    $sqlrequest = "INSERT INTO EntryRequestTable(Year,ModuleCode,Priority,Semester,Day,Period,Duration,";
    $sqlrequest .= "WeekID,NoOfStudents,PreferredRooms,RequestStatus,QualityRoom,WheelchairAccess,DataProjector,";
    $sqlrequest .= "DoubleProjector,Visualiser,VideoDVDBluray,Computer,WhiteBoard,ChalkBoard) VALUES ";
    $sqlrequest .= "($year,'$modulecode',$priority,$semester,'$day',$period,$duration,$weekid,$noofstudents,$noofrooms,";
    $sqlrequest .= "$preferredrooms,'$requeststatus',$qualityroom,$wheelchair,$dataprojector,$visualiser,$videodvdbluray,";
    $sqlrequest .= "$computer,$whiteboard,$chalkboard);";
    $res =& $db->query($sqlrequest);
    if(PEAR::isError($res)){
        die($res->getMessage());
    }
    $requestid = ; //new value just entered.
    
    

	$sqlroom = "INSERT INTO RoomBooking (RequestID, RoomID, ModuleCode) VALUES ($requestid,'$room','$modulecode');";
	 $res =& $db->query($sqlroom);
	 if(PEAR::isError($res)){
		 die($res->getMessage());
	 }

  
?>