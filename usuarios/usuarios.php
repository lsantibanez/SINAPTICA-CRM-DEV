<?php
require_once('../class/db/DB.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "adm,sis,usu"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction(); // VERIFICAR FUNCIONAMIENTO DE ESTE METODO
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = '';
$mandante = '';

if (isset($_SESSION['mandante'])){
  $mandante = $_SESSION['mandante'];
}

if (isset($_SESSION['cedente'])){
  $cedente = $_SESSION['cedente'];
}
$visible = "display:block";

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
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <script src="../plugins/pace/pace.min.js"></script>
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
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
    </style>
    <link rel="stylesheet" href="/css/global/global.css">
</head>
<body>
    <input type="hidden" name="mandante" id="mandante" value="<?php echo $mandante;?>" class="form-control">
    <input type="hidden" name="cedente" id="cedente" value="<?php echo $cedente;?>" class="form-control">

    <div id="container" class="effect mainnav-lg">
      <!--NAVBAR-->
      <!--===================================================-->
      <?php include("../layout/header.php"); ?>
      <!--===================================================-->
      <!--END NAVBAR-->
      <div class="boxed">
        <!--CONTENT CONTAINER-->
        <!--===================================================-->
        <div id="content-container">
          <!--Page Title-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <div id="page-title">
            <h1 class="page-header text-overflow">Gestionar usuarios</h1>
          <!--Searchbox-->
          </div>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End page title-->
          <!--Breadcrumb-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <ol class="breadcrumb">
            <li><a href="#">Configuración</a></li>
            <li><a href="#">Sistema</a></li>
            <li class="active">Gestionar usuarios</li>
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
                      <div class="panel-body">
                        <div class="row" style="padding: 12px;">
                          <div class="col-md-3">
                            <div style="<?php echo $visible ?>">
                              <button class="btn btn-success btn-block gestionar_usu" onclick="location.href='crear_usuarios.php'">
                                <i class="fa fa-user-plus"></i>&nbsp;&nbsp;Nuevo usuario
                              </button>
                            </div>
                          </div>
                        </div>
                        <div class="row" style="padding: 12px;">
                          <div class="col-md-12">
                            <div class="listaUsuarios"></div>
                          </div>
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
    <script src="../js/global/funciones-global.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../js/usuarios/usuarios.js"></script>
</body>
</html>