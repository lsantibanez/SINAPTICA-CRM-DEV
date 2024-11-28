<?php
include_once("../../includes/functions/Functions.php");
include_once("../../class/estrategia/estrategia.php");
QueryPHP_IncludeClasses("db");
$objeto = new Estrategia();
$objeto->desactivarEstrategia($_POST['idEstrategia']);
 ?>