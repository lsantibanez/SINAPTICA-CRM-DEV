<?php
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "gra,graf,est_seg"));
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
    <link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <link href="../css/global/global.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.js"></script>
    
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

 #divtablapeq {
    width: 500px;
    }
 #divtablamed {
    width: 600px;
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
        <!--CONTENT CONTAINER-->
        <!--===================================================-->
        <div id="content-container">
          <!--Page Title-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <div id="page-title">
            <h1 class="page-header text-overflow">Estadistica por Segmento</h1>
          <!--Searchbox-->
          </div>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End page title-->
          <!--Breadcrumb-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <ol class="breadcrumb">
            <li><a href="#">Reporteria</a></li>
            <li class="active">Estadistica por Segmento</li>
          </ol>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End breadcrumb-->
          <!--Page content-->
          <!--===================================================-->
          <div id="page-content">
            <div class="row">
                <div class="eq-height">
                    <div class="col-sm-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <h2 class="panel-title bg-mint">Datos de la Carga:</h2>
                            </div>
                            <div class="panel-body" width="100%">
                                <div class="row" style="padding: 12px;">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="name">Campo:</label>
                                            <select id="Campo" name="Campo" class="selectpicker form-control" title="Seleccione" data-live-search="true" data-width="100%">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="name">Seleccione Valor:</label>
                                            <select class="selectpicker" id="segmento" name="segmento" title="Seleccione" data-live-search="true" data-width="100%">
                                            <!--<?php
                                                echo $_SESSION['cedente'];
                                                if ($_SESSION['cedente'] == '1'){
                                            ?>
                                                    <option value="90+">90+</option>
                                                    <option value="61-90">61-90</option>
                                                    <option value="46-60">46-60</option>
                                                    <option value="31-45">31-45</option>
                                                    <option value="1-30">1-30</option>
                                            <?php
                                                }else{
                                                    if (($_SESSION['cedente'] == '107') || ($_SESSION['cedente'] == '215')){
                                            ?>
                                                        <option value="Mora Dura">Mora Dura</option>
                                                        <option value="Mora Blanda">Mora Blanda</option>                                              
                                            <?php
                                                    }
                                                }
                                            ?>-->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" for="name">&nbsp;</label>
                                        <button class="btn btn-primary btn-block" id="ver">Ver</button>
                                    </div>
                                </div>  
                               </div>
                                 <div class="row" width="100%" id="titulos">   
                                    <!-- Torta 1 tipo contacto -->
                                    <div class="col-md-2" align="center"></div>                                 
                                    <div class="col-md-4" align="center">Grafico por Montos</div>
                                    <div class="col-md-4" align="center">Grafico por Casos</div>
                                    <div class="col-md-2" align="center"></div>
                                 </div> 
                                 <br><br>
                                 <div class="row" width="100%">   
                                    <!-- Torta 1 tipo contacto -->
                                    <div class="col-md-2">
                                        <div class="col-md-12" align="center" id="leyendaMontos">
                                            <div class="list-group">
                                            </div>                                    
                                        </div>  
                                    </div>                                    
                                    <div class="col-md-4"><canvas id="myChart5"></canvas></div>
                                    <div class="col-md-4"><canvas id="myChart"></canvas></div>
                                    <div class="col-md-2">
                                        <div class="col-md-12" align="center" id="leyendaMontos2">
                                            <div class="list-group">
                                            
                                            </div>                                    
                                        </div>  
                                    </div>
                                 </div>   
                                 <div class="row">
                                    <div class="col-md-4"><canvas id="myChart1"></canvas></div>
                                    <div class="col-md-4"><canvas id="myChart2"></canvas></div>
                                    <div class="col-md-4"><canvas id="myChart3"></canvas></div>
                                 </div>
                                 <div class="row" width="100%">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4"><canvas id="myChartBarra"></canvas></div>
                                    <div class="col-md-4"></canvas></div>
                                 </div>
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
    <!--===================================================-->
    <!-- END OF CONTAINER -->
    <!--JAVASCRIPT-->
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
    <script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/reporte/reporteContactabilidad.js"></script>
   
</body>
</html>