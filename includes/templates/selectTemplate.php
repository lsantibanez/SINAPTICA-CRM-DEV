<?php

include_once("../../class/new_email/Template.php");

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {

    $data = json_decode(file_get_contents('php://input'), true);

    $id = $_GET['id'] ?? null;
    $enable = $data['campaignId'] ?? null;

}else {
    echo json_encode(['success' => false, 'message' => 'Método no soportado,Solo PATCH']);
}