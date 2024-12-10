<?php

include_once("../../class/new_email/Template.php");

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);

    $templateId = (int)$data['templateId'] ?? null;
    $data_email_id = (int)$data['dataEmailId'] ?? null;

    $template = new Template();
    $response = $template->asignCustomVariablesTemplate($templateId,$data_email_id);

    echo json_encode($response);
}else {
    echo json_encode(['success' => false, 'message' => 'Método no soportado,Solo POST']);
}