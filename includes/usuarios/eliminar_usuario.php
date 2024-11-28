<?php
include("../../class/usuarios/usuarios.php");
include("../../class/db/DB.php");
$objetoUsuario = new Usuarios();
$objetoUsuario->eliminarUsuarios($_POST['id_usuario']);
 ?>
