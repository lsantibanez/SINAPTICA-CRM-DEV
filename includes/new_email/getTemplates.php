<?php

include_once("../../class/new_email/Template.php");

$template = new Template();

$response = $template->getTemplates();

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);