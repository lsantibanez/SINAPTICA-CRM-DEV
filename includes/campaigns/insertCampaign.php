<?php

include_once("../../class/new_email/Campaign.php");

$campaign = new Campaign();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $request = [
        'name' => $_POST['name'] ?? null,
        'date' => $_POST['date'] ?? null,
        'programmed' => $_POST['programmed'] ?? null,
        'subject' => $_POST['subject'] ?? null,
        'sender' => $_POST['sender'] ?? null,
        'template_id' => $_POST['template_id'] ?? null,
        'emailResponse' => $_POST['emailResponse'] ?? null,
        'file' => $_FILES['file'] ?? null
    ];

    $campaign = new Campaign();

    $response = $campaign->insert($request);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}