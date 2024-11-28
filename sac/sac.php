<?PHP
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/sac/sac.php");
include("../class/global/global.php");
$objetoSession = new Session('1,3,4,2,100',false);
$sac = new sac();
$objetoSession->crearVariableSession($array = array("idMenu" => "crm,vdi,sal"));
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


$clientes = $db->select("SELECT nombre,rut FROM operaciones.clientes LIMIT 50");
$tri = $db->select("SELECT nombre,sucursal,rut FROM operaciones.tripulacion ");
$super = $db->select("SELECT nombre,sucursal,rut FROM operaciones.supervisor ");

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
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
        #mostrar4
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
                    <h1 class="page-header text-overflow">Llamadas Salientes</h1>
                </div>
                <ol class="breadcrumb">
                    <li><a href="#">Gestión</a></li>
                    <li><a href="#">Operaciones</a></li>
                    <li class="active">Llamadas Salientes</li>
                </ol>
                <div id="page-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel" id="demo-panel-collapse" class="collapse in">
                                <div class="panel-heading">
                                    <h3 class="panel-title bg-primary"><i class="fa fa-search"></i> Tipo de Busqueda</h3>
                                </div>
                                <div class="panel-body ">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <select class="selectpicker" id="seleccione_tipo_busqueda"  name="tipo_estrategia" data-live-search="true" data-width="100%">
                                                <option value="0">Seleccione</option>
                                                <option value="1">Cliente</option>
                                                <option value="2">Rut</option>
                                                <option value="3">Contrato</option>
                                                <option value="4">Nombre Contacto</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="busqueda">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <div id="ocultar">
                                                    <select class="selectpicker" disabled="disabled" data-live-search="true" data-width="100%">
                                                        <option value="0">Seleccione</option>
                                                    </select>   
                                                </div> 
                                                <div id="mostrar">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <div id="ocultar2">
                                                    <select class="selectpicker" disabled="disabled" data-live-search="true" data-width="100%">
                                                        <option value="0">Seleccione</option>
                                                    </select>   
                                                </div> 
                                                <div id="mostrar2">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-primary btn-block" value="Buscar" id="buscar">
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>       
                    </div>
                    <div class="row">
                        <div class="col-md-6" id="datos_cliente">
                            <div class="panel" id="demo-panel-collapse" class="collapse in">
                                <div class="panel-heading">
                                    <h3 class="panel-title bg-primary"> Datos Demográficos</h3>
                                </div>
                                <div class="panel-body ">
                                    <div id="ocultar3">Datos de Cliente</div>
                                    <div id="mostrar3"></div>

                                </div>
                            </div>
                        </div>                
                        <div class="col-md-6" id="datos_cliente">
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
                                            <div id="ocultarFono">Teléfonos</div>
                                            <div id="mostrarFono"></div>
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
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel" id="demo-panel-collapse" class="collapse in">
                                <div class="panel-heading">
                                    <h3 class="panel-title bg-primary">Gestiones</h3>
                                </div>
                                <div class="panel-body ">
                                    <div id='ocultarGestion'>Gestiones.</div>
                                    <div id='mostrarGestion'></div>
                                </div> 
                            </div>
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel" id="demo-panel-collapse" class="collapse in">
                                <div class="panel-heading">
                                    <h3 class="panel-title bg-primary">Crear Gestión</h3>
                                </div>
                                <div class="panel-body ">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">Tipificación</label>
                                            <div id="ocultar4">
                                                <select class="selectpicker"   disabled="disabled"  data-live-search="true" data-width="100%">
                                                    <option value="0">Seleccione</option>
                                                </select>  
                                            </div>
                                            <div id="mostrar4">
                                                <select class="selectpicker" id="tipificacion"  name="tipificacion" data-live-search="true" data-width="100%">
                                                    <option value="0">Seleccione</option>
                                                    <option value="RECLAMO">RECLAMO</option>
                                                    <option value="SERVICIO ADICIONAL">SERVICIO ADICIONAL</option>
                                                    <option value="OPERACIONES">OPERACIONES</option>
                                                    <option value="COBRANZA">COBRANZA</option>
                                                    <option value="OTROS">OTROS</option>
                                                </select>  
                                            </div>
                                                                      
                                        </div>
                                    </div>    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Obervación:</label>
                                            <textarea id="observacion" name="observacion" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="control-label">Guardar Gestión:</label>
                                        <button class="form-control  btn btn-purple btn-icon icon-lg guardarGestion">Guardar Gestión</button>
                                    </div>
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

    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
	<script src="../plugins/morris-js/morris.min.js"></script>
    <script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/skycons/skycons.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
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
    <!--<script src="../plugins/dropzone/dropzone.min.js"></script>-->

    <!--Summernote [ OPTIONAL ]-->
    <script src="../plugins/summernote/summernote.min.js"></script>
    <script src="../js/email/summernote-ini.js"></script>
    <script src="../plugins/jqx/js/jqxcore.js"></script>
    <script src="../plugins/jqx/js/jqxchart.js"></script>
    <script src="../plugins/jqx/js/jqxgauge.js"></script>
    <script src="../plugins/jqx/js/jqxbuttons.js"></script>
    <script src="../js/sac/sac.js"></script>


</body>
</html>
