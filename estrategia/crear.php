<?PHP
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "est,ces"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{
  //to fully log out a visitor we need to clear the session varialbles
  $objetoSession->borrarVariablesSession();
  $objetoSession->logoutGoTo("../index.php");
}
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = $_SESSION['cedente'];
$idUsuario = $_SESSION['id_usuario'];

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
    <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
    <link href="../plugins/morris-js/morris.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <script src="../plugins/pace/pace.min.js"></script>
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../css/global/global.css" rel="stylesheet">
    <style type="text/css">
    .select1
    {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CEECF5;
    }
    .select2
    {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CCC;
    }
    .text1
    {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CEECF5;
    }
    .text2
    {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CCC;
    }
    .mostrar_condiciones
    {
        display: none;
    }
    #midiv100
    {
        display: none;
    }
    #oculto
    {
        display: none;
    }
    #guardar
    {
        display: none;
    }
    #folder
    {
        display: none;
    }
    .modal
    {
        display:    none;
        position:   fixed;
        z-index:    1000;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 255, 255, 255, .8 )
        url('../img/gears.gif')
        50% 50%
        no-repeat;
    }
    body.loading
    {
        overflow: hidden;
    }
    body.loading .modal
    {
        display: block;
    }

    </style>
</head>
<body>
    <div id="container" class="effect mainnav-lg">
        <!--NAVBAR-->
        <!--===================================================-->
        <?php
        include("../layout/header.php");
        ?>
        <!--===================================================-->
        <!--END NAVBAR-->

        <div class="boxed">

            <!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">

                <!--Page Title-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <div id="page-title">
                    <h1 class="page-header text-overflow">Crear</h1>
                    <!--Searchbox-->

                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->


                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
                    <li><a href="#">Segmentación</a></li>
                    <li class="active">Crear</li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->

                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="row">
						<div class="eq-height">
							<!--Panel with Header-->
							<!--===================================================-->
							<div class="col-sm-12 eq-box-sm">
                                <div id="contenedor"></div>
                                <div id="mostrar_estrategia">
                                    <div class="panel" id='sql'>
                                        <div class="panel-heading">
                                            <h2 class="panel-title"><i class="fa fa-pencil-square-o"></i>Nueva Segmentación </h2>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" style="padding: 12px 15px;">
                                                <div class="col-md-8">
                                                    <form id="crear_estrategia" autocomplete="off" name="crear_estrategia" action="#" method="POST" class="form-horizontal">
                                                        <div class="form-group">
                                                            <label for="nombre" class="col-sm-2 control-label">Nombre</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" name="nombre_estrategia" id="nombre_estrategia" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nombre" class="col-sm-2 control-label">Descripcion</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="comentario_estrategia" id="comentario_estrategia" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" style="margin-top: 25px;">
                                                            <label for="nombre" class="col-sm-2 control-label">&nbsp;</label>
                                                            <div class="col-sm-4">
                                                                <button type="submit" class="btn btn-success btn-block" title="Guardar">
                                                                    <i class="fa fa-hard-drive"></i>&nbsp;&nbsp;Guardar
                                                                </button>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <button type="button" class="btn btn-info btn-block" title="Guardar" onclick="javascript:window.location.href='/estrategia/estrategias'">
                                                                    <i class="fa fa-angles-left"></i>&nbsp;&nbsp;Regresar
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="cedente" id="cedente" value="<?php echo $cedente;?>" class="form-control">
                                                        <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" class="form-control">
                                                        <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $idUsuario;?>" class="form-control">
                                                        <input type="hidden" name="tipo_estrategia" id="tipo_estrategia" value="1" class="form-control">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

								<!--===================================================-->
								<!--End Panel with Header-->

							</div>
						</div>
					</div>
                </div>
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->



            <!--MAIN NAVIGATION-->
            <!--===================================================-->
            <?php include("../layout/main-menu.php"); ?>
            <!--===================================================-->            <!--END MAIN NAVIGATION-->


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
    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
    <!--JAVASCRIPT-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/estrategia/Estrategias.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
	<script src="../plugins/morris-js/morris.min.js"></script>
    <script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/skycons/skycons.min.js"></script>
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../js/demo/ui-alerts.js"></script>
    <script src="../plugins/jquery-mask/jquery.mask.min.js"></script> 
    <script src="../js/global.js"></script>
    <script src="../js/global/funciones-global.js"></script>
</body>
</html>
