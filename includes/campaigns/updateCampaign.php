<?php

include_once("../../class/new_email/Campaign.php");

$campaign = new Campaign();
    $request = [
        'id' => $_POST['id'] ?? null,
        'name' => $_POST['name'] ?? null,
        'date' => $_POST['date'] ?? null,
        'subject' => $_POST['subject'] ?? null,
        'sender' => $_POST['sender'] ?? null,
        'emailResponse' => $_POST['emailResponse'] ?? null,
        'file' => $_FILES['file'] ?? null
    ];

    $campaign = new Campaign();

    $response = $campaign->update($request);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);