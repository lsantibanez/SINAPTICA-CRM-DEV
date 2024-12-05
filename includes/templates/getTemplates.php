<?php

include_once("../../class/new_email/Template.php");

$template = new Template();

$search = isset($_GET['search']) ? $_GET['search'] : null;

$response = $template->getAllTemplates($search);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);