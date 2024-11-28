<?php
require_once 'db/DB.php';
require_once 'logs.php';
include_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

class Cargas
{
  private $db;
  private $logs;
  private $idCedente;
  private $idMandante;
  private $httpClient = null;
  private $urlLoadSystem;

  public function __construct()
  {
    $this->urlLoadSystem = $_ENV['LOADS_URL'].'/api/v1/';
    $this->db = new Db();
    $this->logs = new Logs();
    $this->idMandante = (int) $_SESSION['mandante'];
    $this->idCedente = (int) $_SESSION['cedente'];
    $this->httpClient = new GuzzleHttp\Client([
			'base_uri' => $this->urlLoadSystem,
			'timeout'  => 120.0,
			'verify' => false,
		]);
  }

  public function getConfigs()
  {
    $lista = [];
    try {
      if (intval($this->idCedente) > 0) {
        $rs = $this->db->select('SELECT id, nombre, files_config FROM load_configs WHERE cedente_id = '.$this->idCedente.' AND activa = 1 ORDER BY nombre ASC');
        if ($rs) {
          $lista =  array_map(function ($item) {
            return [
              'id' => (int) $item['id'],
              'nombre' => (string) $item['nombre'],
              'files_config' => (!is_null($item['files_config']) && !empty($item['files_config']))? json_decode($item['files_config']) : null,
            ];
          }, (array) $rs);

          return $lista;
        }
      }
    } catch (\Exception $ex) {
      $this->logs->error($ex->getMessage());
    }
    return $lista;
  }

  public function getLista()
  {
    $lista = [];
    try {
      if (intval($this->idCedente) > 0) {
        $rs = $this->db->select('SELECT (SELECT Nombre_Cedente FROM Cedente WHERE id = cedente_id LIMIT 1) AS destino, (SELECT l.nombre FROM load_configs AS l WHERE l.id = config_id LIMIT 1) AS configuracion, config_id AS configuracion_id, IF (relacion IS NULL, 0, 1) AS con_asociacion, cedente_id AS proyecto, load_id, archivo, procesado, asignado, registros, columnas, relacion, segmentacion_inicial, resultados, DATE_FORMAT(creado_el, \'%d-%m-%Y %H:%i:%s\') AS creado_el FROM load_files WHERE cedente_id = '.$this->idCedente.' AND active = 1 ORDER BY creado_el DESC LIMIT 25;' );
        if ($rs) {
          foreach ((array) $rs as $key => $value) {
            if (!empty($value['columnas']) && !is_null($value['columnas'])) $rs[$key]['columnas'] = json_decode($value['columnas'], true);
            if (!empty($value['relacion']) && !is_null($value['relacion'])) $rs[$key]['relacion'] = json_decode($value['relacion'], true);
            if (!empty($value['resultados']) && !is_null($value['resultados'])) $rs[$key]['resultados'] = json_decode($value['resultados'], true);
            if ($value['asignado'] === '1') {
              if (!empty($value['segmentacion_inicial']) && !is_null($value['segmentacion_inicial'])) $rs[$key]['segmentacion_inicial'] = json_decode($value['segmentacion_inicial'], true);
            } else {
              $rs[$key]['segmentacion_inicial'] = null;
            }
          }
          return (array) $rs;
        }
      }
    } catch (\Exception $ex) {
      $this->logs->error($ex->getMessage());
    }
    return $lista;
  }

  public function loadData($id)
  {
    $message = '...';
		$body = '';
		$status = 200;

    try {
      $requestData = [
        'loadId' => $id,
      ];
      $ruta = '/proccess';
      $this->logs->debug('Uploads file');
      $res = $this->httpClient->request('POST', 'loads'.$ruta, [
        'json' => $requestData,
      ]);

      $status = $res->getStatusCode();
      $body = $res->getBody()->getContents();
      $message = 'Operación realizada con éxito.';
			if (is_string($body)) $body = json_decode($body, true);
      $this->logs->debug($body);

      if ($body['success'] === true) {
        $this->logs->debug('Exito');
        $message = $body['message'];
        $this->db->query("UPDATE load_files SET procesado = 1 WHERE load_id = '{$id}'");
      }
    } catch(GuzzleHttp\Exception\RequestException $ex) {
      $status = 500; //$ex->getResponse()->getStatusCode();
      $message = 'Se ha presentado un error';
      $this->logs->error($ex->getmessage());
    } catch (GuzzleHttp\Exception\ConnectException $ex) {
      $status = 500;// $ex->getStatusCode();
      $message = 'Se ha presentado un error';
      $this->logs->error($ex->getmessage());
    }
    
    return [
      'status' => $status,
      'success' => ($status === 200)? true:false,
      'message' => $message,
    ];
  }

