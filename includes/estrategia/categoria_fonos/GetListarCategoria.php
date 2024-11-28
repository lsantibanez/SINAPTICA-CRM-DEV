<?php
    include_once("../../../class/estrategia/categoria_fono.php");
    include_once("../../../includes/functions/Functions.php");
    Prints_IncludeClasses("db");
    $categoria = new Categoriafono();
    $colores = $categoria->getListarCategoria();
    $ToReturn = "<option value='0'>Seleccione</option>";
    foreach($colores as $color){
        if($color["categoria"] != ""){
            $ToReturn .= "<option value='".$color["idCategoria"]."'>".$color["categoria"]."</option>";
        }
    }
    echo $ToReturn;
?>