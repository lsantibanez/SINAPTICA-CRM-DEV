<?php    
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/trabajador/trabajador.php");
    QueryPHP_IncludeClasses("db");
    $Trabajador = new Trabajador();   
    $ToReturn = $Trabajador->muestraDatosGeneralesTrabajador($_POST['idTrabajador']);

    header('Content-type: application/json; charset=utf-8');
    echo json_encode($ToReturn);    

?>