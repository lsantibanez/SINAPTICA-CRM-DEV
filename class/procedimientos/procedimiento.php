<?php
    class Procedimiento{
        function getProcedimientos(){
            $db = new DB();
            $query = "  SELECT
                            e.*,
                            hp.id AS STATUS 
                        FROM
                            Estado_eventos e
                            LEFT JOIN Heavy_Process hp ON e.ID = hp.proceso 
                        WHERE
                            e.FINAL_VIEW = 1";
            $ToReturn = $db->select($query);
            return $ToReturn;
        }
        function RunProcedimiento($ID){
            $db = new DB();
            $ToReturn = array();
            $query = "  SELECT
                            * 
                        FROM
                            Heavy_Process 
                        WHERE
                            proceso = '".$ID."'";
            $Ejecutandose = $db->select($query);
            if(!$Ejecutandose){
                $query = "  SELECT
                                *
                            FROM
                                Estado_eventos
                            WHERE 
                                ID = '".$ID."'";
                $Estado_Eventos = $db->select($query);
                if($Estado_Eventos){
                    $descripcion = $Estado_Eventos[0]['EVENT_NAME'];
                    $usuario = $_SESSION['MM_Username'];
                    $Id_Cedente = $_SESSION['cedente'];
                    $query = "INSERT INTO Heavy_Process (proceso,descripcion,usuario,fecha,hora,Id_Cedente) VALUES ('".$ID."','".$descripcion."','".$usuario."',NOW(),NOW(),'".$Id_Cedente."')";
                    $Heavy_Process = $db->query($query);
                    if($Heavy_Process){
                        $query = "  SELECT
                                        *
                                    FROM
                                        Estado_eventos_procedures
                                    WHERE 
                                        ID_EVENT = '".$ID."'
                                    ORDER BY
                                        ORDEN ASC";
                        $Procedimientos = $db->select($query);
                        if($Procedimientos){
                            foreach($Procedimientos as $Procedimiento){
                                $query = $Procedimiento['PROCEDURE'];
                                $db->query($query);
                            }
                        }
                        $query = "UPDATE Estado_eventos SET LAST_EXECUTED = NOW(), USER_LAST_EXECUTED = '".$usuario."' WHERE ID = '".$ID."'";
                        $update = $db->query($query);
                        $query = "DELETE FROM Heavy_Process";
                        $delete = $db->query($query);
                    }
                }
                $ToReturn['result'] = true;
            }else{
                $ToReturn['result'] = false;
                $ToReturn['usuario'] = $Ejecutandose[0]['usuario'];
            }

            return $ToReturn;
        }
    }
?>