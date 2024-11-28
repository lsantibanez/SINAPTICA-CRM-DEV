<?php
    require_once('../../class/db/DB.php');
	if(!isset($_SESSION)){
        session_start();
    }

    $db = new DB();
    $Tables = array();
    $Tables["Persona"] = false;
    $Tables["Deuda"] = false;
    $Tables["FonoCob"] = false;
    $Tables["Direcciones"] = false;
    $Tables["Mail"] = false;
    
    $resultado = $db->select("SELECT COUNT(*) AS NumRows FROM Persona_tmp WHERE Id_Cedente = '".$_SESSION['cedente']."'");
	if($resultado){
        $NumRows = $resultado[0]["NumRows"];
    }else{
        $NumRows = 0;
    }
    if($NumRows > 0){
        $Tables["Persona"] = true;
    }

    $resultado = $db->select("SELECT COUNT(*) AS NumRows FROM Deuda_tmp WHERE Id_Cedente = '".$_SESSION['cedente']."'");
	if($resultado){
        $NumRows = $resultado[0]["NumRows"];
    }else{
        $NumRows = 0;
    }
    if($NumRows > 0){
        $Tables["Deuda"] = true;
    }

    $resultado = $db->select("SELECT COUNT(*) AS NumRows FROM fono_cob_tmp WHERE Id_Cedente = '".$_SESSION['cedente']."'");
	if($resultado){
        $NumRows = $resultado[0]["NumRows"];
    }else{
        $NumRows = 0;
    }
    if($NumRows > 0){
        $Tables["FonoCob"] = true;
    }

    $resultado = $db->select("SELECT COUNT(*) AS NumRows FROM Direcciones_tmp WHERE Id_Cedente = '".$_SESSION['cedente']."'");
	if($resultado){
       $NumRows = $resultado[0]["NumRows"];
    }else{
        $NumRows = 0;
    }
    if($NumRows > 0){
        $Tables["Direcciones"] = true;
    }

    $resultado = $db->select("SELECT COUNT(*) AS NumRows FROM Mail_tmp WHERE Id_Cedente = '".$_SESSION['cedente']."'");
	if($resultado){
        $NumRows = $resultado[0]["NumRows"];
    }else{
        $NumRows = 0;
    }
    if($NumRows > 0){
        $Tables["Mail"] = true;
    }
    
    $resultado = $db->select("SELECT COUNT(*) AS NumRows FROM pagos_deudas_tmp WHERE Id_Cedente = '".$_SESSION['cedente']."'");
	if($resultado){
        $NumRows = $resultado[0]["NumRows"];
    }else{
        $NumRows = 0;
    }
    if($NumRows > 0){
        $Tables["Pagos"] = true;
    }

    echo json_encode($Tables);
 ?>