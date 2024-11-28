<?php
    // ob_start();
    include("../../includes/functions/Functions.php");
    require("../../includes/email/PHPMailer-master/class.phpmailer.php");
	require("../../includes/email/PHPMailer-master/class.smtp.php"); 
    QueryPHP_IncludeClasses("db");
    QueryPHP_IncludeClasses("email");
    include("../../class/crm/crm.php");
    include_once("../../class/email/verifyEmail.php");
    //include('../../db/connect.php');
    $crm = new crm();
    $db = new DB();
    $envio = new Email();
    $opcionesEnvio = new opciones();
    $Correos = $_POST["Correos"];
    $Correos = explode(",",$Correos);
    //$Correos = array("jonathanurbina92@gmail.com");
    $Facturas = isset($_POST["Facturas"]) ? $_POST["Facturas"] : array();
    $Rut = $_POST['Rut'];
    $idTemplate = $_POST['idTemplate'];
    $estrategia = $_POST['Queue'];
    $cedente = $_SESSION["cedente"];
    if($idTemplate != 's'){
        $Template = $opcionesEnvio->getTemplateFactura($idTemplate);
        $Archivos = '';
    }else{
        $idTemplate = 0;
        $Template = array('Template' => $_POST['Html'], 'nombre' => $_POST['Nombre'], 'asunto' => $_POST['Asunto'], 'result' => true);
        
        $CantArchivos = $_POST['CantArchivos'];
        if($CantArchivos){
            $Archivos = array(); 
            for($i = 1; $i <= $CantArchivos; $i++){
                $NombreArchivo = "Archivo_".$i;
                if(isset($_FILES[$NombreArchivo])){
                    $Archivos[] = $_FILES[$NombreArchivo];
                }
            }
        }else{
            $Archivos = '';
        }
    }

    $Config = $opcionesEnvio->configvalues($cedente,"2");
    $ToReturn = "";
    if($Config["result"]){
        if($Template["result"]){
            $html = $Template["Template"];
            $nomTemplate = $Template["nombre"];
            $nomAsunto = $Template["asunto"];
            $codigo = $envio->gen_code();
            $html = $envio->bodyEmail($codigo, $html);
    
            $query_ve = "SELECT variable FROM Variables where id_cedente = '".$cedente."'";
            $variables_existentes = $db->select($query_ve);

            $Variables = array();
            if($variables_existentes){
                foreach($variables_existentes as $var_e){
                    $var = $var_e['variable'];
                    $uso = strpos($html, '['.$var.']');
                    if($uso !== false){
                        array_push($Variables,$var);
                    }
                }
            }

            if($idTemplate != 's'){
                $nomTemplate = $Template["nombre"];
            }else{
                $nomTemplate = 'Sin Template';
            }
            if($Archivos){
                $adjuntar = 1;
            }else{
                $adjuntar = 0;
            } 
            $usuario = $_SESSION["id_usuario"];  
            $cantidad = count($Correos);
            $query = "	INSERT INTO 
                            envio_email (estrategia, cantidad, offset, status, asunto, html, actualizacion, 
                                            adjuntar, Id_Cedente, tabla_email, id_usuario, fechaProceso, template, codigo) 
                            VALUES ('".$estrategia."', '".$cantidad."', '".$cantidad."', '1', '".$nomTemplate."', '".$html."', NOW(), 
                                    '".$adjuntar."', '".$cedente."', '0', '".$usuario."',NOW(), 
                                    '".$idTemplate."', '".$codigo."')";
            $id_envio = $db->insert($query);
            if($id_envio){
                $info = array();
                $adjuntos = array();
                $correos_array = array();
        
                foreach($Correos as $Correo){
                    if($Correo){
                        $Estado = $crm->guardarGestionCorreo($Correo,$Facturas,$Rut,$nomTemplate,$id_envio);
                        if($Estado == '1'){
                            $correos_array[] = $Correo;
                            $info[$Correo] = array();
                            $adjuntos[$Correo] = array();
                            if(count($Variables) > 0){
                                foreach ($Variables as $var){
                                    $info[$Correo][$var] = $envio->get_var_value($Rut,$var,$cedente);
                                }
                            }
                            $info[$Correo]["Rut"] = $Rut;
                            $adjuntos[$Correo] = $Facturas;
                        }
                    }
                }
                $info['adjuntos'] = array();
                switch($_SESSION['tipoSistema']){
                    case "1":
                        $info['adjuntos'] = $adjuntos;
                    break;
                }

                $info['variables'] = $Variables;
                $info['Archivos'] = $Archivos;

                $envio_result = $envio->SendMail($html,$nomAsunto,$correos_array,$info,$cedente,"2");
                if($envio_result){       
                    $ToReturn = "1";            
                }else{
                    $ToReturn = "0";
                }
        
                /* print_r($Variables);
                print_r($Correos);
                print_r($Facturas);
                print_r($info); */
            }
        }else{
            $ToReturn = "2";
        }
    }else{
        $ToReturn = "3";
    }
    // ob_end_clean();
    echo $ToReturn;
    //Salida:
        //3 => No tiene correo configurado
        //2 => No existe template tipo factura
        //1 => Correo Enviado
        //0 => Fallo al enviar correo
?>