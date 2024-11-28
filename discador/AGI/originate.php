<?php
  include ("phpagi-asmanager.php");

  $asm = new AGI_AsteriskManager();
  //$asm->Events("on");
  $asm->connect("192.168.1.15","lponce","lponce");

  $resultado = $asm->originate("SIP/963904627@claro","6003","from-prueba","1","","","18000","227144101","","12345","true","1002");
  print_r($resultado);

  $asm->add_event_handler("OriginateResponse","mostrarDatos");
  $asm->add_event_handler("Hangup","mifuncion");


  while(true){
   $asm->wait_response(true);
  }


  function mostrarDatos($ecode,$data,$server,$port) {
    echo "Ejecutar Ajax!!!";
    echo "received event '$ecode' from $server:$port\n";
    print_r($data);
  }



  function mifuncion($ecode,$data,$server,$port) {
    echo "received event '$ecode' from $server:$port\n";
    print_r($data);
  }

  $asm->disconnect();
  sleep(10);


?>
