<?php
	$username = "cojs12";
    $sql="SELECT * FROM Preferences WHERE username='$username'";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>