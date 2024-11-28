<?php
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "gra,est_car"));
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
          <!--Searchbox-->
          </div>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End page title-->
          <!--Breadcrumb-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

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
                                <h2 class="panel-title bg-mint">Datos de la carga:</h2>
                            </div>
                            <div class="panel-body" width="100%">
                                 <div class="row">          
                                    <!-- Inicio -->
                                    <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="sel1">Fecha de la carga</label>
                                        <input type="text" disabled name="fechaCarga" id="fechaCarga" class="form-control" value="">                                            
                                    </div>
                                </div>  

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div id="Div1">
                                            <label for="sel1">Cantidad de Rut:</label>
                                            <input type="text" disabled name="cantidadRut" id="cantidadRut" class="form-control" value="">
                                        </div>
                                    </div>  
                                </div>
                                
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div id="Div2">
                                            <label for="sel1">Total Deuda:</label>
                                            <input type="text" disabled name="totalDeuda" id="totalDeuda" class="form-control" value="">
                                        </div>
                                    </div> 
                                </div>    
                                
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div id="Div3">
                                             <div class="form-group">
                                            <!--<label class="control-label">Ver Casos</label>
                                            <button class="btn btn-primary btn-block" id="verDeudas">Ver</button> -->
                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                        <div class="form-group">
                                           
                                        </div>
                                    </div>
                                    <!-- Fin -->
                                 </div>

                                  <div class="row">   
                                    <!-- Torta 1 tipo contacto -->
                                    <div class="col-md-3"></div>                                    
                                    <div class="col-md-6"><canvas  style="height: 15px" id="myChart"></canvas></div>
                                    <div class="col-md-3"></div>
                                 </div>   


                                 <div class="row">
                                     <h3 class="text-mint">Casos:</h3><br>
                              
                                 </div>

                                 <div class="row">
                                    <!-- Inicio listar tablas -->
                      
                            <div class="table-responsive" style="height: 500px;overflow: auto;">
                               
                                <table id="listaDeudas" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 20%">Rut</th>
                                            <th style="width: 50%">Nombre</th>
                                            <th style="width: 10%">Monto Mora</th>
                                            <th style="width: 10%">Fecha Vencimiento</th>
                                            <th style="width: 10%">Número de Factura</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                 </table>
                            </div>
                            
                           <!-- Fin listar tablas -->
                                 </div>

                                 <div class="row" width="100%">   
                                    <!-- Torta 1 tipo contacto -->
                                    <div class="col-md-3"></div>                                    
                                    <div class="col-md-6"><canvas id="myChart"></canvas></div>
                                    <div class="col-md-3"></div>
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
    <script src="../js/reporte/reporteEstadisticaCarga.js"></script>
   
</body>
</html>