<?php
	require_once('../class/db/DB.php');
    require_once('../class/session/session.php');

	include("../class/global/global.php");
    $Omni = new Omni;
    $Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

    // $objetoSession = new Session($Permisos,false); // 1,4
    $objetoSession = new Session($Permisos,false); // 1,4
    //Para Id de Menu Actual (Menu Padre, Menu hijo)
    $objetoSession->crearVariableSession($array = array("idMenu" => "gra,oper,rptCar"));
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
    if(isset($_SESSION['cedente'])){
        if($_SESSION['cedente'] != ""){
            $cedente = $_SESSION['cedente'];
        }
    }
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
	    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
	    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
	    <link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
	    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
        <link href="../plugins/sweetalert/sweetalert.css" rel="stylesheet">
	    <link href="../css/global/global.css" rel="stylesheet">
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
	                    <h1 class="page-header text-overflow">Reporte Operativo Cartera</h1>
	                    <!--Searchbox-->
	            
	                </div>
	                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	                <!--End page title-->


	                <!--Breadcrumb-->
	                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	                <ol class="breadcrumb">
	                    <li><a href="#">Reporteria</a></li>
	                    <li class="active">Reporte Operativo Cartera</li>
	                </ol>
	                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	                <!--End breadcrumb-->


	                <!--Page content-->
	                <!--===================================================-->
					<div id="page-content">
                        <div class="row">
                            <div class="col-sm-12">
                            <div class="panel">                                
                                <div class="panel-heading">
                                    <h2 class="panel-title bg-mint">Reportes</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <div class="col-md-12">
                                            <table id="Table" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Nombre</th>
                                                        <th class="text-center">Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->

            
            <!--MAIN NAVIGATION-->
            <!--===================================================-->
            <?php include("../layout/main-menu.php"); ?> 
            <!--===================================================-->
            <!--END MAIN NAVIGATION-->
            

        </div>

        <!--===================================================-->
        <!-- END FOOTER -->
        <!-- SCROLL TOP BUTTON -->
        <!--===================================================-->
        <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
        <div class="modal"><!-- Place at bottom of page --></div>
        <!--===================================================-->
        
        <!--SCRIPT-->
        <script src="../js/jquery-2.2.1.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
	    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
		<script src="../plugins/bootbox/bootbox.min.js"></script>
		<script src="../plugins/datatables/media/js/jquery.dataTables.js"></script>
		<script src="../plugins/datatables/media/js/dataTables.bootstrap.js"></script>
		<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
        <script src="../plugins/numbers/jquery.number.js"></script>
        <script src="../plugins/pace/pace.min.js"></script>
        <script src="../js/nifty.min.js"></script>
        <script src="../js/demo/nifty-demo.min.js"></script>
        <script src="../plugins/sweetalert/sweetalert.min.js"></script>
        <script src="../js/global/methods.js"></script>
        <script src="../js/global/funciones-global.js"></script>
		<script src="../js/reporteria/ReporteOperativoCartera.js"></script>
    </body>
</html>