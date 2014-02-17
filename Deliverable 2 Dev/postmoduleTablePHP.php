This page adds a new module to the module table
using the entered values of module code and module title
verified by the user's department.

nikk

<?php
	require_once 'MDB2.php';
	include "LTF.php"; //to provide $username,$password
	
	$host='co-project.lboro.ac.uk'; //accesses database
	$dbName='team04';//login
	$dsn = "mysql://$username:$password@$host/$dbName"; 
	
	$db =& MDB2::connect($dsn); 
	if(PEAR::isError($db)){ 
	   die($db->getMessage());
	}
	$code = mysql_real_escape_string($_GET["code"]);
	$title = mysql_real_escape_string($_GET["title"]);
	$dept = ($_GET["dept"]);
	$sql="INSERT INTO ModuleTable VALUES ('$code', '$title', '$dept');";
	$db->setFetchMode(MDB2_FETCHMODE_ASSOC);
	
	$res =& $db->query($sql);
	if(PEAR::isError($res)){
		die($res->getMessage());
	} 

?>