<?PHP
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "tar,pdt"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction("../index.php");
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo();
}
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = $_SESSION['cedente'];

$db = new DB();
$SqlTipoSistema = "select tipoSistema from fireConfig";
$TipoSistema = $db->select($SqlTipoSistema);
$firstrow = $TipoSistema[0];
$tipoSistema = $firstrow["tipoSistema"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Sinaptica | Software de Estrategia</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/nifty.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.css" rel="stylesheet">
    <link href="../plugins/chosen/chosen.min.css" rel="stylesheet">
    <link href="../plugins/noUiSlider/nouislider.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/dropzone/dropzone.css" rel="stylesheet">
    <link href="../plugins/summernote/summernote.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <link href="../css/global/global.css" rel="stylesheet">

    <style type="text/css">
    #mostrar_estrategia
             {
        display: none;
             }
    #mostrar_cola
             {
        display: none;
             }
    #mostrar_gestor
             {
        display: none;
             }
    #mostrar_asignacion
             {
        display: none;
             }
    #acciones_seleccionadas
             {
        display: none;
             }
	 #acciones_seleccionadas2
             {
        display: none;
             }
      .select1
             {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #FFFFFF;
    	border-width: 1px;

             }
    .select2
            {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
		border-width: 1px;
        background-color: #CCC;

            }
    .text1
            {
        width: 100%;
        height: 30px;
        border: solid;
        background-color: #FFFFFF;
    	border-width: 1px;
		border-color: #ccc;

            }
    .text2
            {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
		border-width: 1px;
        background-color: #CCC;

            }
        .asignacion
             {
        width: 100%;
        height: 30px;
        border: none;
        border-top-width: thin;
        border-right-width: thin;
        border-bottom-width: thin;
        border-left-width: thin;
        text-align: center;

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
          #page-content.AsignadorOculto {
                width: 0px !important;
                height: 0px !important;
                padding: 0 !important;
                overflow: hidden;
            }
            #page-content {
                padding: 5px 20px 0;
                width: 100%;
                height: 100%;
                transition: all 0.5s ease;
            }
            #AsignadorDeCasos{
                width: 0px;
                transition: all 0.5s ease;
                overflow: hidden;
            }
            #AsignadorDeCasos.AsignadorOculto {
                width: 100% !important;
                padding: 5px 20px 0;
                overflow: hidden;
            }
            .fa.Disabled{
                color: #cccccc;
            }
            .dropdown-menu.open {
                max-height: none !important;
            }
    </style>
