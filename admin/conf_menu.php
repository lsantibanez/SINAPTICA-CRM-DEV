<?php
    include("../class/global/global.php");
    require_once('../class/session/session.php');
    $objetoSession = new Session('1',false); // 1,4
    //Para Id de Menu Actual (Menu Padre, Menu hijo)
    $objetoSession->crearVariableSession($array = array("idMenu" => "adm,sis,conf_menu"));
    // ** Logout the current user. **
    $objetoSession->creaLogoutAction();
    if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
    {
    //to fully log out a visitor we need to clear the session varialbles
        $objetoSession->borrarVariablesSession();
        $objetoSession->logoutGoTo("../index.php");
    }
    $validar = $_SESSION['MM_UserGroup'];
    $objetoSession->creaMM_restrictGoTo();
    $usuario = $_SESSION['MM_Username'];
    if (isset($_SESSION['cedente'])){
        $cedente = $_SESSION['cedente'];
    }else{
        $cedente = '';
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
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
        <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
        <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/global/global.css">
    </head>
    <body>
        <input type="hidden" name="cedente" id="cedente" value="<?php echo $cedente;?>">
        <div id="container" class="effect mainnav-lg">
            <!--NAVBAR-->
            <!--===================================================-->
            <?php include("../layout/header.php"); ?>
            <!--===================================================-->
            <!--END NAVBAR-->
            <div class="boxed">
                <!--CONTENT CONTAINER-->
                <!--===================================================-->
                <div id="content-container">
                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                    <!--Searchbox-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->
                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
                        <li><a href="#">Configuración</a></li>
                        <li><a href="#">Sistema</a></li>
                        <li class="active">Configuración de Menú</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                    <!--Page content-->
                    <!--===================================================-->
                    <div id="page-content">
                        <div class="row">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h2 class="panel-title">Configuración de Menú</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <table id="TableMenu" class="table table-striped table-bordered">
                                            <thead>
                                                <tr id="TableMenu_thead">
                                                </tr>
                                            </thead>
                                            <tbody id="TableMenu_tbody">

                                            </tbody>
                                        </table>
                                    </div>
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
            <?php include("../layout/footer.php"); ?>
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
        
        <!--JAVASCRIPT-->
        <script src="../js/jquery-2.2.1.min.js"></script>
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
        <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
        <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
        <script src="../js/global/funciones-global.js"></script>
        <script src="../js/admin/conf_menu.js"></script>
    </body>
</html>
