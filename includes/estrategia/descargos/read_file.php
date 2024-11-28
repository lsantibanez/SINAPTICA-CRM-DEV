<?php
error_reporting(E_ALL & ~E_NOTICE);
require __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../class/db/DB.php';

use \avadim\FastExcelReader\Excel;
use League\Csv\Writer;

$data = [
  'success' => true,
  'info' => null,
  'message' => 'OK',
];


try {
  $db = new Db();
  $cedente = (int) $_SESSION['cedente'];
  $postData = json_decode(file_get_contents('php://input'), true);

  $rsLoad = $db->select("SELECT config_id, load_id, archivo, CONCAT(ruta_archivo, archivo) AS ruta, relacion, columnas FROM load_files WHERE cedente_id = {$cedente} AND load_id = '{$postData['uuid']}' LIMIT 1");
  if (count((array) $rsLoad) > 0) {
    $carga = $rsLoad[0];
    $carga['relacion'] = json_decode($carga['relacion'], true);
    $carga['columnas'] = json_decode($carga['columnas'], true);
    $rsConfig = $db->select("SELECT * FROM load_configs WHERE id = {$carga['config_id']} LIMIT 1;");
    if (count((array) $rsConfig) > 0) {
      $configuracion = $rsConfig[0];
      $configuracion['campos_destino'] = json_decode($configuracion['campos_destino'], true);
      $configuracion['columnas'] = json_decode($configuracion['columnas'], true);
      if (!is_null($configuracion['pos_rutines'])) $configuracion['pos_rutines'] = json_decode($configuracion['pos_rutines'], true);
      $databasetable = $configuracion['tabla_destino'];

      $excel = Excel::open($carga['ruta']);
      $excel->setDateFormat('Y-m-d');
      $sheet = $excel->getFirstSheet();
      $firstRow = array_values($sheet->readFirstRow());

      $relacion2 = array_reduce($carga['relacion'], function ($result, $item) {
        $result[$item['index']] = $item['table'];
        return $result;
      }, []);

      $headerToCsv = array_values($relacion2);
      $headerToCsv[] = 'Id_Cedente';
      $headerToCsv[] = 'fecha_ingreso';
      $fIngreso = date('Y-m-d');

      $pathToCsv = str_replace('.xlsx','.csv', $carga['ruta']);
      $fieldseparator = ';';
      $lineseparator = "\n";
      $writer = Writer::createFromPath($pathToCsv, 'w+');
      $writer->setDelimiter(';');
      $writer->insertOne($headerToCsv);

      $rows = $sheet->readRows(true, Excel::KEYS_ZERO_BASED);
      foreach ($rows as $fila) {
        $linea = []; 
        foreach($relacion2 as $letra => $nombre) {
          $linea[] = $fila[$letra];
        }
        $linea[] = $cedente;
        $linea[] = $fIngreso;
        $writer->insertOne($linea);
      }
      $data['info']['table_reset'] = 'NO';
      $data['info']['tiene_pos_rutinas'] = 'NO';
      

      if ((int) $configuracion['reinicia_tabla'] === 1) {
        $db->query("TRUNCATE TABLE `{$databasetable}`;");
        $data['info']['table_reset'] = 'SI';
      }
      try {
        $dbConn = $db->getInstance();
        mysqli_options($dbConn, MYSQLI_OPT_LOCAL_INFILE, true);
  
        $strSql = "LOAD DATA LOCAL INFILE '".$dbConn->real_escape_string($pathToCsv)."' INTO TABLE `{$databasetable}` FIELDS TERMINATED BY '".$dbConn->real_escape_string($fieldseparator)."' LINES TERMINATED BY '".$lineseparator."' IGNORE 1 LINES (".implode(',', $headerToCsv).");";
        $loadData = $dbConn->prepare($strSql);
        $loadData->execute();
        $cantLoaded = (int) $loadData->affected_rows;
        $data['info']['rows_loaded'] = $cantLoaded;
  
        $data['info']['pos_rutines'] = [];
        if (!is_null($configuracion['pos_rutines'])) {
          $data['info']['tiene_pos_rutinas'] = 'SI';
          //$data['info']['pos_rutines']['cant'] = (int) count((array) $configuracion['pos_rutines']);
          foreach ($configuracion['pos_rutines'] as $rutina) {
            if ($rutina['type'] === 'SQL') {
              $strQuery = $rutina['query'];
              $update = $dbConn->prepare($strQuery);
              $update->execute();
              $cuenta = (int) $update->affected_rows;
              $data['info']['pos_rutines'][] = [
                'name' => $rutina['name'],
                'type' => $rutina['type'],
                'affected' => $cuenta
              ];
            }
          }
        }
      } catch (\Exception $e) {
        echo $e->getMessage();
        exit;
      }

      $data['success'] = true;
      $data['message'] = 'Completado';
    } else {
      $data['success'] = false;
      $data['message'] = 'Configuración no valida';
    }
  } else {
    $data['success'] = false;
    $data['message'] = 'Carga no existe';
  }
} catch (\Exception $ex) {
  $data['success'] = false;
  $data['message'] = 'Error de ejecución';
}

header('Content-Type: application/json; chatser=utf-8');
echo json_encode($data);
exit;
?>