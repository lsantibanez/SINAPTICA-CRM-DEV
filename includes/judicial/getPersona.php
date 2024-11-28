<?php

    include_once("../functions/Functions.php");
    include_once("../../class/judicial/judicialClass.php");
    QueryPHP_IncludeClasses("db");
    $Judicial = new Judicial();
    $Persona = $Judicial->getPersona($_POST['Rut']);
    echo json_encode($Persona);
?>