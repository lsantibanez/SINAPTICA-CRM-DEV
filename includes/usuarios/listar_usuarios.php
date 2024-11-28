<?php
if (!isset($_SESSION))  session_start();
/*
include_once("../../includes/functions/Functions.php");
QueryPHP_IncludeClasses("usuarios");
QueryPHP_IncludeClasses("db");
*/
include("../../class/usuarios/usuarios.php");
$idMandante = '';
$listarUsuarios = new Usuarios();
if (isset($_SESSION['mandante']) && !empty($_SESSION['mandante'])) $idMandante = $_SESSION['mandante'];
$listarUsuarios->listarUsuarios($_SESSION['MM_UserGroup'], $idMandante);
?>
