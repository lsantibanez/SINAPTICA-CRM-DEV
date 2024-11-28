<?php
include __DIR__.'/../class/db/DB.php';
try {
  
  $anio = str_replace('-','',$_GET['y']);
  $hour = substr($_GET['h'],0,2);
  $phone = substr($_GET['f'], strlen($_GET['f'])-9,9);
  

  $db1 = new Db();
  $mysqli_remote = $db1->getInstance();
  $rs = $mysqli_remote->query("SELECT * FROM audios WHERE fecha = '{$anio}' AND telefono = '{$phone}' AND hora LIKE '{$hour}%' ORDER BY fecha DESC LIMIT 1 ");
  if($rs && $rs->num_rows > 0) {
    $audio = $rs->fetch_all(MYSQLI_ASSOC);
    $archivo = 'https://crmbpro.sinaptica.io/RECORDING/MP3/'.$audio[0]['audio'];
    echo $archivo;
    exit;
  }

  $fichero = exec('find /storage/crm/bpro/audios/ -type f -name "'.$anio.'-'.$hour.'*_'.$phone.'-all.mp3"');
  if (isset($_GET['o']) && !empty($_GET['o']) &&  file_exists('/storage/crm/bpro/audios/'.$_GET['o'])) {
    $archivo = 'https://crmbpro.sinaptica.io/RECORDING/MP3/'.basename($fichero);
    if(isset($_GET['d']) && !empty($_GET['d']) && $_GET['d'] == 'si') {
      header('Location: '.$archivo);
      exit;
    }
    echo 
    exit;
  }
  if (!empty($fichero)) {
    if (file_exists($fichero)) {
      $archivo = 'https://crmbpro.sinaptica.io/RECORDING/MP3/'.basename($fichero);
      if(isset($_GET['d']) && !empty($_GET['d']) && $_GET['d'] == 'si') {
        header('Location: '.$archivo);
        exit;
      }
      echo $archivo;
      exit;
    } else {
      header('HTTP/1.0 404 Not Found', true, 404);
      exit;
    }
  } else {
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
  }
} catch(\Exception $ex) {
  header('HTTP/1.0 500 Internal error', true, 500);
  exit;
}
 