<?php

require_once __DIR__.'/../db/DB.php';
require_once __DIR__.'/../logs.php';

use Ramsey\Uuid\Uuid;

class Segmentador
{
  private $db;
	private $logs;
  private $cedente;

  public function __construct()
	{
		$this->db = new Db();
		$this->logs = new Logs();
    $this->cedente = (int) $_SESSION['cedente'];
	}

  public function listParams()
  {
    $params = [];
    $sqlParams = "SELECT * FROM Segmentador_Params WHERE FIND_IN_SET({$this->cedente}, cedentes) AND active = 1 ORDER BY id ASC;";
    $this->logs->debug($sqlParams);
    $listParams = $this->db->select($sqlParams);
    if (count((array) $listParams) > 0) {
      $listParams = array_map(function ($item) {
        $columns = json_decode($item['columns'], true);
        $columns = array_map(function($elemento) {
          if ($elemento['name'] == 'nombre_ejecutivo' && $elemento['type'] == 'list') {
            $elemento['items'] = $this->__getListAgent($this->cedente);
          }
          if ($elemento['name'] == 'usuario' && $elemento['type'] == 'list') {
            $elemento['items'] = $this->__getListAgent($this->cedente);
          }

          if ($elemento['type'] == 'list' && (isset($elemento['custom']) && $elemento['custom'] === true)) {
            $table = (isset($elemento['table']))? $elemento['table']:'Deuda';
            $field = (isset($elemento['field']))? $elemento['field']:$elemento['name'];
            $elemento['items'] = $this->__getValuesCustom($this->cedente, $field, $table);
          }
          return $elemento;
        }, (array) $columns);
        $item['columns'] = $columns;
        return $item;
      }, (array) $listParams);
      $params = $listParams;
    }

    return $params;
  }

  public function listarSegmentos($datos)
  {
    $list = [];
    $segmentacionId = (int) $datos['id'];
    $sqlList = "SELECT id, guuid, nombre, documentos, facturas, deudas, saldos FROM Querys_Segmentaciones WHERE segmentacion_id = {$segmentacionId} AND cedente_id = {$this->cedente} ORDER BY creado_el DESC;";
    $this->logs->debug($sqlList);
    $list = $this->db->select($sqlList);
    if (count((array) $list) > 0) {
      foreach ((array) $list as $key => $value) {
        $list[$key]['documentos'] = number_format((int) $value['documentos'],0,',','.');
        $list[$key]['facturas'] = number_format((int) $value['facturas'],0,',','.');
        $list[$key]['deudas'] = number_format((float) $value['deudas'],0,',','.');
        $list[$key]['saldos'] = number_format((float) $value['saldos'],0,',','.');
      }
    }
    return $list;
  }

  public function listaServicios()
  {
    $agentes = [];
    $sqlAgents = "SELECT id, usuario, nombre, user_dial AS extension FROM Usuarios WHERE FIND_IN_SET({$this->cedente}, Id_Cedente) AND nivel = 3 ORDER BY nombre ASC;";
    $this->logs->debug($sqlAgents);
    $list = $this->db->select($sqlAgents);
    if (count((array) $list) > 0) $agentes = $list;
    
    $lista = [
      [
        'id' => 'discador',
        'name' => 'Discador',
        'endpoint' => '',
        'params' => [
          'options' => [
            'dealer_type' => '1',
            'assign_to' => 'ALL',
            'agents' => [],
            'intensity' => '1',
            'name' => '',
          ],
          'dealer_type' => [ 
            [ 
              'id' => '1',
              'name' => 'Predictivo',
              'params' => [
                [
                  'label' => 'Intensidad',
                  'type' => 'list',
                  'items' => [
                    [
                      'id' => '1',
                      'name' => '1 por agente'
                    ],
                    [
                      'id' => '5',
                      'name' => '5 por agente'
                    ],
                    [
                      'id' => '10',
                      'name' => '10 por agente'
                    ],
                  ]
                ]
              ]
            ],
            [
              'id' => '2',
              'name' => 'Progresivo',
              'params' => []
            ],
            [
              'id' => '3',
              'name' => 'Asistido',
              'params' => []
            ],
          ],
          'agents' => $agentes,
        ],
      ]
    ];

    return $lista;
  }

