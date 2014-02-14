<?php

    $id = $_GET["id"];
    $sql="SELECT modulecode, moduletitle FROM ModuleTable WHERE departmentid= '$id'";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>