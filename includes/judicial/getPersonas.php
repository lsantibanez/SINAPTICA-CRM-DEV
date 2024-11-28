<?php

    include_once("../functions/Functions.php");
    include_once("../../class/judicial/judicialClass.php");
    QueryPHP_IncludeClasses("db");
    $Judicial = new Judicial();
    $Personas = $Judicial->getPersonas();
    $ToReturn = "";
    foreach($Personas as $Persona){
    	$ToReturn .= "<option value='".$Persona["Rut"]."-".$Persona["Numero_Operacion"]."'>".$Persona["Numero_Operacion"]." - ".utf8_encode($Persona["Nombre_Completo"])."</option>";
    }
    echo $ToReturn;

?>