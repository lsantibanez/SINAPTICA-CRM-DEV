<?php
include("../class/global/global.php");
require_once('../class/session/session.php');


$objetoSession = new Session('1,2,3,4,5,6',false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "inicio,bien"));
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
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <script src="../plugins/pace/pace.min.js"></script>
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    


    <style type="text/css">

    .modalreporte{
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
    body.loading{
        overflow: hidden;
    }
    body.loading .modal{
        display: block;
    }
    #VerReporteOculto{
        display : none;
    }

    </style>
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
            <div id="content-container">
                <div id="page-title">
                    <h1 class="page-header text-overflow">Conf. Pantalla Global</h1>
                </div>
                <br>
                <ol class="breadcrumb">
                    <li><a href="#">Configuración</a></li>
                    <li><a href="#">Configuración CRM</a></li>
                    <li class="active">Conf. Pantalla Global</li>
                </ol>
                <div id="page-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h2 class="panel-title bg-primary">Configuración general</h2>
                                </div>
                                <div class="panel-body">
                            <form action="" id="formulario">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label">Idioma</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select name="idioma" id="idioma" class="selectpicker form-control" data-live-sarch="true" data-width="100%">
                                            <option id="español" value="Español">Español</option>
                                            <option id="ingles" value="Inglés">Inglés</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label">Longitud del Télefono</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input type="number" min="1" max="15" class="form-control" name="longitud_telefono" id="longitud_telefono" placeholder="Ingrese Longitud del Télefono">
                                    </div>
                                </div>
                                
                            <div class="col-sm-12"></div>
                          
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label">Moneda</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select name="moneda" id="moneda" class="selectpicker form-control" data-live-sarch="true" data-width="100%">
                                            <option id="peso" value="Pesos">Pesos</option>
                                            <option id="dolar" value="Dólar2">Dólar</option>
                                            <option id="uf" value="UF">UF</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label">Simbolo</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="simbolo" id="simbolo" placeholder="Ingrese un simbolo">
                                    </div>
                                </div>
                            
                            <div class="col-sm-12"></div>
                            
                            <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label">Horario Funcionamiento</label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                <label class="control-label">Inicio</label>
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <input id="time-start" name="time-start" type="text" class="form-control input-small"  >
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-time"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-offset-1 col-sm-2">
                                <label class="control-label">Termino</label>
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <input id="time-end" name="time-end" type="text" class="form-control input-small"  >
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-time"></i>
                                        </span>
                                    </div>
                                </div>
                                    <div class="form-group col-sm-offset-8 col-sm-3">
                                        <button type="submit" name="btnregistrar" id="btnregistrar" class="btn btn-primary btn-md">Registrar</button>
                                    </div>
                                </div>                               
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <?php include("../layout/main-menu.php"); ?>
            </div>
            <?php include("../layout/footer.php"); ?>

            <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
            
        </div>
    </div>

    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/skycons/skycons.min.js"></script>
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/admin/conf_global.js"></script>
</body>
</html>