<?php
//include_once("../../includes/functions/Functions.php");
//QueryPHP_IncludeClasses("db");
require_once __DIR__.'/../db/DB.php';
header('Content-type: application/json; charset=utf-8');

class Nivel
{
    //NIVEL 1
    private $Id;
    private $Nivel2;
    private $Nivel1;
    private $Nivel3;
    private $Nombre;
    private $Id_TipoGestion;
    private $Ponderacion;
    private $Peso;
    private $db;
    private $idCedente;

    public function __construct()
    {
        $this->db = new Db;
        $this->idCedente = (int) $_SESSION['cedente'];
    }

    public function storeNivel1($Nombre)
    {
        $response_array = [];
        $Nombre = isset($Nombre) ? trim($Nombre) : "";
        if(!empty($Nombre)){
            $this->Nombre = $Nombre;
            $query = "INSERT INTO Nivel1(Respuesta_N1, Id_Cedente) VALUES ('{$this->Nombre}','{$this->idCedente}')";
            //$db = new Db;
            $id = $this->db->insert($query);
            if($id) {
                $response_array['array'] = [
                    'id' => $id, 
                    'nombre' => $this->Nombre
                ];
                $response_array['status'] = 1; 
            } else {
                $response_array['status'] = 0; 
            }
        }else{
            $response_array['status'] = 2; 
        }

        echo json_encode($response_array);
    } 

    public function updateNivel1($Nombre, $Id)
    {
        $response_array = array();
        $Nombre = isset($Nombre) ? trim($Nombre) : "";

        if(!empty($Nombre)) {
            $this->Id = (int) $Id;
            $this->Nombre = $Nombre;

            $query = "UPDATE Nivel1 SET Respuesta_N1 = '".$this->Nombre."' WHERE Id = '".$this->Id."'";
            //$db = new Db;
            $update = $this->db->query($query);

            if($update) {
                $response_array['array'] = [ 
                    'nombre' => $this->Nombre,
                    'id' => $this->Id
                ];
                $response_array['status'] = 1;
            } else {
                $response_array['status'] = 0; 
            }
        } else {
            $response_array['status'] = 2; 
        }
        echo json_encode($response_array);
    }

    public function deleteNivel1($Id)
    {
        $response_array = array();
        $Id = isset($Id) ? trim($Id) : '';

        if(!empty($Id))
        {
            $this->Id = (int) $Id;
            $query = "SELECT * FROM Nivel2 WHERE Id_Nivel1 = '{$this->Id}'";
            //$db = new Db;
            $select = $this->db->select($query);
            if(!$select){
                $query = "DELETE FROM Nivel1 WHERE Id = '{$this->Id}'";
                $data = $this->db->query($query);
                if($data){
                    $response_array['status'] = 1; 
                }else{
                    $response_array['status'] = 0; 
                }
            }else{
                $response_array['status'] = 3; 
            }
        }else{
            $response_array['status'] = 2; 
        }
        echo json_encode($response_array);
    }

    public function showNivel1()
    {
        $query = "SELECT Id AS id, Respuesta_N1 AS nombre FROM Nivel1 WHERE Id_Cedente = '{$this->idCedente}';";
        //$db = new Db;
        $select = $this->db->select($query);
        $array = [];
        if($select) {
            $array = (array) $select;
            /*
            foreach($select as $row){
                $arreglo = array();
                $arreglo['id'] = $row['id'];
                $arreglo['nombre'] = utf8_encode($row['nombre']);
                array_push($array,$arreglo);
            }
            */
        }

        $response_array['array'] = $array;
        echo json_encode($response_array);
    }

    //NIVEL 2
    public function storeNivel2($Nivel1, $Nombre)
    {
        $response_array = array();
        $Nivel1 = isset($Nivel1) ? trim($Nivel1) : "";
        $Nombre = isset($Nombre) ? trim($Nombre) : "";

        if(!empty($Nivel1) && !empty($Nombre)){
            $this->Nivel1 = $Nivel1;
            $this->Nombre=$Nombre;
            $query = "INSERT INTO Nivel2(Id_Nivel1, Respuesta_N2) VALUES ('".$this->Nivel1."','".$this->Nombre."')";
            //$db = new Db;
            $id = $this->db->insert($query);
            if($id){
                $query = "SELECT Respuesta_N1 FROM Nivel1 WHERE Id = '".$this->Nivel1."'";
                $select = $this->db->select($query);
                if($select){
                    $nivel_1 = $select[0]['Respuesta_N1'];
                }else{
                    $nivel_1 = '';
                }

                $array = array('id' => $id, 'id_nivel_1' => $this->Nivel1, 'nivel_1' => $nivel_1, 'nombre' => $this->Nombre);
                $response_array['array'] = $array;
                $response_array['status'] = 1; 
            }else{
                $response_array['status'] = 0; 
            }
        }else{
            $response_array['status'] = 2; 
        }
        echo json_encode($response_array);
    } 


