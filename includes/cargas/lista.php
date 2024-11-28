<?php
include_once '../../class/Cargas.php';
$cargas= new Cargas();

header('Content-type: application/json; charset=utf-8');
echo json_encode($cargas->getLista());
?>