</head>
<body>
  <input type="hidden" id="cedente" value="<?php echo $cedente; ?>">
  <input type="hidden" id="tipoSistema" value="<?php echo $tipoSistema; ?>">
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
                    <h1 class="page-header text-overflow">Asignar</h1>
                    <!--Searchbox-->

                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->


                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
                    <li><a href="#">Asignación</a></li>
                    <li class="active">Asignar</li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->
                <!--Page content-->
                <!--===================================================-->
                    <!-- clase AsignadorOculto -->
                <div id="page-content">
					<div class="row">
                        <div class="eq-height">
                            <div class="col-sm-12 eq-box-sm">
                                <div class="panel" id='padre'>
                                    <div class="panel-heading">
                                    	<h2 class="panel-title"> <i class="fa fa-tasks"></i>  Seleccione Tipo de Estrategia</h2>
                                    </div>
                                    <div class="panel-body">
                                    	<div id="cambiar">
                                       	    <table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                               	        <!-- <th>ID Estrategia</th> -->
                                                        <th><center>Tipo de Estrategia</center></th>
                                                        <th><center>Seleccionar</center></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $i = 1;
                                                        $sql_estrategia = $db->select("SELECT id,nombre FROM SIS_Tipo_Estrategia");
                                                        if($sql_estrategia){
                                                            foreach($sql_estrategia as $row){ ?>
                                                            <tr id='<?php echo $i; ?>'>
                                                                <!-- <td><?php echo $row["id"];?></td> -->
                                                                <td>
                                                                    <center>
                                                                        <?php echo $row["nombre"]; ?><input type="hidden" name="id_cedente" id="id_cedente" value="<?php echo $cedente; ?>">
                                                                    </center>
                                                                </td>
                                                                <td>
                                                                    <center>
                                                                        <input type='checkbox' class='toggle-switch seleccione_tipo' id='uno<?php echo $i; ?>'>
                                                                        <label class='toggle-switch-label'></label></div>
                                                                    </center>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $i++;
                                                            }
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>

                    <div id="mostrar_estrategia">
                        <div class="row">
                            <div class="eq-height">
                                <div class="col-sm-12 eq-box-sm">
                                    <div class="panel" id='padre'>
                                        <div class="panel-heading">
                                            <h2 class="panel-title"> Seleccione Estrategia</h2>
                                        </div>
                                        <div class="panel-body">
                                            <div id="cambiar2">
                                                <div class="table-responsive">
                                                    <table id="mostrar_estrategia_dt" class="table table-striped table-bordered" cellspacing="0" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <!-- <th>ID Estrategia</th> -->
                                                                <th>Nombre de la Estrategia</th>
                                                                <th>Comentario</th>
                                                                <th>Fecha</th>
                                                                <th>Hora</th>
                                                                <th>Seleccionar</th>
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

                    <div id="mostrar_cola">
                        <div class="row">
                            <div class="eq-height">
                                <div class="col-sm-12 eq-box-sm">
                                    <div class="panel" id='padre'>
                                        <div class="panel-heading">
                                            <h2 class="panel-title"> Seleccione Cola Terminal</h2>
                                        </div>
                                        <div class="panel-body">
                                            <div="cambiar3">
                                                <div class="table-responsive">
                                                    <table id="mostrar_cola_dt" class="table table-striped table-bordered" cellspacing="0" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <!-- <th>ID Cola</th> -->
                                                                <th>Cola</th>
                                                                <th>Cantidad de Registros</th>
                                                                <th>Monto</th>
                                                                <th>Prioridad</th>
                                                                <th>Comentario</th>
                                                                <th>Asignar</th>
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

                    <div id="AsignadorDeCasos">
                        <div class="row">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h2 class="panel-title">Seleccione ...</h2>
                                </div>
                                <div class="panel-body">
                                    <button class="btn btn-primary" id="AddEntidad">Agregar Entidad</button>
                                    <br>
                                    <br>
                                    <table id="TablaDeAsignados" class="table-responsive" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Tipo</th>
                                                <th>Porcentaje</th>
                                                <th>ID</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Final</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th id="SumPorcentaje">0%</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <button class="btn btn-primary" id="Seleccionar_Modo_Asignacion">Continuar</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h2 class="panel-title">Archivos a descargar</h2>
                                </div>
                                <div class="panel-body">
                                    <div id="Downloads">
                                        <!-- <div class="list-group list-group-striped col-sm-3" id="Tipo1">
                                            <a class="list-group-item" style='position: relative;'>Formato Descargable Tipo 1 <i class="fa fa-download" style='position: absolute; right: 15px;'></i></a>
                                        </div> -->
                                        <div class="list-group list-group-striped col-sm-3" id="Tipo2">
                                            <a class="list-group-item" style='position: relative;'>Formato Descargable Tipo 1 <i class="fa fa-download" style='position: absolute; right: 15px;'></i></a>
                                        </div>
                                        <!--<div class="list-group list-group-striped col-sm-3" id="Tipo3">
                                            <a  class="list-group-item">TIPO 3</a>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <!--===================================================-->
                <!--End page content-->


                <!--===================================================-->
                    <!--=================TEMPLATES==================-->
                    <script id="TemplateAddEntidad" type="text/template">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Seleccione Tipo de Entidad:</label>
                                        <select class="selectpicker form-control" name="TipoEntidad" title="Seleccione" data-live-search="true" data-width="100%">
                                            <option value="2">Personal</option>
                                            <option value="3">Grupo</option>
                                            <option value="1">Empresa Externa de cobranza</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Seleccione Entidad:</label>
                                        <select class="selectpicker form-control" multiple="" disabled="disabled" name="Entidad" title="Seleccione" data-live-search="true" data-width="100%">
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-md-6-offset-3 form-group" id="NombreGrupo" style='display: none;'>
                                    <label class="control-label">Nombre Grupo</label>
                                    <input type="text" class="form-control" name="nombreGrupo">
                                </div>
                            </div>
                        </div>
                    </script>
                    <script id="TemplateSeleccionModoAsignacion" type="text/template">
                        <div class="row">
                            <div class="col-sm-12">
                                <div style="width: 50%;" class="center-block">
                                    <div class="form-group">
                                        <label class="control-label">Asignar por:</label>
                                        <select class="selectpicker form-control" name="MetodoAsignacion" title="Seleccione" data-live-search="true" data-width="100%">
                                            <option value="1">Ruts</option>
                                            <option value="2">Deuda</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </script>
                    <!--=============FIN DE TEMPLATES===============-->
                <!--===================================================-->

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
    <script id="TemplateCautivo" type="text/template">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <label class="form-checkbox form-icon btn btn-info btn-labeled form-text"><input type="checkbox" name="inputCautiva">Activo</label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group">
                    <label class="control-label">Ejecutivo:</label>
                    <select class="selectpicker form-control" name="EjecutivoColaCautiva" id="EjecutivoColaCautiva" title="Seleccione" data-live-search="true" data-width="100%"></select>
                </div>
            </div>
        </div>
    </script>
    <!--JAVASCRIPT-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../plugins/jquery-mask/jquery.mask.min.js"></script> 
    <script src="../js/global.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/tareas/tareas.js"></script>
</body>
</html>
