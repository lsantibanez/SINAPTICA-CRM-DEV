<?php
include __DIR__.'/../../../vendor/autoload.php';
include __DIR__.'/../../../class/logs.php';

$logs = new Logs();
$client = new \GuzzleHttp\Client();
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();
session_start();
$urlLoadSystem = $_ENV['LOADS_URL'].'/api/v1/';
$idCedente = (int) $_SESSION['cedente'];
$idConfig = (int) $_POST['configId'];
$idMandante = (int) $_SESSION['mandante'];

$logs->debug('URL: '.$urlLoadSystem );
// $logs->debug($_FILES);
$destino = (isset($_POST['route']) && !empty($_POST['route']))? '/'.$_POST['route']:'';

if (isset($_FILES['files']['name'])) {
  foreach ($_FILES['files']['name'] as $key => $file) {
    $filenames[] = [
      'name'      => $_FILES['files']['name'][$key],
      'full_path' => $_FILES['files']['full_path'][$key],
      'type'      => $_FILES['files']['type'][$key],
      'tmp_name'  => $_FILES['files']['tmp_name'][$key],
      'error'     => $_FILES['files']['error'][$key],
      'size'      => $_FILES['files']['size'][$key],
    ];
  }
} else {
  $filenames[] = [
    'name' => $_FILES['file']['name'],
    'full_path' => $_FILES['file']['full_path'],
    'type' => $_FILES['file']['type'],
    'tmp_name' => $_FILES['file']['tmp_name'],
    'error' => $_FILES['file']['error'],
    'size' => $_FILES['file']['size'],
  ];
}
$ruta = '/upload'.$destino;
$multipart = [
  [
    'name'     => 'configId',
    'contents' => $idConfig
  ],
  [
    'name'     => 'cedente',
    'contents' => $idCedente
  ]
];
$files = 0;
foreach ($filenames as $key => $filename) {
  $logs->debug('Archivo: '.$filename['name']);
  // Valid file extensions
  $valid_extensions = array("xlsx");
  // File extension
  $extension = pathinfo($filename['name'], PATHINFO_EXTENSION);
  $logs->debug('Extension del archivo: '.$extension);
  if(in_array(strtolower($extension),$valid_extensions)) { 
    // $logs->debug('Carga bases ruta: '.$urlLoadSystem.'loads'.$ruta. ' | Cedente: '.$idCedente. ' | Config: '.$idConfig);
    if(move_uploaded_file($filename['tmp_name'], "/tmp/".$filename['name'])) {
      $logs->debug('¡¡Cargado!!');
      $multipart[] = [
        'name'     => 'archivo',
        'contents' => file_get_contents("/tmp/".$filename['name']),
        'filename' => $filename['name'],
      ];
      $files++;
    }
  }
}

if ($files > 0) {
  try {
    $logs->debug('*** Cargar ***');
    $res = $client->request('POST', $urlLoadSystem.'loads'.$ruta , [
      'multipart' => $multipart,
    ]);
    $status = $res->getStatusCode();
    $body = $res->getBody()->getContents();
    $message = 'Operación realizada con éxito.';
    $logs->debug('Status: '.$status);
    if (is_string($body)) $body = json_decode($body, true);
  } catch(GuzzleHttp\Exception\RequestException $ex) {
    $logs->error($ex->getMessage());
    $status = 500; //$ex->getResponse()->getStatusCode();
    $message = 'Se ha presentado un error';
  } catch (GuzzleHttp\Exception\ConnectException $ex) {
    $logs->error($ex->getMessage());
    $status = 500;// $ex->getStatusCode();
    $message = 'Se ha presentado un error';
  }
} else {
  $message = 'Se ha presentado un error';
  $logs->debug('Error de extensión: '.$extension);
  $status = 400;
}
// Check extension
/*
if(in_array(strtolower($extension),$valid_extensions) ) {
  
  $logs->debug('Carga bases ruta: '.$urlLoadSystem.'loads'.$ruta. ' | Cedente: '.$idCedente. ' | Config: '.$idConfig);
  // Upload file
  if(move_uploaded_file($_FILES['file']['tmp_name'], "/tmp/".$filename)) {
    try {
      $res = $client->request('POST', $urlLoadSystem.'loads'.$ruta , [
        'multipart' => $multipart,
      ]);
      $status = $res->getStatusCode();
      $body = $res->getBody()->getContents();
      $message = 'Operación realizada con éxito.';
      if (is_string($body)) $body = json_decode($body, true);
    } catch(GuzzleHttp\Exception\RequestException $ex) {
      $logs->error($ex->getMessage());
      $status = 500; //$ex->getResponse()->getStatusCode();
      $message = 'Se ha presentado un error';
    } catch (GuzzleHttp\Exception\ConnectException $ex) {
      $logs->error($ex->getMessage());
      $status = 500;// $ex->getStatusCode();
      $message = 'Se ha presentado un error';
    }
  } else {
    $message = 'Archivo no válido';
    $logs->debug('Archivo no válido');
    $status = 400;
  }
} else {
  $message = 'Se ha presentado un error';
  $logs->debug('Error de extensión: '.$extension);
  $status = 400;
}
  */

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
  'status' => $status,
  'success' => ($status === 200)? true:false,
  'message' => $message,
]);
?>