    public function updateNivel2($Nivel1, $Nombre, $Id)
    {
        $response_array = array();
        $Nivel1 = isset($Nivel1) ? trim($Nivel1) : "";
        $Nombre = isset($Nombre) ? trim($Nombre) : "";

        if(!empty($Nivel1) && !empty($Nombre)){
            $this->Id = (int) $Id;
            $this->Nivel1 = (int) $Nivel1;
            $this->Nombre = $Nombre;

            $query = "UPDATE Nivel2 SET Id_Nivel1 = '".$this->Nivel1."', Respuesta_N2 = '".$this->Nombre."' WHERE Id = '".$this->Id."'";
            //$db = new Db;
            $update = $this->db->query($query);

            if($update) {
                $query = "SELECT Respuesta_N1 FROM Nivel1 WHERE Id = '".$this->Nivel1."'";
                $select = $this->db->select($query);
                if($select){
                    $nivel_1 = $select[0]['Respuesta_N1'];
                }else{
                    $nivel_1 = '';
                }
                $response_array['array'] = [
                    'id_nivel_1' => $this->Nivel1, 
                    'nivel_1' => $nivel_1, 
                    'nombre' => $this->Nombre, 
                    'id' => $this->Id
                ];
                $response_array['status'] = 1; 
            }else{
                $response_array['status'] = 0; 
            }
        }else{
            $response_array['status'] = 2; 
        }
        echo json_encode($response_array);
    }

    public function deleteNivel2($Id)
    {
        $response_array = array();
        $Id = isset($Id) ? trim($Id) : "";
        if(!empty($Id)) {
            $this->Id = (int) $Id;
            $query = "SELECT * FROM Nivel3 WHERE Id_Nivel2 = '".$this->Id."'";
            //$db = new Db;
            $select = $this->db->select($query);
            if(!$select) {
                $query = "DELETE FROM Nivel2 WHERE Id = '".$this->Id."'";
                $data = $this->db->query($query);
                if($data){
                    $response_array['status'] = 1; 
                }else{
                    $response_array['status'] = 0; 
                }
            }else{
                $response_array['status'] = 3; 
            }
        }else{
            $response_array['status'] = 2; 
        }
        echo json_encode($response_array);
    }

    public function showNivel2()
    {
        $query = "SELECT 
                    Nivel2.Id AS id, 
                    Nivel2.Respuesta_N2 AS nombre,
                    Nivel2.Id_Nivel1 AS id_nivel_1,
                    Nivel1.Respuesta_N1 AS nivel_1
                  FROM 
                    Nivel2
                  INNER JOIN 
                    Nivel1 
                  ON 
                    Nivel2.Id_Nivel1 = Nivel1.Id
                  WHERE 
                    Nivel1.Id_Cedente = '{$this->idCedente}'";
            //$db = new Db;
        $select = $this->db->select($query);
        $array = [];
        if($select) {
            $array = (array) $select;
                /*
                foreach((array) $select as $row){
                    $arreglo = array();
                    $arreglo['id'] = $row['id'];
                    $arreglo['nombre'] = utf8_encode($row['nombre']);
                    $arreglo['id_nivel_1'] = $row['id_nivel_1'];
                    $arreglo['nivel_1'] = utf8_encode($row['nivel_1']);
                    array_push($array,$arreglo);
                }
                */
        }
        $response_array['array'] = $array;
        echo json_encode($response_array);
    }

