<?php
require_once 'db/DB.php';
require_once 'logs.php';
include_once __DIR__.'/../vendor/autoload.php';

use League\Csv\Writer;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

class Asignaciones
{
  private $db;
  private $logs;
  private $idCedente;
  private $httpClient = null;
  private $urlDiscador;
  private $rand;

  public function __construct()
  {
    $this->rand = md5(time());
    $this->urlDiscador = $_ENV['DISCADOR_API_URL'];
    $this->db = new Db();
    $this->logs = new Logs();
    $this->idCedente = (int) $_SESSION['cedente'];
    $this->httpClient = new GuzzleHttp\Client([
			'base_uri' => $this->urlDiscador,
			'timeout'  => 120.0,
			'verify' => false,
		]);

  }

  public function validatedata()
  {
    try {
      $datosPost = [
        'id' => 99,
        'cedente' => $this->idCedente,
        'name' => 'Campaña ADT '.date('d-m-Y H:i:s'),
        'description' => 'Campaña para gestionar ADT - '.date('d-m-Y H:i:s'),
        'auto_list' => true,
        'rand' => mt_rand(999,99999999),
        'action' => 'create',
      ];
      $res = $this->httpClient->request('POST', 'campaigns/create?.rand='.$this->rand, [
        'json' => $datosPost,
      ]);
    
      $status = (int) $res->getStatusCode();
      $body = $res->getBody()->getContents();
      $this->logs->debug($body);
    
      if ($status === 200) {
        $body = json_decode($body, true);
      }    
    }catch(RequestException $ex) {
      $status = 500; //$ex->getResponse()->getStatusCode();
      $message = 'Se ha presentado un error';
      $this->logs->error($ex->getmessage());
    } catch (ConnectException $ex) {
      $status = 500;// $ex->getStatusCode();
      $message = 'Se ha presentado un error';
      $this->logs->error($ex->getmessage());
    }
    return $body;
  }

  public function assigData($loadId)
  {
    $strSQL = "SELECT p.Rut AS documento, p.Nombre_Completo AS nombre_cliente, f.codigo_pais, f.formato_subtel AS telefono FROM Persona AS p JOIN fono_cob AS f ON p.Rut = f.Rut WHERE f.vigente = 1 AND p.Id_Cedente IN ({$this->idCedente});";
    $results2 = $this->db->select($strSQL);
    if ($results2 !== false && count((array) $results2)> 0 && !empty($results2[0]['documento'])) {
      $this->logs->debug('Cantidad de filas a asignar: '. count((array) $results2));
      $filePath = '/tmp/lista_asignacion_discador_'.$this->idCedente.'.csv';
      $csv = Writer::createFromPath($filePath, 'w+');
      $csv->insertOne(array_keys($results2[0]));
      $insertadas = (int) $csv->insertAll((array) $results2);
      $this->logs->debug('Insertadas: '.$insertadas);
      if ($insertadas > 0) {
        $fileName = @end(explode('/', $filePath));
        try {
          $idQuery = 99;
          $res = $this->httpClient->request('POST', 'lists/file/load?.rand='.$this->rand, [
              'multipart' => [
                [
                  'name'     => 'file',
                  'contents' => file_get_contents($filePath),
                  'filename' => $fileName
                ],
                [
                  'name'     => 'list_id',
                  'contents' => '9'.str_pad($this->idCedente, 2,'0', STR_PAD_LEFT).str_pad($idQuery,4,'0', STR_PAD_LEFT)
                ]
              ],
          ]);
    
          $status = (int) $res->getStatusCode();
          $body = $res->getBody()->getContents();
          $this->logs->debug($body);          
  
          if ($status === 200) {
            $body = json_decode($body, true);
          }
            
          if (!isset($body['data'])) {
            $status = 403;
            $this->logs->error($body['message']);
            $message = 'No se ha podido procesar los datos.';
          } else {
            if ((int) $body['data']['result']['Good'] === 0) {
              $status = 403;
              $this->logs->error($body['message']);
              $message = 'No se ha podido procesar los datos.';
            } else {
              $registros = (int) $body['data']['result']['Good'];
              $message = 'asignados: '.$registros;
              $this->logs->debug($loadId);
              $this->db->query("UPDATE load_files SET asignado = 1, registros = {$registros} WHERE load_id = '{$loadId}'");
            }
          }

          return [
            'success' => ($status === 200)? true : false,
            'message' => $message,
            // 'body' => $body,
          ];  
        } catch(RequestException $ex) {
          $status = 500; //$ex->getResponse()->getStatusCode();
          $message = 'Se ha presentado un error';
          $this->logs->error($ex->getmessage());
        } catch (ConnectException $ex) {
          $status = 500;// $ex->getStatusCode();
          $message = 'Se ha presentado un error';
          $this->logs->error($ex->getmessage());
        }
      } else {
        return [
          'success' => false,
          'message' => 'No hay datos a procesar',
        ];
      }
    } else {
      return [
        'success' => false,
        'message' => 'No hay datos a procesar',
      ];
    }
  }
}