<?php
	require_once('../class/db/DB.php');
    require_once('../class/session/session.php');

	include("../class/global/global.php");
    $Omni = new Omni;
    $Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

    // $objetoSession = new Session($Permisos,false); // 1,4
    $objetoSession = new Session('1,2,3,4,5,6',false); // 1,4
    //Para Id de Menu Actual (Menu Padre, Menu hijo)
    $objetoSession->crearVariableSession($array = array("idMenu" => "adm,sis,niv"));
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
	    <title>CRM Sinaptica </title>
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
	        <?php include("../layout/header.php");    ?>
	        <!--===================================================-->
	        <!--END NAVBAR-->
	        <div class="boxed">
	            <!--CONTENT CONTAINER-->
	            <!--===================================================-->
	            <div id="content-container">	                
	                <!--Page Title-->
	                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	                <div id="page-title">
	                    <h1 class="page-header text-overflow">Árbol de Tipificación</h1>
	                    <!--Searchbox-->	            
	                </div>
	                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	                <!--End page title-->
	                <!--Breadcrumb-->
	                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	                <ol class="breadcrumb">
	                    <li><a href="#">Configuración</a></li>
                        <li><a href="#">Sistema</a></li>
	                    <li class="active">Árbol de tipificación</li>
	                </ol>
	                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	                <!--End breadcrumb-->
	                <!--Page content-->
	                <!--===================================================-->
					<div id="page-content">
                        <div class="row">
                            <div class="col-sm-12">
                            <div class="panel">                                
                                <div class="panel-heading bg-purple">
                                    <div class="panel-control ">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#nivel_1-tab" data-toggle="tab">Primer Nivel</a></li>
                                            <li><a href="#nivel_2-tab" data-toggle="tab">Sub Nivel 2</a></li>
                                            <li><a href="#nivel_3-tab" data-toggle="tab">Sub Nivel 3</a></li>
                                            <li><a href="#nivel_4-tab" data-toggle="tab">Sub Nivel 4</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="nivel_1-tab">
                                        <div class="tab-base ">
                                            <div class="tab-content">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <button data-toggle="modal" href="#Nivel1Form" class="btn btn-success">
                                                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar
                                                        </button>
                                                        <table id="Nivel1Table" class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nombre</th>
                                                                    <th class="text-center" style="width: 15%;">&nbsp;</th>
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
                                    <div class="tab-pane fade" id="nivel_2-tab">
                                        <div class="tab-base ">
                                            <div class="tab-content">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <button data-toggle="modal" href="#Nivel2Form" class="btn btn-success">
                                                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar
                                                        </button>
                                                        <table id="Nivel2Table" class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 25%;">Nivel 1</th>
                                                                    <th>Nombre</th>
                                                                    <th style="width: 10%;">&nbsp;</th>
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
                                    <div class="tab-pane fade" id="nivel_3-tab">
                                        <div class="tab-base ">
                                            <div class="tab-content">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <button data-toggle="modal" href="#Nivel3Form" class="btn btn-success">
                                                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar
                                                        </button>
                                                        <table id="Nivel3Table" class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 25%;">Primer Nivel</th>
                                                                    <th style="width: 25%;">Sub Nivel 2</th>
                                                                    <th style="width: 25%;">Sub Nivel 3</th>
                                                                    <th class="text-center" style="width: 5%;">Prioridad</th>
                                                                    <th style="width: 10%;">&nbsp;</th>
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
                                    <div class="tab-pane fade" id="nivel_4-tab">
                                        <div class="tab-base ">
                                            <div class="tab-content">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <button data-toggle="modal" href="#Nivel4Form" class="btn btn-success">
                                                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar
                                                        </button>
                                                        <table id="Nivel4Table" class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 20%;">Primer Nivel</th>
                                                                    <th style="width: 20%;">Sub Nivel 2</th>
                                                                    <th style="width: 20%;">Sub Nivel 3</th>
                                                                    <th style="width: 25%;">Sub Nivel 4</th>
                                                                    <th class="text-center" style="width: 5%;">Prioridad</th>
                                                                    <th style="width: 10%;">&nbsp;</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nivel_rapido-tab">
                                        <div class="tab-base ">
                                            <div class="tab-content">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <button data-toggle="modal" href="#NivelRapidoForm" class="btn btn-success">Agregar</button>
                                                        <table id="NivelRapidoTable" class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nivel 3</th>
                                                                    <th class="text-center" style="width: 25%;">Acción</th>
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
        <div id="Nivel1Form" class="modal fade" tabindex="-1" role="dialog" id="load">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gris-oscuro p-t-10 p-b-10">
                        <h4 class="modal-title c-negro">Agregar Nivel 1 <button type="button" data-dismiss="modal" class="close c-negro f-25" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="padding:20px">
                            <form class="form-horizontal" id = "storeNivel1">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nombre</label>
                                        <input id="nombre" name="nombre" type="text" placeholder="Ingrese su nombre" class="form-control input-sm" validation="not_null">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-body -->
                    <div class="modal-footer p-b-20 m-b-20">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-purple" id="guardarNivel1" name="guardarNivel1">Guardar</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="Nivel1FormUpdate" class="modal fade" tabindex="-1" role="dialog" id="load">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gris-oscuro p-t-10 p-b-10">
                        <h4 class="modal-title c-negro">Modificar Primer Nivel <button type="button" data-dismiss="modal" class="close c-negro f-25" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="padding:20px">
                            <form class="form-horizontal" id = "updateNivel1">
                                <input type="hidden" id="id" name="id">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nombre del Nivel</label>
                                        <br>
                                        <input id="nombre" name="nombre" type="text"  class="form-control input-sm" validation="not_null">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-body -->
                    <div class="modal-footer p-b-20 m-b-20">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-purple" id="actualizarNivel1" name="actualizarNivel1">Actualizar</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div id="Nivel2Form" class="modal fade" tabindex="-1" role="dialog" id="load">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gris-oscuro p-t-10 p-b-10">
                        <h4 class="modal-title c-negro">Agregar Nivel 2 <button type="button" data-dismiss="modal" class="close c-negro f-25" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="padding:20px">
                            <form class="form-horizontal" id = "storeNivel2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nivel 1</label>
                                        <div class="select">
                                            <select class="selectpicker form-control" name="nivel_1" id="nivel_1"  data-live-search="true" data-container="body">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix m-b-10"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nombre</label>
                                        <input id="nombre" name="nombre" type="text" placeholder="Ingrese su nombre" class="form-control input-sm" validation="not_null">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-body -->
                    <div class="modal-footer p-b-20 m-b-20">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-purple" id="guardarNivel2" name="guardarNivel2">Guardar</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="Nivel2FormUpdate" class="modal fade" tabindex="-1" role="dialog" id="load">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gris-oscuro p-t-10 p-b-10">
                        <h4 class="modal-title c-negro">Actualizar Nivel 2 <button type="button" data-dismiss="modal" class="close c-negro f-25" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="padding:20px">
                            <form class="form-horizontal" id = "updateNivel2">
                                <input type="hidden" id="id" name="id">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nivel 1</label>
                                        <div class="select">
                                            <select class="selectpicker form-control" name="nivel_1" id="nivel_1"  data-live-search="true" data-container="body">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix m-b-10"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nombre</label>
                                        <input id="nombre" name="nombre" type="text" placeholder="Ingrese su nombre" class="form-control input-sm" validation="not_null">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-body -->
                    <div class="modal-footer p-b-20 m-b-20">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-purple" id="actualizarNivel2" name="actualizarNivel2">Actualizar</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div id="Nivel3Form" class="modal fade" tabindex="-1" role="dialog" id="load">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gris-oscuro p-t-10 p-b-10">
                        <h4 class="modal-title c-negro">Agregar Nivel 3 <button type="button" data-dismiss="modal" class="close c-negro f-25" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="padding:20px">
                            <form class="form-horizontal" id="storeNivel3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nivel 2</label>
                                        <div class="select">
                                            <select class="selectpicker form-control" name="nivel_2" id="nivel_2"  data-live-search="true" data-container="body">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix m-b-10"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nombre</label>
                                        <input id="nombre" name="nombre" type="text" placeholder="Ingrese su nombre" class="form-control input-sm" validation="not_null">
                                    </div>
                                </div>
                                <div class="clearfix m-b-10"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Tipo de Contacto</label>
                                        <div class="select">
                                            <select class="selectpicker form-control" name="Id_TipoGestion" id="Id_TipoGestion"  data-live-search="true" data-container="body">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="display:none">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Ponderación</label>
                                        <input id="Ponderacion" name="Ponderacion" type="number" placeholder="Ingrese la ponderación" class="form-control input-sm" validation="not_null" value="0">
                                    </div>
                                </div>
                                <div class="clearfix m-b-10"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Peso</label>
                                        <input id="Peso" name="Peso" type="number" placeholder="Ingrese el peso" class="form-control input-sm" validation="not_null">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-body -->
                    <div class="modal-footer p-b-20 m-b-20">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-purple" id="guardarNivel3" name="guardarNivel3">Guardar</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="Nivel3FormUpdate" class="modal fade" tabindex="-1" role="dialog" id="load">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gris-oscuro p-t-10 p-b-10">
                        <h4 class="modal-title c-negro">Actualizar Nivel 3 <button type="button" data-dismiss="modal" class="close c-negro f-25" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="padding:20px">
                            <form class="form-horizontal" id = "updateNivel3">
                                <input type="hidden" id="id" name="id">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nivel 2</label>
                                        <div class="select">
                                            <select class="selectpicker form-control" name="nivel_2" id="nivel_2"  data-live-search="true" data-container="body">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix m-b-10"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nombre</label>
                                        <input id="nombre" name="nombre" type="text" placeholder="Ingrese el nombre" class="form-control input-sm" validation="not_null">
                                    </div>
                                </div>
                                <div class="clearfix m-b-10"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Tipo de Contacto</label>
                                        <div class="select">
                                            <select class="selectpicker form-control" name="Id_TipoGestion" id="Id_TipoGestion"  data-live-search="true" data-container="body">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="display:none">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Ponderación</label>
                                        <input id="Ponderacion" name="Ponderacion" type="number" placeholder="Ingrese la ponderación" class="form-control input-sm" validation="not_null">
                                    </div>
                                </div>
                                <div class="clearfix m-b-10"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Peso</label>
                                        <input id="Peso" name="Peso" type="number" placeholder="Ingrese el peso" class="form-control input-sm" validation="not_null">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-body -->
                    <div class="modal-footer p-b-20 m-b-20">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-purple" id="actualizarNivel3" name="actualizarNivel3">Actualizar</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div id="Nivel4Form" class="modal fade" tabindex="-1" role="dialog" id="load">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gris-oscuro p-t-10 p-b-10">
                        <h4 class="modal-title c-negro">Agregar Nivel 4 <button type="button" data-dismiss="modal" class="close c-negro f-25" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="padding:20px">
                            <form class="form-horizontal" id="storeNivel4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nivel 3</label>
                                        <div class="select">
                                            <select class="selectpicker form-control" name="nivel_3" id="nivel_3"  data-live-search="true" data-container="body">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix m-b-10"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nombre</label>
                                        <input id="nombre" name="nombre" type="text" placeholder="Ingrese nombre" class="form-control input-sm" validation="not_null">
                                    </div>
                                </div>
                                <div class="clearfix m-b-10"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Tipo de Contacto</label>
                                        <div class="select">
                                            <select class="selectpicker form-control" name="Id_TipoGestion" id="Id_TipoGestion"  data-live-search="true" data-container="body">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="display:none">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Ponderación</label>
                                        <input id="Ponderacion" name="Ponderacion" type="number" placeholder="Ingrese la ponderación" class="form-control input-sm" validation="not_null" value="0">
                                    </div>
                                </div>
                                <div class="clearfix m-b-10"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Peso</label>
                                        <input id="Peso" name="Peso" type="number" placeholder="Ingrese el peso" class="form-control input-sm" validation="not_null">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-body -->
                    <div class="modal-footer p-b-20 m-b-20">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-purple" id="guardarNivel3" name="guardarNivel3">Guardar</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="Nivel4FormUpdate" class="modal fade" tabindex="-1" role="dialog" id="load">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gris-oscuro p-t-10 p-b-10">
                        <h4 class="modal-title c-negro">Actualizar Nivel 4 <button type="button" data-dismiss="modal" class="close c-negro f-25" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="padding:20px">
                            <form class="form-horizontal" id="updateNivel4">
                                <input type="hidden" id="id" name="id">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nivel 3</label>
                                        <div class="select">
                                            <select class="selectpicker form-control" name="nivel_3" id="nivel_3"  data-live-search="true" data-container="body">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix m-b-10"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nombre</label>
                                        <input id="nombre" name="nombre" type="text" placeholder="Ingrese nombre" class="form-control input-sm" validation="not_null">
                                    </div>
                                </div>
                                <div class="clearfix m-b-10"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Tipo de Contacto</label>
                                        <div class="select">
                                            <select class="selectpicker form-control" name="Id_TipoGestion" id="Id_TipoGestion"  data-live-search="true" data-container="body">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="display:none">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Ponderación</label>
                                        <input id="Ponderacion" name="Ponderacion" type="number" placeholder="Ingrese ponderación" class="form-control input-sm" validation="not_null">
                                    </div>
                                </div>
                                <div class="clearfix m-b-10"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Peso</label>
                                        <input id="Peso" name="Peso" type="number" placeholder="Ingrese el peso" class="form-control input-sm" validation="not_null">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-body -->
                    <div class="modal-footer p-b-20 m-b-20">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-purple" id="actualizarNivel3" name="actualizarNivel3">Actualizar</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div id="NivelRapidoForm" class="modal fade" tabindex="-1" role="dialog" id="load">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gris-oscuro p-t-10 p-b-10">
                        <h4 class="modal-title c-negro">Agregar Nivel Rapido <button type="button" data-dismiss="modal" class="close c-negro f-25" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="padding:20px">
                            <form class="form-horizontal" id = "storeNivelRapido">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Nivel 3</label>
                                        <div class="select">
                                            <select class="selectpicker form-control" name="nivel_3" id="nivel_3"  data-live-search="true" data-container="body">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-body -->
                    <div class="modal-footer p-b-20 m-b-20">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-purple" id="guardarNivelRapido" name="guardarNivelRapido">Guardar</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->        
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
		<script src="../js/nivel/nivel.js"></script>
    </body>
</html>