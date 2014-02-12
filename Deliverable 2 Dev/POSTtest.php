<?php
    
    $preferredroom = 1;
    $roomsarray=array("CC.0.11","CC.0.12","CC.0.14");
    $requestid = 20;
    $modulecode = "COB120";
    
    
    //include "DBquery.php";
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
	
    if($preferredroom == 1){
        foreach($roomsarray as $room){
            $sqlroom = "INSERT INTO RoomBooking (RequestID, RoomID, ModuleCode) VALUES ($requestid,'$room','$modulecode');";
             $res =& $db->query($sqlroom);
             if(PEAR::isError($res)){
                 die($res->getMessage());
             }
        }
    }
   


?>