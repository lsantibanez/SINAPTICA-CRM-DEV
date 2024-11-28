<?php
require_once('../class/db/DB.php');
require_once('../class/session/session.php');
include("../class/global/global.php");
include("../class/global/cedente.php");

$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));
$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "adm,gestion,agregar_campos"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")) {
  //o fully log out a visitor we need to clear the session varialbles
  $objetoSession->borrarVariablesSession();
  $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRM | Software de Estrategia</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/nifty.min.css" rel="stylesheet">
  <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
  <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
  <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
  <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../css/global/global.css" rel="stylesheet">
</head>
<body>
  <div id="container" class="effect mainnav-lg">
    <?php include("../layout/header.php");  ?>
    <div class="boxed">
      <div id="content-container">
        <div id="page-title">
          <h1 class="page-header text-overflow">Columnas estrategias</h1>
        </div><!--Searchbox-->
        <ol class="breadcrumb">
          <li><a href="#">Configuración</a></li>
          <li class="active">Columnas estrategias</li>
        </ol><!--Breadcrumb-->
        <div id="page-content">
          <div class="row">
            <div class="col-md-12">
              <div class="panel">
                <div class="panel-body">
                  <div class="row" style="padding: 12px;">
                    <div class="col-md-12">
                      <button type="button" class="btn btn-success">Agregar columna</button>
                    </div>
                  </div>
                  <div class="row" style="padding: 12px;">
                    <div class="col-md-12">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th style="width: 20%;">Tabla</th>
                            <th>Columna</th>
                            <th style="width: 15%; text-align: center;">Tipo dato</th>
                            <th style="width: 20%; text-align: center;">Lógica</th>
                            <th style="width: 10%; text-align:center;">&nbsp;</th>
                          </tr>
                        </thead>
                        <tbody id="bodyTableColumnas">                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><!-- content-container -->
      <?php include("../layout/main-menu.php"); ?>
    </div><!-- boxed -->
    <?php include("../layout/footer.php"); ?>
    <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
    <div class="modal">&nbsp;</div>
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
      </div><!-- /.modal-dialog-waiting -->
    </div><!-- /.modal -->
  </div><!-- container -->
  <script src="../js/jquery-2.2.1.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../plugins/fast-click/fastclick.min.js"></script>
  <script src="../js/nifty.min.js"></script>
  <script src="../js/global/funciones-global.js"></script>
  <script src="../js/admin/conf_columnas.js"></script>
</body>
</html>