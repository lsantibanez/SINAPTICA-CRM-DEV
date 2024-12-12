<?php

include_once("../../class/new_email/Campaign.php");

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
$id = (int) $_GET['id'] ?? null;
$status = $data['status'] ?? null;

if ($id === null) {
    echo json_encode([
        'success' => false,
        'message' => 'ID no proporcionado',
    ]);
    exit;
}

$campaign = new Campaign();
$response = $campaign->changeStatus($id,$status);

echo json_encode($response);