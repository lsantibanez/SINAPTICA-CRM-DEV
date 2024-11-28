<?php
include("../../class/usuarios/usuarios.php");
$objetoUsuario = new Usuarios();
$response = $objetoUsuario->saveData($_POST);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response); 
?>