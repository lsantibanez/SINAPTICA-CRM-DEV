<?php
    include_once("../../../class/estrategia/categoria_fono.php");
    include_once("../../../includes/functions/Functions.php");
    Prints_IncludeClasses("db");
    $contacto = new Categoriafono();
    $contactos = $contacto->getListarTipoContacto();
    $ToReturn = "<option value='0'>Seleccione</option>";
    foreach($contactos as $con){
        if($con["contacto"] != ""){
            $ToReturn .= "<option value='".$con["idContacto"]."'>".$con["contacto"]."</option>";
        }
    }
    echo $ToReturn;
?>