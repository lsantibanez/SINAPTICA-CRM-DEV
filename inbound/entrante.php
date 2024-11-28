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
    $objetoSession->crearVariableSession($array = array("idMenu" => "crm,vdi,ent"));
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
    </style>    
    <!-- <style type="text/css">
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
</style> -->
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
                    <h1 class="page-header text-overflow">llamadas Entrantes</h1>
                </div>
                <ol class="breadcrumb">
                    <li><a href="#">Gestión</a></li>
                    <li><a href="#">Operaciones</a></li>
                    <li class="active">Llamadas Entrantes</li>
                </ol>
                <div id="page-content">
                    <div class="row">
                        <div class="panel" id="demo-panel-collapse" class="collapse in">
                            <div class="panel-heading">
                                <h3 class="panel-title bg-primary"><i></i>Conectarse a Línea Entrante</h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                        <label class="control-label" for="ColaInbound">Línea Entrante</label>
                                            <select class="selectpicker" name="ColaInbound" title="Seleccione" data-live-search='true' data-width='100%'>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <button class="btn btn-primary" id="btnConectar">Conectar</button>
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
    <script src="../js/inbound/inbound.js"></script>
    <script src="../js/socket.io.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>

    <!--Charts [ SAMPLE ]-->

</body>
</html>
