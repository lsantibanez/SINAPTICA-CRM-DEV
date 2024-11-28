<?php
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$objetoSession = new Session('1,2,3,4,5,6',false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "inicio,bien"));
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
                </div>
                <br>
                <ol class="breadcrumb">
                    <li><a href="#">Periodo</a></li>
                    <li class="active">Mantenedor Periodo</li>
                </ol>
                <div id="page-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h2 class="panel-title bg-primary">Filtro</h2>
                                </div>
                                <div class="panel-body">
                                        <form action="" id="formulario">
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label class="control-label">Seleccione Cedente</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <select name="cedente" id="cedente" class="selectpicker form-control" data-live-sarch="true" data-width="100%">
                                                    </select>
                                                </div>
                                            </div>
                                           <div class="col-sm-12"></div>              
                                            
                                               
                                           <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label class="control-label">Seleccione Periodo en días</label>
                                                </div>
                                            </div>
                                            
                                                <div class="col-sm-2">
                                                <label class="control-label">Inicio</label>
                                                    <div class="form-group">
                                                        <input type="number" min="1" max="31" class="form-control" name="periodo_inicio" id="periodo_inicio" placeholder="Ingrese periodo inicio">
                                                    </div>
                                                </div>

                                                <div class="col-sm-2">
                                                <label class="control-label">Término</label>
                                                    <div class="form-group">
                                                        <input type="number" min="1" max="31" class="form-control" name="periodo_fin" id="periodo_fin" placeholder="Ingrese periodo fin">
                                                    </div>
                                                </div>
                                                <div class="col-sm-offset-6 col-sm-2">
                                                    <div class="form-group">
                                                        <button type="submit" name="btnregistrar" id="btnregistrar" class="btn btn-primary btn-md">Registrar</button>
                                                    </div>
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
    <script src="../js/periodo/periodo.js"></script>
</body>
</html>