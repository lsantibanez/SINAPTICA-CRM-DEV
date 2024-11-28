<?php
include("../class/global/global.php");
require_once('../class/session/session.php');
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "deriva,modDeri"));
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
            <h1 class="page-header text-overflow">Modulo de Derivaciones</h1>
          <!--Searchbox-->
          </div>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End page title-->
          <!--Breadcrumb-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <ol class="breadcrumb">
            <li><a href="#">Derivaciones</a></li>
            <li class="active">Módulo de Derivaciones</li>
          </ol>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End breadcrumb-->
          <!--Page content-->
          <!--===================================================-->
            <div id="page-content">
            <?php
                $db = new DB();
                $SqlDerivaciones = "select codigoDerivaciones from derivaciones where Id_Mandante = '".$_SESSION["mandante"]."'";
                $Derivaciones = $db->select($SqlDerivaciones);
                if(count($Derivaciones) > 0){
                    $codigoDerivaciones = $Derivaciones[0]["codigoDerivaciones"];
                }else{
                    $codigoDerivaciones = "";
                }
                //$codigoDerivaciones = "1";
                switch($codigoDerivaciones){
                    case "1":
            ?>
                    <div class="row">
                        <div class="panel">
                            <div class="panel-heading bg-primary">
                                <h2 class="panel-title">Tipos de Derivaciones</h2>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label">Tipo</label><br>
                                            <select class="selectpicker form-control" name="TipoDerivacion" title="Seleccione" data-live-search="true" data-width="100%">
                                                <option value="repro">REPROGRAMACIONES</option>
                                                <option value="acuerdo">ACUERDOS DE CASTIGO</option>
                                                <option value="cesantia">CESANTIA</option>
                                                <option value="reclamo">RECLAMO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="panelReporgramaciones" style="display: none;">
                        <div class="panel">
                            <div class="panel-heading bg-primary">
                                <h2 class="panel-title">REPROGRAMACIONES</h2>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Tipo</label><br>
                                                <select class="selectpicker form-control" name="TipoReprogramacion" title="Seleccione" data-live-search="true" data-width="100%">
                                                    <option value="diario">Diario</option>
                                                    <option value="mensual" disabled >Mensual</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Fecha</label>
                                                <div class="input-daterange input-group" style="width: 100%;">
                                                    <input type="text" class="form-control" name="fechaRepro" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <button id="buscarRepro" class="btn btn-primary" style="margin-top: 10px;margin-bottom: 10px" type="submit">Buscar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="reprogramacionesDiarias">
                                    <div class="panel">
                                        <div class="panel-heading bg-primary">
                                            <h2 class="panel-title">REPROGRAMACIONES DIARIAS</h2>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" style="margin-bottom: 20px;">
                                                <button class="btn btn-primary" id="downloadRepros">Descargar</button>
                                                <button class="btn btn-primary" id="sendRepros">Enviar</button>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <table id="tablaReprogramacionesDiaria" class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Ejecutivo</th>
                                                                    <th class="text-center">Oficina</th>
                                                                    <th class="text-center">Folio</th>
                                                                    <th class="text-center">Rut</th>
                                                                    <th class="text-center">Saldo adeudado (en base)</th>
                                                                    <th class="text-center">Abono (SI-NO)</th>
                                                                    <th class="text-center">Monto abono</th>
                                                                    <th class="text-center">Día de visita </th>
                                                                    <th class="text-center">Teléfono deudor</th>
                                                                    <th class="text-center">Mail de contacto</th>
                                                                    <th class="text-center">Tipo de Reprogramación</th>
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
                                <div class="row" id="reprogramacionesMensuales">
                                    <div class="panel">
                                        <div class="panel-heading bg-primary">
                                            <h2 class="panel-title">REPROGRAMACIONES MENSUALES</h2>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="clearfix"></div>
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <table id="tablaOficinas" class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">cod</th>
                                                                    <th class="text-center">Nombre</th>
                                                                    <th class="text-center">Jefe Zonal</th>
                                                                    <th class="text-center">Direccion</th>
                                                                    <th class="text-center">Zona</th>
                                                                    <th class="text-center">Ejecutivo Normalización</th>
                                                                    <th class="text-center">Correo</th>
                                                                    <th class="text-center">Telefono</th>
                                                                    <th class="text-center">Agente Sucursal</th>
                                                                    <th class="text-center">Correo Agente</th>
                                                                    <th class="text-center">Telefono Agente</th>
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
                    </div>
                    <div class="row" id="panelAcuerdos" style="display: none;">
                        <div class="panel">
                            <div class="panel-heading bg-primary">
                                <h2 class="panel-title">ACUERDOS DE CASTIGO</h2>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Tipo</label><br>
                                                <select class="selectpicker form-control" name="TipoAcuerdos" title="Seleccione" data-live-search="true" data-width="100%">
                                                    <option value="diario">Diario</option>
                                                    <option value="mensual" disabled >Mensual</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Fecha</label>
                                                <div class="input-daterange input-group" style="width: 100%;">
                                                    <input type="text" class="form-control" name="fechaAcuerdos" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <button id="buscarAcuerdos" class="btn btn-primary" style="margin-top: 10px;margin-bottom: 10px" type="submit">Buscar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="acuerdosDiarios" style="display: none;">
                                    <div class="panel">
                                        <div class="panel-heading bg-primary">
                                            <h2 class="panel-title">ACUERDOS DIARIOS</h2>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" style="margin-bottom: 20px;">
                                                <button class="btn btn-primary" id="downloadAcuerdos">Descargar</button>
                                                <button class="btn btn-primary" id="sendAcuerdos">Enviar</button>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <table id="tablaAcuerdos" class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Ejecutivo</th>
                                                                    <th class="text-center">Oficina</th>
                                                                    <th class="text-center">Folio</th>
                                                                    <th class="text-center">Rut</th>
                                                                    <th class="text-center">Deuda Total</th>
                                                                    <th class="text-center">Deuda Capital</th>
                                                                    <th class="text-center">Abono</th>
                                                                    <th class="text-center">Resultante Menos Abono</th>
                                                                    <th class="text-center">% Rebaja sobre resultante</th>
                                                                    <th class="text-center">A pagar</th>
                                                                    <th class="text-center">Cuotas</th>
                                                                    <th class="text-center">Valor Cuota</th>
                                                                    <th class="text-center">Telefono Cliente</th>
                                                                    <th class="text-center">Dia de Visita</th>
                                                                    <th class="text-center">Mail de Contacto</th>
                                                                    <th class="text-center">Tipo de Pago</th>
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
                                <div class="row" id="acuerdosMensuales" style="display: none;">
                                    <div class="panel">
                                        <div class="panel-heading bg-primary">
                                            <h2 class="panel-title">ACUERDOS MENSUALES</h2>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" style="margin-bottom: 20px;">
                                                <button class="btn btn-primary" id="downloadAcuerdosDiarios">Descargar</button>
                                                <button class="btn btn-primary" id="sendAcuerdosDiarios" disabled>Enviar</button>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <table id="tablaAcuerdos" class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Oficina</th>
                                                                    <th class="text-center">Folio</th>
                                                                    <th class="text-center">Rut</th>
                                                                    <th class="text-center">Deuda Total</th>
                                                                    <th class="text-center">Deuda Capital</th>
                                                                    <th class="text-center">Abono</th>
                                                                    <th class="text-center">Resultante Menos Abono</th>
                                                                    <th class="text-center">% Rebaja sobre resultante</th>
                                                                    <th class="text-center">A pagar</th>
                                                                    <th class="text-center">Cuotas</th>
                                                                    <th class="text-center">Valor Cuota</th>
                                                                    <th class="text-center">Telefono Cliente</th>
                                                                    <th class="text-center">Dia de Visita</th>
                                                                    <th class="text-center">Mail de Contacto</th>
                                                                    <th class="text-center">Tipo de Pago</th>
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
                    </div>
                    <div class="row" id="panelCesantia" style="display: none;">
                        <div class="panel">
                            <div class="panel-heading bg-primary">
                                <h2 class="panel-title">CESANTIA</h2>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Tipo</label><br>
                                                <select class="selectpicker form-control" name="TipoCesantia" title="Seleccione" data-live-search="true" data-width="100%">
                                                    <option value="activo">Activo</option>
                                                    <option value="activara">Activara</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Fecha</label>
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control" name="fechaCesantiaStart" />
                                                    <span class="input-group-addon">a</span>
                                                    <input type="text" class="form-control" name="fechaCesantiaEnd" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <button id="buscarCesantia" class="btn btn-primary" style="margin-top: 10px;margin-bottom: 10px" type="submit">Buscar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="activoCesantia" style="display: none;">
                                    <div class="panel">
                                        <div class="panel-heading bg-primary">
                                            <h2 class="panel-title">ACTIVO CESANTIA</h2>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" style="margin-bottom: 20px;">
                                                <button class="btn btn-primary downloadCesantias">Descargar</button>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <table id="tablaActivoCesantia" class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Cartera</th>
                                                                    <th class="text-center">Rut</th>
                                                                    <th class="text-center">Sucursal</th>
                                                                    <th class="text-center">Observacion</th>
                                                                    <th class="text-center">Fecha</th>
                                                                    <th class="text-center">Ejecutivo</th>
                                                                    <th class="text-center">Respuesta</th>
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
                                <div class="row" id="activaraCesantia" style="display: none;">
                                    <div class="panel">
                                        <div class="panel-heading bg-primary">
                                            <h2 class="panel-title">ACTIVARA CESANTIA</h2>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" style="margin-bottom: 20px;">
                                                <button class="btn btn-primary downloadCesantias">Descargar</button>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <table id="tablaActivaraCesantia" class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Cartera</th>
                                                                    <th class="text-center">Rut</th>
                                                                    <th class="text-center">Sucursal</th>
                                                                    <th class="text-center">Observacion</th>
                                                                    <th class="text-center">Fecha de Visita</th>
                                                                    <th class="text-center">Ejecutivo</th>
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
                    </div>
                    <div class="row" id="panelReclamo" style="display: none;">
                        <div class="panel">
                            <div class="panel-heading bg-primary">
                                <h2 class="panel-title">RECLAMOS</h2>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Fecha</label>
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control" name="fechaReclamoStart" />
                                                    <span class="input-group-addon">a</span>
                                                    <input type="text" class="form-control" name="fechaReclamoEnd" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <button id="buscarReclamo" class="btn btn-primary" style="margin-top: 10px;margin-bottom: 10px" type="submit">Buscar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="showReclamos" style="display: none;">
                                    <div class="panel">
                                        <div class="panel-heading bg-primary">
                                            <h2 class="panel-title">RECLAMOS</h2>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" style="margin-bottom: 20px;">
                                                <button class="btn btn-primary downloadReclamos">Descargar</button>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <table id="tablaReclamos" class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Rut</th>
                                                                    <th class="text-center">Cartera</th>
                                                                    <th class="text-center">Fono</th>
                                                                    <th class="text-center">Fecha</th>
                                                                    <th class="text-center">Sucursal</th>
                                                                    <th class="text-center">Observacion</th>
                                                                    <th class="text-center">Gestion</th>
                                                                    <th class="text-center">Respuesta</th>
                                                                    <th class="text-center">Respuesta Sucursal</th>
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
                    </div>
            <?php
                    break;
                    case "2":
                ?>
                    <div class="row">
                        <div class="panel">
                            <div class="panel-heading bg-primary">
                                <h2 class="panel-title">Tipos de Derivaciones</h2>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label">Tipo</label><br>
                                            <select class="selectpicker form-control" name="TipoDerivacion" title="Seleccione" data-live-search="true" data-width="100%">
                                                <option value="compromisosHites">COMPROMISOS</option>
                                                <option value="compromisosHitesTributario">COMPROMISOS TRIBUTARIOS</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="panelCompromisos" style="display: none;">
                        <div class="panel">
                            <div class="panel-heading bg-primary">
                                <h2 class="panel-title">COMPROMISOS</h2>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Fecha</label>
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control" name="fechaCompromisoHitesStart" />
                                                    <span class="input-group-addon">a</span>
                                                    <input type="text" class="form-control" name="fechaCompromisoHitesEnd" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <button id="buscarCompromisosHites" class="btn btn-primary" style="margin-top: 10px;margin-bottom: 10px" type="submit">Buscar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="showCompromisos" style="display: none;">
                                    <div class="panel">
                                        <div class="panel-heading bg-primary">
                                            <h2 class="panel-title">COMPROMISOS</h2>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" style="margin-bottom: 20px;">
                                                <button class="btn btn-primary" id="downloadCompromisosHites">Descargar</button>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <table id="tablaCompromisosHites" class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Rut</th>
                                                                    <th class="text-center">Tramo</th>
                                                                    <th class="text-center">Monto</th>
                                                                    <th class="text-center">Oferta</th>
                                                                    <th class="text-center">Fecha de Compromiso</th>
                                                                    <th class="text-center">Fecha de Envio</th>
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
                    </div>
                    <div class="row" id="panelCompromisosTributarios" style="display: none;">
                        <div class="panel">
                            <div class="panel-heading bg-primary">
                                <h2 class="panel-title">COMPROMISOS TRIBUTARIOS</h2>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="row">
                                    <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Tipo</label><br>
                                                <select class="selectpicker form-control" name="TipoCompromisosTributarios" title="Seleccione" data-live-search="true" data-width="100%">
                                                    <option value="normales">Nomales</option>
                                                    <option value="especiales">Especiales</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Fecha</label>
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control" name="fechaCompromisoHitesTributarioStart" />
                                                    <span class="input-group-addon">a</span>
                                                    <input type="text" class="form-control" name="fechaCompromisoHitesTributarioEnd" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <button id="buscarCompromisosTributarios" class="btn btn-primary" style="margin-top: 10px;margin-bottom: 10px" type="submit">Buscar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="CompromisosTributariosNormal" style="display: none;">
                                    <div class="panel">
                                        <div class="panel-heading bg-primary">
                                            <h2 class="panel-title">COMPROMISOS TRIBUTARIOS NORMALES</h2>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" style="margin-bottom: 20px;">
                                                <button class="btn btn-primary downloadCompromisosTributarios">Descargar</button>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <table id="tablaCompromisosHitesTributarioNormal" class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Rut</th>
                                                                    <th class="text-center">Año Castigo</th>
                                                                    <th class="text-center">Monto</th>
                                                                    <th class="text-center">Pago Total</th>
                                                                    <th class="text-center">Fecha de Compromiso</th>
                                                                    <th class="text-center">Fecha de Envio</th>
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
                                <div class="row" id="CompromisosTributariosEspecial" style="display: none;">
                                    <div class="panel">
                                        <div class="panel-heading bg-primary">
                                            <h2 class="panel-title">COMPROMISOS TRIBUTARIOS ESPECIALES</h2>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" style="margin-bottom: 20px;">
                                                <button class="btn btn-primary downloadCompromisosTributarios">Descargar</button>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <table id="tablaCompromisosHitesTributarioEspecial" class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                <th class="text-center">Rut</th>
                                                                    <th class="text-center">Año Castigo</th>
                                                                    <th class="text-center">Monto</th>
                                                                    <th class="text-center">Pago Total</th>
                                                                    <th class="text-center">Pago Total Especial</th>
                                                                    <th class="text-center">Fecha de Compromiso</th>
                                                                    <th class="text-center">Fecha de Envio</th>
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
                    </div>
                    <?php
                    break;
                    case "3":
                ?>
                        <div class="row">
                            <div class="panel">
                                <div class="panel-heading bg-primary">
                                    <h2 class="panel-title">Tipos de Derivaciones</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Tipo</label><br>
                                                <select class="selectpicker form-control" name="TipoDerivacion" title="Seleccione" data-live-search="true" data-width="100%">
                                                    <option value="compromisosLaPolarCastigo">COMPROMISOS CASTIGO</option>
                                                    <option value="renegociacionesLaPolar">RENEGOCIACIONES</option>
                                                    <option value="ajustesDePagoLaPolarCastigo">AJUSTES DE PAGO CASTIGO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="panelCompromisosLaPolarCastigo" style="display: none;">
                            <div class="panel">
                                <div class="panel-heading bg-primary">
                                    <h2 class="panel-title">COMPROMISOS</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="control-label">Fecha</label>
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control" name="fechaCompromisoLaPolarCastigoStart" />
                                                        <span class="input-group-addon">a</span>
                                                        <input type="text" class="form-control" name="fechaCompromisoLaPolarCastigoEnd" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <button id="buscarCompromisosLaPolarCastigo" class="btn btn-primary" style="margin-top: 10px;margin-bottom: 10px" type="submit">Buscar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="CompromisosLaPolarCastigo" style="display: none;">
                                        <div class="panel">
                                            <div class="panel-heading bg-primary">
                                                <h2 class="panel-title">COMPROMISOS TRIBUTARIOS NORMALES</h2>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row" style="margin-bottom: 20px;">
                                                    <button class="btn btn-primary downloadCompromisosLaPolarCastigo">Descargar</button>
                                                </div>
                                                <div class="row">
                                                    <div class="table-responsive">
                                                        <div class="col-md-12">
                                                            <table id="tablaCompromisosLaPolarCastigo" class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">Ejecutivo</th>
                                                                        <th class="text-center">Empresa</th>
                                                                        <th class="text-center">Castigo</th>
                                                                        <th class="text-center">Rut</th>
                                                                        <th class="text-center">Sucursal</th>
                                                                        <th class="text-center">Tipo de Compromiso</th>
                                                                        <th class="text-center">Fecha De Pago</th>
                                                                        <th class="text-center">% Desc Sol</th>
                                                                        <th class="text-center">% Desc Campana</th>
                                                                        <th class="text-center">Ano Castigo</th>
                                                                        <th class="text-center">Deuda Actual</th>
                                                                        <th class="text-center">Valor Pago</th>
                                                                        <th class="text-center">Observacion</th>
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
                        </div>
                        <div class="row" id="panelRenegociacionesLaPolar" style="display: none;">
                            <div class="panel">
                                <div class="panel-heading bg-primary">
                                    <h2 class="panel-title">RENEGOCIACIONES</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="control-label">Fecha</label>
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control" name="fechaRenegociacionesLaPolarStart" />
                                                        <span class="input-group-addon">a</span>
                                                        <input type="text" class="form-control" name="fechaRenegociacionesLaPolarEnd" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <button id="buscarRenegociacionesLaPolar" class="btn btn-primary" style="margin-top: 10px;margin-bottom: 10px" type="submit">Buscar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="RenegociacionesLaPolar" style="display: none;">
                                        <div class="panel">
                                            <div class="panel-heading bg-primary">
                                                <h2 class="panel-title">RENEGOCIACIONES</h2>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row" style="margin-bottom: 20px;">
                                                    <button class="btn btn-primary downloadRenegociacionesLaPolar">Descargar</button>
                                                </div>
                                                <div class="row">
                                                    <div class="table-responsive">
                                                        <div class="col-md-12">
                                                            <table id="tablaRenegociacionesLaPolar" class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">Empresa</th>
                                                                        <th class="text-center">Asignación</th>
                                                                        <th class="text-center">Tramo</th>
                                                                        <th class="text-center">Rut</th>
                                                                        <th class="text-center">Monto Insoluto</th>
                                                                        <th class="text-center">Monto Atrasado</th>
                                                                        <th class="text-center">Fecha Mora</th>
                                                                        <th class="text-center">Cartera</th>
                                                                        <th class="text-center">Abono</th>
                                                                        <th class="text-center">Sucursal</th>
                                                                        <th class="text-center">Tipo Compromiso</th>
                                                                        <th class="text-center">Fecha de Pago</th>
                                                                        <th class="text-center">Ejecutivo</th>
                                                                        <th class="text-center">Observación</th>
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
                        </div>
                        <div class="row" id="panelAjustesDePagoLaPolarCastigo" style="display: none;">
                            <div class="panel">
                                <div class="panel-heading bg-primary">
                                    <h2 class="panel-title">AJUSTES DE PAGO CASTIGO</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="control-label">Fecha</label>
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control" name="fechaAjusteDePagoLaPolarCastigoStart" />
                                                        <span class="input-group-addon">a</span>
                                                        <input type="text" class="form-control" name="fechaAjusteDePagoLaPolarCastigoEnd" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <button id="buscarAjusteDePagosLaPolarCastigo" class="btn btn-primary" style="margin-top: 10px;margin-bottom: 10px" type="submit">Buscar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="AjusteDePagosLaPolarCastigo" style="display: none;">
                                        <div class="panel">
                                            <div class="panel-heading bg-primary">
                                                <h2 class="panel-title">AJUSTE DE PAGO CASTIGO</h2>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row" style="margin-bottom: 20px;">
                                                    <button class="btn btn-primary downloadAjusteDePagosLaPolarCastigo">Descargar</button>
                                                </div>
                                                <div class="row">
                                                    <div class="table-responsive">
                                                        <div class="col-md-12">
                                                            <table id="tablaAjusteDePagosLaPolarCastigo" class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">N</th>
                                                                        <th class="text-center">Rut</th>
                                                                        <th class="text-center">Dv</th>
                                                                        <th class="text-center">Ano</th>
                                                                        <th class="text-center">Fecha De Pago</th>
                                                                        <th class="text-center">Insoluto</th>
                                                                        <th class="text-center">Monto Pagado</th>
                                                                        <th class="text-center">Insoluto Antes Pago</th>
                                                                        <th class="text-center">A Pagar</th>
                                                                        <th class="text-center">Diferencia</th>
                                                                        <th class="text-center">Descripcion Real</th>
                                                                        <th class="text-center">Califica</th>
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
                        </div>
                    </div>
                <?php
                    break;
                    case "4":
                ?>
                        <div class="row">
                            <div class="panel">
                                <div class="panel-heading bg-primary">
                                    <h2 class="panel-title">Tipos de Derivaciones</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Tipo</label><br>
                                                <select class="selectpicker form-control" name="TipoDerivacion" title="Seleccione" data-live-search="true" data-width="100%">
                                                    <option value="oferta75DescuentoCruzVerdeConsumo">OFERTA 75% DE DESCUENTO - CC</option>
                                                    <option value="renegociacionCruzVerdeConsumo">RENEGOCIACION DE CONSUMO - CC</option>
                                                    <option value="renegociacionCruzVerdeTarjeta">RENEGOCIACION DE TARJETA - TC</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="panelOferta75DescuentoCruzVerdeConsumo" style="display: none;">
                            <div class="panel">
                                <div class="panel-heading bg-primary">
                                    <h2 class="panel-title">OFERTA DEL 75% DE DESCUENTO</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="control-label">Fecha</label>
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control" name="fechaOferta75DescuentoCruzVerdeConsumoStart" />
                                                        <span class="input-group-addon">a</span>
                                                        <input type="text" class="form-control" name="fechaOferta75DescuentoCruzVerdeConsumoEnd" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <button id="buscarOferta75DescuentoCruzVerdeConsumo" class="btn btn-primary" style="margin-top: 10px;margin-bottom: 10px" type="submit">Buscar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="Oferta75DescuentoCruzVerdeConsumo" style="display: none;">
                                        <div class="panel">
                                            <div class="panel-heading bg-primary">
                                                <h2 class="panel-title">OFERTA DEL 75% DE DESCUENTO</h2>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row" style="margin-bottom: 20px;">
                                                    <button class="btn btn-primary downloadOferta75DescuentoCruzVerdeConsumo">Descargar</button>
                                                </div>
                                                <div class="row">
                                                    <div class="table-responsive">
                                                        <div class="col-md-12">
                                                            <table id="tablaOferta75DescuentoCruzVerdeConsumo" class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">Rut</th>
                                                                        <th class="text-center">Dv</th>
                                                                        <th class="text-center">Fono Contactado</th>
                                                                        <th class="text-center">Abono</th>
                                                                        <th class="text-center">Sucursal</th>
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
                        </div>
                        <div class="row" id="panelRenegociacionCruzVerdeConsumo" style="display: none;">
                            <div class="panel">
                                <div class="panel-heading bg-primary">
                                    <h2 class="panel-title">RENEGOCIACIONES</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="control-label">Fecha</label>
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control" name="fechaRenegociacionCruzVerdeConsumoStart" />
                                                        <span class="input-group-addon">a</span>
                                                        <input type="text" class="form-control" name="fechaRenegociacionCruzVerdeConsumoEnd" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <button id="buscarRenegociacionCruzVerdeConsumo" class="btn btn-primary" style="margin-top: 10px;margin-bottom: 10px" type="submit">Buscar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="RenegociacionCruzVerdeConsumo" style="display: none;">
                                        <div class="panel">
                                            <div class="panel-heading bg-primary">
                                                <h2 class="panel-title">RENEGOCIACIONES CONSUMO</h2>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row" style="margin-bottom: 20px;">
                                                    <button class="btn btn-primary downloadRenegociacionCruzVerdeConsumo">Descargar</button>
                                                </div>
                                                <div class="row">
                                                    <div class="table-responsive">
                                                        <div class="col-md-12">
                                                            <table id="tablaRenegociacionCruzVerdeConsumo" class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">Rut</th>
                                                                        <th class="text-center">Dv</th>
                                                                        <th class="text-center">Fono Contactado</th>
                                                                        <th class="text-center">Abono</th>
                                                                        <th class="text-center">Sucursal</th>
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
                        </div>
                        <div class="row" id="panelRenegociacionCruzVerdeTarjeta" style="display: none;">
                            <div class="panel">
                                <div class="panel-heading bg-primary">
                                    <h2 class="panel-title">AJUSTES DE PAGO CASTIGO</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="control-label">Fecha</label>
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control" name="fechaRenegociacionCruzVerdeTarjetaStart" />
                                                        <span class="input-group-addon">a</span>
                                                        <input type="text" class="form-control" name="fechaRenegociacionCruzVerdeTarjetaEnd" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <button id="buscarRenegociacionCruzVerdeTarjeta" class="btn btn-primary" style="margin-top: 10px;margin-bottom: 10px" type="submit">Buscar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="RenegociacionCruzVerdeTarjeta" style="display: none;">
                                        <div class="panel">
                                            <div class="panel-heading bg-primary">
                                                <h2 class="panel-title">AJUSTE DE PAGO CASTIGO</h2>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row" style="margin-bottom: 20px;">
                                                    <button class="btn btn-primary downloadRenegociacionCruzVerdeTarjeta">Descargar</button>
                                                </div>
                                                <div class="row">
                                                    <div class="table-responsive">
                                                        <div class="col-md-12">
                                                            <table id="tablaRenegociacionCruzVerdeTarjeta" class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">Rut</th>
                                                                        <th class="text-center">Dv</th>
                                                                        <th class="text-center">Fono Contactado</th>
                                                                        <th class="text-center">Abono Minimo</th>
                                                                        <th class="text-center">Abono Realizado</th>
                                                                        <th class="text-center">Observaciones</th>
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
                        </div>
                    </div>
                <?php
                    break;
                }
            ?>
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
    <?php
        switch($codigoDerivaciones){
            case "1":
            ?>
                <script src="../js/derivaciones/derivaciones.js"></script>
            <?php
            break;
            case "2":
            ?>
                <script src="../js/derivaciones/derivacionesHites.js"></script>
            <?php
            break;
            case "3":
            ?>
                <script src="../js/derivaciones/derivacionesLaPolar.js"></script>
            <?php
            break;
            case "4":
            ?>
                <script src="../js/derivaciones/derivacionesCruzVerde.js"></script>
            <?php
            break;
        }
    ?>
</body>
</html>