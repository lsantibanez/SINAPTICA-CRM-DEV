<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../class/trabajador/trabajador.php");
    QueryPHP_IncludeClasses("db");
    $trabajador = new Trabajador();
    $supervisores = $trabajador->getListarSupervisores();
    $ToReturn = "<option value='0'>Seleccione</option>";
    foreach($supervisores as $supervisor){
        if($supervisor["nombreSupervisor"] != ""){
            $ToReturn .= "<option value='".$supervisor["idSupervisor"]."'>".$supervisor["nombreSupervisor"]."</option>";
        }
    }
    echo $ToReturn;
    ?>