<?php
    include_once("../../functions/Functions.php");
    Prints_IncludeClasses("db");
    include_once("../../../class/admin/conf_inhabilitaciones.php");
    $ConfInhabilitacion = new ConfInhabilitacion();

    $Inhabilitaciones = $ConfInhabilitacion->getInhabilitaciones();
    echo json_encode($Inhabilitaciones);
?>