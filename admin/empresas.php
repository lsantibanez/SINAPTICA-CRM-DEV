<?php
include("../class/global/global.php");
require_once('../class/session/session.php');
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "adm,sis,crea_man"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{
  //to fully log out a visitor we need to clear the session varialbles
  $objetoSession->borrarVariablesSession();
  $objetoSession->logoutGoTo("../index");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = '';
if (isset($_SESSION['cedente'])) $cedente = $_SESSION['cedente'];
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
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
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
            background: rgba( 255, 255, 255, .8 ) url('../img/gears.gif') 50% 50% no-repeat;
            }
body.loading
           {
            overflow: hidden;
           }
body.loading .modal
          {
           display: block;
          }

 #divtablapeq {
    width: 500px;
    }
 #divtablamed {
    width: 600px;
    }
    .dropdown-menu.open {
    max-height: none !important;
}

    </style>
</head>
<body>
  <input type="hidden" name="cedente" id="cedente" value="<?php echo $cedente;?>">
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
            <h1 class="page-header text-overflow">Gestionar empresas</h1>
          </div>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End page title-->
          <!--Breadcrumb-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <ol class="breadcrumb">
            <li><a href="#">Configuración</a></li>
            <li><a href="#">Sistema</a></li>
            <li class="active">Gestionar empresas</li>
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
                    <div class="panel" id='sql'>
                      <!--===================================================-->
                      <div class="panel-body">
                        <div class="col-sm-12">
                        <!-- INICIO CONTENIDO PRINCIPAL -->
                           <!-- Inicio listar tablas -->
                           <script id="listaCedente" type="text/template">
                            <div class="table-responsive" style="min-height: 360px; overflow: auto;">
                               <div class="row" style="padding: 12px 0;">
                                   <div class="col-sm-3">
                                      <button id="AddCedente" class="btn btn-success btn-block">
                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;Nuevo proyecto
                                      </button>
                                   </div>
                               </div>
                                <table id="listaCedentes" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 80%">Proyecto</th>
                                            <!-- <th>Campos</th> -->
                                            <th style="width: 20%">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                 </table>
                            </div>
                            </script>
                           <!-- Fin listar tablas -->
                            <!-- Inicio listar mandantes -->
                            <div class="table-responsive">
                               <div class="row">
                                   <div class="col-sm-3">
                                      <button id="AddMandante" class="btn btn-success btn-block">
                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;Nueva empresa
                                      </button>
                                   </div>
                               </div>
                                <table id="listaMandantes" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="width:80%">Empresa</th>
                                            <!-- <th>Campos</th> -->
                                            <th style="width:20%">Acciones</th>
                                        </tr>
                                    </thead>
                                 </table>
                            </div>
                           <!-- Fin listar tablas -->
                           <!-- Inicio Asignar tablas al cedente (Registrar tabla y campos) -->
                          <script id="RegistrarCedente" type="text/template">
                            <div class="row">
                              <div class="col-md-12">
                                <form class="form-horizontal">
                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="form-group">
                                        <div class="col-md-3">
                                          <label>Nombre</label>
                                        </div>
                                        <div class="col-md-8">
                                          <input type="text" name="nombreCedente" id="nombreCedente" class="form-control" value="">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="form-group">
                                        <div class="col-md-3">
                                          <label>Fecha ingreso</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div id="date-range">
                                              <div class="input-daterange input-group" id="datepicker">
                                                  <input type="text" class="form-control" id="fechaIngreso" name="fechaIngreso" />
                                              </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row" id="focoOptions" style="display: none;">
                                    <div class="col-md-12">
                                      <div class="form-group">
                                        <div class="col-md-12">
                                          <div class="col-md-6">
                                            <label>Tipo Operación:</label>
                                            <select name="tipoOperacion" id="TipoOperacion" class="selectpicker" data-width="100%"  title="Seleccione" data-live-search="true" data-width="100%">
                                              <option value = "2">Masivo</option>
                                              <option value = "1" selected>Factura</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-12">
                                          <div class="col-md-6">
                                            <label>Plan Discado:</label>
                                            <select name="planDiscado" id="PlanDiscado" class="selectpicker" data-width="100%"  title="Seleccione" data-live-search="true" data-width="100%">
                                              <option value = "0" selected>Sí</option>
                                              <option value = "1">No</option>
                                            </select>
                                          </div>
                                          <div class="col-md-12" id="IPDiscadorContainer" style="display: none;">
                                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                              <label>IP Discador:</label>
                                              <input type="text" class="form-control" id="IPDiscador" name="IPDiscador" />
                                            </div>
                                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                              <label>IP Discador:</label>
                                              <textarea name="DialPlan" class="form-control" id="DialPlan" rows="4"></textarea>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </script>
                          <!--  Fin Asignar tablas al cedente (Registrar tabla y campos) -->
                          <!-- Inicio modificar cedente -->
                          <script id="modificaCedente" type="text/template">
                            <div class="row">
                              <div class="col-md-12">
                                <form class="form-horizontal" id="form_cedente">
                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="form-group">
                                        <div class="col-md-3">
                                          <label>Nombre</label>
                                        </div>
                                        <div class="col-md-8">
                                          <input type="text" name="nombreCedente" id="nombreCedente" class="form-control" value="">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="form-group">
                                        <div class="col-md-3">
                                          <label>Fecha ingreso</label>
                                        </div>
                                        <div class="col-md-8">
                                          <div id="date-range">
                                            <input type="text" class="form-control" id="fechaIngreso" name="fechaIngreso" />
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div id="focoOptions" style="display: none;">
                                    <div class="row" style="margin-bottom:10px">
                                      <div class="col-md-6">
                                        <label>Tipo Operación:</label>
                                        <select name="TipoOperacion" id="TipoOperacion" class="selectpicker" data-width="100%"  title="Seleccione" data-live-search="true" data-width="100%">
                                          <option value = "2">Masivo</option>
                                          <option value = "1">Factura</option>
                                        </select>
                                      </div>
                                      <div class="col-md-6">
                                        <label>Tipo de Refresco:</label>
                                        <select name="tipo_refresco" id="tipo_refresco" class="selectpicker" data-width="100%"  title="Seleccione" data-live-search="true" data-width="100%">
                                          <option value = "0">Ninguna</option>
                                          <option value = "1">Periodico</option>
                                          <option value = "2">Mensual</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="row" style="margin-bottom:10px">
                                      <div class="col-md-6">
                                        <label>Speech Analytics:</label><br>
                                        <input id='posee_speech' name='posee_speech' class='toggle-switch' type='checkbox'>
                                        <label class='toggle-switch-label'></label>
                                      </div>
                                      <div class="col-md-6">
                                        <label>Plan Discado:</label><br>
                                        <input id='PlanDiscado' name='PlanDiscado' class='toggle-switch' type='checkbox'>
                                        <label class='toggle-switch-label'></label>
                                      </div>
                                    </div>
                                    <div id="IPDiscadorContainer" style="display: none;">
                                      <div class="row" style="margin-bottom:10px">
                                        <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                          <label>IP Discador:</label>
                                          <input type="text" class="form-control" id="IPDiscador" name="IPDiscador" />
                                        </div>
                                        <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                          <label>IP Discador:</label>
                                          <textarea name="DialPlan" class="form-control" id="DialPlan" rows="4"></textarea>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row" style="margin-bottom:10px">
                                      <div class="col-md-6">
                                        <label>Omnicanalidad:</label><br>
                                        <input id='omnicanalidad' name='omnicanalidad' class='toggle-switch' type='checkbox'>
                                        <label class='toggle-switch-label'></label>
                                      </div>
                                      <div class="col-md-6">
                                        <label>Compromiso:</label><br>
                                        <input id='compromiso' name='compromiso' class='toggle-switch' type='checkbox'>
                                        <label class='toggle-switch-label'></label>
                                      </div>
                                    </div>
                                    <div class="row" style="margin-bottom:10px">
                                      <div class="col-md-6">
                                        <label>Agendamiento:</label><br>
                                        <input id='agendamiento' name='agendamiento' class='toggle-switch' type='checkbox'>
                                        <label class='toggle-switch-label'></label>
                                      </div>
                                      <div class="col-md-6">
                                        <label>Facturas:</label><br>
                                        <input id='facturas' name='facturas' class='toggle-switch' type='checkbox'>
                                        <label class='toggle-switch-label'></label>
                                      </div>
                                    </div>
                                    <div id="AgendamientoContainer" style="display: none;">
                                      <div class="row" style="margin-bottom:10px">
                                        <div class="col-md-6">
                                          <label>Agendamiento Obligatorio:</label><br>
                                          <input id='agendamiento_obligatorio' name='agendamiento_obligatorio' class='toggle-switch' type='checkbox'>
                                          <label class='toggle-switch-label'></label>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row" style="margin-bottom:10px">
                                      <div class="col-md-6">
                                        <label>Scoring:</label><br>
                                        <input id='posee_scoring' name='posee_scoring' class='toggle-switch' type='checkbox'>
                                        <label class='toggle-switch-label'></label>
                                      </div>
                                      <div class="col-md-6">
                                        <label>Carga Personalizada:</label><br>
                                        <input id='carga_personalizada' name='carga_personalizada' class='toggle-switch' type='checkbox'>
                                        <label class='toggle-switch-label'></label>
                                      </div>
                                    </div>
                                    <div class="row" style="margin-bottom:10px">
                                      <div class="col-md-6">
                                        <label>Algoritmo Discado:</label>
                                        <select name="algoritmo_discado" id="algoritmo_discado" class="selectpicker" data-width="100%"  title="Seleccione" data-live-search="true" data-width="100%">
                                          <option value = "1">Modo 1</option>
                                          <option value = "2">Modo 2</option>
                                        </select>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </script>
                         <!--  Fin modificar cedente-->
                          <!-- Inicio crear mandante -->
                            <script id="RegistrarMandante" type="text/template">
                              <div class="row">
                                <div class="col-md-12">
                                  <form class="form-horizontal">
                                    <input type="hidden" name="evaluar" id="evaluar" value="0">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                        <label for="nombre" class="col-sm-3 control-label">Nombre:</label>
                                          <div class="col-sm-7">
                                            <input type="text" name="nombreMandante" id="nombreMandante" class="form-control" value="">
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                  </form>
                                </div>
                              </div>
                           </script>
                          <!--  Fin crear mandante -->
                          <!-- Inicio modificar mandante -->
                            <script id="modificarMandante" type="text/template">
                              <div class="row">
                                <div class="col-md-12">
                                  <form class="form-horizontal">
                                    <input type="hidden" name="evaluar" value="0">                                    
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <div class="col-md-3">
                                            <label>Nombre</label>
                                          </div>
                                          <div class="col-md-8">
                                            <input type="text" name="nombreMandante" id="nombreMandante" class="form-control" value="">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </form>
                                </div>
                              </div>
                           </script>
                          <!--  Fin modificar mandante -->
                           <!-- Inicio modificar tablas al cedente (Registrar tabla y campos) -->
                            <script id="ModificarCedente" type="text/template">
                              <div class="row">
                                <div class="col-md-12">
                                  <form class="form-horizontal">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <div class="col-md-3">
                                            <label>Tabla</label>
                                          </div>
                                          <div class="col-md-8">
                                            <select class="selectpicker" title="Seleccione" id="UpdatetablaBD" name="UpdatetablaBD" data-live-search="true" data-width="100%"  title="Seleccione" data-live-search="true" data-width="100%">

                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <div class="col-md-3">
                                            <label>Campos</label>
                                          </div>
                                          <div class="col-md-8">
                                            <select class="selectpicker" multiple title="Seleccione" id="UpdatecamposTabla" name="UpdatecamposTabla" data-live-search="true" data-width="100%"  title="Seleccione" data-live-search="true" data-width="100%">

                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </form>
                                </div>
                              </div>
                           </script>
                          <!--  Fin modificiar al cedente (Registrar tabla y campos) -->
                        <!-- FIN CONTENIDO PRINCIPAL -->
                        </div>
                      </div>
                      <!--===================================================-->
      								<!--End Panel model-->
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
    <script src="../js/admin/cedente.js"></script>
</body>
</html>