  public function releaseFile($id)
  {
    $message = '...';
		$body = '';
		$status = 200;

    try {
      $requestData = [
        'loadId' => $id,
      ];
      $res = $this->httpClient->request('POST', 'loads/release', [
        'json' => $requestData,
      ]);

      $status = $res->getStatusCode();
      $body = $res->getBody()->getContents();
      $message = 'Operación realizada con éxito.';
			if (is_string($body)) $body = json_decode($body, true);
      $this->logs->debug($body);

      if ($body['success'] === true) {
        $this->logs->debug('Exito');
        $message = $body['message'];
        // $this->db->query("UPDATE load_files SET procesado = 1 WHERE load_id = '{$id}'");
      }
    } catch(GuzzleHttp\Exception\RequestException $ex) {
      $status = 500; //$ex->getResponse()->getStatusCode();
      $message = 'Se ha presentado un error';
      $this->logs->error($ex->getmessage());
    } catch (GuzzleHttp\Exception\ConnectException $ex) {
      $status = 500;// $ex->getStatusCode();
      $message = 'Se ha presentado un error';
      $this->logs->error($ex->getmessage());
    }
    
    return [
      'status' => $status,
      'success' => ($status === 200)? true:false,
      'message' => $message,
    ];
  }

  public function getConfiguracion($id)
  {
    try {
      $strSQL = 'SELECT * FROM load_configs WHERE id = '.$id;
      $resultados = $this->db->select($strSQL);
      if ($resultados) {
        $resultados = $resultados[0];
        $resultados['columnas'] = json_decode($resultados['columnas'], true);
      }

      return $resultados;
    } catch (\Exception $ex) {
      $this->logs->error($ex->getmessage());
      return [];
    }
  }

  public function saveAssociations($datos)
  {
    try {
      $resultados = [
        'success' => true,
        'message' => 'Se ha guardado la asociación con éxito',
      ];
      $id = $datos['uuid'];
      $asociacion = json_encode($datos['columnas'], JSON_UNESCAPED_UNICODE);
      $strSQL = "UPDATE load_files SET relacion = '{$asociacion}' WHERE load_id = '{$id}';";
      $rs = $this->db->query($strSQL);
      if (!$rs) {
        $resultados = [
          'success' => false,
          'message' => 'No se guardaron los datos',
        ];
      }

      return $resultados;
    } catch (\Exception $ex) {
      $this->logs->error($ex->getmessage());
      return [
        'success' => false,
        'message' => 'Se ha presentado un error al intentar guardar los datos.',
      ];
    }
  }

