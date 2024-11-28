<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/trabajador/trabajador.php");
    QueryPHP_IncludeClasses("db");
    $Trabajador = new Trabajador(); 
    $trabajadores = $Trabajador->GetListarTrabajadores();
    $ToReturn = "<option value=''>Seleccione</option>";
    foreach($trabajadores as $trabajador){
        if($trabajador["id_usuario"] == "" OR $trabajador["id_usuario"] == "0"){
            $ToReturn .= "<option value='".$trabajador["Actions"]."'>".$trabajador["Nombre"]."</option>";
        }
    }
    echo $ToReturn;
?>