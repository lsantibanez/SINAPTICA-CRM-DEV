<?php

include_once("../../class/new_email/Campaign.php");

$campaign = new Campaign();

$id = $_GET['id'];

$response = $campaign->customVariables($id);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
