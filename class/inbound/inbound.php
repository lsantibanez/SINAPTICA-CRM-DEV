<?php
    class Inbound{
        function getColasInbound($idMandante){
            $db = new DB();
            $SqlColas = "select * from Asterisk_Inbound_Cola where Id_Mandante='".$idMandante."'";
            $Colas = $db->select($SqlColas);
            return $Colas;
        }
        function getInboundDataExtension($Anexo){
            $db = new DB();
            $dbDiscador = new DB("discador");
            $ToReturn = array();
            $ToReturn["result"] = false;
            $ToReturn["Data"] = array();
            $SqlAgentes = "Select * from Asterisk_Agentes_Inbound where Agente='SIP/".$Anexo."'";
            $Agentes = $dbDiscador->select($SqlAgentes);
            if(count($Agentes) > 0){
                $ToReturn["result"] = true;
                $ToReturn["Data"]["Pausa"] = $Agentes[0]["Pausa"];
                
                $ToReturn["Data"]["Cola"] = $Agentes[0]["Queue"];

                $SqlColas = "select * from Asterisk_Inbound_Cola where Queue='".$Agentes[0]["Queue"]."'";
                $Colas = $db->select($SqlColas);
                if(count($Colas) > 0){
                    $ToReturn["Data"]["Cola"] .= " - " .$Colas[0]["Descripcion"];
                }

                $ToReturn["Data"]["isTalking"] = false;
                $SqlBridge = "select * from Asterisk_Bridge_Inbound where Anexo='".$Anexo."' and Queue='".$Agentes[0]["Queue"]."'";
                $Bridge = $dbDiscador->select($SqlBridge);
                if(count($Bridge) > 0){
                    $ToReturn["Data"]["isTalking"] = true;
                    $ToReturn["Data"]["Channel"] = $Bridge[0]["Channel"];
                    $ToReturn["Data"]["DestChannel"] = $Bridge[0]["DestChannel"];
                }
            }else{
                $ToReturn["Data"]["Cola"] = "Desconectado";
            }
            return $ToReturn;
        }
        function pauseInbound($Anexo,$Pausa){
            $dbDiscador = new DB("discador");
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlUpdate = "update Asterisk_Agentes_Inbound set Pausa='".$Pausa."' where Agente='SIP/".$Anexo."'";
            $Update = $dbDiscador->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function insertPersonaInbound($Rut,$Nombre,$Telefono,$Correo,$Direccion,$Comuna,$Ciudad){
            $db = new DB();
            $ToReturn = array();
            $Mandante = $_SESSION['mandante'];
            $Cedente = $_SESSION['cedente'];
            $Dv = $this->obtenerDv($Rut);
            $query = "INSERT IGNORE INTO Persona (Rut,Digito_Verificador,Nombre_Completo,Id_Cedente,Mandante) VALUES ('".$Rut."','".$Dv."','".$Nombre."','".$Cedente."','".$Mandante."') ON DUPLICATE KEY UPDATE Id_Cedente = CONCAT(Id_Cedente,',".$Cedente."'), Mandante = CONCAT(Mandante,',".$Mandante."')";
            $id_persona = $db->insert($query);
            $dt = new DateTime();
            $Fecha = $dt->format('Ymd');
            $query = "INSERT INTO fono_cob (Rut,formato_subtel,vigente,color,cedente) VALUES ('".$Rut."','".$Telefono."','1','35','".$Cedente."') ON DUPLICATE KEY UPDATE cedente = CONCAT(cedente , ',' ,'".$Cedente."_".$Fecha."'), color = 35, fecha_carga = NOW()";
            $db->insert($query);
            if($Correo){
                $query = "INSERT IGNORE INTO Mail (Rut,correo_electronico) VALUES ('".$Rut."','".$Correo."')";
                $db->insert($query);
            }
            if($Direccion OR $Comuna OR $Ciudad){
                $query = "INSERT IGNORE INTO Direcciones (Rut,Direccion,Comuna,Ciudad) VALUES ('".$Rut."','".$Direccion."','".$Comuna."','".$Ciudad."')";
                $db->insert($query);
            }     
            return $Rut;
        }
        function obtenerDv($_rol) {
			/* Bonus: remuevo los ceros del comienzo. */
			while($_rol[0] == "0") {
				$_rol = substr($_rol, 1);
			}
			$factor = 2;
			$suma = 0;
			for($i = strlen($_rol) - 1; $i >= 0; $i--) {
				$suma += $factor * $_rol[$i];
				$factor = $factor % 7 == 0 ? 2 : $factor + 1;
			}
			$dv = 11 - $suma % 11;
			/* Por alguna razón me daba que 11 % 11 = 11. Esto lo resuelve. */
			$dv = $dv == 11 ? 0 : ($dv == 10 ? "K" : $dv);
			return $dv;
		}
    }
?>