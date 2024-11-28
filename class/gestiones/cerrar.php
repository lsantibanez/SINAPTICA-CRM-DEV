<?php
    class Cerrar{
        
        public function search($cedente){
            $db = new Db();
            $sql = "SELECT
                        g.id_gestion,
                        g.rut_cliente,
	                    g.rut_cliente as rut,
                        g.fechahora AS fecha_gestion,
                        g.observacion,
                        p.nombre as nombre_ejecutivo,
                        c.supervisor AS nombre_supervisor,
                        g.file_url 
                    FROM
                        gestion_ult_trimestre AS g
                        JOIN cases AS c ON g.id_gestion = c.id_gestion 
                        JOIN Personal as p ON p.Nombre_Usuario = g.nombre_ejecutivo 
                    WHERE
                        c.cerrado = 0 
                        AND g.cedente = '".$cedente."' 
                    ORDER BY
                        g.id_gestion DESC";
		    $gestiones = $db->select($sql);
            header('Content-type: application/json; charset=utf-8');
            return $gestiones;
            exit();
        }
        
        public function cerrar_gestion($id,$observacion,$nivel1,$nivel2,$nivel3,$rut,$r1,$r2,$r3,$ejecutivo,$cedente){
            $db = new Db();
            //Actualizamos el caso a cerrado, y colocamos la observacion.
            $sql = "UPDATE cases SET cerrado=1, observacion='".$observacion."' WHERE id_gestion = '".$id."' ";
            $db->query($sql);

            $date = date('Y-m-d');
            $time = date('H:i:s');
            $gdate = $date." ".$time;
            
            //Creamos una nueva gestion
            $query = "INSERT INTO gestion_ult_trimestre(resultado, resultado_n2, resultado_n3, observacion,fecha_gestion,hora_gestion,rut_cliente,fechahora,nombre_ejecutivo,Id_TipoGestion,cedente,n1,n2,n3) VALUES ('".$r1."','".$r2."','".$r3."','".$observacion."','".$date."','".$time."','".$rut."','".$gdate."','".$ejecutivo."','6','".$cedente."','".$nivel1."','".$nivel2."','".$nivel3."')";
           
            return $id_gestion = $db->insert($query);
            exit();
        }

    }
?>