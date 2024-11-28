<?php
    include_once("../../includes/functions/Functions.php");
    include_once("../../includes/email/PHPMailer-master/class.phpmailer.php");
	include_once("../../includes/email/PHPMailer-master/class.smtp.php");
    QueryPHP_IncludeClasses("email");
    QueryPHP_IncludeClasses("derivaciones");
    QueryPHP_IncludeClasses("db");
    $DerivacionesClass = new Derivaciones();

    /* $idMandante = $_POST["idMandante"]; */
    /* $idMandante = "4";
    $tipoAcuerdos = "diario"; // diaria => Diaria ; mensual => Mensual
    $fecha = "20180831"; */

    $idMandante = $_POST["idMandante"];
    $tipoAcuerdos = $_POST["TipoAcuerdo"]; // diario => Diaria ; mensual => Mensual
    $fecha = $_POST["fecha"];

    $Acuerdos = $DerivacionesClass->sendAcuerdos($idMandante,$tipoAcuerdos,$fecha);
?>