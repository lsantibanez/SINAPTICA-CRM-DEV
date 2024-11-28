<?php
	class Exclusion{
		public function getExclusiones(){
            $db = new DB();
            $query = "  SELECT
                            e.*,
                            t.nombre AS Tipo,
                            (CASE WHEN e.Fecha_Term = '2999-01-31' THEN '' ELSE e.Fecha_Term END) AS Fecha_Term
                        FROM
                            Exclusiones e
                            INNER JOIN Tipo_Exclusiones t ON e.Tipo = t.id 
                        WHERE
                            e.Fecha_Term >= DATE_FORMAT( NOW( ), '%Y-%m-%d' ) 
                            AND Id_Cedente = ".$_SESSION['cedente']." 
                        ORDER BY
                            e.Fecha_Term";
            $Exclusiones = $db->select($query);
            return $Exclusiones;
        }
        public function getExclusion($id_registr){
            $db = new DB();
            $query = "  SELECT
                            *, 
                            DATE_FORMAT(Fecha_Inic, '%Y/%m/%d') as Fecha_Inic,
                            DATE_FORMAT(Fecha_Term, '%Y/%m/%d') as Fecha_Term
                        FROM
                            Exclusiones
                        WHERE
                            id_registr = '".$id_registr."'";
            $Exclusion = $db->select($query);
            return $Exclusion[0];
        }
        function storeExclusion($Tipo,$Dato,$Fecha_Inic,$Fecha_Term,$Descripcio){
            $db = new DB();
            $dbDiscador = new DB('discador');
            $Id_Cedente = $_SESSION['cedente'];
            $query = "INSERT INTO Exclusiones (Tipo,Dato,Fecha_Inic,Fecha_Term,Descripcio,Id_Cedente) VALUES ('".$Tipo."','".$Dato."',STR_TO_DATE('".$Fecha_Inic."','%Y/%m/%d'),STR_TO_DATE('".$Fecha_Term."','%Y/%m/%d'),'".$Descripcio."','".$Id_Cedente."')";
            $ToReturn = $db->insert($query);
            $ToReturn = $dbDiscador->insert($query);
            return $ToReturn;
        }
        function updateExclusion($Tipo,$Dato,$Fecha_Inic,$Fecha_Term,$Descripcio,$id_registr){
            $db = new DB();
            $dbDiscador = new DB('discador');
            $query = "UPDATE Exclusiones SET Tipo = '".$Tipo."', Dato = '".$Dato."', Fecha_Inic = STR_TO_DATE('".$Fecha_Inic."','%Y/%m/%d'), Fecha_Term = STR_TO_DATE('".$Fecha_Term."','%Y/%m/%d'), Descripcio = '".$Descripcio."' WHERE id_registr = '".$id_registr."'";
            $ToReturn = $db->query($query);
            $ToReturn = $dbDiscador->query($query);
            return $ToReturn;
        }
        function deleteExclusion($id_registr){
            $db = new DB();
            $dbDiscador = new DB('discador');
            $query = "DELETE FROM Exclusiones WHERE id_registr = '".$id_registr."'";
            $ToReturn = $db->query($query);
            $ToReturn = $dbDiscador->query($query);
            return $ToReturn;
        }
    }
?>