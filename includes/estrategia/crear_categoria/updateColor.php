<?php
    include_once("../../../includes/functions/Functions.php");
    include_once("../../../class/estrategia/estrategia.php");
    Prints_IncludeClasses("db");
    $estrategia = new Estrategia();
    $estrategia->updateColor($_POST['color'],$_POST['nombre'],$_POST['comentario'],$_POST['id']);
?>
