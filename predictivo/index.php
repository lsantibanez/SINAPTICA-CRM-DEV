<?PHP
    require_once('../class/db/DB.php');
    $db = new DB();
    require_once('../class/session/session.php');

    include("../class/crm/crm.php");
    include("../class/global/global.php");
    $Omni = new Omni;
    $Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));
    
    $objetoSession = new Session($Permisos,false); // 1,4
    $crm = new crm();
    $objetoSession->crearVariableSession($array = array("idMenu" => "crm,ejec,pred"));
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

//    $user_dial = $_SESSION['user_dial'];
//    $pass_dial = $_SESSION['pass_dial'];

    unset($_SESSION['correos']);
    unset($_SESSION['correos_cc']);
    unset($_SESSION['mfacturas']);
    $validar = $_SESSION['MM_UserGroup'];
    $cedente = $_SESSION['cedente'];
    $NombreUsuarioFoco = $_SESSION['MM_Username'];
    $nombre_usuario  = '';
    $query = "SELECT nombre FROM Usuarios WHERE usuario = '".$NombreUsuarioFoco."' LIMIT 1";
    $usuarios = $db->select($query);
    if($usuarios){
        foreach($usuarios as $row){
            $nombre_usuario = $row["nombre"];
        }
    }

    $user_dial       = isset($_SESSION["user_dial"]) ? $_SESSION["user_dial"] : "";
    $pass_dial       = isset($_SESSION["pass_dial"]) ? $_SESSION["pass_dial"] : "";

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
    <script src="../plugins/pace/pace.min.js"></script>
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="../css/global/global.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
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
    i.Break {
        font-size: 15px;
    }
    #Botonera{
        display : none;
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
<input type="hidden" id="rut_buscado"  name="rut_buscado" value="0">
<input type="hidden" id="fono_discado"  name="fono_discado" value="0">
<input type="hidden" id="ultimo_fono"  name="ultimo_fono" value="0">
<input type="hidden" id="duracion_llamada"  name="duracion_llamada" value="0">
<input type="hidden" id="user_dial"  name="user_dial" value="<?php echo $user_dial;?>">
<input type="hidden" id="pass_dial"  name="pass_dial" value="<?php echo $pass_dial;?>">
<input type="hidden" id="nombre_usuario_foco"  name="nombre_usuario_foco" value="<?php echo $nombre_usuario;?>">
<input type="hidden" id="IdCedente" value="<?php echo $_SESSION['cedente'];?>">
<input type="hidden" id="NombreGrabacion" value="">
<input type="hidden" id="UrlGrabacion" value="">
<input type="hidden" id="CanalGrabacion" value="">
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
                    <h1 class="page-header text-overflow">Modo Predictivo / Progresivo</h1>
                </div>
                <ol class="breadcrumb">
                    <li><a href="#">Gestión</a></li>
                    <li><a href="#">Ejecutivos</a></li>
                    <li class="active">Modo Predictivo / Progresivo</li>
                </ol>
                <div id="page-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel" id="demo-panel-collapse" class="collapse in">
                                <div class="panel-heading">
                                    <h3 class="panel-title bg-primary"><i></i>Discado Predictivo</h3>
                                </div>
                                <div class="panel-body ">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group" id="colaDiscador">
                                                <select class="selectpicker" id="seleccione_tipo_busqueda"  name="tipo_estrategia" data-live-search='true' data-width='100%'>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                            <input type="submit" class="btn btn-primary btn-block entrar" value="Entrar" id="entrar">
                                            </div>
                                        </div>
                                        <div id="Botonera">
                                            <div class="col-sm-5">
                                                <div class="form-group">
                                                    <i class="btn btn-danger Break ion-waterdrop add-tooltip Break" data-placement="bottom" data-toggle="tooltip" data-original-title="Baño" id="bano"></i>
                                                    <i class="btn btn-info Break ion-coffee add-tooltip Break" data-placement="bottom" data-toggle="tooltip" data-original-title="Descanso" id="descanso"> </i>
                                                    <i class="btn btn-warning Break ion-settings add-tooltip Break" readonly='readonly' data-placement="bottom" data-toggle="tooltip" data-original-title="Soporte" id="soporte"></i>
                                                    <i class="btn btn-mint Break ion-edit add-tooltip Break" data-placement="bottom" data-toggle="tooltip" data-original-title="Back office" id="office"></i>
                                                    <i class="btn btn-purple Break ion-help-buoy add-tooltip Break" data-placement="bottom" data-toggle="tooltip" data-original-title="Capacitación" id="capacitacion"></i>
                                                    <i class="btn btn-success Break ion-person-stalker add-tooltip Break" data-placement="bottom" data-toggle="tooltip" data-original-title="Reunión" id="reunion" readonly='readonly'></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div  id="ContainerRutNumber" style="display: none;">
                                            <div class="col-sm-2">
                                                <div class="form-group has-success">
                                                    <input type="text" class="form-control" name="RutNumber">
                                                </div>
                                                <div class="form-group has-success">
                                                    <input type="text" class="form-control" name="nombre_cliente">
                                                </div>
                                            </div>
                                            <div class="col-sm-1" align="center">
                                                <div class="form-group has-success">
                                                    <input type="text" class="form-control" name="anexoFoco" value="<?php echo "Extensión: ".$_SESSION['anexo_foco'];?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- aquiii cierra col 12 -->
                    <div id="contenido"> <!-- Abre row col-lg-12 111 -->
                        <div class="col-md-4">
                            <div class="panel" id="demo-panel-collapse" class="collapse in">
                                <div class="panel-heading">
                                    <h3 class="panel-title bg-info">Script Cobranza
                                    </h3>
                                </div>
                                <div class="panel-body ">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div id="script_cobranza_mostrar"></div>
                                            <div id="script_cobranza_ocultar">
                                                Script de Cobranza.
                                            </div>
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
                                <div class="panel-footer" style="display:none">
                                    <center>Duracion de la LLamada</center>
                                    <div id="timer">
                                        <div class="container">
                                            <div id="hour">00</div>
                                            <div class="divider">:</div>
                                            <div id="minute">00</div>
                                            <div class="divider">:</div>
                                            <div id="second">00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="panel">
                                <div class="panel-heading bg-primary">
                                    <div class="panel-control ">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#demo-tabs-box-1" data-toggle="tab">Teléfonos <button class="btn btn-icon icon-lg fa fa-plus-square" id="nuevo_telefono" disabled="disabled" value=""></button></a></li>
                                            <li><a href="#demo-tabs-box-2" data-toggle="tab">Direcciones<button class="btn btn-icon icon-lg fa fa-plus-square" id="nuevo_direccion" disabled="disabled" value="" ></button></a></li>
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
                                        <div class="tab-pane fade" id="demo-tabs-box-2">
                                        <div id="mostrar_direccion_ocultar">Direcciones de Cliente.</div>
                                            <div id="mostrar_direccion"></div>

                                        </div>
                                        <div class="tab-pane fade" id="demo-tabs-box-3">
                                        <div id="mostrar_correo_ocultar">Correos de Cliente.</div>
                                            <div id="mostrar_correo"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="tab-base">

                                <!--Nav Tabs-->
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a data-toggle="tab" href="#demo-lft-tab-1">Deudas</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#demo-lft-tab-2">Ultima Gestión</a>
                                    </li>

                                    <li>
                                        <a data-toggle="tab" href="#demo-lft-tab-3">Mejor Gestión</a>
                                    </li>

                                    <li>
                                        <a data-toggle="tab" href="#demo-lft-tab-4">Numeros de Transferencia</a>
                                    </li>
                                </ul>

                                    <!--Tabs Content-->
                                <div class="tab-content">
                                    <div id="demo-lft-tab-1" class="tab-pane fade active in">
                                        <div id="mostrar_deudas_ocultar">Deudas de Cliente.</div>
                                        <div id="mostrar_deudas"></div>
                                    </div>
                                    <div id="demo-lft-tab-2" class="tab-pane fade">
                                        <div id="mostrar_ultimagestion_ocultar">Ultima Gestion.</div>
                                        <div id="mostrar_ultimagestion"></div>
                                    </div>
                                    <div id="demo-lft-tab-3" class="tab-pane fade">
                                        <div id="mostrar_mejorgestion_ocultar">Mejor Gestión</div>
                                        <div id="mostrar_mejorgestion"></div>
                                    </div>
                                    <div id="demo-lft-tab-4" class="tab-pane fade">
                                        <div id="mostrar_numeros_transferencias">
                                        <div class='row'>
                                            <div class='form-group col-sm-4'>
                                                <select class='selectpicker form-control' name='NumeroTransferencia' title='Seleccione' data-live-search='true' data-width='100%'>
                                                    <option value=''>-- Seleccione --</option>
                                                    <?php
                                                        $SqlNumeros = "SELECT * FROM Numeros_Transferencias_CRM where Id_Cedente='".$_SESSION['cedente']."'";
                                                        $Numeros = $db->select($SqlNumeros);
                                                        foreach($Numeros as $Numero){
                                                            $Descripcion = $Numero["Descripcion"];
                                                            $Telefono = $Numero["Numero"];
                                                            echo "<option value='".$Telefono."'>".$Descripcion."</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel" id="demo-panel-collapse" class="collapse in">
                                <div class="panel-heading">
                                    <h3 class="panel-title bg-primary">Respuesta Rapida</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div id="respuesta_rapida_ocultar">
                                                <select class="selectpicker"  disabled="disabled" data-live-search='true' data-width='100%'>
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
                        <div class="col-md-9">
                            <div class="panel" >
                                <div class="panel-heading">
                                    <h3 class="panel-title bg-primary">Respuesta / Acción Integral</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                        <label class="control-label">Nivel 1</label>
                                            <div class="nivel_1_ocultar">
                                                <select class="selectpicker" id="" disabled="disabled" name="tipo_estrategia" data-live-search='true' data-width='100%'>
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
                                                <select class="selectpicker" id="tipo_estrategia" disabled="disabled" name="tipo_estrategia" data-live-search='true' data-width='100%'>
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
                                                <select class="selectpicker" id="tipo_estrategia" disabled="disabled" name="tipo_estrategia" data-live-search='true' data-width='100%'>
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
                    </div> <!-- Cierra row col-lg-12 111  -->
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

    <!--===================================================-->

    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
    <!--JAVASCRIPT-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
    <script src="../plugins/morris-js/morris.min.js"></script>
    <script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/skycons/skycons.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../plugins/chosen/chosen.jquery.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../js/demo/ui-alerts.js"></script>
    <script src="../plugins/bootstrap-validator/bootstrapValidator.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../plugins/bootstrap-datetimepicker/moment.js"></script>
    <script src="../plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="../js/demo/ui-modals.js"></script>
    <!--Demo script [ DEMONSTRATION ]-->
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/predictivo/predictivo.js"></script>
    <script src="../js/socket.io.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>

    <!--Charts [ SAMPLE ]-->

</body>
</html>
