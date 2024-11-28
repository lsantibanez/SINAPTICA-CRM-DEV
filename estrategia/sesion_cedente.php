<?php
include('../class/db/DB.php');
include('../class/session/session.php');
$db = new DB();
$sql = sprintf("SELECT tipo, planDiscado, Nombre_Cedente FROM Cedente WHERE Id_Cedente = %s", $db->GetSQLValueString($_POST['cedente'], "int"));
//$sql = "SELECT tipo, planDiscado, Nombre_Cedente FROM Cedente WHERE Id_Cedente = '".$_POST['cedente']."'";
$resultado = $db->select($sql);
$tipo = $resultado[0]['tipo'];
$plan = $resultado[0]['planDiscado'];
$nombreCedente = $resultado[0]['Nombre_Cedente'];
$objetoSession = new Session('1,2,3,4,5,6',false);
unset($_SESSION['empresa']);
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) && ($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $objetoSession->borrarVariablesSession();
  $objetoSession->logoutGoTo("../index.php");
}

$rolNivel = $_SESSION['MM_UserGroup']; 
if ($rolNivel == 2){ // 2 == Calidad
  $tipoSis = $rolNivel;
}else{
  $tipoSis = $tipo; // 0=Masivo, 1=Factura
}
$sqlLogo = "SELECT logo, nombre FROM logo WHERE tipoSistema = '".$tipoSis."'";
$resultLogo = $db->select($sqlLogo);
if($resultLogo){
  $logo = $resultLogo[0]["logo"];
  $nombreLogo = $resultLogo[0]["nombre"];
}else{
  $logo = '';
  $nombreLogo = '';
}

$objetoSession->creaMM_restrictGoTo();
$array = array("cedente" => $_POST['cedente'],"nombreCedente" => $nombreCedente, "planDiscado" => $plan, "tipoSistema" => $tipo, "nombreLogo" => $nombreLogo);
//$array = array("cedente" => $_POST['cedente'],"nombreCedente" => $nombreCedente, "planDiscado" => $plan, "tipoSistema" => $tipo, "logo" => $logo, "nombreLogo" => $nombreLogo);
if(isset($_POST['mandante'])){
  $array["mandante"] = $_POST['mandante'];
  $array["mandante_cedentes"] = $_POST['cedente'];
}
$objetoSession->crearVariableSession($array);
header('Location: ../dashboard/dashboard');
?>