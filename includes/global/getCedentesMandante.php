<?php

include("../../class/db/DB.php");
$idMandante = (int) $_POST['mandante'];
$db = new Db();
$Sql = "SELECT c.Id_Cedente AS id, c.Nombre_Cedente AS nombre FROM Cedente AS c JOIN mandante_cedente AS j ON j.Id_Cedente = c.Id_Cedente WHERE j.Id_Mandante = {$idMandante} ORDER BY c.Nombre_Cedente ASC;";
$result = $db->select($Sql);
$resultHtml = '<option value="">Seleccone</option>';
if (count((array) $result) > 0) {
  foreach($result as $cedente) {
    $resultHtml .= '<option value="'.$cedente['id'].'">'.$cedente['nombre'].'</option>';
  }
}
echo $resultHtml;