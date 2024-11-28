<?php
require_once 'db/DB.php';
require_once 'logs.php';
include_once __DIR__.'/../vendor/autoload.php';


class Consultas
{
  private $db;
  private $logs;
  private $idMandante;
  private $idCedente;

  public function __construct()
  {
    $this->db = new Db();
    $this->logs = new Logs();
    $this->idMandante = (int) $_SESSION['mandante'];
    $this->idCedente  = (int) $_SESSION['cedente'];
  }

  public function getPortfolios()
  {
    $lista = [
      'success' => true,
      'items' => []
    ];
    try {
      $rs = $this->db->select("SELECT c.Id_Cedente AS id, c.Nombre_Cedente AS nombre FROM Cedente AS c JOIN mandante_cedente AS mc ON mc.Id_Cedente = c.Id_Cedente WHERE mc.Id_Mandante = {$this->idMandante} ORDER BY c.Id_Cedente ASC;");
      $lista['items'] = $rs;
    } catch (\Exception $ex) {
      $lista['success'] = false;
      $lista['message'] = 'Se ha presentado un error.';
      $this->logs->debug($ex->getMessage());
    }
    return $lista;
  }

  public function getCampaigns()
  {
    $respuesta = [
      'success' => true,
      'items' => []
    ];

    try {
      $rs = $this->db->select("SELECT id, nombre, tipo, registros, parametros, activa, creada_el FROM campaigns WHERE id_cedente = {$this->idCedente} ORDER BY id DESC;");
      $respuesta['items'] = array_map(function ($item) {
        if (!empty($item['parametros']) && !is_null($item['parametros'])) $item['parametros'] = json_decode($item['parametros'], true);
        $item['parametros']['assing_to'] = ($item['parametros']['assing_to'] == 'ALL')? 'Todos los agentes':'Agentes seleccionados';
        return $item;
      }, (array) $rs);
    } catch (\Exception $ex) {
      $respuesta['success'] = false;
      $respuesta['message'] = 'Se ha presentado un error.';
      $this->logs->debug($ex->getMessage());
    }
    return $respuesta;
  }

  public function findCustomer($data)
  {
    $respuesta = [
      'success' => false,
      'customers' => []
    ];

    try {
      $filtro = '';
      if (!empty($data['portfolio'])) $filtro = ' AND Id_Cedente = '.intval($data['portfolio']);
      $data['rut'] = preg_replace('/[^0-9kK\-]/', '', $data['rut']);
      $rut = explode('-', $data['rut']);
      $select = "SELECT Rut AS rut, Digito_Verificador AS digito, Nombre_Completo AS nombre, Id_Cedente AS id_proyecto FROM Persona WHERE Rut = '{$rut[0]}' {$filtro};";
      if (count((array) $rut) === 2) {
        $select = "SELECT Rut AS rut, Digito_Verificador AS digito, Nombre_Completo AS nombre, Id_Cedente AS id_proyecto FROM Persona WHERE Rut = '{$rut[0]}' AND Digito_Verificador = '{$rut[1]}' {$filtro};";
      }
      $rs = $this->db->select($select);
      $personas = $rs;
      $this->logs->debug($personas);

      if (count((array) $personas) > 0) {
        foreach ((array) $personas as $persona) {
          $persona['proyecto'] = $this->__getProject($persona['id_proyecto']);
          $persona['telefonos'] = $this->__getPhones($persona['rut']);
          $persona['emails'] = $this->__getEmails($persona['rut'], $persona['id_proyecto']);
          $persona['deudas'] = $this->__createDeudasBlock($persona['rut'], $persona['id_proyecto'], false);
          $persona['deudas_vigentes'] = [
            'headers' => [],
            'rows' => [],
            'total' => 0,
          ]; //$this->__createDeudasVigentesBlock($persona['rut'], $persona['id_proyecto'], false);
          $persona['gestiones'] = $this->__getManagements($persona['rut'], $persona['id_proyecto']);
          //$this->logs->debug($persona);
          $respuesta['customers'][] = $persona;
        }
        $respuesta['success'] = true;
      }
    } catch (\Exception $ex) {
      $this->logs->debug($ex->getMessage());
      $respuesta = [
        'success' => false,
        'message' => 'Se ha presentado un error.'
      ];
    }

    return $respuesta;
  }

