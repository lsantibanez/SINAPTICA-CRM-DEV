<?php
include_once("../../class/sms/CampaignSms.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $request = [
        'id' => $_POST['id'] ?? null,
        'name' => $_POST['name'] ?? null,
        'phone' => $_POST['phone'] ?? null,
        'identity' => $_POST['identity'] ?? null,
        'message' => $_POST['message'] ?? null,
        'file' => $_FILES['file'] ?? null
    ];

    $sms = new CampaignSms();

    $response = $sms->insertOrUpdate($request);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}