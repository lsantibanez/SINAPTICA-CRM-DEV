<?PHP
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/crm/crm.php");
include("../class/global/global.php");
$objetoSession = new Session('1,3,4,2,100',false);
$crm = new crm();
$objetoSession->crearVariableSession($array = array("idMenu" => "crm,ejec,vdi"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$objetoSession->creaMM_restrictGoTo();
$id_dial = isset($_SESSION['id']);


unset($_SESSION['correos']);
unset($_SESSION['correos_cc']);
unset($_SESSION['mfacturas']);
$validar = $_SESSION['MM_UserGroup'];
$cedente = $_SESSION['cedente'];
$NombreUsuarioFoco = $_SESSION['MM_Username'];
$nombre_usuario  = '';
$db = new DB();
$query = "SELECT nombre FROM Usuarios WHERE usuario = '".$NombreUsuarioFoco."' LIMIT 1";
$usuarios = $db->select($query);
if($usuarios){
    foreach($usuarios as $row){
        $nombre_usuario = $row["nombre"];
    }
}

$cola       = isset($_SESSION["cola"]) ? $_SESSION["cola"] : "";
$estrategia = isset($_SESSION["estrategia"]) ? $_SESSION["estrategia"] : "";
$asignacion = isset($_SESSION["asignacion"]) ? $_SESSION["asignacion"] : "";
$AccesoDirectoRut = isset($_SESSION["AccesoDirectoRut"]) ? $_SESSION["AccesoDirectoRut"] : "";
$user_dial       = isset($_SESSION["user_dial"]) ? $_SESSION["user_dial"] : "";
$pass_dial       = isset($_SESSION["pass_dial"]) ? $_SESSION["pass_dial"] : "";
$tipoSistema       = isset($_SESSION["tipoSistema"]) ? $_SESSION["tipoSistema"] : "";



unset($_SESSION["cola"]);
unset($_SESSION["estrategia"]);
unset($_SESSION["asignacion"]);
unset($_SESSION["AccesoDirectoRut"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Sinaptica | Software de Estrategia</title>
    <!--STYLESHEET-->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/nifty.min.css" rel="stylesheet">
    <link href="../css/multiple.css" rel="stylesheet"/>
    <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/chosen/chosen.min.css" rel="stylesheet">
    <link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
    <link href="../plugins/morris-js/morris.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="../css/global/global.css" rel="stylesheet">
    <link href="../plugins/bootstrap-validator/bootstrapValidator.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../plugins/dropzone/dropzone.css" rel="stylesheet">
    <!--Summernote [ OPTIONAL ]-->
    <link href="../plugins/summernote/summernote.min.css" rel="stylesheet">
    <link href="../plugins/jqx/css/jqx.base.css" rel="stylesheet">
    <style type="text/css">
        .select1
        {
            width: 100%;
            height: 30px;
            border: solid;
            border-color: #ccc;
            border-width: thin;
            background-color: #FFF;
        }
        .select2
        {
            width: 100%;
            height: 30px;
            border: solid;
            border-color: #ccc;
            border-width: thin;
            background-color: #F6F6F6;
        }

        #oculto
        {
            display: none;
        }
        #colas_mostrar
        {
            display: none;
        }
        #colas_mostrar2
        {
            display: none;
        }
        #mostrar_rut
        {
            display: none;
        }
        #script_cobranza_mostrar
        {
            display: none;
        }
        #busqueda_estrategia
        {
            display: none;
        }
        #busqueda_rut
        {
            display: none;
        }
        .adjuntar_boton {
            min-width: 30%;
            max-width: 30%;
        }
        #timer{margin:30px auto 0;width:100%;}
        #timer .container{display:table;background:#585858;color:#eee;font-weight:bold;width:100%;text-align:center;text-shadow:1px 1px 4px #999;}
        #timer .container div{display:table-cell;font-size:20px;padding:10px;width:10px;}
        #timer .container .divider{width:5px;color:#ddd;}
        .text6
        {
            width: 180px;
            height: 30px;
            border: none;
            text-align: center;
            background-color:transparent;
            text-align: left;
        }
        .fa-file-pdf-o
        {
            color: #FF4000;
        }
        #ProgressBar{
            display: none;
        }
        .modalFactura > .modal-dialog {
            width:70% !important;
        }
        .datepicker table tr td:first-child + td + td + td + td + td {
            color: red
        }
        .datepicker table tr td:first-child + td + td + td + td + td + td {
            color: red
        }
        html, body, #gaugeContainer {
            width: 100%;
            height: 100%;
        }
        .modal-footer{
            background-color: #FFFFFF;
            border-top: none;
        }
    </style>
</head>
<body>
<input type="hidden" id="Anexo" value="<?php echo $_SESSION['anexo_foco'];?>">
<input type="hidden" id="usuario" value="<?php echo $_SESSION['MM_Username'];?>">

<input type="hidden" id="id_dial"  name="id_dial" value="<?php echo $id_dial; ?>">
<input type="hidden" id="numero_cola"  name="numero_cola" value="">
<input type="hidden" id="prefijo"  name="prefijo" value="">
<input type="hidden" id="idc"  name="idc" value="0">
<input type="hidden" id="cedente"  name="cedente" value="0">
<input type="hidden" id="cortar_valor"  name="cortar_valor" value="0">
<input type="hidden" id="rut_ultimo"  name="rut_ultimo" value="0">
<input type="hidden" id="fono_discado"  name="fono_discado" value="0">
<input type="hidden" id="ultimo_fono"  name="ultimo_fono" value="0">
<input type="hidden" id="duracion_llamada"  name="duracion_llamada" value="0">
<input type="hidden" id="user_dial"  name="user_dial" value="<?php echo $user_dial;?>">
<input type="hidden" id="pass_dial"  name="pass_dial" value="<?php echo $pass_dial;?>">
<input type="hidden" id="nombre_usuario_foco"  name="nombre_usuario_foco" value="<?php echo $_SESSION['MM_Username'];?>">
<input type="hidden" id="IdCedente" value="<?php echo $_SESSION['cedente'];?>">
<input type="hidden" id="NombreGrabacion" value="">
<input type="hidden" id="UrlGrabacion" value="">
<input type="hidden" id="CanalGrabacion" value="">
<input type="hidden" id="userGroup" value="<?php echo $_SESSION['MM_UserGroup']; ?>">

<input type="hidden" id="Hablar" value="">
<input type="hidden" id="cola" value="<?php echo $cola; ?>">
<input type="hidden" id="est" value="<?php echo $estrategia; ?>">
<input type="hidden" id="asig" value="<?php echo $asignacion; ?>">
<input type="hidden" id="AccesoDirectoRut" value="<?php echo $AccesoDirectoRut; ?>">

    <div id="container" class="effect mainnav-lg">
      <!--NAVBAR-->
      <!--===================================================-->
      <?php
      include("../layout/header.php");
      ?>
      <!--===================================================-->
      <!--END NAVBAR-->
        <div class="boxed">
            <div id="content-container">
                <div id="page-title">
                    <h1 class="page-header text-overflow">Modo Preview</h1>
                </div>
                <ol class="breadcrumb">
                    <li><a href="#">Gestión</a></li>
                    <li><a href="#">Demo</a></li>
                    <li class="active">Búsqueda Clientes</li>
                </ol>
                <div id="page-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel" id="demo-panel-collapse" class="collapse in">
                                <div class="panel-heading">
                                    <h3 class="panel-title bg-primary"><i class="fa fa-search"></i> Tipo de Busqueda</h3>
                                </div>
                                <div class="panel-body ">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                        <?php echo $cedente2; ?>
                                            <select class="selectpicker" id="seleccione_tipo_busqueda"  name="tipo_estrategia" data-live-search="true" data-width="100%">
                                                <option value="0">Seleccione Tipo Búsqueda</option>
                                                <option value="2">Nombre</option>
                                                <option value="3">Rut</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div id="busqueda_estrategia">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <div id="colas2">
                                                    <select class="selectpicker" id="tipo_estrategia" disabled="disabled" name="tipo_estrategia" data-live-search="true" data-width="100%">
                                                        <option value="">Seleccione Estrategia</option>
                                                    </select>
                                                </div>
                                                <div id="colas_mostrar2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <div id="grupo">
                                                    <select class="selectpicker" id="tipo_estrategia" disabled="disabled" name="tipo_estrategia" data-live-search="true" data-width="100%">
                                                        <option value="">Seleccione Grupo</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if($tipoSistema == '1'){ ?>
                                            <div class="col-sm-2">
                                        <?php }else{ ?>
                                            <div class="col-sm-3">
                                        <?php } ?>
                                            <div class="form-group">
                                                <div id="asignacion">
                                                    <select class="selectpicker" id="tipo_estrategia" disabled="disabled" name="tipo_estrategia" data-live-search="true" data-width="100%">
                                                        <option value="">Seleccione Asignacion</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if($tipoSistema == '1'){ ?>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-success btn-icon icon-lg fa fa-arrow-left" id="prev_rut" value=""></button>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <?php if($tipoSistema == '1'){ ?>
                                            <div class="col-sm-1">
                                        <?php }else{ ?>
                                            <div class="col-sm-2">
                                        <?php } ?>
                                            <div class="form-group">
                                                <div id="ocultar_rut" align="center">
                                                    <input type="text" id="next_rut" value="" disabled="disabled" background-color="#FFFFFF" color="#ff9900"  class="form-control">
                                                </div>
                                                <div id="mostrar_rut"></div>
                                            </div>
                                        </div>
                                        <?php if($tipoSistema == '1'){ ?>
                                            <div class="col-sm-1">
                                                <div class="form-group">
                                                    <center><button class="btn btn-success btn-icon icon-lg fa fa-arrow-right" id="next_rutSiguiente" value=""></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</center>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>


                                    <div id="busqueda_rut">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <select class="selectpicker" id="rut_buscado"  n data-live-search="true" data-width="100%">
                                                <option value="0">Seleccione Tipo Búsqueda</option>
                                                <option value="99518830">Rafael Garcia</option>
                                                <option value="99518831">Soporte</option>

                                            </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-primary btn-block" value="Buscar" id="buscar_rut">
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>    
                        <!-- aquiii cierra col 12 -->
                

                    <div class="col-md-4" id="script_cobranza">
                        <div class="panel" id="demo-panel-collapse" class="collapse in">
                            <div class="panel-heading">
                                <h3 class="panel-title bg-info">Datos de Cliente
                                </h3>
                            </div>
                            <div class="panel-body ">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                     
                                        <div id="script_cobranza_mostrar_2">
                                       
                                        </div>
                                        <!-- 
                                        <div id="script_cobranza_ocultar">
                                        Script de Cobranza.
                                        </div>
                                        -->
                                        
                                        <br>
                                        <div id="botones_modal" style="display:none;margin-top:10px">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <a id="mostrar_script" class="btn btn-primary" style="display:none;">Script Completo</a>
                                                    <a id="mostrar_politicas" class="btn btn-purple" style="display:none;">Politicas</a>
                                                    <a id="mostrar_medios_pago" class="btn btn-warning" style="display:none;">Medios de Pago</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8" id="datos_cliente">
                        <div class="panel" >
                            <div class="panel-heading bg-primary">
                                <div class="panel-control ">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#demo-tabs-box-1" data-toggle="tab">Teléfonos <button class="btn btn-icon icon-lg fa fa-plus-square" id="nuevo_telefono" disabled="disabled" value=""></button></a></li>
                                        <li><a href="#demo-tabs-box-3" data-toggle="tab">Correos<button class="btn btn-icon icon-lg fa fa-plus-square" id="nuevo_correo" disabled="disabled" value="" data-toggle='modal' data-target='#AggCorreoModal'></button></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="demo-tabs-box-1">
                                        <div id="mostrar_fonos_ocultar">Fonos de Cliente.</div>
                                        <div id="mostrar_fonos"></div>
                                    </div>
                                    <div class="tab-pane fade" id="demo-tabs-box-3">
                                    <div id="mostrar_correo_ocultar">Correos de Cliente.</div>
                                        <div id="mostrar_correo"></div>
                                    </div>
                                    <div class="tab-pane fade" id="demo-tabs-box-4">
                                    <div id="mostrar_correo_ocultar_cc">Correos CC</div>
                                        <div id="mostrar_correo_cc"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 hidden-sm hidden-md" id="scoring" style="display:none">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title bg-info">Scoring
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div id="gaugeContainer">
                                    <div id="gauge" style="cursor:not-allowed"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="tab-base">
                            <!--Nav Tabs-->
                            <ul class="nav nav-tabs" id="nav-tabs"></ul>
                             <!--Tabs Content-->
                            <div class="tab-content" id="tab-content"></div>
                        </div>
                    </div>
                    <!--
                    <div class="col-md-3">
                        <div class="panel" id="demo-panel-collapse" class="collapse in">
                            <div class="panel-heading">
                                <h3 class="panel-title bg-primary">Respuesta Rapida</h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div id="respuesta_rapida_ocultar">
                                            <select class="selectpicker"  disabled="disabled" data-live-search="true" data-width="100%">
                                                <option value="">Seleccione</option>
                                            </select>
                                        </div>
                                        <div id="respuesta_rapida">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    -->
                    <div class="col-md-12">
                        <div class="panel" >
                            <div class="panel-heading">
                                <h3 class="panel-title bg-primary">Respuesta / Acción Integral</h3>
                            </div>
                            <div class="panel-body">
                                <?php
                                    switch($tipoSistema){
                                        case "1":
                                        case "2":
                                            $ShowButton = true;
                                            if($_SESSION['MM_UserGroup'] == "4"){
                                                $ShowButton = $crm->isAuthorizedModule("envioCorreoCRM");
                                                $ShowButton = $ShowButton["result"];
                                            }
                                            if($ShowButton){
                                                ?>
                                                    <div class="row" style="margin-bottom: 10px;">
                                                        <div class="col-sm-12">
                                                            <button class="btn btn-purple" id="mostrarTemplates">Enviar Correo</button>
                                                            <button class="btn btn-warning" id="mostrarTemplatesSMS">Enviar SMS</button>
                                                        </div>
                                                    </div>
                                                <?php
                                            }
                                        break;
                                    }
                                ?>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                        <label class="control-label">Nivel 1</label>
                                            <div class="nivel_1_ocultar">
                                                <select class="selectpicker" id="" disabled="disabled" name="tipo_estrategia" data-live-search="true" data-width="100%">
                                                    <option value="">Seleccione</option>
                                                </select>
                                            </div>
                                            <div class="nivel_1_mostrar">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label">Nivel 2</label>

                                            <div class="nivel_2_ocultar">
                                                <select class="selectpicker" id="tipo_estrategia" disabled="disabled" name="tipo_estrategia" data-live-search="true" data-width="100%">
                                                    <option value="">Seleccione</option>
                                                </select>
                                            </div>
                                            <div class="nivel_2_mostrar">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label">Nivel 3</label>

                                            <div class="nivel_3_ocultar">
                                                <select class="selectpicker" id="tipo_estrategia" disabled="disabled" name="tipo_estrategia" data-live-search="true" data-width="100%">
                                                    <option value="">Seleccione</option>
                                                </select>
                                            </div>
                                            <div class="nivel_3_mostrar">
                                            </div>
                                        </div>
                                    </div>
                                    <div id='grupo1'></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modal fade" tabindex="-1" role="dialog" id="Cargando">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="spinner loading"></div>
                            <h4 class="text-center">Procesando por favor espere...</h4>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!--MAIN NAVIGATION-->
        <!--===================================================-->
        <?php include("../layout/main-menu.php"); ?>
        <!--===================================================-->
        <!--END MAIN NAVIGATION-->
    </div>
    <!-- FOOTER -->
    <!--===================================================-->
    <footer id="footer">
        <!-- Visible when footer positions are fixed -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <div class="show-fixed pull-right">
            <ul class="footer-list list-inline">
            </li>
            </ul>
        </div>
    </footer>
    <!--===================================================-->
    <!-- END FOOTER -->
    <!-- SCROLL TOP BUTTON -->
    <!--===================================================-->
    <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
    <div class="modal"><!-- Place at bottom of page --></div>
        <!--===================================================-->

    <!--=========     Modal para Agregar Direccion    =====================-->
    <br>
    <div class="modal fade" id="AggCorreoModal"  role="dialog" >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Ingrese Nuevo Correo</h4>
            </div>
            <div class="modal-body">
            <?php
                //include("../includes/crm/ver_cargo.php");
                $crm->verCargo();
            ?>
            </div>
          <div class="modal-footer">
            <button type="button" id="AddCorreoN" class="btn btn-primary">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="AggCorreoModalcc"  role="dialog" >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Ingrese Nuevo Correo CC</h4>
            </div>
            <div class="modal-body">
            <?php
            //include("../includes/crm/ver_cargo2.php");
            $crm->verCargo2();
            ?>
            </div>
          <div class="modal-footer">
            <button type="button" id="AddCorreoNcc" class="btn btn-primary">Aceptar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalListadoConvenio"  role="dialog" >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body body_convenio">
                  <table class="table table-striped" id="tablaCuotas">
                    <thead>
                      <tr>
                        <th>N. Cuota</th>
                        <th>Fecha Venc.</th>
                        <th>Valor</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
            </div>
        </div>
      </div>
    </div>


    <div class="modal fade" id="modalConvenio" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Creando Convenio</h4>
                </div>
                <div class="modal-body">

                        <div class="row">
                            <div class="col-sm-4">
                                <label for="nombre-template">Monto</label>
                            </div>
                            <div class="col-sm-4 asunto-template">
                                <label for="asunto-template">Decuento</label>
                            </div>
                            <div class="col-sm-4 asunto-template">
                                <label for="asunto-template">Calculo</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input type="text" id="montoConvenio" class="form-control numberinput" >
                                </div>
                            </div>
                            <div class="col-sm-4 asunto-template">
                                <div class="form-group">
                                    <input type="number" min="1" max="5" id="DescuentoConvenio" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-4 asunto-template">
                                <div class="form-group">
                                    <input type="text" id="CalculoConvenio" class="form-control" disabled>
                                </div>
                            </div>
                        </div>                       
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="nombre-template">Dia actual</label>
                            </div>
                            <div class="col-sm-4 asunto-template">
                                <label for="asunto-template">Dias para una cuota</label>
                            </div>
                            <div class="col-sm-4 asunto-template">
                                <label for="asunto-template">Vencimiento Primera Cuota</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input type="text" id="hoyConvenio" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 asunto-template">
                                <div class="form-group">
                                    <input type="number" min="1" max="90" id="diasConvenio" class="form-control numberinput" >
                                </div>
                            </div>
                            <div class="col-sm-4 asunto-template">
                                <div class="form-group">
                                    <input type="text" id="vencimientoConvenio" class="form-control" disableds>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="nombre-template">Cuotas</label>
                            </div>
                            <div class="col-sm-6 asunto-template">
                                <label for="asunto-template">Valor de cuotas</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="number" min="1" max="50" id="cuotasConvenio" class="form-control" >
                                </div>
                            </div>
                            <div class="col-sm-6 asunto-template">
                                <div class="form-group">
                                    <input type="text" id="ValorCuotas" class="form-control" disabled>
                                </div>
                            </div>
                        </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" id="GuardarConvenio">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalTemplate" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Seleccione Template</h4>
                </div>
                <div class="modal-body">

                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="input-group">
                                <select name="idTemplate" id="idTemplate" class="selectpicker" data-live-search="true" data-width="100%">
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div id="SinTemplate" style="display: none">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="nombre-template">Nombre</label>
                            </div>
                            <div class="col-sm-6 asunto-template">
                                <label for="asunto-template">Asunto</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" id="nombre-template" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6 asunto-template">
                                <div class="form-group">
                                    <input type="text" id="asunto-template" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div id="summernote"></div> 
                            </div> 
                            <div class="col-md-12">
                                <form id="file-up" class="dropzone">
                                    <div class="dz-default dz-message">
                                        <div class="dz-icon">
                                            <i class="demo-pli-upload-to-cloud icon-5x"></i>
                                        </div>
                                        <div>
                                            <span class="dz-text">Soltar archivos para cargar</span>
                                            <p class="text-sm text-muted">Haga clic para seleccionar manualmente</p>
                                        </div>
                                    </div>
                                </form>
                            </div>     
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" id="EnviarFacturaCorreos">Enviar Correo</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTemplateSMS" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Seleccione Template</h4>
                </div>
                <div class="modal-body">

                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="input-group">
                                <select name="idTemplateSMS" id="idTemplateSMS" class="selectpicker" data-live-search="true" data-width="100%">
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div id="SinTemplateSMS" style="display: none">
                        <div class="row">
                            <div class="col-sm-12">
                                <textarea class="form-control" name="ContenidoSMS" id="ContenidoSMS" rows="4" maxlength="160"></textarea>
                            </div>     
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" id="EnviarSMS">Enviar SMS</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFono" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Detalle Fono</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="mostrar_detalle_fono"></div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    </div>
    <script id="modalDeuda" type="text/template">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <table id="gestionesFactura" style="width:100%">
                        <thead>
                            <tr>
                                <th>Fecha Gestión</th>
                                <th>Nombre Ejecutivo</th>
                                <th>Fono Discado</th>
                                <th>Respuesta</th>
                                <th>Sub Respuesta</th>
                                <th>Sub Respuesta</th>
                                <th>Fecha Compromiso</th>
                                <th>Monto Compromiso</th>
                                <th>Nº Factura</th>
                                <th>Observación</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </script>

    <div class="modal fade" id="modalScoring" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Detalle Scoring</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <table id="ScoringTable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Variable</th>
                                            <th>Scoring</th>
                                            <th>Porcentaje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="modalScript" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Script de Cobranza</h4>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12" id="script"></div>
                </div>

                <div class="modal-footer">
                    <!-- <button class="btn btn-primary" id="EnviarFacturaCorreos">Enviar Correo</button> -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPolitica" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Politicas</h4>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12" id="politica"></div>
                </div>

                <div class="modal-footer">
                    <!-- <button class="btn btn-primary" id="EnviarFacturaCorreos">Enviar Correo</button> -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMedioPago" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Medios de Pago</h4>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12" id="medio_pago"></div>
                </div>

                <div class="modal-footer">
                    <!-- <button class="btn btn-primary" id="EnviarFacturaCorreos">Enviar Correo</button> -->
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
	<script src="../plugins/morris-js/morris.min.js"></script>
    <script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/skycons/skycons.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../plugins/chosen/chosen.jquery.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/pace/pace.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../js/demo/ui-alerts.js"></script>
    <script src="../plugins/bootstrap-validator/bootstrapValidator.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/bootstrap-datetimepicker/moment.js"></script>   
    <script src="../plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="../js/demo/ui-modals.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/masked-input/jquery.maskedinput.min.js"></script>
    <script src="../plugins/bootstrap-validator/bootstrapValidator.min.js"></script>
    <script src="../js/demo/form-validation.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/dropzone/dropzone.min.js"></script>

    <!--Summernote [ OPTIONAL ]-->
    <script src="../plugins/summernote/summernote.min.js"></script>
    <script src="../js/email/summernote-ini.js"></script>
    <script src="../plugins/jqx/js/jqxcore.js"></script>
    <script src="../plugins/jqx/js/jqxchart.js"></script>
    <script src="../plugins/jqx/js/jqxgauge.js"></script>
    <script src="../plugins/jqx/js/jqxbuttons.js"></script>
    <script src="../js/crm/crm.js?v=<?php echo(rand()); ?>"></script>

</body>
</html>
