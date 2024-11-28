<?php
include("../../class/session/session.php");
include("../../class/global/cedente.php");
include("../../class/crm/crm.php");
//include("../functions/Functions.php");
$objetoSession = new Session('1,2,3,4,5,6',false);
$CedenteClass = new Cedente();
$Array = array();
$Array["isAdmin"] = false;
$Array["focoConfig"] = getFocoConfig();

switch($_SESSION['MM_UserGroup'])
{
        case '1':
            $Array["isAdmin"] = true;
            break;
        case '5':
            break;
        default: 
            include("../../class/calidad/calidad.php");
            $CalidadClass = new Calidad();
            if(isset($_SESSION['cedente'])){
                $Array["id_cedente"] = $_SESSION['cedente'];
                $Array["nombre_cedente"] = utf8_encode($CedenteClass->getCedenteName($Array["id_cedente"]));
            }else{
                $Array["id_cedente"] = 0;
                $Array["nombre_cedente"] = '';
            }
            $Array["isMandante"] = $CalidadClass->isUserMandante();            
            $Array['Empiezo'] = $CalidadClass->Empiezo();
            $Array["have360Evaluation"] = $CalidadClass->CanEvaluate();
            if (isset($_SESSION['mandante'])){
                $Array["id_mandante"] = $_SESSION['mandante'];
                $Array["nombre_mandante"] = $CedenteClass->getMandanteName($Array["id_mandante"]);
            }
            $Array["isCalidadSystem"] = !$Array["isMandante"] && ($_SESSION['MM_UserGroup'] == "6") ? true : false;
            $Cedente = (isset($_SESSION['cedente']) && !empty($_SESSION['cedente']))? $CedenteClass->mostrarCedente($_SESSION['cedente']): [];
            if(isset($Cedente[0])) {
                $Cedente = $Cedente[0];
                $_SESSION['tipoSistema'] = $Cedente["tipo"];
                $Array["id_pais"] = $Cedente["id_pais"];
                $CrmClass = new crm();
                $Prefijo = $CrmClass->getFonoPrefix($Cedente["id_pais"]);
                $Array["FonoPrefix"] = array();
                $Array["FonoPrefix"]["Prefix"] = (isset($Prefijo["prefijo"]))? $Prefijo["prefijo"]: '';
                $Array["FonoPrefix"]["Length"] = (isset($Prefijo["fonoLength"]))? $Prefijo["fonoLength"] : 9;
                $Array["FonoPrefix"]["LengthOperation"] = (isset($Prefijo["lengthOperation"]))? $Prefijo["lengthOperation"]: 0;
            }
            $Array['tipoSistema'] = $Array["focoConfig"]['tipoSistema']; // $_SESSION['tipoSistema'];
            //$_SESSION['tipoSistema'] = $Array["focoConfig"]['tipoSistema'];
            break;  
    }

    $Array['isEjecutivo'] = isset($_SESSION['isEjecutivo']) ? true : false;
    if(!$Array["isAdmin"]){
        if(isset($_SESSION['personal'])){
            $Array['id_personal'] = $_SESSION['personal'];
            $Array['personalName'] = $_SESSION['personalName'];    
        }else{
            $Array['id_personal'] = 0;
            $Array['personalName'] = 0;
        }
    }
    $Array['username'] = $_SESSION['MM_Username'];
    $Array['idMenu'] = $_SESSION['idMenu'];
    $Array['anexo'] = $_SESSION['anexo_foco'];
    $Array['planDiscado'] = isset($_SESSION['planDiscado']) ? $_SESSION['planDiscado'] : 0;
    //$_SESSION['tipoSistema'] = $Array["focoConfig"]['tipoSistema'];

    $Array["logo"] = $_SESSION['logo'];
    $Array["nombreLogo"] = $_SESSION['nombreLogo'];
    $Array["tipoUsuario"] = $_SESSION["MM_UserGroup"];
    $Array["idUsuario"] = $_SESSION["id_usuario"];

    $Array = ValidarVariablesDeAdministrador($Array);

    $ip = $_SERVER['REMOTE_ADDR'];
    //$ip = "10.10.10.52";
    $filtered = filterPrivateIP($ip);
    if($filtered){
        $Array["serverNode"] = $Array["focoConfig"]["serverNodePublica"];
    }else{
        $Array["serverNode"] = $Array["focoConfig"]["serverNodePrivada"];
    }
    //$Array["serverNode"] = "191.102.35.99";
    $Array["portNode"] = $Array["focoConfig"]["portNode"];

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(utf8_ArrayConverter($Array));


    function ValidarVariablesDeAdministrador($Array)
    {
        if ($Array["isAdmin"]){
            $arrMenu = explode(",", $_SESSION['idMenu']);
            $nomMenu = array_pop($arrMenu);
            if (($nomMenu !== 'config_tb') && ($nomMenu !== 'per_gestc') && ($nomMenu !== 'conf_aRptDial')){
                unset($_SESSION['mandante']);
                unset($_SESSION['cedente']);
            }
            if (isset($_SESSION['mandante'])){
               //echo "fdsfsd";
                $Array["id_mandante"] = $_SESSION['mandante'];
                $Array["id_cedente"] = $_SESSION['cedente'];
            }
            
        }
        return $Array;
    }

    function filterPrivateIP($ip)
    {
        $ips = explode(',', $ip);
        foreach($ips as $ip){
            $ip = trim($ip);
            $ip = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
            if($ip !== FALSE){
                return $ip;
            }
        }
    }    
?>
