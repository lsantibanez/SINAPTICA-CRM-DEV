<?PHP
require_once('../class/db/DB.php');
//require_once('../class/db/vicidial_db.php');
require_once('../class/session/session.php');

include("../class/global/global.php");
include_once("../class/logs.php");
$logsClass = new Logs();
$Omni = new Omni;
$Permisos = $Omni->getPermisos('../'.trim(strrchr(__DIR__, DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR) . '/' . basename(__FILE__));

$objetoSession = new Session($Permisos,false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "tar,mcontacts"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction("../index.php");
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo();
}
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = $_SESSION['cedente'];
$db = new Db();

function imprimeTabla($datos) {
    if (count((array) $datos) > 0) { 
      foreach($datos['rows'] as $nivel1) {
        echo '<tr style="background-color: #eee;">'.PHP_EOL;
        echo '<td>'.$nivel1['nombre'].'</td>'.PHP_EOL;
        echo '<td style="text-align: center;">'.$nivel1['ruts'].'</td>'.PHP_EOL;
        //echo '<td style="text-align: right;">$ '.number_format($nivel1['total_alta'],0,',','.').'</td>'.PHP_EOL;
        echo '<td style="text-align: right;">$ '.number_format($nivel1['total_baja'],0,',','.').'</td>'.PHP_EOL;
        echo '</tr>';
        foreach ($nivel1['items'] as $nivel2) {
          echo '<tr>'.PHP_EOL;
          echo '<td style="padding-left: 35px;">'.$nivel2['gestion_n2'].'</td>'.PHP_EOL;
          echo '<td style="text-align: center;">'.$nivel2['ruts'].'</td>'.PHP_EOL;
          //echo '<td style="text-align: right;">$ '.number_format($nivel2['total_alta'],0,',','.').'</td>'.PHP_EOL;
          echo '<td style="text-align: right;">$ '.number_format($nivel2['total_baja'],0,',','.').'</td>'.PHP_EOL;
          echo '</tr>';
        }
      }
      echo '<tr style="background-color: #eee; font-weight: 600;">'.PHP_EOL;
      echo '<td style="padding: 5px 5px 5px 35px; border-top: 1px solid #ccc; text-align: right;">Total:</td>'.PHP_EOL;
      echo '<td style="padding: 5px; border-top: 1px solid #ccc; text-align: center;">'.number_format((int) $datos['general']['ruts'],0,',','.').'</td>'.PHP_EOL;
      //echo '<td style="padding: 5px; border-top: 1px solid #ccc; text-align: right;">$ '.number_format($datos['general']['total_alta'],0,',','.').'</td>'.PHP_EOL;
      echo '<td style="padding: 5px; border-top: 1px solid #ccc; text-align: right;">$ '.number_format($datos['general']['total_baja'],0,',','.').'</td>'.PHP_EOL;
      echo '</tr>';
    } else {
      echo '<tr><td colspan="4" style="text-align: center; padding: 12px;">No hay datos</td></tr>'.PHP_EOL;
    }
}

try {
    $vista = 'mes';
    $fDesde = date('Y-m').'-01';
    $fHasta = date('Y-m-d');
    if (isset($_GET['desde']) && !empty($_GET['desde'])) {
      $fDesde = $_GET['desde'];
    }
    if (isset($_GET['hasta']) && !empty($_GET['hasta'])) {
      $fHasta = $_GET['hasta'];
    }
    if (isset($_GET['vista']) && !empty($_GET['vista'])) {
      $vista = $_GET['vista'];
    }
    $datos = [
      'rows' => [], 
      'general' => [
        'ruts' => 0,
        'total' => 0
      ]
    ];

    $selectVista_a = " AND (DATE(u.fecha_gestion) BETWEEN '{$fDesde}' AND '{$fHasta}') ";
    $selectVista_b = " AND (DATE(b.fecha_gestion)  BETWEEN '{$fDesde}' AND '{$fHasta}') ";

    if ($fDesde == $fHasta) {
      $selectVista_a = " AND (DATE(u.fecha_gestion) = '{$fHasta}') ";
      $selectVista_b = " AND (DATE(b.fecha_gestion) = '{$fHasta}') ";
    }

    $strSQL = "SELECT 
        c.gestion_n1,
        c.gestion_n2,
        COUNT(DISTINCT d.Rut) AS ruts,
        COUNT(d.Factura) AS facturas,
        SUM(d.deuda) AS total_deuda,
        SUM(IF(d.saldo > 0, d.deuda, 0)) AS total_alta,
        SUM(IF(d.saldo = 0, d.deuda, 0)) AS total_baja
      FROM 
      (SELECT 
        b.rut_cliente,
        b.n1,
        (SELECT Respuesta_N1 FROM Nivel1 WHERE id = b.n1 LIMIT 1) AS gestion_n1,
        b.n2,
        (SELECT Respuesta_N2 FROM Nivel2 WHERE id = b.n2 LIMIT 1) AS gestion_n2,
        a.mayor_peso,
        b.cedente,
        b.fecha_gestion
      FROM
      (SELECT
        u.rut_cliente,
        u.Peso AS ultimo_peso,
        MIN(u.Peso) AS mayor_peso
      FROM gestion_ult_trimestre AS u 
      WHERE 
        cedente = {$cedente}
        {$selectVista_a}
      GROUP BY u.rut_cliente) AS a
      JOIN gestion_ult_trimestre AS b
      ON a.rut_cliente = b.rut_cliente AND a.mayor_peso = b.Peso
      WHERE 
        b.cedente = {$cedente}
        {$selectVista_b}
      ) AS c
      JOIN Deuda AS d ON d.Rut = c.rut_cliente AND d.Id_Cedente = c.cedente
      GROUP BY c.n2;";
    //echo $strSQL; exit;
    $result = $db->query($strSQL);
    
    if($result && $result->num_rows > 0) {
      $rows = $result->fetch_all(MYSQLI_ASSOC);
      $result->close();
      $newArr = [];

      foreach($rows as $values) {
        $newArr[$values['gestion_n1']]['nombre'] = $values['gestion_n1'];
        $newArr[$values['gestion_n1']]['total_alta'] = 0;
        $newArr[$values['gestion_n1']]['total_baja'] = 0;
        $newArr[$values['gestion_n1']]['ruts'] = 0;
        $newArr[$values['gestion_n1']]['items'][] =  $values;
      }

      foreach($newArr as $key => $value) {
        $newArr[$key]['ruts'] = array_reduce($value['items'], function($carry, $item) {
          $carry += (int) $item['ruts'];
          return $carry;
        }, 0);
        $newArr[$key]['total_alta'] = array_reduce($value['items'], function($carry, $item) {
          $carry += (float) $item['total_alta'];
          return $carry;
        }, 0);

        $newArr[$key]['total_baja'] = array_reduce($value['items'], function($carry, $item) {
          $carry += (float) $item['total_baja'];
          return $carry;
        }, 0);
      }

      $general['ruts'] = array_reduce(array_values($newArr), function($carry, $item) {
        $carry += (int) $item['ruts'];
        return $carry;
      }, 0);
    
      $general['total_alta'] = array_reduce(array_values($newArr), function($carry, $item) {
        $carry += (float) $item['total_alta'];
        return $carry;
      }, 0);

      $general['total_baja'] = array_reduce(array_values($newArr), function($carry, $item) {
        $carry += (float) $item['total_baja'];
        return $carry;
      }, 0);

      $datos['rows'] = $newArr;
      $datos['general'] = $general;
    }

    if (isset($_GET['update']) && $_GET['update'] == 'true') {
      imprimeTabla($datos);
      exit;
    }
} catch (\Exception $ex) {
    $logsClass->error('ERROR Monitor de contactabilidad');
    $logsClass->error($ex->getMessage());
}

$SqlTipoSistema = "SELECT tipoSistema FROM fireConfig";
$TipoSistema = $db->select($SqlTipoSistema);
$firstrow = $TipoSistema[0];
$tipoSistema = $firstrow["tipoSistema"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Sinaptica | Software de Estrategia</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/nifty.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.css" rel="stylesheet">
    <link href="../plugins/chosen/chosen.min.css" rel="stylesheet">
    <link href="../plugins/noUiSlider/nouislider.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/dropzone/dropzone.css" rel="stylesheet">
    <link href="../plugins/summernote/summernote.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <link href="../css/global/global.css" rel="stylesheet">
    <script src="../plugins/Chart.js/Chart.bundle.min.js"></script>
    <style>
      .form-control {
        padding-top: 3px !important;
        padding-bottom: 3px !important;
        line-height: 18px !important;
      }
    </style>
</head>
<body>
  <input type="hidden" id="cedente" value="<?php echo $cedente; ?>">
  <input type="hidden" id="tipoSistema" value="<?php echo $tipoSistema; ?>">
    <div id="container" class="effect mainnav-lg">
        <!--NAVBAR-->
        <!--===================================================-->
        <?php include("../layout/header.php");  ?>
        <!--===================================================-->
        <!--END NAVBAR-->
        <div class="boxed">
            <!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <!--Page Title-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <div id="page-title">
                    <h1 class="page-header text-overflow">Avance de contactabilidad</h1>
                    <!--Searchbox-->
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->
                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
                    <li><a href="#">Monitor</a></li>
                    <li class="active">Avance de contactabilidad</li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->
                <!--Page content-->
                <!--===================================================-->
                    <!-- clase AsignadorOculto -->
                <div id="page-content">
					        <div class="row">
                        <div class="eq-height">
                            <div class="col-sm-12 eq-box-sm">
                                <div class="panel" id='padre'>
                                    <div class="panel-body">
                                      <div class="row" style="padding: 15px;">
                                        <div class="col-lg-5">
                                          <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-3 control-label" style="text-align: right; padding-top: 5px;">Rango de fecha:</label>
                                            <div class="col-lg-9">
                                              <div class="input-group input-group-sm">
                                                <input type="date" name="desde" id="desde" value="<?php echo $fDesde; ?>" class="form-control" style="border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important; border-right: none !important;">
                                                <span class="input-group-addon" id="sizing-addon1">-</span>
                                                <input type="date" name="hasta" id="hasta" value="<?php echo $fHasta; ?>" class="form-control" style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; border-left: none !important;">
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-lg-2">
                                          <button type="button" id="btnFind" class="btn btn-block btn-primary">Buscar</button>
                                        </div>
                                      </div><!-- row 0 -->

                                        <div class="row" style="padding: 15px;">
                                            <div class="col-md-12">
                                                <div id="cambiar">
                                                    <div id="countdown" style="margin-bottom: 10px;">&nbsp;</div>
                                                       <table id="agentsLists" class="table table-striped" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                              <th>Etiqueta</th>
                                                              <th style="width: 20%; text-align: center;">Ruts</th>
                                                              <!-- <th style="width: 25%; text-align: right;">Saldos (alta)</th> -->
                                                              <th style="width: 25%; text-align: right;">Saldos (baja)</th>  
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                          <?php imprimeTabla($datos); ?>
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
    <script id="TemplateCautivo" type="text/template">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <label class="form-checkbox form-icon btn btn-info btn-labeled form-text"><input type="checkbox" name="inputCautiva">Activo</label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="form-group">
                    <label class="control-label">Ejecutivo:</label>
                    <select class="selectpicker form-control" name="EjecutivoColaCautiva" id="EjecutivoColaCautiva" title="Seleccione" data-live-search="true" data-width="100%"></select>
                </div>
            </div>
        </div>
    </script>
    <!--JAVASCRIPT-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../plugins/jquery-mask/jquery.mask.min.js"></script> 
    <script src="../js/global.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/monitor/monitor-contactabilidad.js"></script>
</body>
</html>