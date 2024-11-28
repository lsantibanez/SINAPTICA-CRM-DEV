<?php
if(!isset($_SESSION)){
    session_start();
}
include("../../includes/functions/Functions.php");
include("../../class/tareas/tareas.php");
include("../../class/db/DB.php");

$tareas = new Tareas();
$tareas->asignarEstrategia($_POST['id'],$_SESSION['cedente']);
echo json_encode(utf8_ArrayConverter($tareas->mostrarEstrategia()));
?>