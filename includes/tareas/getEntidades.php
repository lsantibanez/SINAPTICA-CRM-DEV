<?php
    include("../../includes/functions/Functions.php");
    QueryPHP_IncludeClasses("tareas");
    QueryPHP_IncludeClasses("db");
    $tareas = new Tareas();
    $TipoEntidad = $_POST['tipoEntidad'];
    if(isset($_POST['ArrayIds'])){
        $Array = implode(",",$_POST['ArrayIds']);
    }else{
        $Array = '';
    }
    $Options = $tareas->getEntidades($TipoEntidad,$Array);
    echo $Options;
?>