  public function segmentPortfolios($id)
  {
    $resultado = [
      'success' => false,
      'message' => 'No se pudo completar la operación',
      'items' => [
        'segmentacion' => [],
        'en_baja' => 0,
        'sin_telefonos' => 0,
        'total' => 0
      ]
    ];
    $strSQL = "SELECT c.Id_Cedente AS id, c.Nombre_Cedente AS cedente FROM Cedente AS c LEFT JOIN mandante_cedente AS m ON c.Id_Cedente = m.Id_Cedente WHERE 
    m.id_mandante = (SELECT m.Id_Mandante FROM load_files AS l JOIN mandante_cedente AS m ON l.cedente_id = m.Id_Cedente WHERE l.load_id = '{$id}' LIMIT 1) ORDER BY c.Id_Cedente DESC;";
    $this->logs->debug($strSQL);
    $rs = $this->db->select($strSQL);
    $this->logs->debug($rs);
    if (count((array) $rs)>0) {
      $total = 0;
      $respuesta['success'] = true;
      $respuesta['message'] = 'Procesado con éxito';
      $db = $this->db->getInstance();
      //$this->db->query('BEGIN;');
      $rs[] = [
        'id' => 8,
        'cedente' => 'Pro CJ A'
      ];
      $rs[] = [
        'id' => 8,
        'cedente' => 'Pro CJ C'
      ];
      $this->logs->debug($rs);
      $listaCedentes = [];
      foreach ((array) $rs as $portfolio) {   
        $listaCedentes[] = $portfolio['id'];
        $strUpPortfolios = "UPDATE Deuda SET Id_Cedente = {$portfolio['id']} WHERE Id_Cedente != {$portfolio['id']} AND LOWER(Cartera) = 'business ".mb_strtolower($portfolio['cedente'])."'";
        $this->logs->debug($strUpPortfolios);
        $db->query($strUpPortfolios);
        $cant = (int) $db->affected_rows;
        $this->logs->debug('Cedente: '.$portfolio['cedente'].' | Actualizados: '.$cant);
        $total += $cant;
        /*
        $rrss = $this->db->select('SELECT COUNT(DISTINCT Rut) AS cantidad FROM Deuda WHERE Estado = \'ALTA\' AND Id_Cedente = '.$portfolio['id']);
        $cantInDb = (int) $rrss[0]['cantidad'];
        // if ($cant == 0 && $cantInDb > 0) $cant = $cantInDb;
        $total += $cantInDb;
        $cartera = $portfolio['cedente'];
        $this->logs->debug('Cedente: '.$portfolio['cedente'].' | Cant: '.$cantInDb);
        if (in_array($portfolio['cedente'], ['Pro CJ A','Pro CJ C'])) {
          $this->logs->debug('*** en la lista ***');
          $iC = array_filter($respuesta['items'], function ($item) {
            return $item['cartera'] === 'Pro CJ HIPO';
          });          
          $this->logs->debug($iC);
          $this->logs->debug('***');
          if (count((array) $iC)> 0) {
            $kk = array_keys($iC);
            $k = (int) $kk[0];
            $this->logs->debug($kk[0]);
            $this->logs->debug($respuesta['items']);
            $this->logs->debug($respuesta['items'][$k]);
            $actual = ($respuesta['items'][$k]['cant']);
            $respuesta['items'][$k]['cant'] = number_format(($actual + $cantInDb),0,',','.');
            continue;
          } else {
            $respuesta['items'][] = [
              'cartera' => 'Pro CJ HIPO',
              'cant' => number_format($cantInDb,0,',','.')
            ];
            continue;            
          }
        }
         
        $respuesta['items'][] = [
          'cartera' => $cartera,
          'cant' => number_format($cantInDb,0,',','.')
        ]; 
        */   
      }

      if ($total > 0) {
        $this->db->query("UPDATE Persona a JOIN Deuda b  ON a.Rut = b.Rut SET a.Id_Cedente = b.Id_Cedente  WHERE b.Id_Cedente IN (".implode(',',$listaCedentes).");");

        $rrssA = $this->db->select("SELECT TRIM(Cartera) AS cartera, COUNT(DISTINCT Rut) AS cant FROM Deuda WHERE Id_Cedente IN(".implode(',',$listaCedentes).") GROUP BY Cartera;");
        $listaCarteras = (array) $rrssA;

        $rrssD = $this->db->select('SELECT COUNT(DISTINCT Rut) AS cantidad FROM Deuda WHERE Estado = \'BAJA\' AND Id_Cedente IN('.implode(',',$listaCedentes).')');
        $cantBajas = (int) $rrssD[0]['cantidad'];
        /*
        $rrssT = $this->db->select('SELECT COUNT(DISTINCT Rut) AS cantidad FROM Deuda WHERE Id_Cedente IN('.implode(',',$listaCedentes).')');
        $cantTotal = (int) $rrssD[0]['cantidad'];
        */

        $this->logs->debug($listaCarteras);

        $hipoTotal = array_reduce((array) $listaCarteras, function($carry = 0, $item) {
          if (in_array($item['cartera'], ['Business Pro CJ A','Business Pro CJ C', 'Business Pro CJ HIPO'])) {
            $carry += $item['cant'];
          }
          return $carry;
        });

        $this->logs->debug('HIPO:');
        $this->logs->debug($hipoTotal);

        $key2 = array_search('Business Pro CJ HIPO', array_column($listaCarteras, 'cartera'));
        if (!is_null($key2) && $key2) $listaCarteras[$key2]['cant'] = (int) $hipoTotal;

        $key0 = array_search('Business Pro CJ A', array_column($listaCarteras, 'cartera'));
        $this->logs->debug('Business Pro CJ A:');
        $this->logs->debug($key0);
        $key1 = array_search('Business Pro CJ C', array_column($listaCarteras, 'cartera'));
        $this->logs->debug('Business Pro CJ C:');
        $this->logs->debug($key1);

        if (!is_null($key0) && $key0) unset($listaCarteras[$key0]);   
        if (!is_null($key1) && $key1) unset($listaCarteras[$key1]);

        $this->logs->debug('FINAL:');
        $this->logs->debug($listaCarteras);

        $cantTotal = array_reduce((array) $listaCarteras, function($carry = 0, $item) {
          $carry += $item['cant'];
          return $carry;
        });

        $resultado['items'] = [
          'segmentacion' => $listaCarteras,
          'en_baja' => number_format($cantBajas,0,',','.'),
          'sin_telefonos' => 0,
          'total' => number_format($cantTotal,0,',','.'),
        ];        
        // $respuesta['items'] = $resultados;
        $segmentacion = json_encode($resultado['items'], JSON_UNESCAPED_UNICODE);
        $this->db->query('Actualizar load_files;');
        $db->query("UPDATE load_files SET asignado = 1, segmentacion_inicial = '{$segmentacion}' WHERE load_id = '{$id}';");

        $resultado['success'] = true;
        $resultado['message'] = '¡Separación realizada con éxito!';
      }
    }
    return $resultado;
  }
}