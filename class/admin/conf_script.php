<?php

require_once __DIR__.'/../db/DB.php';

class ConfScript
{
    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    function getScriptTableList(){
            // $db = new DB();
            $query = "  SELECT
                            script_cedente.id_script,
                            script_cedente.id_cedente,
                            Cedente.Nombre_Cedente
                        FROM
                            script_cedente
                        INNER JOIN 
                            Cedente 
                        ON 
                            script_cedente.id_cedente = Cedente.Id_Cedente";
            $Scripts = $this->db->select($query);
            return $Scripts;
        }
        function CrearScript($Script,$Cedente){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $query = "INSERT INTO script_cedente (script,id_cedente) VALUES ('".htmlentities($Script)."','".$Cedente."')";
            $Insert = $this->db->query($query);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getScript($idScript){
            // $db = new DB();
            $query = "SELECT * FROM script_cedente WHERE id_script = '".$idScript."'";
            $Script = $this->db->select($query);
            $Script = $Script[0];
            return $Script;
        }
        function updateScript($Script,$Cedente,$idScript){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $query = "UPDATE script_cedente SET script = '".htmlentities($Script)."', id_cedente = '".$Cedente."' WHERE id_script = '".$idScript."'";
            $Update = $this->db->query($query);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function deleteScript($idScript){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $SqlDelete = "DELETE FROM script_cedente WHERE id_script = '".$idScript."'";
            $Delete = $this->db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }else{
                $ToReturn["result"] = false;
                $ToReturn["message"] = 'Error al eliminar script_cedente';
            }
            return $ToReturn;
        }

        function getCedentesCreate(){
            // $db = new DB();
            $query = "  SELECT DISTINCT 
                            m.id_cedente as idCedente, 
                            c.Nombre_Cedente as NombreCedente 
                        FROM 
                            mandante_cedente as m, 
                            Cedente as c 
                        WHERE 
                            m.id_mandante = '".$_SESSION['mandante']."' 
                        AND 
                            c.id_cedente = m.Id_Cedente
                        AND
                            m.id_cedente NOT IN(SELECT id_cedente FROM script_cedente)";
			$cedentes = $this->db->select($query);
			return $cedentes;
        }
        
        function getCedentesUpdate($id_cedente){
            // $db = new DB();
            $query = "  SELECT DISTINCT 
                            m.id_cedente as idCedente, 
                            c.Nombre_Cedente as NombreCedente 
                        FROM 
                            mandante_cedente as m, 
                            Cedente as c 
                        WHERE 
                            m.id_mandante = '".$_SESSION['mandante']."' 
                        AND 
                            c.id_cedente = m.Id_Cedente
                        AND
                            m.id_cedente NOT IN(SELECT id_cedente FROM script_cedente WHERE id_cedente != '".$id_cedente."')";
			$cedentes = $this->db->select($query);
			return $cedentes;
        }
        
        //SCRIPT COMPLETO

        function getScriptCompletoTableList(){
            // $db = new DB();
            $query = "  SELECT
                            script_completo_cedente.id_script,
                            script_completo_cedente.id_cedente,
                            Cedente.Nombre_Cedente
                        FROM
                            script_completo_cedente
                        INNER JOIN 
                            Cedente 
                        ON 
                            script_completo_cedente.id_cedente = Cedente.Id_Cedente";
            $Scripts = $this->db->select($query);
            return $Scripts;
        }
        function CrearScriptCompleto($Script,$Cedente){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $query = "INSERT INTO script_completo_cedente (script,id_cedente) VALUES ('".htmlentities($Script)."','".$Cedente."')";
            $Insert = $this->db->query($query);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getScriptCompleto($idScript){
            // $db = new DB();
            $query = "SELECT * FROM script_completo_cedente WHERE id_script = '".$idScript."'";
            $Script = $this->db->select($query);
            $Script = $Script[0];
            return $Script;
        }
        function updateScriptCompleto($Script,$Cedente,$idScript){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $query = "UPDATE script_completo_cedente SET script = '".htmlentities($Script)."', id_cedente = '".$Cedente."' WHERE id_script = '".$idScript."'";
            $Update = $this->db->query($query);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function deleteScriptCompleto($idScript){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $SqlDelete = "DELETE FROM script_completo_cedente WHERE id_script = '".$idScript."'";
            $Delete = $this->db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }else{
                $ToReturn["result"] = false;
                $ToReturn["message"] = 'Error al eliminar script_cedente';
            }
            return $ToReturn;
        }

        function getCedentesCreateScriptCompleto(){
            // $db = new DB();
            $query = "  SELECT DISTINCT 
                            m.id_cedente as idCedente, 
                            c.Nombre_Cedente as NombreCedente 
                        FROM 
                            mandante_cedente as m, 
                            Cedente as c 
                        WHERE 
                            m.id_mandante = '".$_SESSION['mandante']."' 
                        AND 
                            c.id_cedente = m.Id_Cedente
                        AND
                            m.id_cedente NOT IN(SELECT id_cedente FROM script_completo_cedente)";
			$cedentes = $this->db->select($query);
			return $cedentes;
        }
        
        function getCedentesUpdateScriptCompleto($id_cedente){
            // $db = new DB();
            $query = "  SELECT DISTINCT 
                            m.id_cedente as idCedente, 
                            c.Nombre_Cedente as NombreCedente 
                        FROM 
                            mandante_cedente as m, 
                            Cedente as c 
                        WHERE 
                            m.id_mandante = '".$_SESSION['mandante']."' 
                        AND 
                            c.id_cedente = m.Id_Cedente
                        AND
                            m.id_cedente NOT IN(SELECT id_cedente FROM script_completo_cedente WHERE id_cedente != '".$id_cedente."')";
			$cedentes = $this->db->select($query);
			return $cedentes;
        }

        //POLITICAS

        function getPoliticaTableList(){
            // $db = new DB();
            $query = "  SELECT
                            politica_cedente.id,
                            politica_cedente.id_cedente,
                            Cedente.Nombre_Cedente
                        FROM
                            politica_cedente
                        INNER JOIN 
                            Cedente 
                        ON 
                            politica_cedente.id_cedente = Cedente.Id_Cedente";
            $Politicas = $this->db->select($query);
            return $Politicas;
        }
        function CrearPolitica($Politica,$Cedente){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $query = "INSERT INTO politica_cedente (politica,id_cedente) VALUES ('".htmlentities($Politica)."','".$Cedente."')";
            $Insert = $this->db->query($query);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getPolitica($id){
            // $db = new DB();
            $query = "SELECT * FROM politica_cedente WHERE id = '".$id."'";
            $Politica = $this->db->select($query);
            $Politica = $Politica[0];
            return $Politica;
        }
        function updatePolitica($Politica,$Cedente,$id){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $query = "UPDATE politica_cedente SET politica = '".htmlentities($Politica)."', id_cedente = '".$Cedente."' WHERE id = '".$id."'";
            $Update = $this->db->query($query);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function deletePolitica($id){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $SqlDelete = "DELETE FROM politica_cedente WHERE id = '".$id."'";
            $Delete = $this->db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }else{
                $ToReturn["result"] = false;
                $ToReturn["message"] = 'Error al eliminar politica_cedente';
            }
            return $ToReturn;
        }

        function getCedentesCreatePolitica(){
            // $db = new DB();
            $query = "  SELECT DISTINCT 
                            m.id_cedente as idCedente, 
                            c.Nombre_Cedente as NombreCedente 
                        FROM 
                            mandante_cedente as m, 
                            Cedente as c 
                        WHERE 
                            m.id_mandante = '".$_SESSION['mandante']."' 
                        AND 
                            c.id_cedente = m.Id_Cedente
                        AND
                            m.id_cedente NOT IN(SELECT id_cedente FROM politica_cedente)";
			$cedentes = $this->db->select($query);
			return $cedentes;
        }
        
        function getCedentesUpdatePolitica($id_cedente){
            // $db = new DB();
            $query = "  SELECT DISTINCT 
                            m.id_cedente as idCedente, 
                            c.Nombre_Cedente as NombreCedente 
                        FROM 
                            mandante_cedente as m, 
                            Cedente as c 
                        WHERE 
                            m.id_mandante = '".$_SESSION['mandante']."' 
                        AND 
                            c.id_cedente = m.Id_Cedente
                        AND
                            m.id_cedente NOT IN(SELECT id_cedente FROM politica_cedente WHERE id_cedente != '".$id_cedente."')";
			$cedentes = $this->db->select($query);
			return $cedentes;
        }

        //MEDIOS DE PAGO

        function getMedioPagoTableList(){
            // $db = new DB();
            $query = "  SELECT
                            medio_pago_cedente.id,
                            medio_pago_cedente.id_cedente,
                            Cedente.Nombre_Cedente
                        FROM
                            medio_pago_cedente
                        INNER JOIN 
                            Cedente 
                        ON 
                            medio_pago_cedente.id_cedente = Cedente.Id_Cedente";
            $Politicas = $this->db->select($query);
            return $Politicas;
        }
        function CrearMedioPago($MedioPago,$Cedente){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $query = "INSERT INTO medio_pago_cedente (medio_pago,id_cedente) VALUES ('".htmlentities($MedioPago)."','".$Cedente."')";
            $Insert = $this->db->query($query);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getMedioPago($id){
            // $db = new DB();
            $query = "SELECT * FROM medio_pago_cedente WHERE id = '".$id."'";
            $MedioPago = $this->db->select($query);
            $MedioPago = $MedioPago[0];
            return $MedioPago;
        }
        function updateMedioPago($MedioPago,$Cedente,$id){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $query = "UPDATE medio_pago_cedente SET medio_pago = '".htmlentities($MedioPago)."', id_cedente = '".$Cedente."' WHERE id = '".$id."'";
            $Update = $this->db->query($query);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function deleteMedioPago($id){
            $ToReturn = array();
            $ToReturn["result"] = false;
            // $db = new DB();
            $SqlDelete = "DELETE FROM medio_pago_cedente WHERE id = '".$id."'";
            $Delete = $this->db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }else{
                $ToReturn["result"] = false;
                $ToReturn["message"] = 'Error al eliminar medio_pago_cedente';
            }
            return $ToReturn;
        }

        function getCedentesCreateMedioPago(){
            // $db = new DB();
            $query = "  SELECT DISTINCT 
                            m.id_cedente as idCedente, 
                            c.Nombre_Cedente as NombreCedente 
                        FROM 
                            mandante_cedente as m, 
                            Cedente as c 
                        WHERE 
                            m.id_mandante = '".$_SESSION['mandante']."' 
                        AND 
                            c.id_cedente = m.Id_Cedente
                        AND
                            m.id_cedente NOT IN(SELECT id_cedente FROM medio_pago_cedente)";
			$cedentes = $this->db->select($query);
			return $cedentes;
        }
        
        function getCedentesUpdateMedioPago($id_cedente){
            // $db = new DB();
            $query = "  SELECT DISTINCT 
                            m.id_cedente as idCedente, 
                            c.Nombre_Cedente as NombreCedente 
                        FROM 
                            mandante_cedente as m, 
                            Cedente as c 
                        WHERE 
                            m.id_mandante = '".$_SESSION['mandante']."' 
                        AND 
                            c.id_cedente = m.Id_Cedente
                        AND
                            m.id_cedente NOT IN(SELECT id_cedente FROM medio_pago_cedente WHERE id_cedente != '".$id_cedente."')";
			$cedentes = $this->db->select($query);
			return $cedentes;
        }
        
    }
?>