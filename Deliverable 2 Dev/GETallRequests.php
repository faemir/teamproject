<?php

    $id = $_GET["id"];
    $sql="SELECT * FROM EntryRequestTable, ModuleTable";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>