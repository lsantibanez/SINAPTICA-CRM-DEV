<?php

include_once("../../class/new_email/Campaign.php");

$campaign = new Campaign();

if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    // Validar el archivo
    $file = $_FILES['file'];
    $response = $campaign->validateExcel($file);
} else {
    $response = [
        'success' => false,
        'message' => 'No se ha recibido un archivo válido. Por favor, intente nuevamente.'
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
