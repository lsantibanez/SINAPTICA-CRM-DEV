<?php

require_once 'db/DB.php';
require_once 'logs.php';
include_once __DIR__.'/../vendor/autoload.php';

class Agentes
{
  private $db;
  private $logs;
  private $idCedente;
  private $httpClient = null;
  // private $urlLoadSystem;

  public function __construct()
  {
    // $this->urlLoadSystem = $_ENV['LOADS_URL'].'/api/v1/';
    $this->db = new Db();
    $this->logs = new Logs();
    $this->idCedente = (int) $_SESSION['cedente'];
    /*
    $this->httpClient = new GuzzleHttp\Client([
			'base_uri' => $this->urlLoadSystem,
			'timeout'  => 120.0,
			'verify' => false,
		]);
    */
  }

  public function getConfigs()
  {
    $lista = [];
    try {
      if (intval($this->idCedente) > 0) {
        $rs = $this->db->select('SELECT id, bloque, `config` AS columnas FROM configuracion_columnas WHERE cedente_id = '.$this->idCedente.' ORDER BY id DESC LIMIT 1');
        if ($rs) {
          $item = (array) $rs[0];
          $this->logs->debug($item);
          if (!is_null($item['columnas']) && !empty($item['columnas'])) {
            $item['columnas'] = json_decode($item['columnas'], true);
          }
          $lista = $item;
        }
      }
    } catch (\Exception $ex) {
      $this->logs->error($ex->getMessage());
    }
    return [
      'success' => true,
      'item' => $lista
    ];
  }
}