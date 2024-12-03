<?php

include_once("../../class/new_email/Template.php");

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];
$enable = $data['enable'];

if (!$id || $enable === null) {
    echo json_encode([
        'success' => false,
        'message' => 'ID o estado no proporcionado',
    ]);
    exit;
}

$template = new Template();
$response = $template->changeStatus($id, $enable);

echo json_encode($response);
