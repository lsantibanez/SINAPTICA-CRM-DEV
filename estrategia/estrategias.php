<?PHP
include("../class/db/DB.php");
require_once('../class/session/session.php');
include("../class/global/global.php");
include("../class/estrategia/estrategia.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "est,egu"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction(); // VERIFICAR FUNCIONAMIENTO DE ESTE METODO
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{ //
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = $_SESSION['cedente'];
$nombreUsuario = $_SESSION['nombreUsuario'];
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
    <link href="../plugins/datatables/media/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="../plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
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
    .modal {
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
        <?php include("../layout/header.php");  ?>
        <!--===================================================-->
        <!--END NAVBAR-->
        <div class="boxed">
            <!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <!--Page Title-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <div id="page-title">
                    <h1 class="page-header text-overflow">Segmentador</h1>
                    <!--Searchbox-->
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->
                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
                    <li><a href="#">Segmentador</a></li>
                    <li class="active">Segmentaciones</li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="row">
						<div class="eq-height">
						    <div class="col-sm-12 eq-box-sm">
                                <div id="contenedor"></div>
                                <div class="mostrar_condiciones">
                                    <div class="panel" id='sql'>
                                        <div class="panel-body">
                                            <div class="row" style="padding: 15px;">
                                                <div class="col-md-3">
                                                    <button type="button" class="btn btn-success btn-block" title="Crear" onclick="javascript:window.location.href='/estrategia/crear'">
                                                        <i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Crear segmentación
                                                    </button>
                                                </div>
                                            </div>
                                            <?php /*
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <button class="btn btn-success btn-block estrategiasInactivas">Estrategias inactivas</button>
                                                </div>
                                            </div>
                                            */ ?>
                                            <input type="hidden" id="cedente" value="<?php echo $cedente; ?>">
                                            <input type="hidden" id="nombreUsuario" value="<?php echo $nombreUsuario; ?>">
                                            <div class="mostrar"></div>
                                            <div class="listadoEstretegias"></div>
                                            <script id="listadoEstretegiasInactivas" type="text/template">
                                                <div class="lisEstretegiasInactivas"></div>
                                            </script>
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
    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
    <!--JAVASCRIPT--> 
  <script src="../js/jquery-2.2.1.min.js"></script>
  <script src="../js/funciones.js"></script>
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
  <script src="../js/demo/ui-alerts.js"></script>
  <script src="../js/global.js"></script>
  <script src="../js/global/funciones-global.js"></script>
  <!--<script src="../plugins/datatables/media/js/dataTables.bootstrap.js"></script>
  <script src="../plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>-->
  <script src="../js/demo/tables-datatables.js"></script>
  <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
  <script src="../plugins/bootbox/bootbox.min.js"></script>
</body>
</html>