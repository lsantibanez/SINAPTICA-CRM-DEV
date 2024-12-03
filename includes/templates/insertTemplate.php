<?php
include_once("../../class/new_email/Template.php");

$template = new Template();

$request = json_decode(file_get_contents('php://input'), true);

$response = $template->insert($request);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);