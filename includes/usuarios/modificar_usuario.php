<?php
include("../../class/usuarios/usuarios.php");
include("../../class/usuarios/hash.php");
include("../../class/db/DB.php");
$objetoHash = new Hash();
// verifico si el usuario cambio la clave para colocare el Hash
$modificoPassword = false;
$pass = $_POST['passwordUsu'];
if ($pass != "*.8//") // vienen diferentes cuando realmente son iguales
{
   $pass = $objetoHash->convertirHash($_POST['passwordUsu']);
   $modificoPassword = true; // ojooooooooooooooooo
}

$objetoUsuario = new Usuarios();
$objetoUsuario->modificarUsuario($_POST['usuario'],$pass,$_POST['nivelUsu'],$_POST['cargoUsu'],$_POST['cedenteUsu'],$_POST['emailUsu'],$_POST['valorocultoarescatar'],$_POST['idMandanteUsu'],$modificoPassword);
?>
