<?php
  include ("phpagi-asmanager.php");

  print_r ($_SERVER['argv']);

  $canal = $_SERVER['argv'][1];
  $nomArchivo = $_SERVER['argv'][2];
  $formato = $_SERVER['argv'][3];

  $asm = new AGI_AsteriskManager();

  $asm->connect("192.168.1.15","lponce","lponce");

  $resultado = $asm->monitor($canal,$nomArchivo,$formato);

  $asm->disconnect();
  sleep(3);


?>

