<?php
	
	$username = 'admin';
	//$username = $_session['username'];
    $sql="SELECT * FROM EntryRequestTable 
	INNER JOIN ModuleTable ON EntryRequestTable.modulecode=ModuleTable.modulecode
	INNER JOIN DepartmentTable ON ModuleTable.departmentid=DepartmentTable.departmentid
	INNER JOIN UserTable ON DepartmentTable.departmentid=UserTable.departmentid
	WHERE UserTable.username='$username'";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>