<?php
  include("../class/db/DB.php");
  $db = new DB();
  include('../class/session/session.php');
  $objetoSession = new Session('1,2,3,4,5,6',false);
  // ** Logout the current user. **
  $objetoSession->creaLogoutAction();
  if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
  {
    //to fully log out a visitor we need to clear the session varialbles
      $objetoSession->borrarVariablesSession();
      //$objetoSession->logoutGoTo("../index.php");
  }
  $objetoSession->creaMM_restrictGoTo();
  $id_cedentes = "";
  $sql = $db->select("SELECT Id_Cedente FROM mandante where id='".$_POST['mandante']."'");
  foreach($sql as $row){
    $id_cedentes = $row["Id_Cedente"];
  }
  $array = array("mandante" => $_POST['mandante'], "mandante_cedentes" => $id_cedentes);
  $objetoSession->crearVariableSession($array);
  header('Location: ../cedente.php');
?>
