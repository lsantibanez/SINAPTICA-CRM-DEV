<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();
    $Username = $_POST["Username"];
    $Password = $_POST["Password"];

    $ReclutamientoClass->Username = $Username;
    $ReclutamientoClass->Password = $Password;
    $Login = $ReclutamientoClass->Login();
    if($Login[0]){
        echo json_encode($Login);
    }else{
        echo false;
    }

    $_SESSION["MM_UserGroup"] = "";
    $_SESSION["cedente"] = "";
    $_SESSION["MM_Username"] = "";
    $_SESSION["idMenu"] = "";
    $_SESSION["anexo_foco"] = "";
    $_SESSION["logo"] = "";
    $_SESSION["nombreLogo"] = "";
    $_SESSION["id_usuario"] = "";
?>