This script takes an Id of a week and outputs a value of 1 if 
the specific requested item is on that week or 0 if not, 
displays all 15 weeks
	Callum
<?php
    $id = $_GET["id"];
	$sql="SELECT *"
	$sql=."FROM WeekTable"
	$sql=."WHERE weekid='$id';";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>