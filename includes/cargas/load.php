<?php
include_once '../../class/Cargas.php';
$cargas= new Cargas();
//$loadId = trim($_POST['id']);
$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: application/json; charset=utf-8');
echo json_encode($cargas->loadData($data['id']));
?>