<?php
    
    $sql="SELECT RequestId, ModuleId, Priority, Semester, Day, Period, RequestStatus FROM EntryRequestTable";
    
    include "DBquery.php";
    
    $JSON = json_encode($res->fetchAll());

	echo $JSON;
?>