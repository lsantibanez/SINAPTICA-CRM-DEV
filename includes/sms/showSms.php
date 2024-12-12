<?php

include_once("../../class/sms/CampaignSms.php");

$template = new CampaignSms();

$id = (int) $_GET['id'];

$response = $template->showSms($id);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);