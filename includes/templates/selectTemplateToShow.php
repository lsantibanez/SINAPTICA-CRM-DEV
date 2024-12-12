<?php

include_once("../../class/new_email/Template.php");

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Error en la decodificación del JSON']);
        exit;
    }

    $dataEmail = $data['dataEmail'] ?? null;
    $template = $data['template'] ?? null;

    if ($dataEmail && $template) {
        $templateObj = new Template();
        $response = $templateObj->asignCustomVariablesTemplate($template, $dataEmail);
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'Faltan parámetros en el payload']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Método no soportado, solo POST']);
}
