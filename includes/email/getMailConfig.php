<?php 
    include_once("../functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("email");
    
    $tipoModulo = $_POST["tipoModulo"];
    $db = new Db();
    $config = new opciones();
    $opciones = $config->configvalues("",$tipoModulo);
    echo json_encode($opciones);
?>