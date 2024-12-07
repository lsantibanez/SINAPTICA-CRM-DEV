<?php

include_once("../../class/sms/Sms.php");

$template = new Sms();

$search = isset($_GET['search']) ? $_GET['search'] : null;

$response = $template->getAllSms($search);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);