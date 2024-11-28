<?php
    include_once("../../includes/functions/Functions.php");
    require("../../includes/email/PHPMailer-master/class.phpmailer.php"); 
	require("../../includes/email/PHPMailer-master/class.smtp.php"); 
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("email");
    QueryPHP_IncludeClasses("reclutamiento");
    $ReclutamientoClass = new Reclutamiento();
    $Preguntas = $_POST["Preguntas"];
    $TestFinalizado = $_POST["TestFinalizado"];
    $Prueba = $ReclutamientoClass->getPruebaActiva();
    switch($Prueba['id_tipotest']){
        case '1':
            $ReclutamientoClass->insertCalificacion($Preguntas,$TestFinalizado);
        break;
        case '2':
            $ReclutamientoClass->insertCalificacionCompetencias($Preguntas,$TestFinalizado);
        break;
        case '3':
            $ReclutamientoClass->insertCalificacionPersonalidad($Preguntas,$TestFinalizado);
        break;
    }
?>