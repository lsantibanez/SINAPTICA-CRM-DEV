<?php
  include ("../phpagi/phpagi-asmanager.php");

  $asm = new AGI_AsteriskManager();

  $asm->connect("192.168.1.15","myasterisk","adminaia123");
//  $resultado = $asm->command("reload");
  $resultado = $asm->command("manager show users");
  print_r($resultado);
  $asm->disconnect();
  sleep(3);


?>
