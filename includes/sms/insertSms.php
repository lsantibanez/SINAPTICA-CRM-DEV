<?php
include_once("../../class/sms/CampaignSms.php");

$sms = new CampaignSms();

$request = json_decode(file_get_contents('php://input'), true);

$response = $sms->insert($request);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);