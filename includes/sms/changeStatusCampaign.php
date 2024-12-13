<?php

include_once("../../class/sms/CampaignSms.php");

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
$id = (int) $_GET['id'];
$status = $data['status'] ?? null;

if ($id === null) {
    echo json_encode([
        'success' => false,
        'message' => 'ID no proporcionado',
    ]);
    exit;
}

$campaignSms = new CampaignSms();
$response = $campaignSms->changeStatus($id,$status);

echo json_encode($response);