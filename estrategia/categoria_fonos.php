<?PHP
//$conexion = mysql_connect("localhost" , "root" , "M9a7r5s3A");
//mysql_select_db("foco",$conexion);
include("../class/global/global.php");
require_once('../class/db/DB.php');
require_once('../class/session/session.php');
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "adm,ad_estra,ccf"));
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
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../css/global/global.css" rel="stylesheet">

    <style type="text/css">
        .select1 {
            width: 100%;
            height: 30px;
            border: solid;
            border-color: #ccc;
            background-color: #FFFFFF;
            border-width: 1px;

        }
        .select2{
            width: 100%;
            height: 30px;
            border: solid;
            border-color: #ccc;
            border-width: 1px;
            background-color: #CCC;

        }
        .text1{
            width: 100%;
            height: 31px;
            border: 1px solid #CDD6E2;
            background-color: #FAFAFA;
            padding-left: 12px;
        }
        .text2{
            width: 100%;
            height: 30px;
            border: solid;
            border-color: #ccc;
            border-width: 1px;
            background-color: #CCC;
        }
        #midiv99{
            display: none;
        }
        #oculto{
            display: none;
        }
        #guardar{
            display: none;
        }
        #folder{
            display: none;
        }
        .condicion_oculta{
            display: none;
        }
        .canvasjs-chart-credit {
            display: none;
        }
        #bell{
            display: none;
        }
    </style>
</head>
<body>
<input type="hidden" id="cedente" value="<?php echo $cedente; ?>">
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
                  <h1 class="page-header text-overflow">Crear Categoria Fonos</h1>
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->

                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
                    <li><a href="#">Estrategia</a></li>
                    <li class="active">Crear Categoria Fonos</li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->

                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="row">
						<div class="eq-height">
							<div class="col-sm-12 eq-box-sm">
								<!--Panel with Header-->
								<!--===================================================-->
                                <div class="panel" id='padre'>
									<div class="panel-heading">
									    <h2 class="panel-title"> Clasificación Teléfonos</h2>
									</div>
                                    <div class="panel-body">
                                        <button class="btn btn-success" id="CrearCategoria">Crear Categoria</button>
                                        <br>
                                        <br>
                                        <table id="CategoriaTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><center>Categoría</center></th>
                                                    <th><center>Color</center></th>
                                                    <th><center>Prioridad</center></th>
                                                    <th><center>Tipo Contacto</center></th>
                                                    <th><center>Dias Atras</center></th>
                                                    <th><center>Condición</center></th>
                                                    <th><center>Cantidad</center></th>
                                                    <th><center>Lógica</center></th>
                                                    <th><center>Condición</center></th>
                                                    <th><center>Cantidad</center></th>
                                                    <th><center>Acciones</center></th>
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

        <script id="CategoriaTemplate" type="text/template"> 
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Asignar Categoría</label>
                    <select class="selectpicker"  name="color" id="color" data-width="100%"></select>
                </div>
                <div class="col-sm-4">
                    <label class="control-label">Tipo Contacto</label>
                    <select class="selectpicker" multiple name="tipo_contacto" id="tipo_contacto" data-width="100%"></select>
                </div>
                <div class="col-sm-4">
                    <label class="control-label">Días atras : </label>
                    <input type="number" name="dias" id="dias" class="text1">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Prioridad</label>                                 
                    <input type="number" name="prioridad" id="prioridad" class="text1">                                          
                </div>
                <div class="col-sm-4">
                    <label class="control-label">Condición</label>
                    <select class="selectpicker"  name="cond1" id="cond1" data-width="100%">
                        <option value = "1">Menor</option>
                        <option value = "2">Menor o Igual</option>
                        <option value = "3">Igual</option>
                        <option value = "4">Mayor</option>
                        <option value = "5">Mayor o Igual</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label class="control-label">Cantidad </label>
                    <input type="number" name="cant1" id="cant1" class="text1">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Lógica</label>
                    <select class="selectpicker"  name="logica" id="logica" data-width="100%">
                        <option value = "1">N/A</option>
                        <option value = "2">Y</option>
                        <option value = "3">O</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label class="control-label">Condición</label>
                    <select class="selectpicker"  class="text2" name="cond2" id="cond2" data-width="100%">
                        <option value = "0">Seleccione</option>
                        <option value = "1">Menor</option>
                        <option value = "2">Menor o Igual</option>
                        <option value = "3">Igual</option>
                        <option value = "4">Mayor</option>
                        <option value = "5">Mayor o Igual</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label class="control-label">Cantidad </label>
                    <input type="number"  name="cant2" id="cant2" class="text1">
                </div>
            </div>
        </script>
    </div>

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
    <script src="../plugins/chosen/chosen.jquery.min.js"></script>
    <script src="../js/demo/ui-modals.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../js/global.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/estrategia/categoria_fonos.js"></script>

</body>
</html>
