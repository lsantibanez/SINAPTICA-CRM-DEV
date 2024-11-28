<?php

error_reporting(E_ALL & ~E_NOTICE);
// require_once '../vendor/autoload.php';
require_once('../../../class/db/DB.php');
require_once('../../../class/session/session.php');
include("../../../class/global/global.php");
require_once('../../../class/logs.php');
require __DIR__.'/../../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use League\Csv\Writer;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

$db = new DB();
$objetoSession = new Session('1,2,3,4,5,6',false);
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout'] == "true"))
{
  //to fully log out a visitor we need to clear the session varialbles
  $objetoSession->borrarVariablesSession();
  $objetoSession->logoutGoTo("../index.php");
}
$logs = new Logs();

$usuario = $_SESSION['MM_Username'];
$cedente = $_SESSION['cedente'];
$nombreUsuario = $_SESSION['nombreUsuario'];
$id_estrategia = $_SESSION['IdEstrategia'];
$idUsuarioLogin = $_SESSION['id_usuario'];

$idQuery = (int) $_POST['id_grupo'];
$idCampaign = (int) $_POST['id_campaign'];
$results = $db->select("SELECT id, nombre AS cola, query FROM `Querys_Segmentaciones` WHERE id = {$idQuery} LIMIT 1");

if ($results !== false && count((array) $results)> 0) {
  $rowQuery = (array) $results[0];
  $cola = str_replace(' ','_', $rowQuery['cola']);
  $strSQL = "SELECT p.Rut AS documento, p.Nombre_Completo AS nombre_cliente, f.codigo_pais, f.formato_subtel AS telefono 
  FROM Persona AS p JOIN fono_cob AS f ON p.Rut = f.Rut
  WHERE f.vigente = 1 AND p.Rut IN ({$rowQuery['query']}) AND p.con_deudas = 1 AND LENGTH(f.formato_subtel) = 9 GROUP BY p.Rut, f.formato_subtel";
  $logs->debug($strSQL);
  // echo 'SQL: '.$strSQL.PHP_EOL;
  $results2 = $db->select($strSQL);
  //$logs->debug($results2);
  
  // var_dump($results2);
  if ($results2 !== false && count((array) $results2)> 0 && !empty($results2[0]['documento'])) {
    $logs->debug('Cantidad de filas: '. count((array) $results2));
    $filePath = '/tmp/lista_estrategica_'.$cola.'_'.$idQuery.'.csv';
    $csv = Writer::createFromPath($filePath, 'w+');
    $csv->insertOne(array_keys($results2[0]));
    $insertadas = (int) $csv->insertAll((array) $results2);
    // echo 'Archivo creado: '.$filePath.PHP_EOL;
    $logs->debug('Insertadas: '.$insertadas);

    if ($insertadas > 0) {
      $url = $_ENV['DISCADOR_API_URL'];
      $logs->debug('Subir a: '.$url);
      $client = new Client([
        'base_uri' => $url,
        'timeout'  => 60.0,
        'verify' => false,
      ]);
      $fileName = end(explode('/', $filePath));
      $typeC = (int) $_POST['type'];
      try {
        $res = $client->request('POST', 'lists/file/load' . $endpoint, [
            'multipart' => [
              [
                'name'     => 'file',
                'contents' => file_get_contents($filePath),
                'filename' => $fileName
              ],
              [
                'name'     => 'list_id',
                'contents' => '9'.str_pad($cedente, 2,'0', STR_PAD_LEFT).str_pad($idQuery,4,'0', STR_PAD_LEFT)
              ],
              [
                'name'     => 'type',
                'contents' => $typeC
              ]
            ],
        ]);
  
        $status = (int) $res->getStatusCode();
        $body = $res->getBody()->getContents();
        //$logs->debug($body);
        
        if ($status === 200) {
          $body = json_decode($body, true);
          $logs->debug('LIST SUCCESS');
          $logs->debug($body);
          $datos = $body['data'];
          $logs->debug('ID Campaña: ', $idCampaign);
          $dato = $db->select('SELECT parametros FROM campaigns WHERE id = '.$idCampaign.' LIMIT 1;');
          if(count((array) $dato) > 0) {
            $logs->debug($dato);
            $parametros = json_decode($dato[0]['parametros'], true);
            $parametros['list_id'] = $datos['list_id'];
            $parametros['records'] = (int) $datos['result']['Good'];
            $parametros['telefonos'] = (int) $datos['result']['Good'];
            $iid = $parametros['id_discador'];
            $logs->debug($parametros);
            $sqlCalculo = "SELECT COUNT(DISTINCT Rut) AS ruts, COUNT(rut) AS documentos, SUM(Deuda) AS saldos FROM Deuda WHERE Id_Cedente = {$cedente} AND Rut IN (".$rowQuery['query'].")";
            if ((int) $cedente == 14) $sqlCalculo = "SELECT COUNT(DISTINCT Rut) AS ruts, COUNT(rut) AS documentos, SUM(Deuda) AS saldos FROM Deuda_Vigente WHERE Id_Cedente = {$cedente} AND Rut IN (".$rowQuery['query'].")";
            $rs0 = $db->select($sqlCalculo);
            $logs->debug('******** Calculo registros en campaña ***********');
            $logs->debug($sqlCalculo);
            $logs->debug($rs0);
            $logs->debug('********* ACTUALIZA **********');        
            $calculos = $rs0[0];
            $rr = $db->query('UPDATE campaigns SET registros = '.$calculos['ruts'].', parametros = \''.json_encode($parametros, JSON_UNESCAPED_UNICODE).'\' WHERE id = '.$idCampaign.';');
            $logs->debug($rr);
            $logs->debug('*******************');
          }
        }

        $message = 'Procesados: '.(int) $body['data']['result']['Good'];
        if ((int) $body['data']['result']['Good'] === 0) {
          $status = 403;
          $message = 'No se ha podido procesar los datos.';
        }

      } catch(RequestException $ex) {
        $status = 500; //$ex->getResponse()->getStatusCode();
        $message = $ex->getmessage();
      } catch (ConnectException $ex) {
        $status = 500;// $ex->getStatusCode();
        $message = $ex->getmessage();
      }

      header('Content-Type: application/json; charset=utf-8');
      echo json_encode([
        'success' => ($status === 200)? true : false,
        'message' => $message,
        'body' => $body,
      ]);
      exit;
    }
  } else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
      'success' => false,
      'message' => 'No hay datos a procesar',
    ]);
    exit;
  }
} else {
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode([
    'success' => false,
    'message' => 'No se encontró segmentación',
  ]);
  exit;
}