        //NIVEL 3
    public function storeNivel3($Nivel2, $Nombre, $Id_TipoGestion, $Ponderacion, $Peso)
    {
        $response_array = array();
        $Nivel2 = isset($Nivel2) ? trim($Nivel2) : "0";
        $Nombre = isset($Nombre) ? trim($Nombre) : "";
        $Id_TipoGestion = isset($Id_TipoGestion) ? trim($Id_TipoGestion) : "0";
        $Ponderacion = isset($Ponderacion) ? trim($Ponderacion) : "0";
        $Peso = isset($Peso) ? trim($Peso) : "0";

        if(($Nivel2 != "") && ($Nombre != "") && ($Id_TipoGestion != "") && ($Peso != "")){
            $this->Nivel2 = $Nivel2;
            $this->Nombre = $Nombre;
            $this->Id_TipoGestion = $Id_TipoGestion;
            $this->Ponderacion = $Ponderacion;
            $this->Peso = $Peso;

            $query = "INSERT INTO Nivel3(Id_Nivel2, Respuesta_N3, Id_TipoGestion, Ponderacion, Peso) VALUES ('".$this->Nivel2."','".$this->Nombre."','".$this->Id_TipoGestion."','".$this->Ponderacion."','".$this->Peso."')";
            //$db = new Db;
            $id = $this->db->insert($query);
            if($id){
                $query = "SELECT Id_Nivel1, Respuesta_N2 FROM Nivel2 WHERE Id = '".$this->Nivel2."'";
                $select = $this->db->select($query);
                if($select){
                    $nivel_2 = $select[0]['Respuesta_N2'];
                    $query = "SELECT Respuesta_N1 FROM Nivel1 WHERE Id = '".$select[0]['Id_Nivel1']."'";
                    $select = $this->db->select($query);
                    $nivel_1 = $select[0]['Respuesta_N1'];
                    if(!$select) $nivel_1 = '';
                }else{
                    $nivel_2 = '';
                }
                $query = "SELECT Nombre FROM Tipo_Contacto WHERE Id_TipoContacto = '".$this->Id_TipoGestion."'";
                $select = $this->db->select($query);
                if($select){
                    $TipoContacto = $select[0]['Nombre'];
                }else{
                    $TipoContacto = '';
                }
                $response_array['array'] = [
                    'id' => (int) $id, 
                    'id_nivel_2' => (int) $this->Nivel2, 
                    'nivel_1' => $nivel_1,
                    'nivel_2' => $nivel_2,
                    'nombre'  => $this->Nombre,
                    'TipoContacto' => $TipoContacto,
                    'Ponderacion' => (int) $this->Ponderacion, 
                    'Peso' => (int) $this->Peso
                ];
                $response_array['status'] = 1; 
            }else{
                $response_array['status'] = 0; 
            }
        }else{
            $response_array['status'] = 2; 
        }
        echo json_encode($response_array);
    } 


    public function updateNivel3($Nivel2, $Nombre, $Id_TipoGestion, $Ponderacion, $Peso, $Id)
    {
        $response_array = array();
        $Nivel2 = isset($Nivel2) ? trim($Nivel2) : "";
        $Nombre = isset($Nombre) ? trim($Nombre) : "";
        $Id_TipoGestion = isset($Id_TipoGestion) ? trim($Id_TipoGestion) : "";
        $Ponderacion = isset($Ponderacion) ? trim($Ponderacion) : "";
        $Peso = isset($Peso) ? trim($Peso) : "";

        if(!empty($Nivel2) && !empty($Nombre) && !empty($Id_TipoGestion) && !empty($Peso)){
            $this->Id=$Id;
            $this->Nivel2=$Nivel2;
            $this->Nombre=$Nombre;
            $this->Id_TipoGestion=$Id_TipoGestion;
            $this->Ponderacion=$Ponderacion;
            $this->Peso=$Peso;

            $query = "UPDATE Nivel3 SET Id_Nivel2 = '".$this->Nivel2."', Respuesta_N3 = '".$this->Nombre."' WHERE Id = '".$this->Id."'";
            //$db = new Db;
            $update = $this->db->query($query);
            if($update){
                $query = "SELECT Respuesta_N2 FROM Nivel2 WHERE Id = '".$this->Nivel2."'";
                $select = $this->db->select($query);
                if($select){
                    $nivel_2 = $select[0]['Respuesta_N2'];
                }else{
                    $nivel_2 = '';
                }
                $query = "SELECT Nombre FROM Tipo_Contacto WHERE Id_TipoContacto = '".$this->Id_TipoGestion."'";
                $select = $this->db->select($query);
                if($select){
                    $TipoContacto = $select[0]['Nombre'];
                }else{
                    $TipoContacto = '';
                }
                $array = array('id_nivel_2' => $this->Nivel2, 'nivel_2' => $nivel_2, 'nombre' => $this->Nombre, 'TipoContacto' => $TipoContacto, 'Ponderacion' => $this->Ponderacion, 'Peso' => $this->Peso, 'id' => $this->Id);
                $response_array['array'] = $array;
                $response_array['status'] = 1; 
            }else{
                $response_array['status'] = 0; 
            }
        }else{
            $response_array['status'] = 2; 
        }
        echo json_encode($response_array);
    }
     
