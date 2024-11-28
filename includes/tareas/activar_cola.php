<?php
include("../../class/tareas/tareas.php");
include("../../class/db/DB.php");
$tareas = new Tareas();
$tareas->activarCola($_POST['id']);
?>