  public function addPhone($data)
  {
    try {
      $tipo = 'Otro';
      if (substr($data['phone'],0,1) == '9') $tipo = 'Celular';
      $rs = $this->db->insert("INSERT IGNORE INTO fono_cob (Rut, formato_subtel, tipo_fono, Nombre) VALUES ('{$data['rut']}','{$data['phone']}','{$tipo}','{$data['mark']}');");
      return [
        'success' => true,
        'message' => 'Operación realizada',
        'item' => [
          'phone' => $data['phone'],
          'type'  => $tipo,
          'mark' => $data['mark']
        ]
      ];
    } catch (\Exception $ex) {
      return [
        'success' => false,
        'message' => 'Se ha presentado un error'
      ];
    }
  }


  public function addEmail($data)
  {
    try {

      if (!$this->__validateEmail($data['email'])) {
        return [
          'success' => false,
          'message' => '¡Dirección de e-mail ingresada no es válida!'
        ];
      }

      $rs = $this->db->insert("INSERT IGNORE INTO Email (Rut, Email, Marca) VALUES ('{$data['rut']}','{$data['email']}','{$data['mark']}');");
      return [
        'success' => true,
        'message' => 'Operación realizada',
        'item' => [
          'email' => $data['email'],
          'mark' => $data['mark']
        ]
      ];
    } catch (\Exception $ex) {
      return [
        'success' => false,
        'message' => 'Se ha presentado un error'
      ];
    }
  }

