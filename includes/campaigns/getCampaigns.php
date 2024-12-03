<?php

include_once("../../class/new_email/Campaign.php");

$campaign = new Campaign();

$response = $campaign->select();

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
