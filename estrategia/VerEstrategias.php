<?PHP
error_reporting(E_ALL & ~E_NOTICE);

require_once('../class/db/DB.php');
$db = new DB();
require_once('../class/session/session.php');
include("../class/global/global.php");
/* $Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));
$objetoSession = new Session($Permisos,false); // 1,4 */
$objetoSession = new Session('1,2,3,4,5,6',false);
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")) {
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = $_SESSION['cedente'];
$nombreUsuario = $_SESSION['nombreUsuario'];
$id_estrategia = $_SESSION['IdEstrategia'];
$idUsuarioLogin = $_SESSION['id_usuario'];
$idUsuarioEstrategia = 0;
$sql=$db->select("SELECT nombre, id_usuario FROM SIS_Estrategias WHERE id = '".$id_estrategia."' AND Id_Cedente = '".$cedente."'");
foreach((array) $sql as $row){
    $nombre_estrategia = $row["nombre"];
    $idUsuarioEstrategia = $row["id_usuario"];
}
if(empty($id_estrategia) || $idUsuarioEstrategia == 0) {
  header('Location: estrategias.php'); 
  exit;
} else{
  $id_estrategia = $id_estrategia;
}

/**
  * Verifico sip el usuario conectado es el mismo que creo la estrategia
  * para asi dejarlo crear y deshacer, de lo contrario deshabilitar los botones
*/
if ($idUsuarioEstrategia == $idUsuarioLogin) {
  $habilitado = "";
} else {
  $habilitado = "disabled='disabled'";
}

$NombreEstrategia = '';
$QueryNombreEstrategia = $db->select("SELECT Nombre FROM SIS_Estrategias WHERE id = $id_estrategia LIMIT 1");
if ($QueryNombreEstrategia) {
  $NombreEstrategia = $QueryNombreEstrategia[0]["Nombre"];
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
    <link href="../css/multiple.css" rel="stylesheet"/>
    <link href="../css/nifty.min.css" rel="stylesheet">
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
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../css/global/global.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-validator/bootstrapValidator.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <style type="text/css">             
    .btn-group > .btn:first-child 
    {
      margin-left: 5px;
    }
    #Between{
        display:none;
    }
    .text-transparent-max
    {
        width: auto;
        height: 20px;
        border: none;
        text-align: center;
        background-color:transparent;
    }

    .text-transparent-left
    {
        width: auto;
        height: 20px;
        border: none;
        text-align: left;
        background-color:transparent;
    }

    .text-transparent-min
    {
        width: auto;
        height: 20px;
        border: none;
        text-align: center;
        background-color:transparent;
    }
  

    /* .modal 
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
    } */

    body.loading
    {
        overflow: hidden;
    }

    body.loading .modal
    {
        display: block;
    }
    </style>
    <link rel="stylesheet" href="/css/list-new.css">
