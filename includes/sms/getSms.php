<?php

include_once("../../class/sms/CampaignSms.php");

$template = new CampaignSms();

$search = isset($_GET['search']) ? $_GET['search'] : null;

$response = $template->getAllSms($search);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);