<?php
include("../class/global/global.php");
require_once('../class/db/DB.php');
require_once('../class/session/session.php');
$objetoSession = new Session('1,2,3,4,5,6',false); 
$objetoSession->crearVariableSession($array = array("idMenu" => "inicio,bien"));
$objetoSession->creaLogoutAction();

$estadisticas = [
  'carteras' => [],
  'resumen' => [
    'total_gestionados' => 0,
    'porcentaje_gestionados' => 0,
    'total_fatantes' => 0,
    'porcentaje_faltantes' => 0,
    'total' => 0
  ]
];

if (isset($_SESSION['mandante']) && !empty($_SESSION['mandante']) && intval($_SESSION['mandante']) > 0) {
  $db = new DB();
  $idMandante = (int) $_SESSION['mandante'];
  $mes = date('Y-m');
	$rsCedentes = $db->select("SELECT c.Id_Cedente AS id, c.Nombre_Cedente AS nombre FROM Cedente AS c JOIN mandante_cedente AS m ON m.Id_Cedente = c.Id_Cedente WHERE m.Id_Mandante = {$idMandante} ORDER BY c.Id_Cedente ASC;");
  $totFaltantes = 0;
	$totGestionados = 0;
  $totalAsignados = 0;
  foreach ((array) $rsCedentes as $cedente) {
		$faltantes = 0; $gestionados = 0;
		$rsGestionados = $db->select("SELECT COUNT(DISTINCT d.Rut) AS cantidad FROM gestion_ult_trimestre AS g JOIN Deuda AS d ON g.rut_cliente = d.Rut WHERE g.cedente = {$cedente['id']} AND DATE_FORMAT(g.fecha_gestion, '%Y-%m') = '{$mes}';");
		if (count((array) $rsGestionados) > 0) $gestionados = (int) $rsGestionados [0]['cantidad'];
		$rsFaltantes = $db->select("SELECT COUNT(DISTINCT Rut) AS cantidad FROM Deuda t1 WHERE NOT EXISTS (SELECT NULL FROM gestion_ult_trimestre t2 WHERE t2.rut_cliente = t1.Rut AND DATE_FORMAT(t2.fecha_gestion, '%Y-%m') = '{$mes}') AND t1.Id_Cedente = {$cedente['id']}");
		if (count((array) $rsFaltantes) > 0) $faltantes = (int) $rsFaltantes[0]['cantidad'];

    $totGestionados += (int) $gestionados;
		$totFaltantes += (int) $faltantes;
    $totalCategoria = ((int) $faltantes + (int) $gestionados);
    $totalAsignados += $totalCategoria;
    $porcentajeGestionados = ($totalCategoria > 0)? (((int) $gestionados * 100) / ($totalCategoria)): 0;
		$porcentajeFaltantes = ($totalCategoria > 0)? (((int) $faltantes * 100) / ($totalCategoria)):0;
		$estadisticas['carteras'][] = [
			'cartera' => $cedente['nombre'],
			'gestionados' => number_format((int) $gestionados,0,',','.'),
      'porcentaje_gestionados' => number_format((float) $porcentajeGestionados,2,',','.'),
			'faltantes' => number_format((int) $faltantes,0,',','.'),
      'porcentaje_faltantes' => number_format((float) $porcentajeFaltantes,2,',','.'),
      'total' => number_format((int) $totalCategoria,0,',','.'),
		];
	}

  $porcentajeTotalGestionados = ($totalAsignados > 0)? (((int) $totGestionados * 100) / ($totalAsignados)): 0;
	$porcentajeTotalFaltantes = ($totalAsignados > 0)? (((int) $totFaltantes * 100) / ($totalAsignados)):0;

  $estadisticas['resumen'] = [
    'total_gestionados' => number_format((int) $totGestionados,0,',','.'),
    'porcentaje_gestionados' => number_format((float) $porcentajeTotalGestionados,2,',','.'),
    'total_fatantes' => number_format((int) $totFaltantes,0,',','.'),
    'porcentaje_faltantes' => number_format((float) $porcentajeTotalFaltantes,2,',','.'),
    'total' => number_format((int) $totalAsignados,0,',','.')
  ];
}

header('Content-Type: application/json;charset=utf-8');
echo json_encode($estadisticas);