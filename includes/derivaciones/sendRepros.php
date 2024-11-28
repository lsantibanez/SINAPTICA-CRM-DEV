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
    $tipoRepros = "diario"; // diaria => Diaria ; mensual => Mensual
    $fecha = "20180831"; */

    $idMandante = $_POST["idMandante"];
    $tipoRepros = $_POST["TipoReprogramacion"]; // diaria => Diaria ; mensual => Mensual
    $fecha = $_POST["fecha"];

    $Mail = new Email();
    //$Mail->SendMailGeneral("HTML","ASUNTO","jonathanurbina92@gmail.com;info@nflag.io","200","0","jonathan@focoestrategico.cl;sales@nflag.io");
    $Reprogramaciones = $DerivacionesClass->sendRepros($idMandante,$tipoRepros,$fecha);
?>