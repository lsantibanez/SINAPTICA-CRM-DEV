<?php

include_once("../../class/new_email/Template.php");

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $campaignId = (int) $_GET['id'] ?? null;

    $template = new Template();
    $response = $template->getTopDataEmails($campaignId);

    echo json_encode($response);
}else {
    echo json_encode(['success' => false, 'message' => 'Método no soportado,Solo POST']);
}