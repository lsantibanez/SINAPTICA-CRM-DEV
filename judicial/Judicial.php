<?php
    require_once('../class/db/DB.php');
    require_once('../class/session/session.php');

    include("../class/global/global.php");
    $Omni = new Omni;
    $Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

    $objetoSession = new Session($Permisos,false); // 1,4
    // $objetoSession = new Session('1,2,3,4,5,6',false); // 1,4
    //Para Id de Menu Actual (Menu Padre, Menu hijo)
    $objetoSession->crearVariableSession($array = array("idMenu" => "jud,jud"));
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
    <script src="../plugins/pace/pace.min.js"></script>
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
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
                    <h1 class="page-header text-overflow">Judicial</h1>
                    <!--Searchbox-->
            
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->


                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
                    <li><a href="#">Judicial</a></li>
                    <li class="active">Judicial</li>
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
                                    <div class="panel-control">
                                        <!--Nav tabs-->
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-toggle="tab" href="#calculo_avenimiento" aria-expanded="true">Calculo de Avenimiento</a>
                                            </li>
                                            <li class=""><a data-toggle="tab" href="#calculo_liquidacion_judicial" aria-expanded="true">Calculo Liquidación Judicial</a>
                                            </li>
                                            <li class=""><a data-toggle="tab" href="#calculo_reavenimiento" aria-expanded="true">Calculo Re-avenimiento</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div id="calculo_avenimiento" class="tab-pane fade active in">

                							<!--Panel with Header-->
                							<!--===================================================-->
                							<div class="col-sm-12 minPanel">
                                                <div class="row">
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title bg-primary">Antecedentes Generales</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Numero Operación(es)</label>
                                                                        <select class="selectpicker form-control" name="personaempresa_id" id="personaempresa_id" title="Seleccione"  data-live-search="true" data-container="body" validation="not_null" data-nombre="Cliente">
                                                                            <option value="">Seleccione Cliente</option>
                                                                        </select>
                                                                    </div>
                                                                </div>


                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Rut</label>
                                                                        <input id="rut" name="rut" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Domicilio Particular</label>
                                                                        <input id="domicilio_particular" name="domicilio_particular" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Teléfono Particular</label>
                                                                        <input id="telefono_particular" name="telefono_particular" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Domicilio Laboral</label>
                                                                        <input id="domicilio_laboral" name="domicilio_laboral" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Teléfono Laboral</label>
                                                                        <input id="telefono_laboral" name="telefono_laboral" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                							</div>

                                            <!--===================================================-->
                                            <!--End Panel-->

                                            <div class="col-sm-12 minPanel">
                                                <div class="row">
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title bg-primary">Desglose de la deuda</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-striped table-bordered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="text-center" style="width: 25%"></th>
                                                                                    <th class="text-center" style="width: 25%">% Dcto</th>
                                                                                    <th class="text-center" style="width: 25%">Op. Original</th>
                                                                                    <th class="text-center" style="width: 25%">Monto c/Dcto</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>

                                                    <!--             <div class="col-md-3">
                                                                    <label class="control-label h5">Numero Operación(es)</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <select class="selectpicker form-control" name="numero_operaciones" id="numero_operaciones" title="Seleccione"  data-live-search="true" data-container="body" validation="not_null" data-nombre="Numero Operacion">
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div> -->

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Fecha Castigo</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <input id="fecha_castigo" name="fecha_castigo" class="form-control input-sm date">
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Saldo Capital</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="saldo_capital" name="saldo_capital" class="form-control input-sm number avenimiento" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_saldo_capital" name="monto_saldo_capital" class="form-control input-sm number desglose_disabled" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Interes Vencido</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input id="porcentaje_interes_vencido" name="porcentaje_interes_vencido" class="form-control input-sm percent avenimiento_porcentaje" data-input="interes_vencido" value="0">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>                                     
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="interes_vencido" name="interes_vencido" class="form-control input-sm number avenimiento" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_interes_vencido" name="monto_interes_vencido" class="form-control input-sm number desglose_disabled" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Interes Suspendido</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input id="porcentaje_interes_suspendido" name="porcentaje_interes_suspendido" class="form-control input-sm percent avenimiento_porcentaje" data-input="interes_suspendido" value="0">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>                                     
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="interes_suspendido" name="interes_suspendido avenimiento" class="form-control input-sm number" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_interes_suspendido" name="monto_interes_suspendido" class="form-control input-sm number desglose_disabled" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Interes Penal</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input id="porcentaje_interes_penal" name="porcentaje_interes_penal" class="form-control input-sm percent avenimiento_porcentaje" data-input="interes_penal" value="0">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>                                     
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="interes_penal" name="interes_penal" class="form-control input-sm number avenimiento" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_interes_penal" name="monto_interes_penal" class="form-control input-sm number desglose_disabled" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Gastos de Cobranza</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input id="porcentaje_gastos_cobranza" name="porcentaje_gastos_cobranza" class="form-control input-sm percent avenimiento_porcentaje" data-input="gastos_cobranza" value="0">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>                                     
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="gastos_cobranza" name="gastos_cobranza" class="form-control input-sm number avenimiento" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_gastos_cobranza" name="monto_gastos_cobranza" class="form-control input-sm number desglose_disabled" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Costos Judiciales</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input id="porcentaje_costos_judiciales" name="porcentaje_costos_judiciales" class="form-control input-sm percent avenimiento_porcentaje" data-input="costos_judiciales" value="0">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>                                     
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="costos_judiciales" name="costos_judiciales" class="form-control input-sm number avenimiento">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_costos_judiciales" name="monto_costos_judiciales" class="form-control input-sm number desglose_disabled" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Honorario Abogado por Abono Inicial</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input id="porcentaje_honorario_abogado" name="porcentaje_honorario_abogado" class="form-control input-sm percent avenimiento_porcentaje" data-input="honorario_abogado" value="0">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>                                     
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="honorario_abogado" name="honorario_abogado" class="form-control input-sm number" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div> -->

                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_honorario_abogado" name="monto_honorario_abogado" class="form-control input-sm number desglose_disabled" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">% Honorario Abogado por Deuda Avenida</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input id="porcentaje_deuda_avenida" name="porcentaje_deuda_avenida" class="form-control input-sm percent avenimiento_porcentaje" value="3">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>                                     
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_deuda_avenida" name="monto_deuda_avenida" class="form-control input-sm number" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Total Deuda</label>
                                                                </div>


                                                                <div class="col-md-3 col-md-offset-6">
                                                                    <div class="form-group">
                                                                        <input id="total_deuda" name="total_deuda" class="form-control input-sm number" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                            </div>


                                                            <div class="clearfix"></div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--===================================================-->
                                            <!--End Panel-->

                                            <div class="col-sm-12 minPanel">
                                                <div class="row">
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title bg-primary">Condiciones del Avenimiento</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                   
                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">&nbsp; Abono Inicial</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label">(Minimo: $<span id="abono_inicial_minimo">0</span>)</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="abono_inicial" name="abono_inicial" class="form-control input-sm">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Fecha Abono</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label">(Fecha Contable)</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="fecha_abono" name="fecha_abono" class="form-control input-sm date">
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Total a Avenir</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="total_avenir" name="total_avenir" class="form-control input-sm number" value="0" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">No de Cuotas</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label">(Maximo 60 cuotas)</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="numero_cuotas" name="numero_cuotas" class="form-control input-sm number" value="48">
                                                                    </div>
                                                                </div>


                                                                <!-- <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Tasa a aplicar</label>
                                                                </div>


                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">       
                                                                            <input id="tasa_aplicar" name="tasa_aplicar" class="form-control input-sm number" value="0" disabled>
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div> -->


                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3 h5">Valor Cuota</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="valor_cuota" name="valor_cuota" class="form-control input-sm number" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">1er Vcto </label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <input id="primer_vencimiento" name="primer_vencimiento" class="form-control input-sm date">
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--===================================================-->
                                            <!--End Panel-->

                                            <div class="col-sm-12 minPanel">
                                                <div class="row">
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title bg-primary">Desarrollo del Crédito (Avenimiento)</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="table-responsive">
                                                                    <div class="col-md-12">
                                                                        <table class="table table-striped table-bordered" id="CreditoTable">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="text-center">Vencimiento</th>
                                                                                    <th class="text-center">Cuota No</th>
                                                                                    <th class="text-center">Monto Cuota</th>
                                                                                    <th class="text-center">Intereses</th>
                                                                                    <th class="text-center">Amortización</th>
                                                                                    <th class="text-center">Saldo Insoluto</th>
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

                                            <!--===================================================-->
                                            <!--End Panel-->

                                        </div> <!--TAB PANE-->

                                        <div id="calculo_liquidacion_judicial" class="tab-pane">
                                            <div class="col-sm-12 minPanel">
                                                <div class="row">
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title bg-primary">Antecedentes Generales</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Numero Operación(es)</label>
                                                                        <select class="selectpicker form-control" name="personaempresa_id_liquidacion" id="personaempresa_id_liquidacion"  data-live-search="true" data-container="body" validation="not_null" data-nombre="Cliente" title="Seleccione">
                                                                            <option value="">Seleccione Cliente</option>
                                                                        </select>
                                                                    </div>
                                                                </div>


                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Rut</label>
                                                                        <input id="rut_liquidacion" name="rut_liquidacion" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Domicilio Particular</label>
                                                                        <input id="domicilio_particular_liquidacion" name="domicilio_particular_liquidacion" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Teléfono Particular</label>
                                                                        <input id="telefono_particular_liquidacion" name="telefono_particular_liquidacion" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Domicilio Laboral</label>
                                                                        <input id="domicilio_laboral_liquidacion" name="domicilio_laboral_liquidacion" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Teléfono Laboral</label>
                                                                        <input id="telefono_laboral_liquidacion" name="telefono_laboral_liquidacion" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--===================================================-->
                                            <!--End Panel-->

                                            <div class="col-sm-12 minPanel">
                                                <div class="row">
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title bg-primary">Desglose de la deuda</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-striped table-bordered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="text-center" style="width: 25%"></th>
                                                                                    <th class="text-center" style="width: 25%">Monto Original</th>
                                                                                    <th class="text-center" style="width: 25%">% Dcto</th>
                                                                                    <th class="text-center" style="width: 25%">Monto c/Dcto</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>

                                                                <!-- <div class="col-md-3">
                                                                    <label class="control-label h5">Numero Operación(es)</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <select class="selectpicker form-control" name="numero_operaciones_liquidacion" id="numero_operaciones_liquidacion" title="Seleccione"  data-live-search="true" data-container="body" validation="not_null" data-nombre="Numero Operacion">
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div> -->

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Fecha Castigo</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="fecha_castigo_liquidacion" name="fecha_castigo_liquidacion" class="form-control input-sm date">
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Saldo Capital</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="saldo_capital_liquidacion" name="saldo_capital_liquidacion" class="form-control input-sm number liquidacion" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_saldo_capital_liquidacion" name="monto_saldo_capital_liquidacion" class="form-control input-sm number desglose_disabled_liquidacion" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Interes Vencido</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="interes_vencido_liquidacion" name="interes_vencido_liquidacion" class="form-control input-sm number liquidacion" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input id="porcentaje_interes_vencido_liquidacion" name="porcentaje_interes_vencido_liquidacion" class="form-control input-sm percent liquidacion_porcentaje" data-input="interes_vencido_liquidacion" value="0">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>                                     
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_interes_vencido_liquidacion" name="monto_interes_vencido_liquidacion" class="form-control input-sm number desglose_disabled_liquidacion" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Interes Suspendido</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="interes_suspendido_liquidacion" name="interes_suspendido_liquidacion" class="form-control input-sm number liquidacion" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input id="porcentaje_interes_suspendido_liquidacion" name="porcentaje_interes_suspendido_liquidacion" class="form-control input-sm percent liquidacion_porcentaje" data-input="interes_suspendido_liquidacion" value="0">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>                                     
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_interes_suspendido_liquidacion" name="monto_interes_suspendido_liquidacion" class="form-control input-sm number desglose_disabled_liquidacion" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Interes Penal</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="interes_penal_liquidacion" name="interes_penal_liquidacion" class="form-control input-sm number liquidacion" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input id="porcentaje_interes_penal_liquidacion" name="porcentaje_interes_penal_liquidacion" class="form-control input-sm percent liquidacion_porcentaje" data-input="interes_penal_liquidacion" value="0">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>                                     
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_interes_penal_liquidacion" name="monto_interes_penal_liquidacion" class="form-control input-sm number desglose_disabled_liquidacion" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Gastos de Cobranza</label>
                                                                </div>


                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="gastos_cobranza_liquidacion" name="gastos_cobranza_liquidacion" class="form-control input-sm number liquidacion" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input id="porcentaje_gastos_cobranza_liquidacion" name="porcentaje_gastos_cobranza_liquidacion" class="form-control input-sm percent liquidacion_porcentaje" data-input="gastos_cobranza_liquidacion" value="0">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>                                     
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_gastos_cobranza_liquidacion" name="monto_gastos_cobranza_liquidacion" class="form-control input-sm number desglose_disabled_liquidacion" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Costos Judiciales</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="costos_judiciales_liquidacion" name="costos_judiciales_liquidacion" class="form-control input-sm number liquidacion">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input id="porcentaje_costos_judiciales_liquidacion" name="porcentaje_costos_judiciales_liquidacion" class="form-control input-sm percent liquidacion_porcentaje" data-input="costos_judiciales_liquidacion" value="0">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>                                     
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_costos_judiciales_liquidacion" name="monto_costos_judiciales_liquidacion" class="form-control input-sm number desglose_disabled_liquidacion" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Sub-Total Deuda</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="subtotal_deuda_liquidacion" name="subtotal_deuda_liquidacion" class="form-control input-sm number" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <input id="subtotal_deuda_descuento_liquidacion" name="subtotal_deuda_descuento_liquidacion" class="form-control input-sm number" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Honorario Abogado</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="honorario_abogado_liquidacion" name="honorario_abogado_liquidacion" class="form-control input-sm number" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input id="porcentaje_honorario_abogado_liquidacion" name="porcentaje_honorario_abogado_liquidacion" class="form-control input-sm percent liquidacion_porcentaje" data-input="honorario_abogado_liquidacion" value="0">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>                                     
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="monto_honorario_abogado_liquidacion" name="monto_honorario_abogado_liquidacion" class="form-control input-sm number" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Total Deuda</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="total_deuda_liquidacion" name="total_deuda_liquidacion" class="form-control input-sm number" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <input id="total_deuda_descuento_liquidacion" name="total_deuda_descuento_liquidacion" class="form-control input-sm number" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                            </div>


                                                            <div class="clearfix"></div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--===================================================-->
                                            <!--End Panel-->

                                        </div> <!--TAB PANE-->

                                        <div id="calculo_reavenimiento" class="tab-pane">

                                            <!--Panel with Header-->
                                            <!--===================================================-->
                                            <div class="col-sm-12 minPanel">
                                                <div class="row">
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title bg-primary">Antecedentes Generales</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Numero Operación(es)</label>
                                                                        <select class="selectpicker form-control" name="personaempresa_id_reavenimiento" id="personaempresa_id_reavenimiento"  data-live-search="true" data-container="body" validation="not_null" data-nombre="Cliente" title="Seleccione">
                                                                            <option value="">Seleccione Cliente</option>
                                                                        </select>
                                                                    </div>
                                                                </div>


                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Rut</label>
                                                                        <input id="rut_reavenimiento" name="rut_reavenimiento" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Domicilio Particular</label>
                                                                        <input id="domicilio_particular_reavenimiento" name="domicilio_particular_reavenimiento" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Teléfono Particular</label>
                                                                        <input id="telefono_particular_reavenimiento" name="telefono_particular_reavenimiento" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Domicilio Laboral</label>
                                                                        <input id="domicilio_laboral_reavenimiento" name="domicilio_laboral_reavenimiento" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="control-label" for="name">Teléfono Laboral</label>
                                                                        <input id="telefono_laboral_reavenimiento" name="telefono_laboral_reavenimiento" class="form-control input-sm" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--===================================================-->
                                            <!--End Panel-->

                                            <div class="col-sm-12 minPanel">
                                                <div class="row">
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title bg-primary">Desglose de la Deuda</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-striped table-bordered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="text-center" style="width: 25%"></th>
                                                                                    <th class="text-center" style="width: 25%"></th>
                                                                                    <th class="text-center" style="width: 25%">Op. Original</th>
                                                                                    <th class="text-center" style="width: 25%">Monto</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>

                                                                <!-- <div class="col-md-3">
                                                                    <label class="control-label h5">Numero Operación(es)</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <select class="selectpicker form-control" name="numero_operaciones_reavenimiento" id="numero_operaciones_reavenimiento" title="Seleccione"  data-live-search="true" data-container="body" validation="not_null" data-nombre="Numero Operacion">
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div> -->

                                                                <div class="col-md-3 h5">Valor Cuota</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-6">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="valor_cuota_reavenimiento" name="valor_cuota_reavenimiento" class="form-control input-sm number reavenimiento">
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Numero de Cuotas totales</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-6">
                                                                    <div class="form-group">
                                                                        <input id="numero_cuotas_totales_reavenimiento" name="numero_cuotas_totales_reavenimiento" class="form-control input-sm number reavenimiento">
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Numero de Cuotas pagadas</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-6">
                                                                    <div class="form-group">
                                                                        <input id="numero_cuotas_pagadas_reavenimiento" name="numero_cuotas_pagadas_reavenimiento" class="form-control input-sm number reavenimiento">
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Numero de Cuotas morosas</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-6">
                                                                    <div class="form-group">
                                                                        <input id="numero_cuotas_morosas_reavenimiento" name="numero_cuotas_morosas_reavenimiento" class="form-control input-sm number reavenimiento">
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>
                   
                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Cuotón</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-6">
                                                                    <div class="form-group">
                                                                        <input id="cuoton_reavenimiento" name="cuoton_reavenimiento" class="form-control input-sm reavenimiento">
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Deuda actual avenimiento moroso</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-6">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="deuda_actual_reavenimiento" name="deuda_actual_reavenimiento" class="form-control input-sm number" value="0" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3 h5">Costos judiciales de la reactivación</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-6">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="costos_judiciales_reavenimiento" name="costos_judiciales_reavenimiento" class="form-control input-sm number reavenimiento">
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3 h5">Honorario abogado por abono inicíal</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-6">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="honorario_abogado_reavenimiento" name="honorario_abogado_reavenimiento" class="form-control input-sm number" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3 h5">% Honorario abogado por deuda reavenida</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <input id="porcentaje_honorario_abogado_reavenimiento" name="porcentaje_honorario_abogado_reavenimiento" class="form-control input-sm number" value="3">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>  
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="monto_honorario_abogado_reavenimiento" name="monto_honorario_abogado_reavenimiento" class="form-control input-sm number" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                 <div class="col-md-3 h5">Total deuda reavenimiento</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-6">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="total_deuda_reavenimiento" name="total_deuda_reavenimiento" class="form-control input-sm number" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>


                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--===================================================-->
                                            <!--End Panel-->

                                            <div class="col-sm-12 minPanel">
                                                <div class="row">
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title bg-primary">Condiciones del Re-Avenimiento</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                   
                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">&nbsp; Abono Inicial</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label">(Minimo: $<span id="abono_inicial_minimo">0</span>)</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="abono_inicial_reavenimiento" name="abono_inicial_reavenimiento" class="form-control input-sm">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Fecha Abono</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label">(Fecha Contable)</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="fecha_abono_reavenimiento" name="fecha_abono_reavenimiento" class="form-control input-sm date">
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">Total a Avenir</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="total_avenir_reavenimiento" name="total_avenir_reavenimiento" class="form-control input-sm number" value="0" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">No de Cuotas</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label">(Maximo 60 cuotas)</label>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <input id="numero_cuotas_reavenimiento" name="numero_cuotas_reavenimiento" class="form-control input-sm number" value="48">
                                                                    </div>
                                                                </div>


                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3 h5">Valor Cuota</label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-dollar"></i>
                                                                            </span>
                                                                            <input id="valor_cuota_condiciones" name="valor_cuota_condiciones" class="form-control input-sm number" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="col-md-3">
                                                                    <label class="control-label h5">1er Vcto </label>
                                                                </div>

                                                                <div class="col-md-3 col-md-offset-3">
                                                                    <div class="form-group">
                                                                        <input id="primer_vencimiento_reavenimiento" name="primer_vencimiento_reavenimiento" class="form-control input-sm date">
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--===================================================-->
                                            <!--End Panel-->

                                            <div class="col-sm-12 minPanel">
                                                <div class="row">
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title bg-primary">Desarrollo del Crédito (Re-Avenimiento)</h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="table-responsive">
                                                                    <div class="col-md-12">
                                                                        <table class="table table-striped table-bordered" id="ReavenimientoTable">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="text-center">Vencimiento</th>
                                                                                    <th class="text-center">Cuota No</th>
                                                                                    <th class="text-center">Monto Cuota</th>
                                                                                    <th class="text-center">Intereses</th>
                                                                                    <th class="text-center">Amortización</th>
                                                                                    <th class="text-center" id="saldo_insoluto">Saldo Insoluto</th>
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

                                            <!--===================================================-->
                                            <!--End Panel-->

                                        </div><!--TAB PANE-->

                                    </div><!--TAB CONTENT-->
                                </div><!--PANEL BODY-->

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
    <script src="../js/nifty.min.js"></script>
<!-- 	<script src="../plugins/morris-js/morris.min.js"></script>
    <script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/skycons/skycons.min.js"></script>
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../js/demo/ui-alerts.js"></script> -->

    <script src="../plugins/bootbox/bootbox.min.js"></script>       
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>


    <script src="../plugins/jquery-mask/jquery.mask.min.js"></script>
    <script src="../plugins/fullcalendar/lib/moment.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/judicial/Judicial.js"></script>

</body>
</html>
