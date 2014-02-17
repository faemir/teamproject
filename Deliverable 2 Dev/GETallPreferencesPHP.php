Finds the user's preferences from the database.
The user's username is stored when they login.
This is used to filter the preferences for that particular user.
These preferences are used for initial values in the adding requests page
and displaying chosen headers when viewing current requests.
- Nikk Williams
<?php
	$username = $_GET["username"];
    $sql="SELECT * FROM Preferences WHERE username='$username';";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>