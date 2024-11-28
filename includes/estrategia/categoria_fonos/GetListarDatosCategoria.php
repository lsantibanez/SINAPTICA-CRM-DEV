<?php    
    include_once("../../../class/estrategia/categoria_fono.php");
    include_once("../../../includes/functions/Functions.php");
    Prints_IncludeClasses("db");
    $categoria = new Categoriafono(); 
    echo json_encode($categoria->getListarDatosCategoria($_POST['idCategoria']));
?>