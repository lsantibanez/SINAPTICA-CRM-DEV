<?php
    
    class Discador{
        
        public $QueueName;
        public $ChannelNumber;
        public $Cedente;
        public $QueueAsterisk;
        public $QueueOriginal;
        public $IdQueue;

        public $IpServidorDiscado;
        public $CodigoFoco;

        public $FonoPrefix;

       function __construct($IdArg = ""){
            if($IdArg != ""){
                $db = new Db();
                $SqlRecord  = "SELECT q.Queue,d.Cola,d.numero_canales,d.Id_Cedente FROM Asterisk_All_Queues q , Asterisk_Discador_Cola d WHERE q.id_discador = d.id AND d.id=$IdArg";
                $Records = $db -> select($SqlRecord);
                foreach($Records as $Record){
                    $this->QueueName = $Record['Queue'];
                    $this->QueueOriginal = $Record['Cola'];
                    $this->ChannelNumber = $Record['numero_canales'];
                    $this->Cedente = $Record['Id_Cedente'];
                    $this->QueueAsterisk  =  "DR_".$this->QueueName."_".$this->QueueOriginal;
                }  
                $this->IdQueue = $IdArg;
            }

            $FocoConfig = $this->getFocoConfig();
            $this->IpServidorDiscado = $FocoConfig['IpServidorDiscado'];
            $this->CodigoFoco = $FocoConfig['CodigoFoco'];
        }

        function getCredencialesDiscador(){
            $ToReturn = array();
            //create curl resource
            $ch = curl_init();
            //set url
            curl_setopt($ch, CURLOPT_URL, "http://".$this->IpServidorDiscado."/includes/server/getCredencialesDatabase-webService.php");
            //setup post Variables
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"CodigoFoco=".$this->CodigoFoco);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //$output contains the output string 
            $output = curl_exec($ch);
            $ToReturn = $output;
            $ToReturn = json_decode($ToReturn,true);
            //$ToReturn = json_decode($ToReturn,true);
            //close curl resource to free up system resources
            curl_close($ch);
            return $ToReturn;
        }
        public function Stop(){

            $db = new Db();
            $PlayRecord = "SELECT * FROM Asterisk_Discador_Cola WHERE id = $this->IdQueue AND Status = 1 AND Estado = 1";
            $CountPlay = count($db -> select($PlayRecord));
            $StopRecord = "SELECT * FROM Asterisk_Discador_Cola WHERE id = $this->IdQueue AND Status = 1 AND Estado = 0";
            $CountStop = count($db -> select($StopRecord));
            $PauseRecord = "SELECT * FROM Asterisk_Discador_Cola WHERE id = $this->IdQueue AND Status = 1 AND Estado = 2";
            $CountPause = count($db -> select($PauseRecord));
            $DisableRecord = "SELECT * FROM Asterisk_Discador_Cola WHERE id = $this->IdQueue AND Status = 0";
            $CountDisable = count($db -> select($DisableRecord));
            $Stop = 0;
            switch (true) {
                case ($CountPlay==1 && $CountPause==0 &&  $CountStop==0 && $CountDisable==0):
                    $Stop = 1;
                    return $Stop;
                break;

                case ($CountPlay==0 && $CountPause==0 &&  $CountStop==1 && $CountDisable==0):
                    $Stop = 0;
                    $FechaHora = date("Y-m-d G:i:s");
                    $QueryUpdate = "UPDATE $this->QueueAsterisk SET llamado=0  WHERE llamado=1";
                    $UpdateRecord = $db -> query($QueryUpdate);
                    $QueryDiscador = "UPDATE Asterisk_Discador_Cola SET Estado=$Stop ,FeFin = '$FechaHora' WHERE id = $this->IdQueue";
                    $UpdateRecordDiscador = $db -> query($QueryDiscador);
                    return $Stop;
                break;

                case ($CountPlay==0 && $CountPause==1 &&  $CountStop==0 && $CountDisable==0):
                    $Stop = 2;
                    $FechaHora = date("Y-m-d G:i:s");
                    $QueryDiscador = "UPDATE Asterisk_Discador_Cola SET Estado=$Stop ,FeFin = '$FechaHora' WHERE id = $this->IdQueue";
                    $UpdateRecordDiscador = $db -> query($QueryDiscador);
                    return $Stop;
                break;

                case ($CountDisable==1):
                    $Stop = 3;
                    $FechaHora = date("Y-m-d G:i:s");
                    $QueryUpdate = "UPDATE $this->QueueAsterisk SET llamado=0  WHERE llamado=1";
                    $UpdateRecord = $db -> query($QueryUpdate);
                    $QueryDiscador = "UPDATE Asterisk_Discador_Cola SET Estado=$Stop ,FeFin = '$FechaHora' WHERE id = $this->IdQueue";
                    $UpdateRecordDiscador = $db -> query($QueryDiscador);
                    return $Stop;
                break;
            }
        }

        public function Start($Provider){
            $db = new Db();
            $Stop = $this->Stop();
            $ArrayMultipler = $this->getMultipler();
            $MultiplerReturn = $ArrayMultipler['Multipler'];
            $PauseReturn = $ArrayMultipler['Pause'];
     
            while($Stop == 1){
                while($Stop == 1 && $MultiplerReturn==0){
                    echo "Waiting...";
                    $ArrayMultipler = $this->getMultipler();
                    $MultiplerReturn = $ArrayMultipler['Multipler'];
                    $PauseReturn = $ArrayMultipler['Pause'];
                    $Stop = $this->Stop();
                }
                $BeginRecord = "SELECT id,Fono,Rut FROM $this->QueueAsterisk WHERE llamado = 0 LIMIT $MultiplerReturn";
                $CountBegin = count($db -> select($BeginRecord));
                $Records = $db -> select($BeginRecord);
                if($CountBegin > 0){
                    $Stop = $this->Stop();
                    foreach($Records as $Record){
                        $Fono = $Record['Fono'];
                        $Id = $Record['id'];
                        $Rut = $Record['Rut'];
                        
                        $InCallQuery = "SELECT * FROM Asterisk_InCall WHERE Queue = $this->QueueName";
                        $CountIncall = count($db -> select($InCallQuery));

                        if($CountIncall>=$MultiplerReturn){
                            echo "No insertar";
                            $Stop = $this->Stop();
                            $ArrayMultipler = $this->getMultipler();
                            $MultiplerReturn = $ArrayMultipler['Multipler'];
                            $PauseReturn = $ArrayMultipler['Pause'];
                        }
                        else{

                            $SqlInsertRecord = "INSERT INTO Asterisk_InCall(Fono,Rut,Queue) VALUES ('$Fono','$Rut','$this->QueueName')";
                            $InsertRecord = $db -> query($SqlInsertRecord);

                            $SqlUpdateRecord = "UPDATE $this->QueueAsterisk SET llamado = 1 WHERE Fono = $Fono";
                            $UpdateRecord = $db -> query($SqlUpdateRecord);

                            $FonoSip = "SIP/".$Fono."@".$Provider;
                            $asm = new AGI_AsteriskManager();
                            $asm->connect($this->IpServidorDiscado,"lponce","lponce");
                            $VarAgi = "Id=".$Id."&".$Fono."&".$this->QueueName."&".$Rut."&".$this->Cedente;
                            //$resultado = $asm->originate("$FonoSip","$NombreQueue","from-prueba","1","","","18000","227144101","","","","");
                            $Call = $asm->originate("$FonoSip","$this->QueueName",$Provider,"1","","","18000","6003200400","$VarAgi","$VarAgi","false","1001");

                            print_r($Call);
                            $Stop = $this->Stop();
                            $asm->disconnect();
                            echo "Llamando";
                        }
                    }

                }
                else{
                    $Stop=0;
                }
            }
        }

        public function getMultipler(){

            $asm = new AGI_AsteriskManager();
            $asm->connect($this->IpServidorDiscado,"lponce","lponce");

            $db = new Db();
            $AgentAvailables = array();
            $AgentQuery= "SELECT Agente FROM Asterisk_Agentes WHERE Queue = $this->QueueName";
            $Records = $db -> select($AgentQuery);
            foreach($Records as $Record){
                $Agent = $Record['Agente'];
                array_push($AgentAvailables,"$Agent");
            }

            $Unavailable = array();
            $Available = array();
            $ToReturn = array();
            $CountAgent = count($AgentAvailables);
            $Multipler = 0;
            $i = 0;
            while($i<$CountAgent){
                $Result= $asm->Command("queue show $this->QueueName");
                $Agent = $AgentAvailables[$i];
                $Test = implode("\n",$Result);
                $Array = explode("\n",$Test);
                $Count= count($Array);
                $j = 0;
                while($j<$Count){
                    $ArrayTest = explode(" ",$Array[$j]);
                    if  (in_array("$Agent", $ArrayTest) && in_array("(Unavailable)", $ArrayTest)) {
                    }
                    else if (in_array("$Agent", $ArrayTest) && in_array("(paused)", $ArrayTest)){
                        array_push($Unavailable, "$Agent");
                    }
                    else if (in_array("$Agent", $ArrayTest)){
                        array_push($Available, "$Agent");
                    }
                    else{

                    }
                    $j++;
                }
                $i++;
            }
            echo "Function Multipler";
            echo "Paused : "; echo $Pause  =  count($Unavailable);
            echo "Availables: "; echo $Availables = count($Available);
            echo "Multipler :"; echo $Multipler = $Availables*$this->ChannelNumber;
            $ToReturn = array('Multipler' => $Multipler, 'Pause' => $Pause );
            return $ToReturn;
            $asm->disconnect();
        }
        function Discar($Fono,$Anexo,$Cedente,$User,$Provider,$CodigoFoco,$FonoPrefix){
            $ToReturn = "";
            $asm = new AGI_AsteriskManager();
            $asm->connect($this->IpServidorDiscado,"lponce","lponce");
            $Anio = date("Y");
            $Mes = date("m");
            $Dia = date("d");
            $Hora = date("G");
            $Minuto = date("H");
            $Segundo = date("s");
            $AnexoSip =  "SIP/".$Anexo;
            $FonoSip = "SIP/".$FonoPrefix.$Fono."@".$Provider;
            $resultado = $asm->originate($AnexoSip,$FonoPrefix.$Fono,$Provider,"1","","","15000",$FonoPrefix.$Fono,"","12345","false",$Anexo);
            sleep(1);
            $ChannelsReponse = $asm->command("core show channels concise");
            $Channels = explode(PHP_EOL,$ChannelsReponse["data"]);
            $Canal = '';
            foreach($Channels as $Channel){
                if(strpos($Channel,"SIP/".$Anexo) !== false){
                    $PosIni = strpos($Channel,"SIP/".$Anexo);
                    $PosFin = strpos($Channel,"!",$PosIni);
                    $Lenght = $PosFin - $PosIni;
                    $Canal = substr($Channel,$PosIni,$Lenght);
                }
            }
            if(strlen($Cedente) > 2){
                $Cedente = $Cedente;
            }else{
                if(strlen($Cedente) == 2){
                    $Cedente = "0".$Cedente;
                }else{
                    if(strlen($Cedente) == 1){
                        $Cedente = "00".$Cedente;
                    }
                }
            }
            $nomArchivoGestion = $Anio.$Mes.$Dia."-".$Hora.$Minuto.$Segundo."_".$Fono."_".$Cedente."_".$User;
            $nomArchivo = $nomArchivoGestion."-all";
            $formato = "wav";
            /*$CanRecord = false;
            //while(!$CanRecord){
            $Cont = 0;
            do{
                $ChannelsReponse = $asm->command("core show channels");
                $Channels = explode(PHP_EOL,$ChannelsReponse["data"]);
                $Channels = $Channels[1];
                foreach($Channels as $Channel){
                    $ChannelArray = explode(PHP_EOL,$Channel);
                    echo $Channel;
                    if(strpos($Channel,"SIP/".$Anexo) !== false){
                        if(strpos($Channel,"Up") !== false){
                            $CanRecord = true;
                            //echo "true".strpos($Channel,"Up");
                        }else{
                            //echo "false";
                        }
                    }
                }
                $Cont++;
                if($Cont > 1000){
                    $CanRecord = true;
                }
            }while($CanRecord === false);
            */
            $resultadoGrabacion = $asm->monitor($Canal,$CodigoFoco."/".$Cedente."/".$Anio.$Mes.$Dia."/".$User."/".$nomArchivo,$formato,true);
            $UrlGrabacion = "http://".$this->IpServidorDiscado."/"."Records"."/".$CodigoFoco."/".$Cedente."/".$Anio.$Mes.$Dia."/".$User."/".$nomArchivo.".".$formato;
            //$resultadoGrabacion = $asm->monitor($Canal,$CodigoFoco."/".$nomArchivo,$formato,true);
            $ToReturn = array('uno' => $Canal, 'dos' => $nomArchivoGestion, 'tres' => $UrlGrabacion, 'cuatro' => $FonoPrefix.$Fono);
            $asm->disconnect();
            return $ToReturn;
        }    
        function CortarDiscado($Anexo){
            $Canal = "";
            $asm = new AGI_AsteriskManager();
            $asm->connect($this->IpServidorDiscado,"lponce","lponce");
            $resultado = $asm->command("core show channels concise");
            $Channels = explode(PHP_EOL,$resultado["data"]);
            foreach($Channels as $Channel){
                if(strpos($Channel,"SIP/".$Anexo) !== false){
                    $Channel;
                    $PosIni = strpos($Channel,"SIP/".$Anexo);
                    $PosFin = strpos($Channel,"!",$PosIni);
                    $Lenght = $PosFin - $PosIni;
                    $Canal = substr($Channel,$PosIni,$Lenght);
                }
            }
            $asm->hangup($Canal);
            $asm->disconnect();
        }
        function UnPause_Predictivo($Queue,$Anexo){
            $asm = new AGI_AsteriskManager();
            $asm->connect($this->IpServidorDiscado,"lponce","lponce");
            $resultado = $asm->Command("queue unpause member $Anexo queue $Queue reason 1");
            $asm->disconnect();
        }
        function Pause_Predictivo($Queue,$Anexo){
            $asm = new AGI_AsteriskManager();
            $asm->connect($this->IpServidorDiscado,"lponce","lponce");
            $resultado = $asm->Command("queue pause member $Anexo queue $Queue reason 1");
            $asm->disconnect();
        }
        function entrarCola($Queue,$Anexo){
            $asm = new AGI_AsteriskManager();
            $asm->connect($this->IpServidorDiscado,"lponce","lponce");
            $resultado = $asm->QueueAdd($Queue,$Anexo,"1");
            $asm->disconnect();
        }
        function salirCola($Queue,$Anexo){
            $asm = new AGI_AsteriskManager();
            $asm->connect($this->IpServidorDiscado,"lponce","lponce");
            $resultado = $asm->QueueRemove($Queue,$Anexo,"1");
            $asm->disconnect();
        }
        function getFocoConfig(){
            $db = new DB();
            $SqlFocoConfig = "select * from fireConfig";
            $FocoConfig = $db->select($SqlFocoConfig);
            return $FocoConfig[0];
        }
        function descargarGestionesNegativas($Fecha){
            $ToReturn = array();
            //create curl resource
            $ch = curl_init();
            //set url
            curl_setopt($ch, CURLOPT_URL, "http://".$this->IpServidorDiscado."/includes/Gestiones/getAutoGestiones-webService.php");
            //setup post Variables
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"CodigoFoco=".$this->CodigoFoco."&Fecha=".$Fecha);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //$output contains the output string 
            $output = curl_exec($ch);
            $ToReturn = $output;
            $ToReturn = json_decode($ToReturn,true);
            //$ToReturn = json_decode($ToReturn,true);
            //close curl resource to free up system resources
            curl_close($ch);
            return $ToReturn;
            
            /*$focoConfig = $this->getFocoConfig();

            $dbDiscador = new DB("discador");
            $SqlIpLinkedServer = "select IpLinkedServer from foco where CodigoFoco = '".$focoConfig["CodigoFoco"]."'";
            $IpLinkedServer = $dbDiscador->select($SqlIpLinkedServer);
            if(count($IpLinkedServer) > 0){
                $IpLinkedServer = $IpLinkedServer[0]["IpLinkedServer"];
            }else{
                $IpLinkedServer = "";
            }

            $SqlGestionesNegativas = "select
                                        ag.rut_cliente,
                                        ag.fono_discado,
                                        ag.status_name,
                                        ag.fechahora,
                                        ag.fecha_gestion,
                                        ag.hora_gestion,
                                        ag.Id_TipoGestion,
                                        ag.origen,
                                        ag.Id_Cedente,
                                        ''
                                    from
                                        auto_gestiones ag
                                            left join [".$IpLinkedServer."].[foco].[dbo].gestion_ult_trimestre g on g.rut_cliente=ag.rut_cliente and g.fono_discado = ag.fono_discado and g.fechahora = ag.fechahora
                                    where
                                        g.rut_cliente is null and
                                        ag.CodigoFoco='".$focoConfig["CodigoFoco"]."'
                                        and ag.fecha_gestion='".$Fecha."'";

            $SqlDownloadGrabaciones = "insert into [".$IpLinkedServer."].[foco].[dbo].gestion_ult_trimestre
                                        (rut_cliente,fono_discado,status_name,fechahora,fecha_gestion,hora_gestion,Id_TipoGestion,origen,cedente,factura)
                                        ".$SqlGestionesNegativas;
            $DownloadGrabaciones = $dbDiscador->query($SqlDownloadGrabaciones);
            return $DownloadGrabaciones;*/
        }
        function insertGestionesNegativasFoco($Gestiones){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $InsertValues = array();
            $Cont1000 = 0;
            $Array1000 = array();
            $Array1000[$Cont1000] = array();
            $ContValues = 1;
            $ArrayCedentes = array();
            $SqlCedentes = "select tipo, Id_Cedente from Cedente";
            $Cedentes = $db->select($SqlCedentes);
            foreach($Cedentes as $Cedente){
                $ArrayCedentes[$Cedente["Id_Cedente"]] = $Cedente["tipo"];
            }
            foreach($Gestiones as $Gestion){
                $Rut = $Gestion["Rut"];
                $Fono = $Gestion["Fono"];
                $StatusName = $Gestion["StatusName"];
                $FechaHora = $Gestion["FechaHora"];
                $Fecha = $Gestion["Fecha"];
                $Hora = $Gestion["Hora"];
                $IdGestion = $Gestion["IdGestion"];
                $Origen = $Gestion["Origen"];
                $Cedente = $Gestion["Cedente"];
                
                $FieldNOperacion = "";
                //switch($_SESSION['tipoSistema']){
                switch($ArrayCedentes[$Cedente]){
                    case "1":
                        $FieldNOperacion = "Numero_Factura";
                    break;
                    default:
                        $FieldNOperacion = "Numero_Operacion";
                    break;
                }
                // $SqlOperaciones = "select distinct GROUP_CONCAT(".$FieldNOperacion.") as NOperacion from Deuda where Id_Cedente='".$Cedente."' and Rut='".$Rut."'";
                $SqlOperaciones = "select distinct ".$FieldNOperacion." as NOperacion from Deuda where Id_Cedente = '".$Cedente."' and Rut = '".$Rut."'";
                $Operaciones = $db->select($SqlOperaciones);
                $Operaciones = $Operaciones[0]["NOperacion"];

                if($Operaciones == ""){
                    $ValueTmp = "('".$Rut."','".$Fono."','".$StatusName."','".$FechaHora."','".$Fecha."','".$Hora."','".$IdGestion."','".$Origen."','".$Cedente."','')";
                    //array_push($InsertValues,$ValueTmp);
                    $SqlInsert = "INSERT INTO gestion_ult_trimestre (rut_cliente,fono_discado,status_name,fechahora,fecha_gestion,hora_gestion,Id_TipoGestion,origen,cedente,factura) values ".$ValueTmp;
                    $Insert = $db->query($SqlInsert);
                    array_push($Array1000[$Cont1000],$ValueTmp);
                    $ContValues++;
                }else{
                    $ArrayOperaciones = explode(",",$Operaciones);
                    $Cont = 0;
                    foreach($ArrayOperaciones as $Factura){
                        $Hora = $this->sumarSegundoFecha($Hora);
                        $ValueTmp = "('".$Rut."','".$Fono."','".$StatusName."','".$FechaHora."','".$Fecha."','".$Hora."','".$IdGestion."','".$Origen."','".$Cedente."','".$Factura."')";
                        $SqlInsert = "INSERT INTO gestion_ult_trimestre (rut_cliente,fono_discado,status_name,fechahora,fecha_gestion,hora_gestion,Id_TipoGestion,origen,cedente,factura) values ".$ValueTmp;
                        $Insert = $db->query($SqlInsert);
                        //array_push($InsertValues,$ValueTmp);
                        array_push($Array1000[$Cont1000],$ValueTmp);
                        $ContValues++;
                        $Cont++;
                    }
                }
                if($ContValues == 1000){
                    $ContValues = 1;
                    $Cont1000++;
                    $Array1000[$Cont1000] = array();
                }
            }
            //$ArrayValuesImplode = implode(",",$InsertValues);
            /* foreach($Array1000 as $Arrays){
                $ArrayValuesImplode = implode(",",$Arrays);
                $SqlInsert = "INSERT INTO gestion_ult_trimestre (rut_cliente,fono_discado,status_name,fechahora,fecha_gestion,hora_gestion,Id_TipoGestion,origen,cedente,factura) values ".$ArrayValuesImplode;
                $Insert = $db->query($SqlInsert);
            } */
            
            if($Insert){
                $ToReturn["result"] = true;
                $ToReturn["message"] = "Gestiones guardadas satisfactoriamente";
            }
            return $ToReturn;
        }
        public function sumarSegundoFecha($fecha){
            $fecha = date($fecha);
            $fecha = strtotime($fecha) + 1;
            return date('Y-m-d H:i:s',$fecha);
        }
    }
?>