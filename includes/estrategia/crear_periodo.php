<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/estrategia/periodo_gestion.php");
    QueryPHP_IncludeClasses("db");
    $PeriodoGestion = new PeriodoGestion(); 
    echo json_encode($PeriodoGestion->creaPeriodo($_POST['fechaInicio'], $_POST['fechaTermino'], $_POST['Descripcion'], $_POST['idCedente']));
?>