<?php
    class proveedoresDiscado{
        function __construct(){
            if(class_exists("DB")){
                $FocoConfig = $this->getFocoConfig();
                $db = new Db;

                if(!$db->isLocalhost()){
                    $this->IpServidorDiscado = $FocoConfig['IpServidorDiscado'];
                }else{
                    $this->IpServidorDiscado = $FocoConfig['IpServidorDiscadoAux'];
                }
                
                $this->IpServidorDiscadoAux = $FocoConfig['IpServidorDiscadoAux'];
            }
        }

        function getProveedores($CodigoFoco){
            //create curl resource
            
            $ch = curl_init();
            //set url
            curl_setopt($ch, CURLOPT_URL, "http://".$this->IpServidorDiscado."/includes/proveedores/getProveedoresDiscado-webService.php");
            //setup post Variables
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"CodigoFoco=".$CodigoFoco);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //$output contains the output string 
            $output = curl_exec($ch);
            echo $output;
            //close curl resource to free up system resources
            echo curl_error($ch);
            curl_close($ch);
        }
        function insertNewProveedor($CodigoFoco,$CodigoProveedor,$NombreProveedor,$ProviderRules,$DialPlan){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $ch = curl_init();
            //set url
            curl_setopt($ch, CURLOPT_URL, "http://".$this->IpServidorDiscado."/includes/proveedores/insertProveedorDiscado-webService.php");
            //setup post Variables
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"CodigoFoco=".$CodigoFoco."&CodigoProveedor=".$CodigoProveedor."&NombreProveedor=".$NombreProveedor."&ProviderRules=".$ProviderRules."&DialPlan=".$DialPlan);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //$output contains the output string 
            $output = curl_exec($ch);
            echo $output;
            //close curl resource to free up system resources
            curl_close($ch);
            $asm = new AGI_AsteriskManager();
            $asm->connect($this->IpServidorDiscado,"lponce","lponce");
            $ChannelsReponse = $asm->command("reload");
        }
        function deseleccionarProveedor($CodigoFoco){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $ch = curl_init();
            //set url
            curl_setopt($ch, CURLOPT_URL, "http://".$this->IpServidorDiscado."/includes/proveedores/deseleccionarProveedorDiscado-webService.php");
            //setup post Variables
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"CodigoFoco=".$CodigoFoco);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //$output contains the output string 
            $output = curl_exec($ch);
            echo $output;
            //close curl resource to free up system resources
            curl_close($ch);
            //return $ToReturn;
        }
        function seleccionarProveedor($CodigoFoco,$idProveedor){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $ch = curl_init();
            //set url
            curl_setopt($ch, CURLOPT_URL, "http://".$this->IpServidorDiscado."/includes/proveedores/seleccionarProveedorDiscado-webService.php");
            //setup post Variables
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"CodigoFoco=".$CodigoFoco."&idProveedor=".$idProveedor);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //$output contains the output string 
            $output = curl_exec($ch);
            echo $output;
            //close curl resource to free up system resources
            curl_close($ch);
            //return $ToReturn;
        }
        function deleteProveedor($idProveedor,$CodigoFoco){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $ch = curl_init();
            //set url
            curl_setopt($ch, CURLOPT_URL, "http://".$this->IpServidorDiscado."/includes/proveedores/deleteProveedorDiscado-webService.php");
            //setup post Variables
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"idProveedor=".$idProveedor."&codigoFoco=".$CodigoFoco);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //$output contains the output string 
            $output = curl_exec($ch);
            echo $output;
            //close curl resource to free up system resources
            curl_close($ch);
            $asm = new AGI_AsteriskManager();
            $asm->connect($this->IpServidorDiscado,"lponce","lponce");
            $ChannelsReponse = $asm->command("reload");
        }
        function getProveedor($idProveedor){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $ch = curl_init();
            //set url
            curl_setopt($ch, CURLOPT_URL, "http://".$this->IpServidorDiscado."/includes/proveedores/getProveedorDiscado-webService.php");
            //setup post Variables
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"idProveedor=".$idProveedor);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //$output contains the output string 
            $output = curl_exec($ch);
            echo $output;
            //close curl resource to free up system resources
            curl_close($ch);
            //return $ToReturn;
        }
        function updateProveedor($CodigoFoco,$idProveedor,$CodigoProveedor,$NombreProveedor,$ProviderRules,$DialPlan){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $ch = curl_init();
            //set url
            curl_setopt($ch, CURLOPT_URL, "http://".$this->IpServidorDiscado."/includes/proveedores/updateProveedorDiscado-webService.php");
            //setup post Variables
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"idProveedor=".$idProveedor."&CodigoProveedor=".$CodigoProveedor."&NombreProveedor=".$NombreProveedor."&ProviderRules=".$ProviderRules."&DialPlan=".$DialPlan."&codigoFoco=".$CodigoFoco);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //$output contains the output string 
            $output = curl_exec($ch);
            echo $output;
            //close curl resource to free up system resources
            curl_close($ch);
            $asm = new AGI_AsteriskManager();
            $asm->connect($this->IpServidorDiscado,"lponce","lponce");
            $ChannelsReponse = $asm->command("reload");
        }

        function GetExtensionDisponibleDiscado($CodigoFoco,$Username,$idUsuario){
            $ToReturn = array();
            //create curl resource
            $ch = curl_init();
            //set url
            curl_setopt($ch, CURLOPT_URL, "http://".$this->IpServidorDiscado."/includes/proveedores/getExtensionDisponibleDiscado-webService.php");
            //setup post Variables
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"CodigoFoco=".$CodigoFoco."&Username=".$Username."&idUsuario=".$idUsuario);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //$output contains the output string 
            $output = curl_exec($ch);
            $ToReturn = $output;
            //close curl resource to free up system resources
            curl_close($ch);
            $asm = new AGI_AsteriskManager();
            $asm->connect($this->IpServidorDiscado,"lponce","lponce");
            $ChannelsReponse = $asm->command("reload");
            return $ToReturn;
        }

        function GetServerStatus($CodigoFoco){
            
            $ToReturn = array();
            $ToReturn["result"] = false;

            if(trim($this->IpServidorDiscado)){
                //create curl resource
                $ch = curl_init();
                //set url
                curl_setopt($ch, CURLOPT_URL, "http://".$this->IpServidorDiscado."/includes/server/getServerStatus-webService.php");
                //setup post Variables
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,"CodigoFoco=".$CodigoFoco);
                //return the transfer as a string
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                //$output contains the output string 
                $output = curl_exec($ch);
                
                if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200){
                    $ToReturn = json_decode($output);
                }else if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == 404){
                    $ToReturn["message"] = "Error 404";
                }else{
                    $ToReturn["message"] = "Error: ".curl_error($ch);
                }
                //close curl resource to free up system resources
                curl_close($ch);
            }else{
                $ToReturn["message"] = "No posee una dirección ip configurada";
            }

            return $ToReturn;
        }
        
        function getIpServidorDiscado(){
            $ToReturn = array();
            $ToReturn["IpServidorDiscado"] = $this->IpServidorDiscado;
            $ToReturn["IpServidorDiscadoAux"] = $this->IpServidorDiscadoAux;
            return $ToReturn;
        }
        function updateIpServidorDiscado($IpServidorDiscado,$IpServidorDiscadoAux){
            $db = new Db();
            $ToReturn = false;
            $SqlUpdate = "update fireConfig set IpServidorDiscado = '".$IpServidorDiscado."',IpServidorDiscadoAux = '".$IpServidorDiscadoAux."'";
            $Update = $db -> query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getFocoConfig(){
            $db = new DB();
            $SqlFocoConfig = "select * from fireConfig";
            $FocoConfig = $db->select($SqlFocoConfig);
            return $FocoConfig[0];
        }
        function UpdateExtensionDiscado($CodigoFoco,$Username,$idUsuario){
            $ToReturn = array();
            //create curl resource
            $ch = curl_init();
            //set url
            curl_setopt($ch, CURLOPT_URL, "http://".$this->IpServidorDiscado."/includes/proveedores/updateExtensionDiscado-webService.php");
            //setup post Variables
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"CodigoFoco=".$CodigoFoco."&Username=".$Username."&idUsuario=".$idUsuario);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //$output contains the output string 
            $output = curl_exec($ch);
            $ToReturn = $output;
            //close curl resource to free up system resources
            curl_close($ch);
            $asm = new AGI_AsteriskManager();
            $asm->connect($this->IpServidorDiscado,"lponce","lponce");
            $ChannelsReponse = $asm->command("reload");
            return $ToReturn;
        }
        function DeleteExtensionDiscado($CodigoFoco,$idUsuario){
            $ToReturn = array();
            //create curl resource
            $ch = curl_init();
            //set url
            curl_setopt($ch, CURLOPT_URL, "http://".$this->IpServidorDiscado."/includes/proveedores/deleteExtensionDiscado-webService.php");
            //setup post Variables
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"CodigoFoco=".$CodigoFoco."&idUsuario=".$idUsuario);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //$output contains the output string 
            $output = curl_exec($ch);
            $ToReturn = $output;
            //close curl resource to free up system resources
            curl_close($ch);
            $asm = new AGI_AsteriskManager();
            $asm->connect($this->IpServidorDiscado,"lponce","lponce");
            $ChannelsReponse = $asm->command("reload");
            return $ToReturn;
        }
    }
?>