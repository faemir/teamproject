returns all module codes and titles from the DB where the id = the id of 
timetablers department
	Josh
<?php

    $id = $_GET["id"];
    $sql="SELECT modulecode, moduletitle FROM ModuleTable WHERE departmentid="
	$sql.="'$id'";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>