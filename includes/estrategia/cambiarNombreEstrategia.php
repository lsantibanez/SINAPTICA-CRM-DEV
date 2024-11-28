<?php
//include("../../class/db/DB.php");
include("../../class/estrategia/estrategia.php");
$idEstrategia = $_POST["idEstrategia"];
$nombreEstrategia = $_POST["nombreEstrategia"];
$estrategia = new Estrategia();
$ToReturn = $estrategia->cambiarNombreEstrategia($idEstrategia,$nombreEstrategia);
echo json_encode($ToReturn);
?>