<?php 
include("../../class/db/DB.php");
include("../../class/estrategia/estrategias.php");
if(isset($_POST['ver_agenda'])){
    $ver_agenda = 1;
}else{
    $ver_agenda = 0;
}

if(isset($_POST['Categorias'])){
    $Categorias = $_POST['Categorias'];
}else{
    $Categorias = 0;
}

if(isset($_POST['TipoCategoria'])){
    $TipoCategoria = $_POST['TipoCategoria'];
}else{
    $TipoCategoria = 0;
}

if(isset($_POST['idUserCautiva'])){
    $idUserCautiva = $_POST['idUserCautiva'];
}else{
    $idUserCautiva = 0;
}

if(isset($_POST['comentario'])){
    $comentario = $_POST['comentario'];
}else{
    $comentario = 0;
}

$Estrategia = new Estrategia();
echo $Estrategia->Terminal($_POST['IdTerminal'],$_POST['Check'],$Categorias,$TipoCategoria,$ver_agenda,$idUserCautiva,$comentario);
?>    