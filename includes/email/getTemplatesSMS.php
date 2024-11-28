<?php 
    include_once("../functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("email");

    $db = new Db();
    $opciones = new opciones();
    echo $opciones->templatesSMS();
?>