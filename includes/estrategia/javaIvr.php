<?php
include("../../class/db/DB.php");
include("../../class/estrategia/estrategia.php");

$estrategia = new Estrategia();
$estrategia->javaGetIvr($_POST['data']);

?>