  public function creaSegmento($datos, $save = false)
  {
    $cantPositiva = 0;
    $cantBillsPositiva = [
      'ruts' => 0,
      'documentos' => 0,
      'saldos' => 0
    ];
    $cantNegativa = 0;
    $cantBillsNegativa = [
      'ruts' => 0,
      'documentos' => 0,
      'saldos' => 0
    ];

    $this->logs->debug($datos);

    $condiciones = '';
    $tabla = 'Deuda';
    foreach ((array) $datos['data'] as $condicion) {
      $tabla = $condicion['table'];
      $condiciones .= "{$condicion['action']} ";
      $valor = $condicion['value'];
      if (in_array($condicion['type'],['money','integer'])) {
        $valor = (int) $valor;
      } else {
        $valor = "'{$valor}'";
      }
      $condiciones .= "({$condicion['column']} {$condicion['logic']} {$valor})";
    }
    $this->logs->debug($datos['data']);
    $this->logs->debug($condiciones);
    $nombreSegmento = $datos['name'];
    $sqlPositiva = base64_decode($datos['token']);
    $this->logs->debug('SQL POSITIVA:');
    $this->logs->debug($sqlPositiva);
    $sqlNegativa = "SELECT DISTINCT Rut FROM {$tabla} WHERE Rut NOT IN ({$sqlPositiva}) AND Id_Cedente = {$this->cedente}";
    //$condicionesNegativas = " AND Rut NOT IN ({$sqlPositiva})";

    $this->logs->debug($sqlNegativa);
    $positiva = $this->db->query($sqlPositiva);

    if((int) $positiva->num_rows > 0) {
      $cantPositiva = number_format((int) $positiva->num_rows,0,',','.');
      $cantBillsPositiva = $this->__countBills($sqlPositiva, $tabla);
    }
    $positiva->close();

    $negativa = $this->db->query($sqlNegativa);
    if((int) $negativa->num_rows > 0) {
      $cantNegativa  = number_format((int) $negativa->num_rows,0,',','.');
      $cantBillsNegativa = $this->__countBills($sqlNegativa, $tabla);
    }
    $negativa->close();

    $resultados = [
      [
        'segment' => $datos['name'],
        'documents' => $cantPositiva,
        'bills' => $cantBillsPositiva,
      ],
      [
        'segment' => 'Restantes',
        'documents' => $cantNegativa,
        'bills' => $cantBillsNegativa,
      ],
    ];

    if ($save)
    {
      $instanceMy = $this->db->getInstance();
      $segmentoId = (int) $datos['id'];
      $cantPositiva = (float) str_replace('.','',$cantPositiva);
      $cantNegativa = (float) str_replace('.','',$cantNegativa);
      $gUuid = Uuid::uuid4();
      $sqlPositiva = str_replace('  ',' ', $sqlPositiva);
      $sqlNegativa = str_replace('  ',' ', $sqlNegativa);
      $sqlInsert = "INSERT INTO Querys_Segmentaciones (cedente_id, segmentacion_id, guuid, nombre, documentos, facturas, deudas, saldos, `query`) VALUES({$this->cedente},{$segmentoId},'{$gUuid}','{$nombreSegmento}',{$cantPositiva},".floatval(str_replace('.','',$cantBillsPositiva['ruts'])).",".floatval(str_replace('.','',$cantBillsPositiva['documentos'])).",".floatval(str_replace('.','',$cantBillsPositiva['saldos'])).",'".$instanceMy->real_escape_string($sqlPositiva)."')";
      $sqlInsertNegativa = "INSERT INTO Querys_Segmentaciones (cedente_id, segmentacion_id, guuid, nombre, documentos, facturas, deudas, saldos, `query`) VALUES({$this->cedente},{$segmentoId},'{$gUuid}','Restantes',{$cantNegativa},".floatval(str_replace('.','',$cantBillsNegativa['ruts'])).",".floatval(str_replace('.','',$cantBillsNegativa['documentos'])).",".floatval(str_replace('.','',$cantBillsNegativa['saldos'])).",'".$instanceMy->real_escape_string($sqlNegativa)."')";
      $this->logs->debug($sqlInsert);
      $this->logs->debug($sqlInsertNegativa);
      $resultados[0]['id'] = $this->db->insert($sqlInsert);
      if ((int) $cantNegativa > 0)  $resultados[1]['id'] = $this->db->insert($sqlInsertNegativa);
    }

    return $resultados;
  }

  private function __getListAgent($cedente)
  {
    $sqlAgents = "SELECT usuario AS id, nombre AS `name`, user_dial AS extension FROM Usuarios WHERE FIND_IN_SET({$cedente}, Id_Cedente) AND nivel = 3 ORDER BY nombre ASC;";
    return $this->db->select($sqlAgents);
  }

  private function __getValuesCustom($cedente, $field, $table = 'Deuda')
  {
    $sqlAgents = "SELECT DISTINCT {$field} AS id, {$field} AS `name`  FROM {$table} WHERE FIND_IN_SET({$cedente}, Id_Cedente) ORDER BY {$field} ASC;";
    return $this->db->select($sqlAgents);
  }

  private function __guardarSegmento($datos)
  {

  }

  private function __countBills($sqlFrom, $table)
  {
    $cantidad = [
      'ruts' => 0,
      'documentos' => 0,
      'saldos' => 0
    ];
    $patrón = '/(Deuda)/';
    preg_match($patrón, $table, $coincidencias);
    if (count((array) $coincidencias) == 0) $table = 'Deuda';
    $sqlCalculo = "SELECT COUNT(DISTINCT Rut) AS ruts, COUNT(rut) AS documentos, SUM(Deuda) AS saldos FROM {$table} WHERE Id_Cedente = {$this->cedente} AND Rut IN ({$sqlFrom})";
    // $sqlCalculo = "SELECT COUNT(DISTINCT Rut) AS ruts, COUNT(rut) AS documentos, SUM(Deuda) AS saldos FROM Deuda WHERE Id_Cedente = {$this->cedente} {$sqlFrom};";
    $this->logs->debug($sqlCalculo);
    $calculo = $this->db->select($sqlCalculo);

    if (count((array) $calculo) > 0) {
      $results = $calculo[0];
      $cantidad['ruts'] = number_format((int) $results['ruts'],0,',','.');
      $cantidad['documentos'] = number_format((float) $results['documentos'],0,',','.');
      $cantidad['saldos'] = number_format((float) $results['saldos'],0,',','.');
    }
    
    return $cantidad;
  }
}