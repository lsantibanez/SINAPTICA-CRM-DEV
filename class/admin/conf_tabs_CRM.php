	<?php

    class ConfTab
    {
        function __construct(){
            if(!isset($_SESSION)){
                session_start();
            }
        }
        function getTabs(){
            $ToReturn = array();
            $db = new DB();
            $query = "	SELECT 
                            ta.*, ts.tab, ts.id as id_tab_sistema
                        FROM 
                            Tabs_Sistema_Asignacion_CRM ts
                        LEFT JOIN 
                            Tabs_Asignacion_CRM ta on ta.id_tab_sistema = ts.id
                        WHERE 
                            (ta.Id_Cedente IS NULL OR ta.Id_Cedente = '".$_SESSION["cedente"]."')";
            $Tabs = $db->select($query);
            $NotIn = '';
            foreach($Tabs as $Tab){			
                $ArrayTmp = array();
                $ArrayTmp["Tab"] = $Tab["tab"];
                $ArrayTmp["Sistema"] = 1;
                $ArrayTmp["IdSistema"] = $Tab["id_tab_sistema"];
                //$ArrayTmp["Prioridad"] = $Tab["prioridad"] ?? 0;
                $ArrayTmp["Prioridad"] = $Tab["prioridad"] == "" ? "0": $Tab["prioridad"];
                $ArrayTmp["Activo"] = $Tab["activo"] == "" ? "0": $Tab["activo"];
                $ArrayTmp["Accion"] = $Tab["id"] == "" ? "0": $Tab["id"];
                if($NotIn){
                    $NotIn .= ",".$Tab["id_tab_sistema"];
                }else{
                    $NotIn = $Tab["id_tab_sistema"];
                }
                array_push($ToReturn,$ArrayTmp);
            }
            if($NotIn){
                $WhereNotIn = "WHERE id NOT IN (".$NotIn.")";
            }else{
                $WhereNotIn = "";
            }
            $query = "	SELECT 
                            tab, id as id_tab_sistema
                        FROM 
                            Tabs_Sistema_Asignacion_CRM
                        ".$WhereNotIn;
            $Tabs = $db->select($query);
            foreach($Tabs as $Tab){			
                $ArrayTmp = array();
                $ArrayTmp["Tab"] = $Tab["tab"];
                $ArrayTmp["Sistema"] = 1;
                $ArrayTmp["IdSistema"] = $Tab["id_tab_sistema"];
                $ArrayTmp["Prioridad"] = 0;
                $ArrayTmp["Activo"] = 0;
                $ArrayTmp["Accion"] = 0;
                array_push($ToReturn,$ArrayTmp);
            }
            $query = "	SELECT 
                            *
                        FROM 
                            Tabs_Asignacion_CRM
                        WHERE 
                            Id_Cedente = '".$_SESSION["cedente"]."'
                        AND 
                            sistema = 0";
            $Tabs = $db->select($query);
            foreach($Tabs as $Tab){			
                $ArrayTmp = array();
                $ArrayTmp["Tab"] = $Tab["tab"];
                $ArrayTmp["Sistema"] = 0;
                $ArrayTmp["IdSistema"] = $Tab["id_tab_sistema"];
                $ArrayTmp["Prioridad"] = $Tab["prioridad"];
                $ArrayTmp["Activo"] = $Tab["activo"];
                $ArrayTmp["Accion"] = $Tab["id"];

                array_push($ToReturn,$ArrayTmp);
            }

            return $ToReturn;
        }
        function saveTab($Tab,$Prioridad){
            $ToReturn = array();
            $db = new DB();
            $query = "INSERT INTO Tabs_Asignacion_CRM(prioridad,tab,Id_Cedente,sistema,id_tab_sistema,activo) VALUES ('".$Prioridad."','".$Tab."','".$_SESSION["cedente"]."','0','0','1')";
            $Insert = $db->query($query);
            if($Insert){
                $ToReturn['result'] = true;
            }else{
                $ToReturn['result'] = false;
            }
            return $ToReturn;
        }
        function getColumnas($ID,$Tabla){
            $ToReturn = array();
            $db = new DB();
            $query = "  SELECT 
                            COLUMN_NAME AS columna 
                        FROM 
                            INFORMATION_SCHEMA.COLUMNS
                        WHERE 
                            TABLE_NAME = '".$Tabla."'
                        AND 
                            COLUMN_NAME
                        NOT IN
                            (
                                SELECT 
                                    columna 
                                FROM 
                                    Columnas_Tabs_Asignacion_CRM cta
                                INNER JOIN 
                                    Tabs_Asignacion_CRM ta on cta.id_tab = ta.id
                                WHERE 
                                    ta.Id_Cedente = ".$_SESSION['cedente']." 
                                AND 
                                    ta.id = '".$ID."'
                            )";
            $Columnas = $db->select($query);
            foreach($Columnas as $Columna){			
                array_push($ToReturn,$Columna['columna']);
            }
            return $ToReturn;
        }
        function updateActivo($Value,$ID,$Tab,$Sistema,$IdSistema){
            $ToReturn = array();
            $db = new DB();
            if($ID){
                $query = "UPDATE Tabs_Asignacion_CRM SET activo = '".$Value."' WHERE id = '".$ID."'";
            }else{
                $query = "INSERT INTO Tabs_Asignacion_CRM(prioridad,tab,Id_Cedente,sistema,id_tab_sistema,activo) VALUES ('0','".$Tab."','".$_SESSION["cedente"]."','".$Sistema."','".$IdSistema."','".$Value."')";
            }
            $Update = $db->query($query);
            if($Update){
                $ToReturn['result'] = true;
            }else{
                $ToReturn['result'] = false;
            }
            return $ToReturn;
        }

        function updatePrioridad($Value,$ID,$Tab,$Sistema,$IdSistema){
            $ToReturn = array();
            $db = new DB();
            if($ID){
                $query = "UPDATE Tabs_Asignacion_CRM SET prioridad = '".$Value."' WHERE id = '".$ID."'";
            }else{
                $query = "INSERT INTO Tabs_Asignacion_CRM(prioridad,tab,Id_Cedente,sistema,id_tab_sistema,activo) VALUES ('".$Value."','".$Tab."','".$_SESSION["cedente"]."','".$Sistema."','".$IdSistema."','0')";
            }
            $Update = $db->query($query);
            if($Update){
                $ToReturn['result'] = true;
            }else{
                $ToReturn['result'] = false;
            }
            return $ToReturn;
        }
        function deleteTab($ID){
            $ToReturn = array();
            $db = new DB();
            $query = "DELETE FROM Tabs_Asignacion_CRM WHERE id = '".$ID."'";
            $Delete = $db->query($query);
            if($Delete){
                $this->deleteColumnas($ID);
                $ToReturn['result'] = true;
            }else{
                $ToReturn['result'] = false;
            }
            return $ToReturn;
        }
        function saveColumna($Tabla,$Columna,$Prioridad,$IdTab){
            $ToReturn = array();
            $db = new DB();
            $query = "INSERT INTO Columnas_Tabs_Asignacion_CRM(tabla,columna,id_tab,prioridad) VALUES ('".$Tabla."','".$Columna."','".$IdTab."','".$Prioridad."')";
            $Insert = $db->query($query);
            if($Insert){
                $ToReturn['result'] = true;
            }else{
                $ToReturn['result'] = false;
            }
            return $ToReturn;
        }
        function getColumnasTab($IdTab){
            $ToReturn = array();
            $db = new DB();
            $query = "  SELECT 
                            id, prioridad as Prioridad, columna as Columna, tabla as Tabla
                        FROM 
                            Columnas_Tabs_Asignacion_CRM
                        WHERE 
                            id_tab = '".$IdTab."'";
            $ToReturn = $db->select($query);
            return $ToReturn;
        }
        function deleteColumna($ID){
            $ToReturn = array();
            $db = new DB();
            $query = "DELETE FROM Columnas_Tabs_Asignacion_CRM WHERE id = '".$ID."'";
            $Delete = $db->query($query);
            if($Delete){
                $ToReturn['result'] = true;
            }else{
                $ToReturn['result'] = false;
            }
            return $ToReturn;
        }
        function deleteColumnas($ID){
            $ToReturn = array();
            $db = new DB();
            $query = "DELETE FROM Columnas_Tabs_Asignacion_CRM WHERE id_tab = '".$ID."'";
            $Delete = $db->query($query);
            if($Delete){
                $ToReturn['result'] = true;
            }else{
                $ToReturn['result'] = false;
            }
            return $ToReturn;
        }
    }

    ?>