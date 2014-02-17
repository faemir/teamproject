Brings in the username and encrypted password from the user
table for checking on login to ensure their details are valid
		Dan
<?php
    $sql = "SELECT Username, Password FROM UserTable";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;
?>