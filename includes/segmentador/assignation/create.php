<?php

require_once __DIR__.'/../../../class/db/DB.php';
require_once __DIR__.'/../../../class/session/session.php';
require_once __DIR__.'/../../../class/global/global.php';
require_once __DIR__.'/../../../class/logs.php';
require __DIR__.'/../../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use League\Csv\Writer;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

$db = new DB();
$logs = new Logs();

$objetoSession = new Session('1,2,3,4,5,6',false);
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout'] == "true")) {
  //to fully log out a visitor we need to clear the session varialbles
  $objetoSession->borrarVariablesSession();
  $objetoSession->logoutGoTo("../../index.php");
}

/** Variables desde la sesión */
$usuario = $_SESSION['MM_Username'];
$cedente = (int) $_SESSION['cedente'];
$nombreUsuario = $_SESSION['nombreUsuario'];
$id_estrategia = (int) $_SESSION['IdEstrategia'];
$idUsuarioLogin = (int) $_SESSION['id_usuario'];

$datosPost = json_decode(file_get_contents("php://input"), true);

$idQuery = (int) $datosPost['id_grupo'];
$url = $_ENV['DISCADOR_API_URL'];
//$datosPost = $_POST;
$nombreCedente = '';
$rsCedente = $db->select("SELECT * FROM Cedente WHERE Id_cedente = {$cedente}");
if ($rsCedente) {
  $nombreCedente = $rsCedente[0]['Nombre_Cedente'];
}

$logs->debug('Crear campaña: '.$url);
// $logs->debug($datosPost);

$client = new Client([
  'base_uri' => $url,
  'timeout'  => 10.0,
  'verify' => false,
]);

$message = '';
//$datosPost['id'] = (int) $datosPost['cedente'];

try {
  if (!empty($nombreCedente)) {
    $datosPost['params']['name'] = $nombreCedente.' - '.$datosPost['params']['name'];
  }

  if (strlen($datosPost['params']['name']) > 40) {
    $body = [
      'success' => false,
      'message' => 'Nombre de la campaña no debe exeder los 40 caracteres.'
    ];
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($body);
    exit;
  }


  $tipoDiscado = [
    '1' => 'Progresivo',
    '2' => 'Predictivo',
    '3' => 'Asistido'
  ];
  $datosPost['cedente'] = $cedente;
  $datosPost['description'] = 'Campaña - '.$datosPost['params']['name'];

  $logs->debug($datosPost);
  $res = $client->request('POST', 'campaigns/create', [
    'json' => $datosPost,
  ]);

  $status = (int) $res->getStatusCode();
  $body = $res->getBody()->getContents();
  $logs->debug('Respuesta: ');
  //$logs->debug($body);
  $paramsPost = $datosPost['params'];

  if ($status === 200) {
    $body = json_decode($body, true);
    $discado = (array_key_exists($paramsPost['dealer_type'], $tipoDiscado))? $tipoDiscado[$paramsPost['dealer_type']]: 'N/D';
    //$logs->debug('Desde discador: ');
    //$logs->debug($body);
    $data = $body['data'];
    $nombreCampania = $data['campaign_name'];
    $parametros = json_encode([
      'id_discador' => $data['campaign_id'],
      'dialer_type' => $discado,
      'intensity' => (int) $paramsPost['intensity'],
      'assing_to' => ($paramsPost['assign_to'] != 'ALL')? 'Agentes seleccionados': 'Todos los agentes',
      'agents' => (count((array) $paramsPost['agents']) > 0)? $paramsPost['agents']:[],
    ]);
    $body['data']['id_campaign'] = (int) $db->insert("INSERT IGNORE INTO campaigns (id_cedente, service_id, nombre, tipo, registros, parametros, estadisticas) VALUES({$cedente},'{$data['campaign_id']}','{$nombreCampania}','discador',0,'{$parametros}','[]') ON DUPLICATE KEY UPDATE parametros = '{$parametros}', registros = 0;");
  } 

} catch(RequestException $ex) {
  $status = 500; //$ex->getResponse()->getStatusCode();
  $message = $ex->getmessage();
  $logs->error($message);
  $body = [
    'success' => false,
    'message' => 'Se ha presentado un error al intentar procesar la información.'
  ];
} catch (ConnectException $ex) {
  $status = 500;// $ex->getStatusCode();
  $message = $ex->getmessage();
  $logs->error($message);
  $body = [
    'success' => false,
    'message' => 'Se ha presentado un error al intentar transferir los datos al discador.'
  ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($body);
exit;