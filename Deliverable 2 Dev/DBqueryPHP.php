The script that uses LTF.php to connect to the Database
and acts as a basis for all php scripts that connect to the database.
	Josh
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
	
	$db->setFetchMode(MDB2_FETCHMODE_ASSOC);
	
	$res =& $db->query($sql);
	if(PEAR::isError($res)){
		die($res->getMessage());
	} 

?>