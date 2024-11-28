<?php
include("../../class/db/DB.php");
include("../../class/estrategia/estrategia.php");
$cedente = $_POST['cedente'];
$nombreUsuario = $_POST['nombreUsuario'];
$estrategias = new Estrategia();
$estrategias->estrategiasInactivas($cedente,$nombreUsuario);
?>