<?php
require_once('../../class/db/DB.php');
include("../../class/session/session.php");
include("../../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$idCedente = $_POST['idCedenteMandante'];
$idMandanteAdmin = $_POST['idMandanteAdmin'];
echo $objetoSession->crearVariableSession($array = array("cedente" => $idCedente,"mandante" => $idMandanteAdmin));
?>