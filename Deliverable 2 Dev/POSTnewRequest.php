<?php


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
	$other = $_GET["other"];

    $sql = "INSERT INTO EntryRequestTable(Year,ModuleCode,Priority,Semester,Day,Period,Duration,";
    $sql .= "WeekID,NoOfStudents,NoOfRooms,PreferredRooms,RequestStatus,QualityRoom,WheelchairAccess,DataProjector,";
    $sql .= "DoubleProjector,Visualiser,VideoDVDBluray,Computer,WhiteBoard,ChalkBoard,NearestRoom,Other) VALUES ";
    $sql .= "($year,'$modulecode',$priority,$semester,'$day',$period,$duration,$weekid,$noofstudents,$noofrooms,";
    $sql .= "$preferredroom,'$requeststatus',$qualityroom,$wheelchair,$dataprojector,$doubleprojector,$visualiser,$videodvdbluray,";
    $sql .= "$computer,$whiteboard,$chalkboard,$nearestroom,'$other');";

	include "DBquery.php";
	//echo $sql;


?>