</head>
<body>
  <div id="container" class="effect mainnav-lg isVerEstrategia">
  <?php include("../layout/header.php"); ?>
    <div class="boxed">
      <!--CONTENT CONTAINER-->
      <!--===================================================-->
      <div id="content-container">
        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
          <!--Searchbox-->
          <h1 class="page-header text-overflow">Segmentación</h1>
        </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->
                <ol class="breadcrumb">
                  <li><a href="#">Segmentación</a></li>
                  <li class="active">Resultados</li>
                </ol>
                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->                
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                  <input type="hidden" id="IdCedente" value="<?php echo $_SESSION['cedente']; ?>">
                  <input type="hidden" id="IdEstrategia" value="<?php echo $_SESSION['IdEstrategia']; ?>">
                  <input type="hidden" id="IdSubQuery" value="0">
                  <input type="hidden" id="SeleccioneTipoEstrategia" value="0">
                  <div class="row">
                    <div class="col-md-3" id="msgRegistros" style="display: none;">
                      <div class="panel">
                        <div class="panel-body" style="padding: 10px;">
                          <div id="DivRegistros"></div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-9" id="divFormularioSegmentacion" style="display: none;">
                      <div class="panel">
                        <div class="panel-body" style="padding: 15px;">
                          <strong style="margin-bottom: 20px; padding-bottom: 8px; width: 100%; border-bottom: 1px solid #ccc; display: block;">Nueva segmentación</strong>
                          <form class="form-horizontal" style="margin-top: 10px;">
                            <div class="row" style="padding: 5px 12px; min-height: 80px;">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="tabla" class="col-sm-3 control-label" style="vertical-align: middle;">Tabla:</label>
                                  <div class="col-sm-9" style="padding: 0 5px;">
                                    <div class="select-dropdown">
                                      <select name="selTable" id="selTable">
                                      </select>
                                    </div>
                                  </div>
                                </div>
                              </div><!-- col 0 -->
                              <div class="col-md-4">
                                <div class="form-group" id="inputColumna" style="display: none;">
                                  <label for="tabla" class="col-sm-3 control-label">Columna:</label>
                                  <div class="col-sm-9" style="padding: 0 10px;">
                                    <div class="select-dropdown">
                                      <select name="selColumn" id="selColumn">
                                      </select>
                                     </div>
                                  </div>
                                </div>
                              </div><!-- col 1 -->
                              <div class="col-md-4">
                                <div class="form-group" id="inputLogica" style="display: none;">
                                  <label for="tabla" class="col-sm-3 control-label">Lógica:</label>
                                  <div class="col-sm-9" style="padding: 0 20px 0 2px;">
                                    <div class="select-dropdown">
                                      <select name="selLogic" id="selLogic"></select>
                                    </div>
                                  </div>
                                </div>
                              </div><!-- col 2 -->
                            </div><!-- row 0 -->
                            <div class="row" style="padding: 5px 12px;">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="tabla" class="col-sm-3 control-label">Valor:</label>
                                  <div class="col-sm-9" style="padding: 0 5px;" id="DivValor">
                                    <input type="text" name="" class="form-control" id="" disabled>
                                  </div>
                                </div> 
                              </div>
                              <div class="col-md-4" id="Between" style="display: none;">
                                <div class="form-group">
                                  <label for="tabla" class="col-sm-3 control-label">Valor final:</label>
                                  <div class="col-sm-9" style="padding: 0 5px;" id="DivValor2">
                                    <input type="text" name="" class="form-control" id="" disabled>
                                  </div>
                                </div> 
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="tabla" class="col-sm-3 control-label" style="text-align: right;">Segmento:</label>
                                  <div class="col-sm-9" style="padding: 0 5px;" id="DivCola">
                                    <input type="text" name="" class="form-control" id="" disabled>
                                  </div>
                                </div> 
                              </div>
                            </div><!-- row 1 -->
                            <div class="row" style="padding: 5px 15px;">
                              <div class="col-md-6">
                                <button class="btn btn-success btn-block" disabled="disabled" id="CrearEstrategia" type="button">
                                  <i class="fa fa-object-ungroup"></i>&nbsp;&nbsp;Crear
                                </button>
                              </div>
                              <div class="col-md-3">
                                <button class="btn btn-info btn-block" id="resetForm" type="button" title="Reiniciar segmentación">
                                  <i class="fa fa-refresh"></i>&nbsp;&nbsp;Reiniciar
                                </button>
                              </div>
                              <div class="col-md-3">
                                <button class="btn btn-danger btn-block" id="cancelForm" type="button" title="Cancelar segmentación">
                                  <i class="fa fa-close"></i>&nbsp;&nbsp;Cancelar
                                </button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
					        <div class="row">
                        <div class="col-sm-12">                            
                            <div class="panel">                          
                                <div class="panel-body" style="padding: 12px 25px;">
                                  <div class="row" style="padding: 15px;">
                                    <div class="col-md-10">
                                      <h2 style="margin-top: 0;"><i class="fa-solid fa-folder-closed"></i>&nbsp;&nbsp;<?php echo $NombreEstrategia;?></h2>
                                    </div>
                                    <div class="col-md-2" style="vertical-align: middle;">
                                      <button type="button" class="btn btn-block btn-danger" id="Deshacer" title="Eliminar">
                                        <i class="fa fa-eraser"></i>&nbsp;&nbsp;Deshacer
                                      </button>                                      
                                    </div>
                                  </div>                                   
                                  <div class="row" style="padding: 12px;">
                                    <div class="col-md-12">
                                      <div id="DivMostrarEstrategias"></div>
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
        <div class="modal fade" tabindex="-1" role="dialog" id="modalAsignar">
            <div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bg-gris-oscuro p-t-10 p-b-10">
						<h4 class="modal-title c-negro">Asignar a servicio <button type="button" data-dismiss="modal" class="close c-negro f-25" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
					</div>
					<div class="modal-body">
            <form action="" onsubmit="return false;" id="frmAsignarServicio">
              <div class="row mb-3" id="seleccionarServicio" style="margin-bottom: 20px;">
                <div class="col-sm-12">
                  <label for="nombreServicio" style="margin-bottom: 10px;">Servicio:</label>
                  <select class="form-control form-control-lg" name="servicio" id="selServicio">
                    <option value="">-- Seleccione --</option>
                    <option value="discador">Discador telefónico</option>
                  </select>
                </div>
              </div>
              <div class="row" id="modalDivDiscador" style="margin-bottom: 10px; margin-top: 25px; display: none;">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="nombreCampaña">Tipo campaña:</label>
                    <select name="tipo_discado" id="tipo_discado" class="form-control">
                      <option value="1">Predictivo</option>
                      <option value="2">Progresivo</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="nombreCampaña">Intensidad:</label>
                    <select name="intensidad" id="intensidad" class="form-control">
                      <option value="1">1 por agente</option>
                      <option value="5">5 por agente</option>
                    </select>
                  </div>
                  <!--
                  <div class="form-group">
                    <label for="nombreCampaña">Nombre de la Campaña:</label>
                    <input type="text" class="form-control" id="nombre_campana" name="nombre_campana">
                  </div>
                  <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <input type="text" class="form-control" id="descripcion_campana" name="descripcion_campana">
                  </div>
                  -->
                  <button class="btn btn-primary crear_campana" id="Asignacion_Servicio_btn" style="margin-top: 15px;">
                    <i class="fa fa-share-from-square"></i>&nbsp;&nbsp;Asignar
                  </button>
                </div>
              </div>
              <input type="hidden" name="cedente" value="<?php echo (int) $_SESSION['cedente']; ?>" />
              <input type="hidden" name="name" id="nombre_campana" value="">
              <input type="hidden" name="description" id="descripcion_campana" value="">
              <input type="hidden" name="id_grupo" id="idFlow" value="">
            </form>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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
  </div>
        </div>
        <div class="modal"><!-- Place at bottom of page --></div>
        <!--===================================================-->
        <script id="Template" type="text/template">
            <input type="hidden" class="form-control" name='IdCola' id='IdCola'>
            <div class="row">
                <div class="col-sm-12">
			        <div class="form-group">
				        <label class="control-label">Porcentaje</label>
                        <select class="form-control selectpicker" data-live-search="true" id="Porcentaje" name="Porcentaje">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
        </script>
        <div id="AsignadorDeCasos" class="modal fade" tabindex="-1" role="dialog" style="z-index:1050">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gris-oscuro p-t-10 p-b-10">
                        <h4 class="modal-title c-negro">Asignar: <span id="NombreCola"></span><button type="button" data-dismiss="modal" class="close c-negro f-25" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
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
                    </div><!-- /.modal-body -->
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
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
        </div>
        
        <!-- /.modal -->
        <!--=============FIN DE TEMPLATES===============-->
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
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../plugins/bootstrap-validator/bootstrapValidator.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../js/demo/ui-alerts.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../js/demo/ui-modals.js"></script>
    <script src="../plugins/bootstrap-datetimepicker/moment.js"></script>   
    <script src="../plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="../plugins/jquery-mask/jquery.mask.min.js"></script> 
    <script src="../js/estrategia/Estrategias.js"></script>
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet" media="screen">
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
</body>
</html>