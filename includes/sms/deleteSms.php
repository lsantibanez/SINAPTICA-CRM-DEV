<?php

include_once("../../class/sms/CampaignSms.php");

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);

$id = $_GET["id"] ?? null;

if (!$id === null) {
    echo json_encode([
        'success' => false,
        'message' => 'ID no proporcionado',
    ]);
    exit;
}
$template = new CampaignSms();
$response = $template->delete($id);

echo json_encode($response);

