<?php
require_once('class/db/DB.php');
require_once('class/session/session.php');
$objetoSession = new Session('1,2,3,4,5,6',false);
$db = new DB();
$idCedente = (int) $_POST['cedente'];
$resultado = $db->select("SELECT Id_Cedente AS id, Nombre_Cedente AS nombre FROM Cedente WHERE Id_Cedente = {$idCedente}");
$nombreCedente = $resultado[0]['nombre'];
$_SESSION['mandante'] = (int) $_POST['mandante'];
$_SESSION['cedente'] = $idCedente;
$_SESSION['nombreCedente'] = $nombreCedente;
?>
