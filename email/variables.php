<?php 
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include('../class/email/opciones.php'); 
include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "adm,omnica,email_var"));
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
$nombreUsuario = $_SESSION['nombreUsuario']; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Sinaptica | Software de Estrategia</title>
    <!--STYLESHEET-->
    <!--=================================================-->

    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="../css/nifty.min.css" rel="stylesheet">
    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <!--Summernote [ OPTIONAL ]-->
    <link href="../plugins/summernote/summernote.min.css" rel="stylesheet">
    <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../css/global/global.css" rel="stylesheet">
    <!--Custom CSS-->
    <style>
    #nombre{
        display: inline-block;
        width: 80%;
        margin: 0 5px;
    }
    .panelVariables{
        display: none;
    }
    </style>
    <!--=================================================-->
</head>

<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->
<body>
    <div id="container" class="effect aside-float aside-bright mainnav-lg">
        <!--NAVBAR-->
        <!--===================================================-->
        <?php include('../layout/header.php'); ?>
        <!--===================================================-->
        <!--END NAVBAR-->
        <div class="boxed">
            <!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <!--Page Title-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <div id="page-title">
                    <h1 class="page-header text-overflow">Creación de Variables</h1>
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->
                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
                    <li><a href="#">Omnicanalidad</a></li>
                    <li class="active"><a href="#">Creación de Variables</a></li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-heading bg-primary">
                                    <h3 class="panel-title">Crear Variable</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-md-1 control-label" style="padding-top: 5px;font-size: 15px;">
                                            Canal: 
                                        </label>
                                        <div class="col-md-4">
                                            <select class="selectpicker" title="Seleccione" data-live-search="true" 
                                                        data-width="100%" id="canal" name="canal">
                                                <option value="0">EMAIL</option>
                                                <option value="1">SMS</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panelVariables">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><span id="titleVariable"></span></h3>
                                </div>
                                <form class="panel-body form-horizontal form-padding">
                                    <div class="form-group">
                                        <label class="col-md-1 control-label">Nombre</label>
                                        <div class="col-md-4">
                                            <p>[<input type="text" class="form-control" name="nombre" id="nombre" value="">]</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-1 control-label">Tipo</label>
                                        <div class="col-md-3">                                     
                                            <select class="selectpicker" title="Seleccione" data-live-search="true" 
                                                        data-width="100%" id="tipo" name="tipo">
                                                <option value="">Seleccione</option>
                                                <option value="valor">Valor</option>
                                                <option value="tabla">Tabla</option>
                                                <option value="operacion">Operación</option>
                                            </select>
                                        </div>
                                    </div>                               
                                    <div class="form-group" id="operacion-wrapper" style="display:none;">
                                        <label class="col-md-1 control-label">Operación</label>
                                        <div class="col-md-5">                                     
                                            <select class="selectpicker" title="Seleccione" data-live-search="true" 
                                                        data-width="100%" id="operacion" name="operacion">
                                                <option value="">Seleccione</option>
                                                <option value="SUM">SUMA</option>
                                                <option value="AVG">PROMEDIO</option>
                                                <option value="COUNT">CONTAR</option>
                                                <option value="MIN">VALOR MINIMO</option>
                                                <option value="MAX">VALOR MÁXIMO</option>
                                            </select>
                                        </div>
                                    </div>                           
                                    <div class="form-group">
                                        <label class="col-md-1 control-label">Tabla</label>
                                        <div class="col-md-3">                                     
                                            <select class="selectpicker" title="Seleccione" data-live-search="true" 
                                                        data-width="100%" id="tabla" name="tabla">
                                                <option value="Persona">Persona</option>
                                                <option value="Deuda">Deuda</option>
                                                <option value="Direcciones">Direcciones</option>
                                                <option value="Mail">Mail</option>
                                            </select>
                                        </div>
                                    </div>                           
                                    <div class="form-group">
                                        <label class="col-md-1 control-label">Campos</label>
                                        <div class="col-md-3" id="campos-persona">
                                            <select class="selectpicker" title="Seleccione" data-live-search="true" 
                                                        data-width="100%" name="campos">
                                                <?php $campos = new opciones;
                                                    echo $campos->campos('Persona');
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3" id="campos-deuda" style="display: none;">              
                                            <select class="selectpicker" title="Seleccione" data-live-search="true" 
                                                        data-width="100%" name="campos">
                                                <?php $campos = new opciones;
                                                    echo $campos->campos('Deuda');
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3" id="campos-direcciones" style="display: none;">              
                                            <select class="selectpicker" title="Seleccione" data-live-search="true" 
                                                        data-width="100%" name="campos">
                                                <?php $campos = new opciones;
                                                    echo $campos->campos('Direcciones');
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3" id="campos-mail" style="display: none;">              
                                            <select class="selectpicker" title="Seleccione" data-live-search="true" 
                                                        data-width="100%" name="campos">
                                                <?php $campos = new opciones;
                                                    echo $campos->campos('Mail');
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="Alias" id="Alias" style="display: none;">
                                            <button id="agregar" class="btn btn-primary" type="button" style="display: none;">
                                                Agregar
                                            </button>
                                            <input type="hidden" id="fields">
                                            <input type="hidden" id="current-var">
                                        </div>
                                    </div>
                                    <div class="form-group" id="ContainerOrdenamiento" style="display: none;">
                                        <label class="col-md-1 control-label">Ordenado por:</label>
                                        <div class="col-md-3">
                                            <select class="selectpicker" title="Seleccione" data-live-search="true" 
                                                        data-width="100%" name="Ordenamiento"></select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="previsualizar" style="display: none;">
                                        <label class="col-md-1 control-label">Previsualizar</label>
                                        <div class="col-md-9">
                                            <table class="table">
                                                <thead>
                                                    <tr></tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>    
                                </form>
                                <div class="panel-footer text-right">
                                    <button id="clean" class="btn btn-primary" type="button">Limpiar</button>
                                    <button id="guardar-variable" class=" btn btn-primary" type="button">Guardar</button>
                                    <button id="actualizar-variable" class=" btn btn-primary" type="button" style="display: none;">
                                        Actualizar
                                    </button>
                                </div>    
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Editar Variables EMAIL</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="templates">
                                            <?php $templates = new opciones;
                                                echo $templates->variables(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Editar Variables SMS</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="templatesSMS">
                                            <?php $templates = new opciones;
                                                echo $templates->variablesSMS(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End page content-->
            </div>
            <!--END CONTENT CONTAINER-->
            <!--MAIN NAVIGATION-->
            <!--===================================================-->
            <?php include('../layout/main-menu.php'); ?>
            <!--===================================================-->
            <!--END MAIN NAVIGATION-->
        </div>
        <!-- FOOTER -->
        <!--===================================================-->
        <footer id="footer">
            <!-- Visible when footer positions are fixed -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="show-fixed pull-right">
                You have <a href="#" class="text-bold text-main"><span class="label label-danger">3</span> pending action.</a>
            </div>
            <!-- Visible when footer positions are static -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="hide-fixed pull-right pad-rgt">
                <!-- 14GB of <strong>512GB</strong> Free. -->
            </div>
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- <p class="pad-lft">&#0169; 2016 Your Company</p> -->
        </footer>
        <!--===================================================-->
        <!-- END FOOTER -->
        <!-- SCROLL PAGE BUTTON -->
        <!--===================================================-->
        <button class="scroll-top btn">
            <i class="pci-chevron chevron-up"></i>
        </button>
        <!--===================================================-->
    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
      <!--JAVASCRIPT-->
    <!--=================================================-->
    <!--jQuery [ REQUIRED ]-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="../js/bootstrap.min.js"></script>
    <!--Fast Click [ OPTIONAL ]-->
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <!--Nifty Admin [ RECOMMENDED ]-->
    <script src="../js/nifty.min.js"></script>
    <!--Switchery [ OPTIONAL ]-->
    <script src="../plugins/switchery/switchery.min.js"></script>
    <!--Bootstrap Select [ OPTIONAL ]-->
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <!--Demo script [ DEMONSTRATION ]-->
    <script src="../js/demo/nifty-demo.min.js"></script>
    <!--SUMMERNOTE INITIATION-->
    <script src="../js/email/variables.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>    
</body>
</html>
