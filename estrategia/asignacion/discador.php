<?php

require_once __DIR__.'/../../class/db/DB.php';
require_once __DIR__.'/../../class/session/session.php';
require_once __DIR__.'/../../class/global/global.php';
require_once __DIR__.'/../../class/logs.php';
require __DIR__.'/../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use League\Csv\Writer;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
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
$idQuery = (int) $_POST['id_grupo'];
$url = $_ENV['DISCADOR_API_URL'];
$datosPost = $_POST;
$nombreCedente = '';
$rsCedente = $db->select("SELECT * FROM Cedente WHERE Id_cedente = {$cedente}");
if ($rsCedente) {
  $nombreCedente = $rsCedente[0]['Nombre_Cedente'];
}

$logs->debug('Crear campaña: '.$url);
$logs->debug($datosPost);

$client = new Client([
  'base_uri' => $url,
  'timeout'  => 10.0,
  'verify' => false,
]);

$message = '';
//$datosPost['id'] = (int) $datosPost['cedente'];

try {
  if (!empty($nombreCedente)) {
    $datosPost['name'] = str_replace('Campaña',$nombreCedente, $datosPost['name']);
  }
  $tipoDiscado = [
    '1' => '',
    '2' => ''
  ];
  $res = $client->request('POST', 'campaigns/create', [
    'json' => $datosPost,
  ]);

  $status = (int) $res->getStatusCode();
  $body = $res->getBody()->getContents();
  $logs->debug($body);

  if ($status === 200) {
    $body = json_decode($body, true);
  }

}catch(RequestException $ex) {
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