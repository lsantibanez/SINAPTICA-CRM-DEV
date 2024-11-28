<?php
require_once __DIR__.'/../class/menu/menu.php';
require_once __DIR__.'/../class/db/DB.php';
// envio el nivel de usuario y el nom del menu donde estoy parada
$objetoMenu = new Menu(explode( ',' ,$_SESSION['idMenu']),$_SESSION['MM_UserGroup']);
$objetoMenu->crearMenu();
?>