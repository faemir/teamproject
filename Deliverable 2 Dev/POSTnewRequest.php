<?php
    //all variables -----------------------------------------//
    // strings need to be passed as string with ' '.
    
    $requestid = ; //get from page
    $year = ; //get from page
    $modulecode = $_GET["modulecode"]; 
    $priority = $_GET["priority"];
    $semester = $_GET["semester"];
    $day = $_GET["day"];
    $duration = $_GET["duration"];
    
    $weekid = 0; //if not empty then normal, else if empty write new row to weekstable
    $weeksarray = array(1,1,1,0,1,0,1,0,1,0,1,0,1,0,1);
    
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
    
    //week table entry
    if($weekid == 0){
        $sqlwk = "INSERT INTO WeekTable(Week1,Week2,Week3,Week4,Week5,Week6,Week7,Week8,";
        $sqlwk .= "Week9,Week10,Week11,Week12,Week13,Week14,Week15) VALUES ($weeksarray[0],";
        $sqlwk .= "$weeksarray[1],$weeksarray[2],$weeksarray[3],$weeksarray[4],$weeksarray[5],";
        $sqlwk .= "$weeksarray[6],$weeksarray[7],$weeksarray[8],$weeksarray[9],$weeksarray[10],";
        $sqlwk .= "$weeksarray[11],$weeksarray[12],$weeksarray[13],$weeksarray[14]); ";
        $res =& $db->query($sqlwk);
        if(PEAR::isError($res)){
            die($res->getMessage());
        }
        $weekid = ; //new value just entered
    }
    
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