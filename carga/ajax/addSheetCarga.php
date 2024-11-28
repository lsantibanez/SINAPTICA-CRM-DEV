<?php
    include_once("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("carga");
    QueryPHP_IncludeClasses("db");
    $CargaClass = new Carga();

    $NombreHoja = $_POST["NombreHoja"];
    $NumeroHoja = $_POST["NumeroHoja"];
    $TipoCarga = $_POST["TipoCarga"];
    $id_template = $_POST["id_template"];
    $ToReturn = array();
    $ToReturn = $CargaClass->addSheetCarga($NombreHoja,$NumeroHoja,$TipoCarga,$id_template);

    echo json_encode($ToReturn);

?>