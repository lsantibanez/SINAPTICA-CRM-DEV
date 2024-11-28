<?php

require __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../class/db/DB.php';

use Ramsey\Uuid\Uuid;
use \avadim\FastExcelReader\Excel;

$data = [
  'success' => true,
  'info' => null,
  'message' => 'OK',
];

try {
  $db = new Db();
  $cedente = (int) $_SESSION['cedente'];
  
  $filename = $_FILES['file']['name'];
  $filename = str_replace('-','', $filename);
  $filename = str_replace(' ','_', $filename);
  $filename = str_replace('__','_', $filename);
  $filename = mb_strtolower($filename);

  $valid_extensions = array("xlsx");
  $extension = pathinfo($filename, PATHINFO_EXTENSION);

  if (in_array(strtolower($extension),$valid_extensions)) {
    if (move_uploaded_file($_FILES['file']['tmp_name'], '/tmp/'.$filename)) {
      $loadId = Uuid::uuid4();
      $columnas = '';
      $excel = Excel::open('/tmp/'.$filename);
      $sheet = $excel->getFirstSheet();
      $columnas = array_values($sheet->readFirstRow());
      $relacion = [
        [
          'table' => 'entidad',
          'file' => 'Entidad ',
          'name' => 'Id de cliente',
          'index' => -1,
          'exists' => false,
        ],
        [
          'table' => 'factura',
          'file' => 'Factura',
          'name' => 'Factura',
          'index' => -1,
          'exists' => false,
        ],
        [
          'table' => 'fecha_vencimiento',
          'file' => 'Fecha Vencimiento ',
          'name' => 'Fecha de vencimiento',
          'index' => -1,
          'exists' => false,
        ],
        [
          'table' => 'saldo',
          'file' => 'Saldo',
          'name' => 'Saldo',
          'index' => -1,
          'exists' => false,
        ],
        [
          'table' => 'saldo_dia',
          'file' => 'S-HOY',
          'name' => 'Saldo al día',
          'index' => -1,
          'exists' => false,
        ],
      ];

      $relacion2 = array_reduce($relacion, function ($result, $item) use ($columnas) {
        $index = array_search($item['file'], $columnas);
        $item['exists'] = ($index !== -1)? true: false;
        $item['index'] = $index;
        $result[] = $item;
        return $result;
      }, []);

      $jsonColumns = json_encode($columnas);
      $jsonRelacion = json_encode($relacion2);
      $rows = (int) $sheet->countRows() - 1;

      $strSQL = "INSERT INTO `load_files` (config_id, load_id, ruta_archivo, archivo, columnas, relacion, cedente_id, registros) VALUES (5, '{$loadId}', '/tmp/', '{$filename}', '{$jsonColumns}', '{$jsonRelacion}', {$cedente}, {$rows}) ON DUPLICATE KEY UPDATE procesado = 0, registros = {$rows}, load_id = '{$loadId}';";
      $db->insert($strSQL);

      $data['success'] = true;
      $data['info'] = [
        'code' => $loadId,
        'file' => $filename,
        'relation' => $relacion2,
        'configuration' => 'Descargo de facturas'
      ];
      $data['message'] = 'Archivo cargado con éxito';
    } else {
      $data['success'] = false;
      $data['message'] = 'No se pudo procesar el archivo.';
    }
  } else {
    $data['success'] = false;
    $data['message'] = 'Archivo no válido.';
  }
} catch (\Exception $ex) {
  $data['success'] = false;
  $data['message'] = $ex->getMessage(); //'Se ha presentado un error al intentar procesar la petición.';
}

header('Content-Type: application/json; chatser=utf-8');
echo json_encode($data);
exit;
?>