    public function deleteNivel3($Id)
    {
        $response_array = array();
        $Id = isset($Id) ? trim($Id) : "";
        if(!empty($Id)){
            $this->Id=$Id;
            $query = "SELECT * FROM Respuesta_Rapida WHERE Respuesta_Nivel3 = '".$this->Id."'";
            //$db = new Db;
            $select = $this->db->select($query);
            if(!$select){
                //$db = new Db;
                $query = "DELETE FROM Nivel3 WHERE Id = '".$this->Id."'";
                $data = $this->db->query($query);
                if($data){
                    $response_array['status'] = 1; 
                } else {
                    $response_array['status'] = 0; 
                }
            } else {
                $response_array['status'] = 3; 
            }
        } else {
            $response_array['status'] = 2; 
        }
        echo json_encode($response_array);
    }
    
    public function showNivel3()
    {
        $query = "SELECT 
                    Nivel3.Id AS id, 
                    Nivel3.Respuesta_N3 AS nombre,
                    Nivel3.Id_Nivel2 AS id_nivel_2,
                    Nivel3.Ponderacion,
                    Nivel3.Peso,
                    Nivel2.Respuesta_N2 AS nivel_2,
                    Nivel1.Respuesta_N1 AS nivel_1,
                    Tipo_Contacto.Nombre AS TipoContacto
                 FROM 
                    Nivel3
                 INNER JOIN 
                    Nivel2 
                 ON 
                    Nivel3.Id_Nivel2 = Nivel2.Id
                 INNER JOIN 
                    Nivel1 
                 ON 
                    Nivel2.Id_Nivel1 = Nivel1.Id
                 INNER JOIN 
                    Tipo_Contacto 
                 ON 
                    Nivel3.Id_TipoGestion = Tipo_Contacto.Id_TipoContacto
                 WHERE 
                    find_in_set('".$this->idCedente."', Nivel1.Id_Cedente)";
            //$db = new Db;
        $select = $this->db->select($query);
        $array = [];
        if($select) {
            $array = (array) $select;
                /*
                foreach($select as $row){
                    $arreglo = array();
                    $arreglo['id'] = $row['id'];
                    $arreglo['nombre'] = utf8_encode($row['nombre']);
                    $arreglo['id_nivel_2'] = $row['id_nivel_2'];
                    $arreglo['nivel_2'] = utf8_encode($row['nivel_2']);
                    $arreglo['nivel_1'] = utf8_encode($row['nivel_1']);
                    $arreglo['TipoContacto'] = utf8_encode($row['TipoContacto']);
                    $arreglo['Ponderacion'] = $row['Ponderacion'];
                    $arreglo['Peso'] = $row['Peso'];
                    array_push($array,$arreglo);
                }
                */
        }
        $response_array['array'] = $array;
        echo json_encode($response_array);
    }

    public function showNivel4()
    {
        $query = "SELECT 
                    Nivel4.Id AS id, 
                    Nivel4.Respuesta_N4 AS nombre,
                    Nivel4.Id_Nivel3 AS id_nivel_3,
                    Nivel4.Ponderacion,
                    Nivel4.Peso,
                    Nivel3.Respuesta_N3 AS nivel_3,
                    Nivel2.Respuesta_N2 AS nivel_2,
                    Nivel1.Respuesta_N1 AS nivel_1,
                    Tipo_Contacto.Nombre AS TipoContacto
                FROM 
                    Nivel4
                INNER JOIN Nivel3 ON Nivel4.Id_Nivel3 = Nivel3.Id
                INNER JOIN Nivel2 ON Nivel3.Id_Nivel2 = Nivel2.Id
                INNER JOIN Nivel1 ON Nivel2.Id_Nivel1 = Nivel1.Id
                 INNER JOIN Tipo_Contacto ON Nivel4.Id_TipoGestion = Tipo_Contacto.Id_TipoContacto
                 WHERE 
                    find_in_set('".$this->idCedente."', Nivel1.Id_Cedente)";
            //$db = new Db;
        $select = $this->db->select($query);
        $array = [];
        if($select) {
            $array = (array) $select;
                /*
                foreach($select as $row){
                    $arreglo = array();
                    $arreglo['id'] = $row['id'];
                    $arreglo['nombre'] = utf8_encode($row['nombre']);
                    $arreglo['id_nivel_2'] = $row['id_nivel_2'];
                    $arreglo['nivel_2'] = utf8_encode($row['nivel_2']);
                    $arreglo['nivel_1'] = utf8_encode($row['nivel_1']);
                    $arreglo['TipoContacto'] = utf8_encode($row['TipoContacto']);
                    $arreglo['Ponderacion'] = $row['Ponderacion'];
                    $arreglo['Peso'] = $row['Peso'];
                    array_push($array,$arreglo);
                }
                */
        }
        $response_array['array'] = $array;
        echo json_encode($response_array);
    }

