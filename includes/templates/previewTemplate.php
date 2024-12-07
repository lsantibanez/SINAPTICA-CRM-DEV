<?php
include_once("../../class/new_email/Template.php");

$template = new Template();

$templateId = $_POST['templateId'] ?? null;
$campaignId = $_POST['campaignId'] ?? null;

$response = $template->asignCustomVariablesTemplate($templateId,$campaignId);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);