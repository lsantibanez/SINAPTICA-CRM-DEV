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
$objetoSession->crearVariableSession($array = array("idMenu" => "tar,agents"));
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

function getStatusText($valor)
{
    $estados = [
        'PAUSED' => [
            'class' => 'info',
            'texto' => 'En pausa'
        ],
        'READY'  => [
            'class' => 'warning',
            'texto' => 'Esperando llamada'
        ],
        'INCALL' => [
            'class' => 'success',
            'texto' => 'En llamada'
        ]
    ];

    if (in_array($valor , array_keys($estados))) {
        return [
            $estados[$valor]['class'],
            $estados[$valor]['texto']
        ];
    }
     
    return ['',$valor];
}

function imprimeTabla($agentes) {
    if (count((array) $agentes) > 0) { 
        $totLlamadas = 0;
        $totTitulares = 0;
        $totTerceros = 0;
        $totNocontacto = 0;
        $totPendientes = 0;
        $totAvances = 0;
        foreach((array) $agentes as $agente)
        { 
            $pendientes = ((int) $agente['registros'] - (int) $agente['llamadas']);
            $avance  = ((int) $agente['llamadas'] / (int) $agente['registros']) * 100;
            $totAvances += $avance;
            echo '<tr>'.PHP_EOL;
            echo '<td style="text-align: left !important;">';
            echo "{$agente['agente']}";
            echo '</td>';
            echo '<td style="text-align: left !important;">'.$agente['campania'].'</td>';
            echo "<td>{$agente['llamadas']}&nbsp;&nbsp;-&nbsp;&nbsp;".number_format($avance,2,',','.')." %</td>";
            echo "<td>{$agente['titulares']}</td>";
            echo "<td>{$agente['terceros']}</td>";
            echo "<td>{$agente['no_contacto']}</td>";
            echo "<td>".number_format($pendientes,0,',','.')."</td>";
            echo "<td>{$agente['fecha']}</td>";
            echo "</tr>".PHP_EOL; 
            $totLlamadas += (int) $agente['llamadas'];
            $totTitulares += (int) $agente['titulares'];
            $totTerceros += (int) $agente['terceros'];
            $totNocontacto += (int) $agente['no_contacto'];   
            $totPendientes += $pendientes;       
        }

        // $avanceTotal  = ((int) $totLlamadas / (int) $agente['registros']) * 100;
        echo '<tr style="font-weight: 600 !important;">'.PHP_EOL;
        echo '<td colspan="2" style="text-align: right !important;">Total:</td>'.PHP_EOL;
        echo "<td>{$totLlamadas}&nbsp;&nbsp;-&nbsp;&nbsp;".number_format($totAvances,2,',','.')." %</td>".PHP_EOL;
        echo "<td>{$totTitulares}</td>".PHP_EOL;
        echo "<td>{$totTerceros}</td>".PHP_EOL;
        echo "<td>{$totNocontacto}</td>".PHP_EOL;
        echo "<td>".number_format($totPendientes,0,',','.')."</td>".PHP_EOL;
        echo '<td>&nbsp;</td>'.PHP_EOL;
        echo '</tr>'.PHP_EOL;
    } else {
        echo '<tr><td colspan="8" style="text-align: center; padding: 12px;">No hay datos</td></tr>'.PHP_EOL;
    }
}

try {
    $fecha = date('Y-m-d');
    if (isset($_GET['fecha']) && !empty($_GET['fecha'])) {
        $fecha = $_GET['fecha'];
    }

    $db1 = new Db();
    $mysqli_remote = $db1->getInstance();
    $agentes = [];
    /*
    $strSQL = "SELECT 
        l.`user` AS agent,
        (SELECT cc.campaign_name FROM vicidial_campaigns AS cc WHERE cc.campaign_id = c.campaign_id LIMIT 1) AS campaign,
        COUNT(1) AS calls,
        SUM(IF(l.vendor_lead_code != 'DIS' AND l.`status` NOT IN('INCALL'),1,0)) AS calls,
        SUM(IF(l.`status` IN('SALE','B') AND l.called_since_last_reset = 'Y' ,1,0)) AS `owner`,
        SUM(IF(l.`status` = 'CALLBK' AND l.called_since_last_reset = 'Y',1,0)) AS non_owner,
        SUM(IF(l.`status` = 'PM' AND l.called_since_last_reset = 'Y',1,0)) AS no_contacted,
        SUM(IF(l.`status` = 'DROP' AND l.called_since_last_reset = 'Y',1,0)) AS not_attended,
        SUM(IF(l.`status` = 'DNC' AND l.vendor_lead_code = 'DIS',1,0)) AS `disabled`,
        DATE_FORMAT(l.last_local_call_time, '%d-%m-%Y') AS fecha
        FROM vicidial_lists AS c JOIN vicidial_list AS l
        ON c.list_id = l.list_id
        WHERE 
        c.campaign_id LIKE '{$cedente}C%'
        AND 
        l.`status` != 'NEW'
        AND l.`user` != 'VDAD'
        AND 
        l.called_since_last_reset = 'Y'
        AND l.`user` != ''
        AND 
        DATE(l.last_local_call_time) = '$fecha'
        GROUP BY l.`user`, c.campaign_id
        ORDER BY l.`user` ASC, DATE(l.last_local_call_time) DESC;";
        */
    $strSQL = "SELECT
        SUM(IF(g.Id_TipoGestion = 1, 1, 0 )) AS titulares,
        SUM(IF(g.Id_TipoGestion = 3, 1, 0 )) AS terceros,
        SUM(IF(g.Id_TipoGestion = 2, 1, 0 )) AS no_contacto,
        COUNT(g.rut_cliente) AS llamadas,
        c.registros,
        c.nombre AS campania,
        (SELECT nombre FROM Usuarios WHERE usuario = g.nombre_ejecutivo LIMIT 1) AS agente,
        DATE_FORMAT(fecha_gestion, '%d-%m-%Y') AS fecha
    FROM
        gestion_ult_trimestre AS g JOIN
        campaigns AS c ON c.service_id = g.cod_campaign
    WHERE
        g.cedente = {$cedente}
        AND g.fecha_gestion = '{$fecha}'
    GROUP BY
        g.cod_campaign, g.nombre_ejecutivo
    ORDER BY llamadas DESC;";
    
    $sql_monitor = $mysqli_remote->query($strSQL);
    if($sql_monitor && $sql_monitor->num_rows > 0) {
        $agentes = $sql_monitor->fetch_all(MYSQLI_ASSOC);
        $sql_monitor->close();
    }

    if (isset($_GET['update']) && $_GET['update'] == 'true') {
        imprimeTabla($agentes);
        exit;
    }
} catch (\Exception $ex) {
    $logsClass->error('ERROR Monitor de agentes');
    $logsClass->error($ex->getMessage());
}

