<?php

include_once("../../class/new_email/Template.php");

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);

    $id = (int)$_GET['id'] ?? null;
    $preview = $data['preview'] ?? null;

    $template = new Template();
    $response = $template->selectTemplate($id,$preview);

    echo json_encode($response);
}else {
    echo json_encode(['success' => false, 'message' => 'Método no soportado,Solo POST']);
}