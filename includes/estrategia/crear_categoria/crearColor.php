<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/estrategia/estrategia.php");
    Prints_IncludeClasses("db");
    $estrategia = new Estrategia();
    $estrategia->crearColor($_POST['color'],$_POST['nombre'],$_POST['comentario']);
?>
