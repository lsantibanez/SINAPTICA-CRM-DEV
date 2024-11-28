<?php
include("../../includes/functions/Functions.php");
include("../../class/tareas/tareas.php");
include("../../class/db/DB.php");

$tareas = new Tareas();
$tareas->asignarTipo($_POST['id'],$_POST['id_cedente']);
echo json_encode(utf8_ArrayConverter($tareas->mostrarTipo()));
?>