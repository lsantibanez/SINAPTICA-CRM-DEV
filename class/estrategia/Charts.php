<?php
    class Charts{

        public function mostrarTorta($tabla,$cedente,$lista)
        {
            $db = new DB();
            $this->tabla=$tabla;
            $this->cedente=$cedente;
            $this->lista=$lista;
            if($this->lista==-1)
            {
                $ToReturn = "";
                $ArrayGestiones = array(); 
                $ContArrayGestiones = 0;
                $q1 = $db->select("SELECT Id,Respuesta_N1 FROM Nivel1 WHERE FIND_IN_SET('$this->cedente',Id_Cedente)");
                $num = count($q1);
                $cant = 0;
                $SumCant = 0;
                foreach($q1 as $Result1){
                    $Gestion = array();
                    $nombre = $Result1["Respuesta_N1"];
                    $registros = $Result1["Id"];
                    $q2 = $db->select("SELECT Rut FROM Ultima_Gestion WHERE Respuesta_N1 = $registros AND Id_Cedente = $this->cedente ");
                    $cant =  count($q2);
                    $Gestion = array();
                    $Gestion["label"] = $nombre;
                    $Gestion["data"] = $cant;
                    $ArrayGestiones[$ContArrayGestiones] = $Gestion;
                    $ContArrayGestiones++;
                    $SumCant += $cant;
                }
                if($SumCant > 0){
                    $ToReturn = json_encode($ArrayGestiones);
                }else{
                    $ToReturn = "";
                }
                echo $ToReturn;
            }  
            else
            {  
                $ToReturn = "";
                $ArrayGestiones = array(); 
                $ContArrayGestiones = 0;
                $q1 = $db->select("SELECT Id,Respuesta_N1 FROM Nivel1 WHERE FIND_IN_SET('$this->cedente',Id_Cedente)");
                $num = count($q1);
                $cant = 0;
                $SumCant = 0;
                foreach($q1 as $Result1){
                    $Gestion = array();
                    $nombre = $Result1["Respuesta_N1"];
                    $registros = $Result1["Id"];
                    $q2 = $db->select("SELECT Rut FROM Ultima_Gestion WHERE Respuesta_N1 = $registros AND Id_Cedente = $this->cedente ");
                    $cant =  count($q2);
                    $Gestion = array();
                    $Gestion["label"] = $nombre;
                    $Gestion["data"] = $cant;
                    $ArrayGestiones[$ContArrayGestiones] = $Gestion;
                    $ContArrayGestiones++;
                    $SumCant += $cant;
                }
                 if($SumCant > 0){
                    $ToReturn = json_encode($ArrayGestiones);
                 }else{
                     $ToReturn = "";
                 }
                 echo $ToReturn;
            }     
        }
        public function mostrarTorta2($tabla,$cedente,$lista,$id)
        {
            $db = new DB();
            $this->tabla=$tabla;
            $this->cedente=$cedente;
            $this->lista=$lista;
            $this->id=$id;
            if($this->lista==-1)
            {
                $ToReturn = "";
                $ArrayGestiones = array(); 
                $ContArrayGestiones = 0;
                $q1 = $db->select("SELECT Id,Respuesta_N2  FROM Nivel2 WHERE Id_Nivel1 = $id");
                $num = count($q1);
                $cant = 0;
                $SumCant = 0;
                foreach($q1 as $Result1){
                    $Gestion = array();
                    $nombre = $Result1["Respuesta_N2"];
                    //$nombre = '';
                    $registros = $Result1["Id"];
                    $q2 = $db->select("SELECT Rut FROM Ultima_Gestion WHERE Respuesta_N2 = $registros AND Id_Cedente = $this->cedente ");
                    $cant =  count($q2);
                    $Gestion = array();
                    $Gestion["label"] = $nombre;
                    $Gestion["data"] = $cant;
                    $ArrayGestiones[$ContArrayGestiones] = $Gestion;
                    $ContArrayGestiones++;
                    $SumCant += $cant;
                }
                if($SumCant > 0){
                    $ToReturn = json_encode($ArrayGestiones);
                }else{
                    $ToReturn = "";
                }
                echo $ToReturn;
            }
            else
            {    
                $ToReturn = "";
                $ArrayGestiones = array(); 
                $ContArrayGestiones = 0;
                $q1 = $db->select("SELECT Id,Respuesta_N2  FROM Nivel2 WHERE Id_Nivel1 = $id");
                $num = count($q1);
                $cant = 0;
                $SumCant = 0;
                foreach($q1 as $Result1){
                    $Gestion = array();
                    $nombre = $Result1["Respuesta_N2"];
                    //$nombre = '';
                    $registros = $Result1["Id"];
                    $q2 = $db->select("SELECT Rut FROM Ultima_Gestion WHERE Respuesta_N2 = $registros AND Id_Cedente = $this->cedente ");
                    $cant =  count($q2);
                    $Gestion = array();
                    $Gestion["label"] = $nombre;
                    $Gestion["data"] = $cant;
                    $ArrayGestiones[$ContArrayGestiones] = $Gestion;
                    $ContArrayGestiones++;
                    $SumCant += $cant;
                }
                if($SumCant > 0){
                    $ToReturn = json_encode($ArrayGestiones);
                }else{
                    $ToReturn = "";
                }
                echo $ToReturn;
            }      
        }
        public function mostrarTorta3($tabla,$cedente,$lista,$id)
        {
            $db = new DB();
            $this->tabla=$tabla;
            $this->cedente=$cedente;
            $this->lista=$lista;
            $this->id=$id;
            if($this->lista==-1)
            {
                $ToReturn = "";
                $ArrayGestiones = array(); 
                $ContArrayGestiones = 0;
                $q1 = $db->select("SELECT Id,Respuesta_N3  FROM Nivel3 WHERE Id_Nivel2 = $id");
                $num = count($q1);
                $cant = 0;
                $SumCant = 0;
                foreach($q1 as $Result1){
                    $Gestion = array();
                    $nombre = $Result1["Respuesta_N3"];
                    //$nombre = '';
                    $registros = $Result1["Id"];
                    $q2 = $db->select("SELECT Rut FROM Ultima_Gestion WHERE Respuesta_N3 = $registros AND Id_Cedente = $this->cedente ");
                    $cant =  count($q2);
                    $Gestion = array();
                    $Gestion["label"] = $nombre;
                    $Gestion["data"] = $cant;
                    $ArrayGestiones[$ContArrayGestiones] = $Gestion;
                    $ContArrayGestiones++;
                    $SumCant += $cant;
                }
                if($SumCant > 0){
                    $ToReturn = json_encode($ArrayGestiones);
                }else{
                    $ToReturn = "";
                }
                echo $ToReturn;
            }
            else
            {    
                $ToReturn = "";
                $ArrayGestiones = array(); 
                $ContArrayGestiones = 0;
                $q1 = $db->select("SELECT Id,Respuesta_N3  FROM Nivel3 WHERE Id_Nivel2 = $id");
                $num = count($q1);
                $cant = 0;
                $SumCant = 0;
                foreach($q1 as $Result1){
                    $Gestion = array();
                    $nombre = $Result1["Respuesta_N3"];
                    //$nombre = '';
                    $registros = $Result1["Id"];
                    $q2 = $db->select("SELECT Rut FROM Ultima_Gestion WHERE Respuesta_N3 = $registros AND Id_Cedente = $this->cedente ");
                    $cant =  count($q2);
                    $Gestion = array();
                    $Gestion["label"] = $nombre;
                    $Gestion["data"] = $cant;
                    $ArrayGestiones[$ContArrayGestiones] = $Gestion;
                    $ContArrayGestiones++;
                    $SumCant += $cant;
                }
                if($SumCant > 0){
                    $ToReturn = json_encode($ArrayGestiones);
                }else{
                    $ToReturn = "";
                }
                echo $ToReturn;
            }    
        }
    }
?>