    //NIVEL 3
    public function storeNivelRapido($Nivel3)
    {
        $response_array = array();
        $Nivel3 = isset($Nivel3) ? trim($Nivel3) : "";
        if(!empty($Nivel3)){
            $this->Nivel3=$Nivel3;
            $query = "INSERT INTO Respuesta_Rapida(Respuesta_Nivel3, Id_Cedente) VALUES ('".$this->Nivel3."','".$this->idCedente."')";
            //$db = new Db;
            $id = $this->db->query($query);
            if($id){
                $query = "SELECT Respuesta_N3 FROM Nivel3 WHERE Id = '".$this->Nivel3."'";
                $select = $this->db->select($query);
                if($select){
                    $nivel_3 = $select[0]['Respuesta_N3'];
                }else{
                    $nivel_3 = '';
                }
                $array = array('id' => $id, 'id_nivel_3' => $this->Nivel3, 'nivel_3' => $nivel_3);
                $response_array['array'] = $array;
                $response_array['status'] = 1; 
            }else{
                $response_array['status'] = 0; 
            }
        }else{
            $response_array['status'] = 2; 
        }
        echo json_encode($response_array);
  	}

    public function deleteNivelRapido($Id)
    {
        $response_array = array();
        //$db = new Db;
        $Id = isset($Id) ? trim($Id) : "";
        if(!empty($Id)){
            $this->Id=$Id;
            $query = "SELECT Respuesta_Nivel3 FROM Respuesta_Rapida WHERE id_Respuesta_Rapida = '".$this->Id."'";
            $Respuesta_Rapida = $this->db->select($query);

            if($Respuesta_Rapida){
                $query = "SELECT id, Respuesta_N3 FROM Nivel3 WHERE id = '".$Respuesta_Rapida[0]['Respuesta_Nivel3']."'";
                $Nivel3 = $this->db->select($query);
                if($Nivel3){
                    $query = "DELETE FROM Respuesta_Rapida WHERE id_Respuesta_Rapida = '".$this->Id."'";
                    $data = $this->db->query($query);
                    if($data){
                        $array = array('id' => $Nivel3[0]['id'], 'nombre' => $Nivel3[0]['Respuesta_N3']);
                        $response_array['array'] = $array; 
                        $response_array['status'] = 1; 
                    }else{
                        $response_array['status'] = 0; 
                    }
                }else{
                    $response_array['status'] = 0; 
                }
            }else{
                $response_array['status'] = 3; 
            }
        }else{
            $response_array['status'] = 2; 
        }

        echo json_encode($response_array);
    }

    public function showNivelRapido()
    {
        $query = "SELECT 
                    Respuesta_Rapida.id_Respuesta_Rapida AS id, 
                    Respuesta_Rapida.Respuesta_Nivel3 AS id_nivel_3,
                    Nivel3.Respuesta_N3 AS nivel_3
                  FROM 
                    Respuesta_Rapida
                  INNER JOIN 
                    Nivel3 
                  ON 
                    Respuesta_Rapida.Respuesta_Nivel3 = Nivel3.Id
                  WHERE 
                    Respuesta_Rapida.Id_Cedente = '".$this->idCedente."'";
            //$db = new Db;
        $select = $this->db->select($query);
        $array = [];
        if($select) {
            $array = (array) $select;
            /*
            foreach($select as $row){
                    $arreglo = array();
                    $arreglo['id'] = $row['id'];
                    $arreglo['id_nivel_3'] = $row['id_nivel_3'];
                    $arreglo['nivel_3'] = utf8_encode($row['nivel_3']);
                    array_push($array,$arreglo);
                }
            */
        }
        $response_array['array'] = $array;
        echo json_encode($response_array);
    }

    public function getTipoContacto()
    {
        //$db = new Db;
        $query = "SELECT * FROM Tipo_Contacto";
        $select = $this->db->select($query);
        echo json_encode((array) $select);
    }
}
?>