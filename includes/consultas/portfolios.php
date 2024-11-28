<?php
include_once '../../class/Consultas.php';
$agentes = new Consultas();
header('Content-type: application/json; charset=utf-8');
echo json_encode($agentes->getPortfolios());
?>