  private function __validateEmail($email)
  {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return true;
    }
    return false;
  }

  private function __getProject($id)
  {
    $rs = $this->db->select('SELECT Nombre_Cedente AS nombre FROM Cedente WHERE Id_Cedente = '.$id.' LIMIT 1;');
    return (count((array) $rs) > 0 )? $rs[0]['nombre'] : '--';
  }

  private function __getPhones($rut)
  {
    $rs = $this->db->select("SELECT formato_subtel AS phone, tipo_fono AS `type`, nombre AS `mark` FROM fono_cob WHERE Rut = '{$rut}';");
    return (count((array) $rs) > 0 )? $rs : [];
  }

  private function __getEmails($rut, $cedente)
  {
    $rs = $this->db->select("SELECT Email AS email, marca AS `mark` FROM Email WHERE Rut = '{$rut}';");
    return (count((array) $rs) > 0 )? $rs : [];
  }

  private function __getManagements($rut, $proyecto)
  {
    $rs = $this->db->select("SELECT 
          (SELECT r.Respuesta_N1 FROM `Nivel1` AS r WHERE r.id = n1 LIMIT 1) AS n1,
          (SELECT r2.Respuesta_N2 FROM `Nivel2` AS r2 WHERE r2.Id_Nivel1 = n1 AND r2.id = n2 LIMIT 1) AS n2,
          (SELECT r3.Respuesta_N3 FROM `Nivel3` AS r3 WHERE r3.Id_Nivel2 = n2 AND r3.id = n3 LIMIT 1) AS n3,
          nombre_ejecutivo AS agente,
          fono_discado AS telefono,
          observacion,
          DATE_FORMAT(fechahora, '%d-%m-%Y %H:%i:%s') AS fecha
          FROM gestion_ult_trimestre 
          WHERE rut_cliente = '{$rut}' ORDER BY fechahora DESC");
    return (count((array) $rs) > 0 )? $rs : [];
  }

  private function __createDeudasBlock ($rut, $cedente, $showExtras = true)
  {
    $datosDeudas = [
      'headers' => [],
      'rows' => [],
      'total' => 0,
    ];

    $deudas = $this->db->select("SELECT * FROM Deuda WHERE Rut = '{$rut}' AND Id_Cedente = {$cedente} ORDER BY Fecha_Emision DESC;");
    $this->logs->debug($deudas);
    if ($deudas && count((array) $deudas) > 0) {
      $cedenteCampos = $this->db->select("SELECT bloque, config FROM configuracion_columnas WHERE bloque = 'deudas' AND cedente_id = '{$cedente}' LIMIT 1;");//DB::connection('crm')->table('configuracion_columnas')->where('cedente_id','=',$cedente)->where('bloque','deudas')->first();
      if (count((array) $cedenteCampos) > 0) {
        $dataConfig = json_decode(json_encode(current((array) $cedenteCampos)), true);
        $campos = $dataConfig['config'];
      } else {
        $campos = '[{"field":"Factura","name":"Factura","type":"string","tAlign":"center"},{"field":"Entidad","name":"Nro. Cliente","type":"string","tAlign":"center"},{"field":"Tipo_Factura","name":"Tipo factura","type":"string","tAlign":"center"},{"field":"Deuda","name":"Deuda","type":"saldo","tAlign":"right"},{"field":"Saldo_Dia","name":"Saldo","type":"saldo","tAlign":"right"},{"field":"Estado","name":"Estado","type":"string","tAlign":"center"},{"field":"Fecha_Vencimiento","name":"Vencimiento","type":"date","tAlign":"center"},{"field":"Rechazo","name":"Motivo de rechazo","type":"string","tAlign":"left"}]';
      }
      $campos = json_decode($campos, true);
      $this->logs->debug('Cedente: '.$cedente);
      $this->logs->debug($campos);
      $footer = [];

      $fieldsConfig = array_map(function ($item) {
        $nItem =  ['campo' => $item['field'], 'tipo' => $item['type'], 'tAling' => $item['tAlign']];
        if (isset($item['suma'])) $nItem['suma'] = $item['suma'];
        if (isset($item['igual'])) $nItem['igual'] = $item['igual'];
        if (isset($item['oculto'])) $nItem['oculto'] = $item['oculto'];
        return $nItem;
      },$campos);

      $headersConfig = array_map(function ($item) {
        return ['title' => $item['name'], 'tAling' => $item['tAlign']];
      },$campos);

      if($showExtras) {
        $headersConfig[] = [
          'title' => 'Extra',
          'tAling' => 'center'
        ];
      }
      $datosDeudas['headers'] = $headersConfig;
      $footerData = [
        ['value' => 'Total: ', 'tAling' => 'rigth', 'type' => 'string'],
      ];
      $datosDeudas['footer'] = $footerData;
      $footerData = array_map(function ($item) {
        $nItem =  ['value' => '', 'tAling' => 'right', 'type' => 'string' ];
        //if (isset($item['suma'])) $nItem['suma'] = $item['suma'];
        return $nItem;
      }, $headersConfig);
      $footerData[0]['value'] = 'TOTAL: ';
      $this->logs->debug('Nueva');
      $this->logs->debug($footerData);
      $totalPos = 0;
    
      foreach ((array) $deudas as $deuda) {
        // Log::debug($deuda);
        $direccionDeuda = null; //$deuda->direccion($cedente)->get();
        //$deuda = $deuda->toArray();
        $extras = [];
        $item = [];
        foreach ($fieldsConfig as $p => $campo) {
          //$this->logs->debug($campo);
          //$footerData[$p] = ['value' => 0, 'tAling' => 'right' ];
          $valor = '';       
          if (isset($deuda[$campo['campo']])) {
            $valor = $deuda[$campo['campo']];
            switch ($campo['tipo']) {
              case 'money':
                $valor = '$ '.number_format((float) $valor, 0, '', '.');
                break;
              case 'saldo':
                // $this->logs->debug($footerData[$p]);
                if (isset($campo['suma']) && $campo['suma'] == 'true') {
                  $datosDeudas['total'] += (float) $valor;
                  $footerData[$p]['type'] = 'saldo';
                  $footerData[$p]['value'] = (float) $footerData[$p]['value'] + (float) $valor;
                  if (isset($campo['igual']) && $campo['igual'] == 'true') $footerData[$p]['value'] = (float) $valor;
                }
                //$this->logs->debug($footerData[$p]);
                $valor = '$ '.number_format((float) $valor, 0, '', '.');
                if (isset($campo['oculto']) && $campo['oculto'] == 'true') $valor = '...';
                break;
              case 'date':
                $valor = date('d-m-Y', strtotime($valor));
                break;
              case 'text_percent':
                  $valor = number_format((float)$valor,1,',','.').' %';
                  break;
              default:
                $valor = trim($valor);
                break;
            }
          }
          $item[$campo['campo']] = [
           'value' =>  $valor,
           'tAling' => $campo['tAling']
          ];
        }

        if (mb_strtolower($deuda['Estado']) === 'baja' && (isset($deuda['Fecha_Descargo']) && !is_null($deuda['Fecha_Descargo']) && !empty($deuda['Fecha_Descargo']))) {
          $extras[] = [
            'title' => 'Fecha de rebaja',
            'name' => 'Fecha_Descargo',
            'value' =>  date('d-m-Y', strtotime($deuda['Fecha_Descargo']))
          ];
        }

        if (isset($deuda['Tramo_Doc']) && !empty($deuda['Tramo_Doc'])) {
          $extras[] = [
            'title' => 'Tramo documento',
            'name' => 'tramo_doc',
            'value' => $deuda['Tramo_Doc']
          ];
        }

        if (isset($deuda['Comprobante']) && !empty($deuda['Comprobante'])) {
          $extras[] = [
            'title' => 'Comprobante',
            'name' => 'compromabte',
            'value' => $deuda['Comprobante']
          ];
        }

        if (isset($deuda['Tramo_Antiguo']) && !empty($deuda['Tramo_Antiguo'])) {
          $extras[] = [
            'title' => 'Tramo más antiguo',
            'name' => 'tramo_antiguo',
            'value' =>  $deuda['Tramo_Antiguo']
          ];
        }

        if (isset($deuda['Cartera']) && !empty($deuda['Cartera'])) {
          $extras[] = [
            'title' => 'Cartera',
            'name' => 'cartera',
            'value' =>  $deuda['Cartera']
          ];
        }

        if (!is_null($direccionDeuda)) {
          $direccion = $direccionDeuda->toArray();
          if (count((array) $direccion) > 0) {
            $direccion = reset($direccion);
            $pos = (int) count((array) $extras);
            $extras[$pos] = [
              'title' => 'Dirección',
              'name' => 'direccion',
              'value' => [
                'region' => trim($direccion['region']),
                'comuna' => trim($direccion['comuna']),
                'direccion' => trim($direccion['direccion']),
              ]
            ];
          }
        }            

        $item['extras'] = [
          'extras' => true,
          'value' =>  $extras,
          'text_align' => 'center'
        ];
        if(!$showExtras) {
          unset($item['extras']);
        }
        
        $datosDeudas['rows'][] = $item;
      }
      //$datosDeudas['footer'] = $footer;
      //$this->logs->debug($footerData);
      $footerData = array_map(function ($item) {
        $nItem =  [
          'value' => $item['value'], 
          'tAling' => $item['tAling'],
          'type' => $item['type'],
        ];
        if (!empty($nItem['value']) && (int) $nItem['value'] > 0) {
          if($nItem['type'] == 'saldo')  $nItem['value'] = '$ '.number_format($nItem['value'],0,'','.');
          if($nItem['type'] != 'saldo')  $nItem['value'] = number_format($nItem['value'],0,'','.');
        }
        return $nItem;
      }, $footerData);
      $datosDeudas['footer'] = $footerData;
      $datosDeudas['total'] = '$ '.number_format($datosDeudas['total'],0,'','.');
    }

    return $datosDeudas;
  }

  private function __createDeudasVigentesBlock($rut, $cedente, $showExtras = true)
  {
    $datosDeudas = [
      'headers' => [],
      'rows' => [],
      'total' => 0,
    ];

    $deudas = $this->db->select("SELECT * FROM Deuda_Vigente WHERE Rut = '{$rut}' ORDER BY Fecha_Emision DESC;");

    if ($deudas && count((array) $deudas) > 0) {
      $cedenteCampos = $this->db->select("SELECT bloque, config FROM configuracion_columnas WHERE bloque = 'vigentes' AND cedente_id = '{$cedente}' LIMIT 1;");//DB::connection('crm')->table('configuracion_columnas')->where('cedente_id','=',$cedente)->where('bloque','deudas')->first();
      if (count((array) $cedenteCampos) > 0) {
        $dataConfig = json_decode(json_encode(current((array) $cedenteCampos)), true);
        $campos = $dataConfig['config'];
      } else {
        $campos = '[{"field":"Factura","name":"Factura","type":"string","tAlign":"center"},{"field":"Entidad","name":"Nro. Cliente","type":"string","tAlign":"center"},{"field":"Tipo_Factura","name":"Tipo factura","type":"string","tAlign":"center"},{"field":"Deuda","name":"Deuda","type":"saldo","tAlign":"right"},{"field":"Saldo_Dia","name":"Saldo","type":"saldo","tAlign":"right"},{"field":"Estado","name":"Estado","type":"string","tAlign":"center"},{"field":"Fecha_Vencimiento","name":"Vencimiento","type":"date","tAlign":"center"},{"field":"Rechazo","name":"Motivo de rechazo","type":"string","tAlign":"left"}]';
      }
      $campos = json_decode($campos, true);
      $this->logs->debug('Cedente: '.$cedente);
      $this->logs->debug($campos);
      $footer = [];

      $fieldsConfig = array_map(function ($item) {
        $nItem =  ['campo' => $item['field'], 'tipo' => $item['type'], 'tAling' => $item['tAlign']];
        if (isset($item['suma'])) $nItem['suma'] = $item['suma'];
        if (isset($item['igual'])) $nItem['igual'] = $item['igual'];
        if (isset($item['oculto'])) $nItem['oculto'] = $item['oculto'];
        return $nItem;
      },$campos);

      $headersConfig = array_map(function ($item) {
        return ['title' => $item['name'], 'tAling' => $item['tAlign']];
      },$campos);

      if($showExtras) {
        $headersConfig[] = [
          'title' => 'Extra',
          'tAling' => 'center'
        ];
      }
      $datosDeudas['headers'] = $headersConfig;
      $footerData = [
        ['value' => 'Total: ', 'tAling' => 'rigth', 'type' => 'string'],
      ];
      $datosDeudas['footer'] = $footerData;
      $footerData = array_map(function ($item) {
        $nItem =  ['value' => '', 'tAling' => 'right', 'type' => 'string' ];
        //if (isset($item['suma'])) $nItem['suma'] = $item['suma'];
        return $nItem;
      }, $headersConfig);
      $footerData[0]['value'] = 'TOTAL: ';
      $this->logs->debug('Nueva');
      $this->logs->debug($footerData);
      $totalPos = 0;
    
      foreach ((array) $deudas as $deuda) {
        // Log::debug($deuda);
        $direccionDeuda = null; //$deuda->direccion($cedente)->get();
        //$deuda = $deuda->toArray();
        $extras = [];
        $item = [];
        foreach ($fieldsConfig as $p => $campo) {
          //$this->logs->debug($campo);
          //$footerData[$p] = ['value' => 0, 'tAling' => 'right' ];
          $valor = '';       
          if (isset($deuda[$campo['campo']])) {
            $valor = $deuda[$campo['campo']];
            switch ($campo['tipo']) {
              case 'money':
                $valor = '$ '.number_format((float) $valor, 0, '', '.');
                break;
              case 'saldo':
                // $this->logs->debug($footerData[$p]);
                if (isset($campo['suma']) && $campo['suma'] == 'true') {
                  $datosDeudas['total'] += (float) $valor;
                  $footerData[$p]['type'] = 'saldo';
                  $footerData[$p]['value'] = (float) $footerData[$p]['value'] + (float) $valor;
                  if (isset($campo['igual']) && $campo['igual'] == 'true') $footerData[$p]['value'] = (float) $valor;
                }
                //$this->logs->debug($footerData[$p]);
                $valor = '$ '.number_format((float) $valor, 0, '', '.');
                if (isset($campo['oculto']) && $campo['oculto'] == 'true') $valor = '...';
                break;
              case 'date':
                $valor = date('d-m-Y', strtotime($valor));
                break;
              case 'text_percent':
                  $valor = number_format((float)$valor,1,',','.').' %';
                  break;
              default:
                $valor = trim($valor);
                break;
            }
          }
          $item[$campo['campo']] = [
           'value' =>  $valor,
           'tAling' => $campo['tAling']
          ];
        }

        if (mb_strtolower($deuda['Estado']) === 'baja' && (isset($deuda['Fecha_Descargo']) && !is_null($deuda['Fecha_Descargo']) && !empty($deuda['Fecha_Descargo']))) {
          $extras[] = [
            'title' => 'Fecha de rebaja',
            'name' => 'Fecha_Descargo',
            'value' =>  date('d-m-Y', strtotime($deuda['Fecha_Descargo']))
          ];
        }

        if (isset($deuda['Tramo_Doc']) && !empty($deuda['Tramo_Doc'])) {
          $extras[] = [
            'title' => 'Tramo documento',
            'name' => 'tramo_doc',
            'value' => $deuda['Tramo_Doc']
          ];
        }

        if (isset($deuda['Comprobante']) && !empty($deuda['Comprobante'])) {
          $extras[] = [
            'title' => 'Comprobante',
            'name' => 'compromabte',
            'value' => $deuda['Comprobante']
          ];
        }

        if (isset($deuda['Tramo_Antiguo']) && !empty($deuda['Tramo_Antiguo'])) {
          $extras[] = [
            'title' => 'Tramo más antiguo',
            'name' => 'tramo_antiguo',
            'value' =>  $deuda['Tramo_Antiguo']
          ];
        }

        if (isset($deuda['Cartera']) && !empty($deuda['Cartera'])) {
          $extras[] = [
            'title' => 'Cartera',
            'name' => 'cartera',
            'value' =>  $deuda['Cartera']
          ];
        }

        if (!is_null($direccionDeuda)) {
          $direccion = $direccionDeuda->toArray();
          if (count((array) $direccion) > 0) {
            $direccion = reset($direccion);
            $pos = (int) count((array) $extras);
            $extras[$pos] = [
              'title' => 'Dirección',
              'name' => 'direccion',
              'value' => [
                'region' => trim($direccion['region']),
                'comuna' => trim($direccion['comuna']),
                'direccion' => trim($direccion['direccion']),
              ]
            ];
          }
        }            

        $item['extras'] = [
          'extras' => true,
          'value' =>  $extras,
          'text_align' => 'center'
        ];
        if(!$showExtras) {
          unset($item['extras']);
        }
        
        $datosDeudas['rows'][] = $item;
      }
      //$datosDeudas['footer'] = $footer;
      //$this->logs->debug($footerData);
      $footerData = array_map(function ($item) {
        $nItem =  [
          'value' => $item['value'], 
          'tAling' => $item['tAling'],
          'type' => $item['type'],
        ];
        if (!empty($nItem['value']) && (int) $nItem['value'] > 0) {
          if($nItem['type'] == 'saldo')  $nItem['value'] = '$ '.number_format($nItem['value'],0,'','.');
          if($nItem['type'] != 'saldo')  $nItem['value'] = number_format($nItem['value'],0,'','.');
        }
        return $nItem;
      }, $footerData);
      $datosDeudas['footer'] = $footerData;
      $datosDeudas['total'] = '$ '.number_format($datosDeudas['total'],0,'','.');
    }

    return $datosDeudas;
  }
}