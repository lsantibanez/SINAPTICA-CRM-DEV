<?PHP 
require_once('../class/db/DB.php'); 
include("../class/crm/crm.php");

$pantalla = new crm();
$pantalla->Pantalla($_GET['id'],$_GET['rut'],$_GET['cedente'],$_GET['fono'],$_GET['usuario']);
?>