$db = new Db();
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
    <style type="text/css">
    #mostrar_estrategia
             {
        display: none;
             }
    #mostrar_cola
             {
        display: none;
             }
    #mostrar_gestor
             {
        display: none;
             }
    #mostrar_asignacion
             {
        display: none;
             }
    #acciones_seleccionadas
             {
        display: none;
             }
	 #acciones_seleccionadas2
             {
        display: none;
             }
      .select1
             {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #FFFFFF;
    	border-width: 1px;

             }
    .select2
            {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
		border-width: 1px;
        background-color: #CCC;

            }
    .text1
            {
        width: 100%;
        height: 30px;
        border: solid;
        background-color: #FFFFFF;
    	border-width: 1px;
		border-color: #ccc;

            }
    .text2
            {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
		border-width: 1px;
        background-color: #CCC;

            }
        .asignacion
             {
        width: 100%;
        height: 30px;
        border: none;
        border-top-width: thin;
        border-right-width: thin;
        border-bottom-width: thin;
        border-left-width: thin;
        text-align: center;

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
          #page-content.AsignadorOculto {
                width: 0px !important;
                height: 0px !important;
                padding: 0 !important;
                overflow: hidden;
            }
            #page-content {
                padding: 5px 20px 0;
                width: 100%;
                height: 100%;
                transition: all 0.5s ease;
            }
            #AsignadorDeCasos{
                width: 0px;
                transition: all 0.5s ease;
                overflow: hidden;
            }
            #AsignadorDeCasos.AsignadorOculto {
                width: 100% !important;
                padding: 5px 20px 0;
                overflow: hidden;
            }
            .fa.Disabled{
                color: #cccccc;
            }
            .dropdown-menu.open {
                max-height: none !important;
            }
            th {
                text-align: center !important;
            }
            table > tbody > tr > td {
                text-align: center !important;
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
                    <h1 class="page-header text-overflow">Avance agentes</h1>
                    <!--Searchbox-->
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->
                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
                    <li><a href="#">Monitor</a></li>
                    <li class="active">Avance agentes</li>
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
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="col-sm-2 control-label" style="text-align: right; padding-top: 5px;">Fecha:</label>
                                                    <div class="col-sm-8">
                                                        <input type="date" value="<?php echo $fecha; ?>" class="form-control" name="fechaGestion" id="fechaGestion" style="line-height: 8px;" title="Fecha gestión">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" id="btnFind" class="btn btn-block btn-primary">Buscar</button>
                                            </div>
                                        </div>
                                        <div class="row" style="padding: 15px;">
                                            <div class="col-md-12">
                                                <div id="cambiar">
                                                    <div id="countdown" style="margin-bottom: 10px;">&nbsp;</div>
                                                       <table id="agentsLists" class="table table-striped" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                   <!-- <th>ID Estrategia</th> -->
                                                                <th style="text-align: left !important;">Usuario</th>
                                                                <th style="width: 20%; text-align: left !important;">Campaña</th>
                                                                <th style="width: 10%;">Llamadas</th>
                                                                <th style="width: 10%;">Titular</th>
                                                                <th style="width: 10%;">Tercero</th>
                                                                <th style="width: 10%;">No contactado</th>
                                                                <th style="width: 10%;">Pendientes</th>
                                                                <th style="width: 15%;">Fecha</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php imprimeTabla($agentes); ?>
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
    <script src="../js/monitor/monitor-campaign-agents.js"></script>
</body>
</html>
