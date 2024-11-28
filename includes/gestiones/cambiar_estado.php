<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/gestiones/cerrar.php");
    QueryPHP_IncludeClasses("db");
    $GestionClass= new Cerrar();
    echo json_encode($GestionClass->cerrar_gestion($_POST['id'],$_POST['observacion'],$_POST['nivel1'],$_POST['nivel2'],$_POST['nivel3'],$_POST['rut'],$_POST['r1'],$_POST['r2'],$_POST['r3'],$_SESSION['MM_Username'],$_SESSION['cedente']));
?>