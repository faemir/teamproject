This gets the department ID (eg. CO or MA) from the user table.
It filters by the username which is stored when the user logs in.
This is used for validating a new module that's been entered.
It also finds the user's name to display that as a welcome message.
- Nikk Williams
<?php
	$username = $_GET['username'];
    $sql="SELECT * FROM UserTable WHERE username='$username'";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>