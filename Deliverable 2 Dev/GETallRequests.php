<?php
    
    $sql="SELECT * FROM EntryRequestTable";
    
    include "DBquery.php";
    
    $JSON = json_encode($res->fetchAll());

	echo $JSON;
?>