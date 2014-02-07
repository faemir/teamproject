<?php

    $sql="SELECT * FROM EntryRequestTable INNER JOIN ModuleTable ON EntryRequestTable.ModuleCode=ModuleTable.ModuleCode";
    include "DBquery.php";
    $JSON = json_encode($res->fetchAll());
    echo $JSON;

?>