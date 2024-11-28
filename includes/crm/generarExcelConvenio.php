<?php 
include("../../class/crm/crm.php");
include("../../class/db/DB.php");
$crm = new crm();
$list = $crm->excelConvenio($_POST['id']);
ini_set("auto_detect_line_endings", true);
$header = [
"Numero Cuota",
"Fecha Vencimiento",
"Monto",
];
$file = fopen('../../file/convenio-detalle.csv', 'w');
fputcsv($file, $header);
 
// save each row of the data
foreach ($list as $row)
{	
    $row = array_map("utf8_decode", $row);
    fputcsv($file, $row);
}
 
// Close the file
fclose($file);
echo("listo");



?>    