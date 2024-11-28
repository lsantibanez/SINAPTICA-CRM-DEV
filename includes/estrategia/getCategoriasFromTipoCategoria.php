<?php 
    include("../../class/estrategia/estrategias.php");
    include("../../class/db/DB.php");
    $Estrategia = new Estrategia();
    $Tipo = $_POST['Tipo'];
    $ToReturn = $Estrategia->getCategoriasFromTipoCategoria($Tipo);
    echo $ToReturn;
?>    