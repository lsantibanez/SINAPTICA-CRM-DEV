<?php
    class ConfInhabilitacion{
        function getInhabilitaciones(){
            $db = new DB();
            $Id_Cedente = $_SESSION['cedente'];
            $query = "  SELECT
                            Nivel1.Id AS Nivel1,
                            Nivel2.id AS Nivel2,
                            Nivel3.id AS Nivel3
                        FROM
                            config_inhabilitacionFonos
                        INNER JOIN Nivel3 ON config_inhabilitacionFonos.id_nivel3 = Nivel3.id
                        INNER JOIN Nivel2 ON Nivel3.Id_Nivel2 = Nivel2.id
                        INNER JOIN Nivel1 ON Nivel2.Id_Nivel1 = Nivel1.Id
                        WHERE
                            config_inhabilitacionFonos.Id_Cedente = '".$Id_Cedente."'";
            $Niveles = $db->select($query);
            if($Niveles){
                $Nivel1 = $Niveles[0]['Nivel1'];
                $Nivel2 = $Niveles[0]['Nivel2'];
                $Nivel3 = $Niveles[0]['Nivel3'];
            }else{
                $Nivel1 = '';
                $Nivel2 = '';
                $Nivel3 = '';
            }
               
            return array('Nivel1' => $Nivel1, 'Nivel2' => $Nivel2, 'Nivel3' => $Nivel3);
        }
        public function updateInhabilitaciones($Nivel){
            $db = new DB();
            $Id_Cedente = $_SESSION['cedente'];
            $query = "SELECT id_nivel3 FROM config_inhabilitacionFonos WHERE Id_Cedente = '".$Id_Cedente."'";
            $Inhabilitacion = $db->select($query);
            if($Inhabilitacion){
                $query = "UPDATE config_inhabilitacionFonos SET id_nivel3 = '".$Nivel."' WHERE Id_Cedente = '".$Id_Cedente."'";
            }else{
                $Id_Mandante = $_SESSION['mandante'];
                $query = "INSERT INTO config_inhabilitacionFonos (id_nivel3,Id_Cedente,Id_Mandante) VALUE ('".$Nivel."','".$Id_Cedente."','".$Id_Mandante."')";
            }
            
            $ToReturn = $db->query($query);
            return $ToReturn;
        }
    }
?>