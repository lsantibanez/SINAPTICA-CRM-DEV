<?php

    include_once("../functions/Functions.php");
    include_once("../../class/judicial/judicialClass.php");
    QueryPHP_IncludeClasses("db");
    $Judicial = new Judicial();
    $Deuda = $Judicial->getDeuda($_POST['Id']);
    echo json_encode($Deuda);
?>