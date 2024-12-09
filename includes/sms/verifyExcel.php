<?php
include_once("../../class/sms/CampaignSms.php");

$sms = new CampaignSms();

$request = json_decode(file_get_contents('php://input'), true);

$file = $_FILES['file'];

$response = $sms->verifyExcel($file);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);