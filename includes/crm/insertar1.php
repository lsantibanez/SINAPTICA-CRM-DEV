<?php 
    include("../../class/crm/crm.php");
    include("../../class/db/DB.php");
    require("../../includes/email/PHPMailer-master/class.phpmailer.php"); 
    require("../../includes/email/PHPMailer-master/class.smtp.php"); 
    include("../../class/email/email.php");
    include("../../class/email/opciones.php");
    include("../../class/admin/conf_campos_gestion.php");
    $crm = new crm();
    
    $crm->insertar1($_POST['nivel1'],$_POST['nivel2'],$_POST['nivel3'],$_POST['comentario'],$_POST['rut'],$_POST['fono_discado'],$_POST['tipo_gestion'],$_POST['cedente'],$_POST['usuario_foco'],$_POST['lista'],$_POST['fecha_compromiso'],$_POST['monto_compromiso'],$_POST['tiempoLlamada'],$_POST['NombreGrabacion'],$_POST['asignacion'],$_POST['origen'],$_POST['facturas'],$_POST['fechaAgendamiento'],$_POST['Hablar'],$_POST['UrlGrabacion'], $_POST['canales'], $_POST['prioridades'], $_POST['ArrayCampos'], $_FILES, $_POST['derivacion'],$_POST['refacturar'],$_POST['monto'],$_POST['montoAgregado']);
?>    