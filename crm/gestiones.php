<?PHP
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/crm/crm.php");
include("../class/global/global.php");
$objetoSession = new Session('1,3,4,2,100',false);
$crm = new crm();
$objetoSession->crearVariableSession($array = array("idMenu" => "crm,vdi,mges"));
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
                    <h1 class="page-header text-overflow">Mis Gestiones</h1>
                </div>
                <ol class="breadcrumb">
                    <li><a href="#">Gestión</a></li>
                    <li><a href="#">Operaciones</a></li>
                    <li class="active">Mis Gestiones</li>
                </ol>
                <div id="page-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel" id="demo-panel-collapse" class="collapse in">
                                <div class="panel-heading">
                                    <h3 class="panel-title bg-primary"><i class="fa fa-phone"></i> Mis Gestiones</h3>
                                </div>
                                <div class="panel-body ">
                                    <div id='tabla-mis-gestiones'></div>
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
    <script src="../js/crm/operaciones.js"></script>

</body>
</html>
