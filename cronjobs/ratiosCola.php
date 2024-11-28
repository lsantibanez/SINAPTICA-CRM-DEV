<?php
    include("../class/db/DB.php");

    $db = new DB();

    /*SELECCIONANDO LOS ID DE LAS ASIGNACIONES*/
    $sqlCola = "SELECT DISTINCT(id_cola) FROM asignacion_cola";
    $colas = $db->select($sqlCola);

    $sqlMuestras = "SELECT * FROM ratios_mantenedor";
    $muestras = $db->select($sqlMuestras);

    foreach($colas as $cola){
        /**********************************************************
         ******** EVALUAR RATIOS POR CADA COLA REGISTRADA  ********
         *********************************************************/
        foreach($muestras as $muestra){
            $ratio = $muestra['id'];
            $limit = $muestra['muestra'];

            $sqlMuestra = "SELECT 
                                count(*) AS pctj
                            FROM 
                                (SELECT 
                                        id_tipo_gestion 
                                    FROM 
                                        titular_niveles_cola 
                                    WHERE 
                                        id_cola = '" . $cola['id_cola'] . "' 
                                    ORDER BY fecha_hora DESC 
                                    LIMIT $limit) AS gestiones
                            WHERE gestiones.id_tipo_gestion IN (SELECT 
																	id_tipo_contacto 
                                                                FROM 
                                                                    ratios_tipo_contacto 
                                                                WHERE 
                                                                    id_ratio = '" . $ratio . "')";

            $result = $db->select($sqlMuestra);

            $porcentaje = 0;
            if($result){
                $pctj = $result[0]["pctj"];

                $porcentaje = (($pctj/$limit)*100);
            }

            /**********************************************************
            ***** ENCONTRAR SI EXISTE COLA EN LA TABLA RATIOS_COLA ****
            **********************************************************/
            $sqlRatioCola = "SELECT 
                                    id 
                                FROM 
                                    ratios_cola 
                                WHERE 
                                    id_cola = '" . $cola['id_cola'] . "' 
                                    AND id_ratio = '" . $ratio . "'";
            $exist = $db->select($sqlRatioCola);

            $sqlRegist = "";
            //SÍ EXISTE, SE ACTUALIZA LA INFORMACIÓN
            if(count($exist)){
                $id = $exist[0]["id"];
                $sqlRegist = "UPDATE ratios_cola SET porcentaje = '" . $porcentaje . "' WHERE id = '" . $id . "'";
            }else{//SÍ NO EXISTE, SE INSERTA EL REGISTRO
                $sqlRegist = "INSERT INTO ratios_cola (id_cola, id_ratio, porcentaje) VALUES ('" . $cola['id_cola'] . "', '" . $ratio . "', '" . $porcentaje . "')";
            }

            $act = $db->query($sqlRegist);
        }
    }
?>