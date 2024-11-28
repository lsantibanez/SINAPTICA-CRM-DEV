<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/global/cedente.php");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    $idMandante = $_POST["idMandante"];
    $fechaStart = $_POST["fechaStart"];
    $fechaEnd = $_POST["fechaEnd"];

    $CompromisosLaPolarCastigo = $DerivacionesClass->getAjusteDePagoLaPolarCastigo($idMandante,$fechaStart,$fechaEnd);
    echo json_encode($CompromisosLaPolarCastigo);
?>