<?php
    include_once("../../../class/estrategia/categoria_fono.php");
    include_once("../../../includes/functions/Functions.php");
    Prints_IncludeClasses("db");
    $id = $_POST['id'];
    $Categoriafono = new Categoriafono();
    $Categoria = $Categoriafono->deleteCategoria($id);
    echo json_encode($Categoria);
?>
