<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    $ToReturn = $DerivacionesClass->updateCorreosCC($_POST['correosCC']);
    echo $ToReturn;
?>