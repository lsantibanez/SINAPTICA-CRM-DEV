<?php
include_once("../../class/new_email/Template.php");

$template = new Template();

$id = $_GET['id'];

$response = $template->unselectTemplate($id);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);