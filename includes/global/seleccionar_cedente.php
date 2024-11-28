<?php
include("../../class/global/cedente.php");
//include("../../class/db/DB.php");
$formCedente = new Cedente();
$formCedente->formCedente($_POST['cedente'], $_POST['mandante']);
?>
