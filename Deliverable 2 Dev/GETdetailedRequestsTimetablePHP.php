Displays all of the details of a specific request, to be displayed in 
ViewTimetable after an onclick. The php connects to the database via DBquery
	Callum
<?php
	$id = $_GET["id"];

	$sql="SELECT * ";
	$sql.="FROM EntryRequestTable INNER JOIN ModuleTable ";
	$sql.="ON EntryRequestTable.modulecode=ModuleTable.modulecode "; 
	$sql.="LEFT JOIN  ConfirmedBooking ";
	$sql.="ON EntryRequestTable.requestid= ConfirmedBooking.requestid ";
	$sql.="WHERE EntryRequestTable.requestid='$id' ORDER BY day, period";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>