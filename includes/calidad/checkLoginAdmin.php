<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/usuarios/hash.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("session");
    
    $userName = $_POST["userName"];
    $password = $_POST["password"];
    $Level = $_POST["Level"];
    
    $SessionClass = new Session("","");
    $ToReturn = $SessionClass->checkLogin($userName,$password,$Level);
    
    echo json_encode($ToReturn);
?>