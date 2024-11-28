<?php
include __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../class/logs.php';

try {
  function reArrayFiles(&$file_post)
  {
    $file_ary = [];
    $file_count = count($file_post['name']);
    $file_keys  = array_keys($file_post);
  
    for ($i=0; $i<$file_count; $i++) {
      foreach ($file_keys as $key) {
        $file_ary[$i][$key] = $file_post[$key][$i];
      }
    }
  
    return $file_ary;
  }

  $logs = new Logs();
  $client = new \GuzzleHttp\Client();
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
  $dotenv->load();
  session_start();
  $urlLoadSystem = $_ENV['LOADS_URL'].'/api/v1/';
  $idCedente = (int) $_SESSION['cedente'];
  $idConfig = (int) $_POST['configId'];
  
  $files = reArrayFiles($_FILES['archivos']);
  $valid_extensions = array("txt");
  $multipart = [];
  $items = [];
  
  if (count((array) $files) > 0) {
    foreach($files as $key => $file) {
      $filename = $file['name'];
      $logs->debug($file);
      $extension = pathinfo($filename, PATHINFO_EXTENSION);
      if(in_array(strtolower($extension), $valid_extensions) ) {
        if(move_uploaded_file($file['tmp_name'], "/tmp/".$filename)) {
          $multipart[] = [
            'name'     => 'archivos',
            'contents' => file_get_contents("/tmp/".$filename),
            'filename' => $filename
          ];
        }
      }
    }

    if (count($multipart) == 0) {
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode([
        'status' => 400,
        'success' => false,
        'message' => 'Faltan seleccionar los archivos',
        'items' => $items,
      ]);
      exit;
    }

    $logs->debug($multipart);
  
    try {
      $res = $client->request('POST', $urlLoadSystem.'loads/upload-vigente', [
        'multipart' => $multipart,
      ]);
      $status = $res->getStatusCode();
      $body = $res->getBody()->getContents();
      $message = 'Operación realizada con éxito.';
      if (is_string($body)) $body = json_decode($body, true);
      $logs->debug($body);

      if ($body['status'] == true) {
        $message .= ' - '.$body['message'];
        $status = 200;
        $items = $body['data']['files'];
      }
    } catch(GuzzleHttp\Exception\RequestException $ex) {
      $status = 500; //$ex->getResponse()->getStatusCode();
      $logs->debug($ex->getMessage());
      $message = 'Se ha presentado un error';
    } catch (GuzzleHttp\Exception\ConnectException $ex) {
      $status = 500;// $ex->getStatusCode();
      $logs->debug($ex->getMessage());
      $message = 'Se ha presentado un error';
    }
  }
    
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode([
    'status' => $status,
    'success' => ($status === 200)? true:false,
    'message' => $message,
    'items' => $items,
  ]);
} catch (\Exception $ex) {
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode([
    'status' => 500,
    'success' => false,
    'message' => 'Se ha presentado un error',
    'items' => $items,
  ]);
  exit;
}
?>