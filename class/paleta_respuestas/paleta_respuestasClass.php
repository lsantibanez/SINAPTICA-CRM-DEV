<?php
class Paleta {

    public function getMandante(){
        $db = new DB();

        $mandantes = $db->select("SELECT id, nombre FROM mandante");
        $output = '';
        if(count($mandantes)){
            foreach($mandantes as $mandante){
                $output .= "<option value='" . $mandante["id"] . "'>" . $mandante["nombre"] . "</option>";
            }
        }

        echo $output;
    }

    public function getCedentes($idMandante){
        $db = new DB();

        $output = '';
        $sqlCedentes = "SELECT 
                                c.Id_Cedente AS id, c.Nombre_Cedente AS nombre 
                            FROM 
                                Cedente AS c 
                                INNER JOIN mandante_cedente AS mc ON (c.Id_Cedente = mc.Id_Cedente)
                            WHERE 
                                mc.Id_Mandante = '" . $idMandante . "'";

        $cedentes = $db->select($sqlCedentes);

        if (count($cedentes) > 0){
            foreach($cedentes as $cedente){
                $output .= "<option value='" . $cedente["id"] . "'>" . $cedente["nombre"] . "</option>";
            }
        }
        echo $output;
    }
    
    public function insertNivel1($respuesta, $idcedente, $Descripcion_Cedente){
        $db = new DB();
        if(!empty($respuesta) && !empty($idcedente) && !empty($Descripcion_Cedente)){
            $respuesta_clear = $idcedente_clear = $Descripcion_Cedente_clear = '';
            $respuesta_clear = trim($respuesta);
            $idcedente_clear = trim($idcedente);
            $Descripcion_Cedente_clear = trim($Descripcion_Cedente);

            $db->query("INSERT INTO 
                                Nivel1 (Respuesta_N1, Id_Cedente, Descripcion_Cedente) 
                            VALUES ('" . $respuesta_clear . "', '" . $idcedente_clear . "', '" . $Descripcion_Cedente_clear . "')");
            echo "Nivel 1 Creado";
        } else {
            echo "Todos los campos son requeridos";
            return false;
        }
    }
    
    public function insertNivel2($idnivel1, $respuesta, $Descripcion_Cedente){
        $db = new DB();
        if(!empty($idnivel1) && !empty($respuesta) && !empty($Descripcion_Cedente)){
            $idnivel1_clear = $respuesta_clear = $Descripcion_Cedente_clear = '';
            
            $respuesta_clear = trim($respuesta);
            $idnivel1_clear = trim($idnivel1);
            $Descripcion_Cedente_clear = trim($Descripcion_Cedente);

             $db->query("INSERT INTO 
                                Nivel2(Respuesta_N2, Id_Nivel1, Descripcion_Cedente) 
                            VALUES ('" . $respuesta_clear . "', '" . $idnivel1_clear . "', '" . $Descripcion_Cedente_clear . "')");
             echo "Nivel 2 Creado";
        } else {
            echo "Todos los campos son requeridos";
            return false;
        }
    }

    public function insertNivel3($idnivel2, $tipoGestion, $ponderacion, $peso, $respuesta, $Descripcion_Cedente){
        $db = new DB();
        if(!empty($idnivel2) && !empty($tipoGestion) && !empty($ponderacion) && !empty($peso) && !empty($respuesta) && !empty($Descripcion_Cedente)){
            $idnivel2_clear = $tipoGestion_clear = $ponderacion_clear = $peso_clear = $respuesta_clear  = $Descripcion_Cedente_clear = '';
            
            $idnivel2_clear = trim($idnivel2);
            $tipoGestion_clear = trim($tipoGestion);
            $ponderacion_clear = trim($ponderacion);
            $peso_clear = trim($peso);
            $respuesta_clear = trim($respuesta);
            $Descripcion_Cedente_clear = trim($Descripcion_Cedente);

            $db->query("INSERT INTO 
                                Nivel3(Respuesta_N3, Id_Nivel2, Id_TipoGestion, Descripcion_Cedente, Ponderacion, Peso) 
                          VALUES ('" . $respuesta_clear . "', '" . $idnivel2_clear . "', '" . $tipoGestion_clear . "', 
                                    '" . $Descripcion_Cedente_clear . "', '" . $ponderacion_clear . "', '" . $peso_clear . "')");
            echo "Nivel 3 Creado";
        } else {
            echo "Todos los campos son requeridos";
            return false;
        }
    }

    public function getNombreNivel1($idcedente){
        $db = new DB();
        $output = '';

        $nivel1 = $db->select("SELECT Id, Respuesta_N1 FROM Nivel1 WHERE Id_Cedente = '" .$idcedente . "'");
        if(count($nivel1)){
            foreach($nivel1 as $nombre){
                $output .= "<option value='" . $nombre["Id"] . "'>" . $nombre["Id"] . " " . $nombre["Respuesta_N1"] . "</option>";
            }
        }
        echo $output;
    }

    public function getNombreNivel2($idnivel1){
        $db = new DB();
        $output = '';

        $nivel2 = $db->select("SELECT id, Respuesta_N2 FROM Nivel2 WHERE Id_Nivel1 = '" . $idnivel1 . "'");
        if(count($nivel2)){
            foreach($nivel2 as $nombre){
                $output .= "<option value='" . $nombre["id"] . "'>" . $nombre["id"] . " " . $nombre["Respuesta_N2"] . "</option>";
            }
        }

        echo $output;
    }

    public function getNombreNivel3($idnivel2){
        $db = new DB();
        $output = '';

        $nivel3 = $db->select("SELECT id, Respuesta_N3 FROM Nivel3 WHERE Id_Nivel2 = '" . $idnivel2 . "'");
        if(count($nivel3)){
            foreach($nivel3 as $nombre){
                $output .= "<option value='" . $nombre["id"] . "'>" . $nombre["id"] . " " . $nombre["Respuesta_N3"] . "</option>";
            }
        }

        echo $output;
    }

    public function Nivel3getNombreNivel2(){
        $db = new DB();
        $output = '';

        $nombres = $db->select("SELECT id, Respuesta_N2 FROM Nivel2");
        if(count($nombres)){
            foreach($nombres as $nombre){
                $output .= "<option value='" . $nombre["id"] . "'>" . $nombre["id"] . " " . $nombre["Respuesta_N2"] . "</option>";
            }
        }
        echo $output;
    }
}
?>