<?php

include_once("../../class/new_email/Template.php");

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? null;
$enable = $data['enable'] ?? null;

if($enable){
    $enable = 1;
}else{
    $enable = 0;
}

if (!$id || $enable === null) {
    echo json_encode([
        'success' => false,
        'message' => 'ID o estado no proporcionado',
    ]);
    exit;
}

$template = new Template();
$response = $template->updateStatus($id, $enable);

echo json_encode($response);
