<?php
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "car,carAuAdm"));
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
if (isset($_SESSION['cedente'])){
    $cedente = $_SESSION['cedente'];
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
    <script src="../plugins/pace/pace.min.js"></script>
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../css/global/global.css" rel="stylesheet">

    <style type="text/css">
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
        .dropdown-menu.open {
            max-height: none !important;
        }
        #Cierres td{
            border-top: 1px solid #CCCCCC;
        }
        #TablePlan{
            width: 100% !important;
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
            <h1 class="page-header text-overflow">Administrador de Carga Automática</h1>
          <!--Searchbox-->
          </div>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End page title-->
          <!--Breadcrumb-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <ol class="breadcrumb">
            <li><a href="#">Carga</a></li>
            <li class="active">Administrador de Carga Automática</li>
          </ol>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End breadcrumb-->
          <!--Page content-->
          <!--===================================================-->
            <div id="page-content">
                <div class="row">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h2 class="panel-title">Administrador de Carga Automática</h2>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <button style="margin: 10px 0;" id="AddTemplateCarga" class="btn btn-purple">Agregar</button>
                                <br>
                                <table table-responsive id="TableTemplate">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Tipo de Archivo</th>
                                            <th>Separador</th>
                                            <th>Posee Cabecero</th>
                                            <th>Cantidad de Hojas</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="Sheets">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h2 class="panel-title">Hojas</h2>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <button style="margin: 10px 0" id="AddSheet" class="btn btn-purple">Agregar</button>
                                    <br>
                                    <table table-responsive id="TableSheets">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Nombre</th>
                                                <th>Numero de Hoja</th>
                                                <th>Tipo de Carga</th>
                                                <th>Acción</th>
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
        <?php include("../layout/footer.php"); ?>
        <!--===================================================-->
        <!-- END FOOTER -->
        <!-- SCROLL TOP BUTTON -->
        <!--===================================================-->
        <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
        <div class="modal"><!-- Place at bottom of page --></div>
        <!--===================================================-->
    </div>
    <script id="CargaTemplate" type="text/template">
        <input type="hidden" name="id" id="id">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Nombre:</label>
                    <input class="form-control" name="NombreTemplate" type="text">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label">Tipo de Archivo:</label>
                    <select class="selectpicker form-control" name="TipoArchivo" title="Seleccione" data-live-search="true" data-width="100%">
                        <option value="xlsx">Archivo Excel (XLSX)</option>
                        <option value="xls">Archivo Excel (XLS)</option>
                        <option value="csv">Archivo CSV</option>
                        <option value="txt">Archivo de Texto</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4" id="ContainerSeparador" style="display: none;">
                <div class="form-group">
                    <label class="control-label">Separador:</label>
                    <select class="selectpicker form-control" name="Separador" data-live-search="true" data-width="100%" disabled>
                        <option value="">Sin separador</option>
                        <option value=";">;</option>
                        <option value="|">|</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4" id="ContainerCabecero" style="display: none;">
                <div class="form-group" style="margin-top: 28px;">
                    Contiene Fila de Encabezado <input type="checkbox" class='toggle-switch' name="Cabecero"><label class='toggle-switch-label'></label>
                </div>
            </div>
        </div>
    </script>
    <script id="ColumnasTemplate" type="text/template">
        <div class="row">
            <div class="Table">
                <button style="margin: 10px 0;" id="AddColumna" class="btn btn-success">Agregar</button>
                <br>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 class="panel-title"><label class="form-checkbox form-icon form-no-label btn btn-primary checkboxTable" tablename="Persona" tabledesc="Persona" style="margin-top: 10px;margin-right: 10px;"><input type="checkbox"></label>Tabla Personas</h2>
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="ColumnsTablePersona" cellspacing="0" width="100%">
                            <thead>
                                <th>N°</th>
                                <th>Tabla</th>
                                <th>Campo</th>
                                <th class="ColumnaPosicion">Columna</th>
                                <th class="FuncionCantCaracteres">Funcion</th>
                                <th>Parametro</th>
                                <th>Configurado</th>
                                <th>Mandatorio</th>
                                <th>Acción</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 class="panel-title"><label class="form-checkbox form-icon form-no-label btn btn-primary checkboxTable" tablename="Deuda" tabledesc="Deuda" style="margin-top: 10px;margin-right: 10px;"><input type="checkbox"></label>Tabla Deudas</h2>
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="ColumnsTableDeuda" cellspacing="0" width="100%">
                            <thead>
                                <th>N°</th>
                                <th>Tabla</th>
                                <th>Campo</th>
                                <th class="ColumnaPosicion">Columna</th>
                                <th class="FuncionCantCaracteres">Funcion</th>
                                <th>Parametro</th>
                                <th>Configurado</th>
                                <th>Mandatorio</th>
                                <th>Acción</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 class="panel-title"><label class="form-checkbox form-icon form-no-label btn btn-primary checkboxTable" tablename="fono_cob" tabledesc="Telefono" style="margin-top: 10px;margin-right: 10px;"><input type="checkbox"></label>Tabla Fonos</h2>
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="ColumnsTableFono" cellspacing="0" width="100%">
                            <thead>
                                <th>N°</th>
                                <th>Tabla</th>
                                <th>Campo</th>
                                <th class="ColumnaPosicion">Columna</th>
                                <th class="FuncionCantCaracteres">Funcion</th>
                                <th>Parametro</th>
                                <th>Configurado</th>
                                <th>Mandatorio</th>
                                <th>Acción</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 class="panel-title"><label class="form-checkbox form-icon form-no-label btn btn-primary checkboxTable" tablename="Direcciones" tabledesc="Direcciones" style="margin-top: 10px;margin-right: 10px;"><input type="checkbox"></label>Tabla Direcciones</h2>
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="ColumnsTableDireccion" cellspacing="0" width="100%">
                            <thead>
                                <th>N°</th>
                                <th>Tabla</th>
                                <th>Campo</th>
                                <th class="ColumnaPosicion">Columna</th>
                                <th class="FuncionCantCaracteres">Funcion</th>
                                <th>Parametro</th>
                                <th>Configurado</th>
                                <th>Mandatorio</th>
                                <th>Acción</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 class="panel-title"><label class="form-checkbox form-icon form-no-label btn btn-primary checkboxTable" tablename="Mail" tabledesc="Mails" style="margin-top: 10px;margin-right: 10px;"><input type="checkbox"></label>Tabla Mails</h2>
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="ColumnsTableMail" cellspacing="0" width="100%">
                            <thead>
                                <th>N°</th>
                                <th>Tabla</th>
                                <th>Campo</th>
                                <th class="ColumnaPosicion">Columna</th>
                                <th class="FuncionCantCaracteres">Funcion</th>
                                <th>Parametro</th>
                                <th>Configurado</th>
                                <th>Mandatorio</th>
                                <th>Acción</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script id="ColumnasTemplatePagos" type="text/template">
        <div class="row">
            <div class="Table">
                <button style="margin: 10px 0;" id="AddColumna" class="btn btn-success">Agregar</button>
                <br>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 class="panel-title"><label class="form-checkbox form-icon form-no-label btn btn-primary checkboxTable" tablename="pagos_deudas" tabledesc="Pagos" style="margin-top: 10px;margin-right: 10px;"><input type="checkbox"></label>Tabla Pagos</h2>
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="ColumnsTablePagos" cellspacing="0" width="100%">
                            <thead>
                                <th>N°</th>
                                <th>Tabla</th>
                                <th>Campo</th>
                                <th class="ColumnaPosicion">Columna</th>
                                <th class="FuncionCantCaracteres">Funcion</th>
                                <th>Parametro</th>
                                <th>Configurado</th>
                                <th>Mandatorio</th>
                                <th>Acción</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script id="ColumnasTemplateGestiones" type="text/template">
        <div class="row">
            <div class="Table">
                <button style="margin: 10px 0;" id="AddColumna" class="btn btn-success">Agregar</button>
                <br>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 class="panel-title"><label class="form-checkbox form-icon form-no-label btn btn-primary checkboxTable" tablename="gestion_ult_trimestre" tabledesc="Gestiones" style="margin-top: 10px;margin-right: 10px;"><input type="checkbox"></label>Tabla Gestiones</h2>
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="ColumnsTableGestiones" cellspacing="0" width="100%">
                            <thead>
                                <th>N°</th>
                                <th>Tabla</th>
                                <th>Campo</th>
                                <th class="ColumnaPosicion">Columna</th>
                                <th class="FuncionCantCaracteres">Funcion</th>
                                <th>Parametro</th>
                                <th>Configurado</th>
                                <th>Mandatorio</th>
                                <th>Acción</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script id="AddColumnasTemplate" type="text/template">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Tabla:</label>
                    <select class="selectpicker form-control" name="Tabla" title="Seleccione" data-live-search="true" data-width="100%">
                        <option value="Persona">Persona</option>
                        <option value="Deuda">Deuda</option>
                        <option value="fono_cob">Fono</option>
                        <option value="Mail">Mail</option>
                        <option value="Direcciones">Direccion</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Campo:</label>
                    <select class="selectpicker form-control" name="Campo" title="Seleccione" data-live-search="true" data-width="100%"></select>
                </div>
            </div>
            <div class="col-sm-6" id="ContainerPatronFecha" style="display: none;">
                <div class="col-sm-6">
                    <div class="form-group" style="margin: 0 !important;">
                        <label class="control-label">Patron de Fecha:</label>
                        <select class="selectpicker form-control" name="PatronFechaSelect" title="Seleccione" data-live-search="true" data-width="100%">
                            <option value="dd-MM-yyyy">dd-MM-yyyy - 01-01-1900</option>
                            <option value="MM-dd-yyyy">MM-dd-yyyy - 01-01-1900</option>
                            <option value="yyyyMMdd">yyyyMMdd - 19000101</option>
                            <option value="HH:mm:ss">HH:mm:ss - 15:37:15</option>
                            <option value="hh:mm:ss">hh:mm:ss - 3:37:15</option>
                            <option value="dd-MM-yyyy HH:mm:ss">dd-MM-yyyy HH:mm:ss - 01-01-1900 15:37:15</option>
                            <option value="o">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group" style="margin: 0 !important;">
                        <label class="control-label">Patron de Fecha:</label>
                        <input type="text" class="form-control" name="PatronFecha">
                    </div>
                </div>
            </div>
            <div class="col-sm-6" id="ContainerPrioridadFono" style="display: none;">
                <div class="form-group" style="margin: 0 !important;">
                    <label class="control-label">Prioridad de Teléfono:</label>
                    <input type="text" class="form-control SoloNumeros" name="PrioridadFono" value="1">
                </div>
            </div>
            <div class="col-sm-6" id="ContainerCaracteresTxt" style="display: none;">
                <div class="col-sm-6">
                    <div class="form-group" style="margin: 0 !important;">
                        <label class="control-label">Posicion Inicio:</label>
                        <input type="number" class="form-control" name="CaracterDesde">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group" style="margin: 0 !important;">
                        <label class="control-label">Cant. de Caracteres:</label>
                        <input type="number" class="form-control" name="CaracterHasta">
                    </div>
                </div>
            </div>
            <div class="col-sm-6" id="ContainerNumeroColumna" style="display: none;">
                <div class="form-group">
                    <label class="control-label">Columna:</label>
                    <input type="text" class="form-control" name="ColumnaExcel">
                </div>
            </div>
            <div id="ContainerFuncion">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Funcion:</label>
                        <select class="selectpicker form-control" name="Funcion" title="Seleccione" data-live-search="true" data-width="100%"></select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Parametros:</label>
                        <input type="text" class="form-control" name="Parametros">
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script id="AddColumnasTemplatePagos" type="text/template">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Tabla:</label>
                    <select class="selectpicker form-control" name="Tabla" title="Seleccione" data-live-search="true" data-width="100%">
                        <option value="pagos_deudas">Pagos</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Campo:</label>
                    <select class="selectpicker form-control" name="Campo" title="Seleccione" data-live-search="true" data-width="100%"></select>
                </div>
            </div>
            <div class="col-sm-6" id="ContainerPatronFecha" style="display: none;">
                <div class="col-sm-6">
                    <div class="form-group" style="margin: 0 !important;">
                        <label class="control-label">Patron de Fecha:</label>
                        <select class="selectpicker form-control" name="PatronFechaSelect" title="Seleccione" data-live-search="true" data-width="100%">
                            <option value="dd-MM-yyyy">dd-MM-yyyy - 01-01-1900</option>
                            <option value="MM-dd-yyyy">MM-dd-yyyy - 01-01-1900</option>
                            <option value="yyyyMMdd">yyyyMMdd - 19000101</option>
                            <option value="HH:mm:ss">HH:mm:ss - 15:37:15</option>
                            <option value="hh:mm:ss">hh:mm:ss - 3:37:15</option>
                            <option value="dd-MM-yyyy HH:mm:ss">dd-MM-yyyy HH:mm:ss - 01-01-1900 15:37:15</option>
                            <option value="o">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group" style="margin: 0 !important;">
                        <label class="control-label">Patron de Fecha:</label>
                        <input type="text" class="form-control" name="PatronFecha">
                    </div>
                </div>
            </div>
            <div class="col-sm-6" id="ContainerCaracteresTxt" style="display: none;">
                <div class="col-sm-6">
                    <div class="form-group" style="margin: 0 !important;">
                        <label class="control-label">Posicion Inicio:</label>
                        <input type="number" class="form-control" name="CaracterDesde">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group" style="margin: 0 !important;">
                        <label class="control-label">Cant. de Caracteres:</label>
                        <input type="number" class="form-control" name="CaracterHasta">
                    </div>
                </div>
            </div>
            <div class="col-sm-6" id="ContainerNumeroColumna" style="display: none;">
                <div class="form-group">
                    <label class="control-label">Columna:</label>
                    <input type="text" class="form-control" name="ColumnaExcel">
                </div>
            </div>
            <div id="ContainerFuncion">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Funcion:</label>
                        <select class="selectpicker form-control" name="Funcion" title="Seleccione" data-live-search="true" data-width="100%"></select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Parametros:</label>
                        <input type="text" class="form-control" name="Parametros">
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script id="UpdateColumnasTemplate" type="text/template">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Tabla:</label>
                    <select class="selectpicker form-control" name="Tabla" title="Seleccione" data-live-search="true" data-width="100%">
                        <option value="Persona">Persona</option>
                        <option value="Deuda">Deuda</option>
                        <option value="fono_cob">Fono</option>
                        <option value="Mail">Mail</option>
                        <option value="Direcciones">Direccion</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Campo:</label>
                    <select class="selectpicker form-control" name="Campo" title="Seleccione" data-live-search="true" data-width="100%"></select>
                </div>
            </div>
            <div class="col-sm-6" id="ContainerPatronFecha" style="display: none;">
                <div class="col-sm-6">
                    <div class="form-group" style="margin: 0 !important;">
                        <label class="control-label">Patron de Fecha:</label>
                        <select class="selectpicker form-control" name="PatronFechaSelect" title="Seleccione" data-live-search="true" data-width="100%">
                            <option value="dd-MM-yyyy">dd-MM-yyyy - 01-01-1900</option>
                            <option value="MM-dd-yyyy">MM-dd-yyyy - 01-01-1900</option>
                            <option value="yyyyMMdd">yyyyMMdd - 19000101</option>
                            <option value="HH:mm:ss">HH:mm:ss - 15:37:15</option>
                            <option value="hh:mm:ss">hh:mm:ss - 3:37:15</option>
                            <option value="dd-MM-yyyy HH:mm:ss">dd-MM-yyyy HH:mm:ss - 01-01-1900 15:37:15</option>
                            <option value="o">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group" style="margin: 0 !important;">
                        <label class="control-label">Patron de Fecha:</label>
                        <input type="text" class="form-control" name="PatronFecha">
                    </div>
                </div>
            </div>
            <div class="col-sm-6" id="ContainerPrioridadFono" style="display: none;">
                <div class="form-group" style="margin: 0 !important;">
                    <label class="control-label">Prioridad de Teléfono:</label>
                    <input type="text" class="form-control SoloNumeros" name="PrioridadFono" value="1">
                </div>
            </div>
            <div class="col-sm-6" id="ContainerCaracteresTxt" style="display: none;">
                <div class="col-sm-6">
                    <div class="form-group" style="margin: 0 !important;">
                        <label class="control-label">Posicion Inicio:</label>
                        <input type="number" class="form-control" name="CaracterDesde">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group" style="margin: 0 !important;">
                        <label class="control-label">Cant. de Caracteres:</label>
                        <input type="number" class="form-control" name="CaracterHasta">
                    </div>
                </div>
            </div>
            <div class="col-sm-6" id="ContainerNumeroColumna" style="display: none;">
                <div class="form-group">
                    <label class="control-label">Columna:</label>
                    <input type="text" class="form-control" name="ColumnaExcel">
                </div>
            </div>
            <div id="ContainerFuncion">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Funcion:</label>
                        <select class="selectpicker form-control" name="Funcion" title="Seleccione" data-live-search="true" data-width="100%"></select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Parametros:</label>
                        <input type="text" class="form-control" name="Parametros">
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script id="AddSheetsTemplate" type="text/template">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group" style="margin: 0 !important;">
                    <label class="control-label">Tipo de Carga:</label>
                    <select class="selectpicker form-control" name="TipoCarga" title="Seleccione" data-live-search="true" data-width="100%">
                        <option value="carga">Carga</option>
                        <option value="pagos">Pagos</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Nombre de Hoja:</label>
                    <input type="text" class="form-control" name="NombreHoja">
                </div>
            </div>
            <div class="col-sm-6" id="ContainerNumeroHoja">
                <div class="form-group">
                    <label class="control-label">Numero de Hoja:</label>
                    <input type="number" class="form-control" name="NumeroHoja">
                </div>
            </div>
        </div>
    </script>
    <script id="UpdateSheetsTemplate" type="text/template">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group" style="margin: 0 !important;">
                    <label class="control-label">Tipo de Carga:</label>
                    <select class="selectpicker form-control" id="TipoCarga" name="TipoCarga" title="Seleccione" data-live-search="true" data-width="100%">
                        <option value="carga">Carga</option>
                        <option value="pagos">Pagos</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Nombre de Hoja:</label>
                    <input type="text" class="form-control" name="NombreHoja">
                </div>
            </div>
            <div class="col-sm-6" id="ContainerNumeroHoja">
                <div class="form-group">
                    <label class="control-label">Numero de Hoja:</label>
                    <input type="number" class="form-control" name="NumeroHoja">
                </div>
            </div>
        </div>
    </script>
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
    <script src="../js/carga/CargaAutomaticaAdm.js"></script>
</body>
</html>
