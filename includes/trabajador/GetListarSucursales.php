<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/trabajador/trabajador.php");
    QueryPHP_IncludeClasses("db");
    $trabajador = new Trabajador();
    $sucursales = $trabajador->getListarSucursales();
    $ToReturn = "<option value='0'>Seleccione</option>";
    foreach($sucursales as $sucursal){
        if($sucursal["nombreSucursal"] != ""){
            $ToReturn .= "<option value='".$sucursal["idSucursal"]."'>".$sucursal["nombreSucursal"]."</option>";
        }
    }
    echo $ToReturn;
    ?>