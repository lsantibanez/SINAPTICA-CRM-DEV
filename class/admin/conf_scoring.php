<?php
    class ConfScoring{
        function getVariables(){
            $db = new DB();
            $query = "  SELECT
                            v.id,
                            v.nombre_columna as Columna,
                            v.activo as Activo,
                            tv.nombre_variable as Variable,
                            te.nombre_escala as Escala
                        FROM
                            variables_scoring v
                        INNER JOIN 
                            tipos_variables_scoring tv 
                        ON 
                            v.id_tipo_variable = tv.id
                        INNER JOIN 
                            tipos_escalas_scoring te 
                        ON 
                            v.id_tipo_escala = te.id
                        WHERE 
                            v.id_cedente = '".$_SESSION['cedente']."'";
            $Variables = $db->select($query);
            return $Variables;
        }
        function getTiposVariablesCreate(){
            $db = new DB();
            $query = "  SELECT
                            *
                        FROM
                            tipos_variables_scoring
                        WHERE 
                            id 
                        NOT IN
                            (
                                SELECT id_tipo_variable FROM variables_scoring WHERE id_cedente = ".$_SESSION['cedente']."
                            )";
            $Variables = $db->select($query);
            return $Variables;
        }
        function getColumnas($Tabla,$Definida){
            if($Tabla){
                $db = new DB();
                if($Definida == 1){
                    $Cuerpo = "DATA_TYPE = 'int' OR DATA_TYPE = 'varchar'";
                }else{
                    $Cuerpo = "DATA_TYPE = 'date' OR DATA_TYPE = 'datetime'";
                }
                // $query = "SELECT COLUMN_NAME AS Columna FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$Tabla."'";
                $query = "SELECT COLUMN_NAME AS Columna FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$Tabla."' AND (".$Cuerpo.")";
                $Columnas = $db->select($query);
            }else{
                $Columnas = array();
            }
            return $Columnas;
        }
        function getTiposEscalas(){
            $db = new DB();
            $query = "  SELECT
                            *
                        FROM
                            tipos_escalas_scoring";
            $Escalas = $db->select($query);
            return $Escalas;
        }
        function CrearVariable($TipoVariable,$TipoEscala,$NombreColumna,$Definida){
            $ToReturn = array();
            $ToReturn["result"] = false;
            if($TipoVariable == 1){
                $NombreTabla = 'Deuda_Historico';
            }else{
                $NombreTabla = 'Persona';
            }
            $db = new DB();
            $query = "INSERT INTO variables_scoring (id_tipo_variable,id_tipo_escala,nombre_tabla,nombre_columna,definida,id_cedente) VALUES ('".$TipoVariable."','".$TipoEscala."','".$NombreTabla."','".$NombreColumna."','".$Definida."','".$_SESSION['cedente']."')";
            $Insert = $db->query($query);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getVariable($idVariable){
            $db = new DB();
            $query = "  SELECT
                            *
                        FROM
                            variables_scoring
                        WHERE 
                            id = '".$idVariable."'";
            $Variable = $db->select($query);
            return $Variable[0];
        }
        function getTiposVariablesUpdate($idVariable){
            $db = new DB();
            $query = "  SELECT
                            *
                        FROM
                            tipos_variables_scoring
                        WHERE 
                            id 
                        NOT IN
                            (
                                SELECT 
                                    id_tipo_variable 
                                FROM 
                                    variables_scoring 
                                WHERE 
                                    id_cedente = ".$_SESSION['cedente']." 
                                AND 
                                    id_tipo_variable != '".$idVariable."'
                            )";
            $Variables = $db->select($query);
            return $Variables;
        }
        function updateActivo($idVariable,$Activo){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $query = "UPDATE variables_scoring SET activo = '".$Activo."' WHERE id = '".$idVariable."'";
            $Update = $db->query($query);
            if($Update){
                $Variable = $this->getVariable($idVariable);
                if(!$Activo){
                    $this->deletePorcentajes($idVariable);
                }
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function updateVariable($TipoVariable,$TipoEscala,$NombreColumna,$Definida,$idVariable){
            $ToReturn = array();
            $ToReturn["result"] = false;
            if($TipoVariable == 1){
                $NombreTabla = 'Deuda_Historico';
            }else{
                $NombreTabla = 'Persona';
            }
            $db = new DB();
            $query = "UPDATE variables_scoring SET id_tipo_variable = '".$TipoVariable."', id_tipo_escala = '".$TipoEscala."', nombre_tabla = '".$NombreTabla."', nombre_columna = '".$NombreColumna."', definida = '".$Definida."' WHERE id = '".$idVariable."'";
            $Update = $db->query($query);
            if($Update){
                $Variable = $this->getVariable($idVariable);
                if($Variable['id_tipo_escala'] != $TipoEscala){
                    $this->deleteNiveles($idVariable);
                }
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function deleteVariable($idVariable){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlDelete = "DELETE FROM variables_scoring WHERE id = '".$idVariable."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $this->deleteNiveles($idVariable);
                $this->deletePorcentajes($idVariable);
                $ToReturn["result"] = true;
            }else{
                $ToReturn["result"] = false;
                $ToReturn["message"] = 'Error al eliminar variable';
            }
            return $ToReturn;
        }
        function deleteNiveles($idVariable){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlDelete = "DELETE FROM niveles_scoring WHERE id_variable = '".$idVariable."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }else{
                $ToReturn["result"] = false;
                $ToReturn["message"] = 'Error al eliminar variable';
            }
            return $ToReturn;
        }
        function deletePorcentajes($idVariable){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlDelete = "DELETE FROM porcentajes_scoring WHERE id_variable = '".$idVariable."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }else{
                $ToReturn["result"] = false;
                $ToReturn["message"] = 'Error al eliminar variable';
            }
            return $ToReturn;
        }
        function getNiveles($idVariable){
            $db = new DB();
            $query = "  SELECT
                            n.id,
                            n.porcentaje as Porcentaje,
                            n.valor as Valor,
                            e.nombre_escala as Escala
                        FROM
                            niveles_scoring n
                        INNER JOIN 
                            variables_scoring v 
                        ON 
                            n.id_variable = v.id
                        INNER JOIN 
                            escalas_scoring e 
                        ON 
                            n.id_escala = e.id
                        WHERE 
                            v.id = '".$idVariable."'
                        AND
                            v.id_cedente = '".$_SESSION['cedente']."'";
            $Niveles = $db->select($query);
            return $Niveles;
        }
        function getEscalas($idVariable){
            $db = new DB();
            $query = " SELECT id_tipo_escala FROM variables_scoring WHERE id = '".$idVariable."'";
            $Variable = $db->select($query);
            $Variable = $Variable[0];
            if($Variable['id_tipo_escala'] == 1){
                $whereNivel = " AND e.id != 2";
            }else{
                $whereNivel = "";
            }
            $query = "  SELECT
                            *
                        FROM
                            escalas_scoring e
                        WHERE 
                            e.id 
                        NOT IN
                            (
                                SELECT 
                                    n.id_escala 
                                FROM 
                                    niveles_scoring n
                                INNER JOIN 
                                    variables_scoring v 
                                ON 
                                    n.id_variable = v.id
                                WHERE 
                                    v.id_cedente = ".$_SESSION['cedente']."
                                AND 
                                    v.id = ".$idVariable."
                            )
                        ".$whereNivel;
            $Escalas = $db->select($query);
            return $Escalas;
        }
        function ComprobarNivelEscalas($Escala,$Porcentaje,$Valor,$idVariable){
            $ToReturn["result"] = false;
            $ToReturn["message"] = 'Error al crear el registro';
            $db = new DB();
            $query = " SELECT id_tipo_variable, id_tipo_escala FROM variables_scoring WHERE id = '".$idVariable."'";
            $Variable = $db->select($query);
            $Variable = $Variable[0];
            if($Escala == 1){
                if($Variable['id_tipo_variable'] == 1){
                    $whereVariable = "  AND ( 
                                            n.porcentaje >= '".$Porcentaje."'
                                        OR
                                            n.valor <= '".$Valor."')";
                    $cuerpoVariable = 'El registro no puede ser creado ya que el valor debe ser menor a la escala ';
                }else{
                    $whereVariable = "  AND ( 
                                            n.porcentaje >= '".$Porcentaje."'
                                        OR
                                            n.valor >= '".$Valor."')";
                    $cuerpoVariable = 'El registro no puede ser creado ya que el valor debe ser mayor a la escala ';
                }
                if($Variable['id_tipo_escala'] == 1){
                    $whereNivel = " AND 
                                        v.id_tipo_escala != 2";
                    $cuerpoNivel = 'Bajo';
                }else{
                    $whereNivel = "";
                    $cuerpoNivel = 'Medio';
                }

                $Where = $whereVariable . $whereNivel;
                $message = $cuerpoVariable . $cuerpoNivel;
                    
            }else if($Escala == 2){
                if($Variable['id_tipo_variable'] == 1){
                    $Where = "  AND 
                                ((
                                    n.id_escala = 1 AND (n.porcentaje <= '".$Porcentaje."' OR n.valor >= '".$Valor."')
                                )
                                OR 
                                (
                                    n.id_escala = 3 AND (n.porcentaje >= '".$Porcentaje."' OR n.valor <= '".$Valor."')
                                ))";
                    $Escala1 = 'Bajo';
                    $Escala2 = 'Alto';
                    
                }else{
                    $Where = "  AND 
                                ((
                                    n.id_escala = 1 AND (n.porcentaje <= '".$Porcentaje."' OR n.valor <= '".$Valor."')
                                )
                                OR 
                                (
                                    n.id_escala = 3 AND (n.porcentaje >= '".$Porcentaje."' OR n.valor >= '".$Valor."')
                                ))";
                    $Escala1 = 'Alto';
                    $Escala2 = 'Bajo';
                }
                $message = 'El registro no puede ser creado ya que el valor debe ser menor a la escala '.$Escala1.' y mayor a la escala '.$Escala2;
            }else{
                if($Variable['id_tipo_variable'] == 1){
                    $whereVariable = "  AND ( 
                                            n.porcentaje <= '".$Porcentaje."'
                                        OR
                                            n.valor >= '".$Valor."')";
                    $cuerpoVariable = 'El registro no puede ser creado ya que el valor debe ser mayor a la escala ';
                }else{
                    $whereVariable = "  AND ( 
                                            n.porcentaje <= '".$Porcentaje."'
                                        OR
                                            n.valor <= '".$Valor."')";
                    $cuerpoVariable = 'El registro no puede ser creado ya que el valor debe ser menor a la escala ';
                }
                if($Variable['id_tipo_escala'] == 1){
                    $whereNivel = " AND 
                                        v.id_tipo_escala != 2";
                    $cuerpoNivel = 'Alto';
                }else{
                    $whereNivel = "";
                    $cuerpoNivel = 'Medio';
                }

                $Where = $whereVariable . $whereNivel;
                $message = $cuerpoVariable . $cuerpoNivel;
            }
            $query = "  SELECT 
                COUNT(*) AS Cantidad 
            FROM 
                niveles_scoring n 
            INNER JOIN 
                variables_scoring v 
            ON 
                n.id_variable = v.id
            WHERE 
                v.id_cedente = '".$_SESSION['cedente']."' 
            AND
                v.id = '".$idVariable."'"
            .$Where;
            $Select = $db->select($query);
            if($Select[0]['Cantidad'] == 0){
               
                $ToReturn["result"] = true;

                //
                // $query = "  SELECT
                //                 COUNT(n.id) as Cantidad,
                //                 SUM(n.porcentaje) as Porcentaje,
                //                 v.id_tipo_escala
                //             FROM
                //                 niveles_scoring n
                //             INNER JOIN 
                //                 variables_scoring v 
                //             ON 
                //                 n.id_variable = v.id
                //             WHERE 
                //                 v.id = '".$idVariable."'
                //             GROUP BY
                //                 v.id_tipo_escala";
                // $Variable = $db->select($query);
                // if($Variable){
                //     $Variable = $Variable[0];
                //     if($Variable['id_tipo_escala'] == 1){
                //         if($Variable['Cantidad'] == 1){
                //             if(intval($Variable['Porcentaje']) + intval($Porcentaje) == 100){
                //                 $ToReturn["result"] = true;
                //             }else{
                //                 $ToReturn["message"] = 'El registro no puede ser creado ya que la sumatoria de los porcentajes debe dar 100';
                //                 $ToReturn["result"] = false;
                //             }
                //         }else{
                //             $ToReturn["result"] = true;
                //         }
                //     }else{
                //         if($Variable['Cantidad'] == 2){
                //             if(intval($Variable['Porcentaje']) + intval($Porcentaje) == 100){
                //                 $ToReturn["result"] = true;
                //             }else{
                //                 $ToReturn["message"] = 'El registro no puede ser creado ya que la sumatoria de los porcentajes debe dar 100';
                //                 $ToReturn["result"] = false;
                //             }
                //         }else{
                //             $ToReturn["result"] = true;
                //         }
                //     }
                // }else{
                //     $ToReturn["result"] = true;
                // }
            }else{
                $ToReturn["message"] = $message;
            }
            return $ToReturn;
        }
        function CrearNivel($Escala,$Porcentaje,$Valor,$idVariable){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $query = "INSERT INTO niveles_scoring (id_escala,porcentaje,valor,id_variable) VALUES ('".$Escala."','".$Porcentaje."','".$Valor."','".$idVariable."')";
            $Insert = $db->query($query);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function deleteNivel($idNivel){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlDelete = "DELETE FROM niveles_scoring WHERE id = '".$idNivel."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }else{
                $ToReturn["result"] = false;
                $ToReturn["message"] = 'Error al eliminar nivel';
            }
            return $ToReturn;
        }
        function getPorcentajes(){
            $db = new DB();
            $query = "  SELECT
                            p.id,
                            p.porcentaje as Porcentaje,
                            p.scoring as Scoring,
                            tv.nombre_variable as Variable
                        FROM
                            porcentajes_scoring p
                        INNER JOIN 
                            variables_scoring v 
                        ON 
                            p.id_variable = v.id
                        INNER JOIN 
                            tipos_variables_scoring tv 
                        ON 
                            v.id_tipo_variable = tv.id
                        WHERE 
                            p.id_cedente = '".$_SESSION['cedente']."'";
            $Porcentajes = $db->select($query);
            return $Porcentajes;
        }
        function getVariablesPorcentajeCreate(){
            $db = new DB();
            $query = "  SELECT
                            v.id,
                            tv.nombre_variable
                        FROM
                            variables_scoring v
                        INNER JOIN 
                            tipos_variables_scoring tv 
                        ON 
                            v.id_tipo_variable = tv.id
                        WHERE 
                            v.id_cedente = '".$_SESSION['cedente']."'
                        AND 
                            v.activo = 1 
                        AND 
                            v.id
                        NOT IN
                            (
                                SELECT id_variable FROM porcentajes_scoring WHERE id_cedente = ".$_SESSION['cedente']."
                            )";
            $Variables = $db->select($query);
            return $Variables;
        }
        function ComprobarPorcentajesVariableCreate($Porcentaje){
            $db = new DB();
            $query = "  SELECT
                            SUM(porcentaje) as Porcentaje,
                            COUNT(id) as Cantidad
                        FROM
                            porcentajes_scoring
                        WHERE 
                            id_cedente = '".$_SESSION['cedente']."'";
            $Total = $db->select($query);
            $Total = $Total[0];
            $query = "  SELECT
                            COUNT(id) as Cantidad
                        FROM
                            variables_scoring
                        WHERE 
                            id_cedente = '".$_SESSION['cedente']."'
                        AND
                            activo = 1";
            $Variable = $db->select($query);
            $Variable = $Variable[0];
            if(intval($Total['Porcentaje']) + intval($Porcentaje) <= 100){
                if(intval($Total['Cantidad']) == intval($Variable['Cantidad']) - 1){
                    if(intval($Total['Porcentaje']) + intval($Porcentaje) == 100){
                        $ToReturn['result'] = true;
                    }else{
                        $ToReturn['result'] = false;
                        $ToReturn['message'] = 'La sumatoria de los porcentajes debe ser igual a 100';
                    }
                }else{
                    $ToReturn['result'] = true;
                }
            }else{
                $ToReturn['result'] = false;
                $ToReturn['message'] = 'La sumatoria de los porcentajes no puede ser mayor a 100';
            }
            return $ToReturn;
        }
        function CrearPorcentaje($idVariable,$Porcentaje,$Scoring){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $query = "INSERT INTO porcentajes_scoring (id_variable,porcentaje,scoring,id_cedente) VALUES ('".$idVariable."','".$Porcentaje."','".$Scoring."','".$_SESSION['cedente']."')";
            $Insert = $db->query($query);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getPorcentaje($idPorcentaje){
            $db = new DB();
            $query = "  SELECT
                            *
                        FROM
                            porcentajes_scoring
                        WHERE 
                            id = '".$idPorcentaje."'";
            $Porcentaje = $db->select($query);
            return $Porcentaje[0];
        }
        function getVariablesPorcentajeUpdate($idVariable){
            $db = new DB();
            $query = "  SELECT
                            v.id,
                            tv.nombre_variable
                        FROM
                            variables_scoring v
                        INNER JOIN 
                            tipos_variables_scoring tv 
                        ON 
                            v.id_tipo_variable = tv.id
                        WHERE 
                            v.id_cedente = '".$_SESSION['cedente']."'
                        AND 
                            v.activo = 1 
                        AND 
                            v.id
                        NOT IN
                            (
                                SELECT 
                                    id_variable 
                                FROM 
                                    porcentajes_scoring 
                                WHERE 
                                    id_cedente = ".$_SESSION['cedente']."
                                AND 
                                    id_variable != '".$idVariable."'
                            )";
            $Variables = $db->select($query);
            return $Variables;
        }
        function ComprobarPorcentajesVariableUpdate($Porcentaje,$idPorcentaje){
            $db = new DB();
            $query = "  SELECT
                            SUM(porcentaje) as Porcentaje,
                            COUNT(id) as Cantidad
                        FROM
                            porcentajes_scoring
                        WHERE 
                            id_cedente = '".$_SESSION['cedente']."'
                        AND
                            id != '".$idPorcentaje."'";
            $Total = $db->select($query);
            $Total = $Total[0];
            $query = "  SELECT
                            COUNT(id) as Cantidad
                        FROM
                            variables_scoring
                        WHERE 
                            id_cedente = '".$_SESSION['cedente']."'
                        AND
                            activo = 1";
            $Variable = $db->select($query);
            $Variable = $Variable[0];
            if(intval($Total['Porcentaje']) + intval($Porcentaje) <= 100){
                if(intval($Total['Cantidad']) == intval($Variable['Cantidad']) - 1){
                    if(intval($Total['Porcentaje']) + intval($Porcentaje) == 100){
                        $ToReturn['result'] = true;
                    }else{
                        $ToReturn['result'] = false;
                        $ToReturn['message'] = 'La sumatoria de los porcentajes debe ser igual a 100';
                    }
                }else{
                    $ToReturn['result'] = true;
                }
            }else{
                $ToReturn['result'] = false;
                $ToReturn['message'] = 'La sumatoria de los porcentajes no puede ser mayor a 100';
            }
            return $ToReturn;
        }
        function updatePorcentaje($idVariable,$Porcentaje,$Scoring,$idPorcentaje){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $query = "UPDATE porcentajes_scoring SET id_variable = '".$idVariable."', porcentaje = '".$Porcentaje."', scoring = '".$Scoring."' WHERE id = '".$idPorcentaje."'";
            $Update = $db->query($query);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function deletePorcentaje($idPorcentaje){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlDelete = "DELETE FROM porcentajes_scoring WHERE id = '".$idPorcentaje."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }else{
                $ToReturn["result"] = false;
                $ToReturn["message"] = 'Error al eliminar porcentaje';
            }
            return $ToReturn;
        }
        function generarScoring(){

            $db = new DB();
            $query = "DELETE FROM resultados_scoring WHERE fecha = DATE_FORMAT(NOW(),'%Y-%m-%d')";
            $db->query($query);
            $query = "  SELECT
                            Id_Cedente
                        FROM
                            Cedente";
            $Cedentes = $db->select($query);
            
            if($Cedentes){
                foreach($Cedentes as $Cedente){

                    $Id_Cedente = $Cedente['Id_Cedente'];

                    $query = "  SELECT
                                    COUNT(id) as Cantidad
                                FROM
                                    porcentajes_scoring
                                WHERE 
                                    id_cedente = '".$Id_Cedente."'";
                    $TotalPorcentajes = $db->select($query);
                    $TotalPorcentajes = $TotalPorcentajes[0];
                    $query = "  SELECT
                                    COUNT(id) as Cantidad,
                                    (GROUP_CONCAT(nombre_columna SEPARATOR ', ') FROM variables_scoring WHERE id_cedente = '".$Id_Cedente."') as Columnas,
                                    (GROUP_CONCAT(nombre_tabla SEPARATOR ', ') FROM variables_scoring WHERE id_cedente = '".$Id_Cedente."') as Tablas
                                FROM
                                    variables_scoring
                                WHERE 
                                    id_cedente = '".$Id_Cedente."'
                                AND
                                    activo = 1";
                    $TotalVariables = $db->select($query);
                    $TotalVariables = $TotalVariables[0];
                    if($TotalVariables['Cantidad'] == $TotalPorcentajes['Cantidad']){
                        $query = "  SELECT
                                        p.porcentaje as Porcentaje,
                                        p.scoring as Scoring,
                                        v.id as idVariable,
                                        v.nombre_tabla as Tabla,
                                        v.nombre_columna as Columna,
                                        v.id_tipo_variable as TipoVariable,
                                        v.id_tipo_escala as TipoEscala,
                                        v.definida as Definida,
                                        tv.nombre_variable as NombreVariable
                                    FROM
                                        porcentajes_scoring p
                                    INNER JOIN 
                                        variables_scoring v 
                                    ON 
                                        p.id_variable = v.id
                                    INNER JOIN 
                                        tipos_variables_scoring tv 
                                    ON 
                                        v.id_tipo_variable = tv.id
                                    WHERE 
                                        p.id_cedente = '".$Id_Cedente."'
                                    AND
                                        v.activo = 1";
                        $Variables = $db->select($query);
                        if($Variables){
                            $Columnas = explode(',',$TotalVariables['Columnas']);
                            $Tablas = explode(',',$TotalVariables['Tablas']);
                            $Where = '';
                            if($Columnas){
                                $Where .= " AND (";
                                foreach ($Columnas as $Index => $Columna){
                                    if($Index != 0){
                                        $Where .= " OR ".$Tablas[$Index].".".$Columna." IS NOT NULL";
                                    }else{
                                        $Where .= $Tablas[$Index].".".$Columna." IS NOT NULL";
                                    }
                                }
                                $Where .= ")";
                            }
                            $query = "  SELECT DISTINCT
                                            Persona.Rut
                                        FROM
                                            Persona
                                        LEFT JOIN 
                                            Deuda_Historico
                                        ON 
                                            Deuda_Historico.Rut = Persona.Rut
                                        WHERE 
                                            FIND_IN_SET('".$Id_Cedente."',Persona.Id_Cedente)"
                                        .$Where;
                            $Personas = $db->select($query);
                            if($Personas){
                                foreach($Personas as $Persona){
                                    $Rut = $Persona['Rut'];
                                    // $TotalPorcentaje = 0;
                                    // $TotalScoring = 0;
                                    foreach($Variables as $Variable){
                                        $idVariable = $Variable['idVariable'];
                                        $TipoEscala = $Variable['TipoEscala'];
                                        $TipoVariable = $Variable['TipoVariable'];
                                        $Tabla = $Variable['Tabla'];
                                        $Columna = $Variable['Columna'];
                                        $Porcentaje = $Variable['Porcentaje'];
                                        $NombreVariable = $Variable['NombreVariable'];
                                        $Scoring = $Variable['Scoring'];
                                        $Definida = $Variable['Definida'];
                                        $query = "  SELECT
                                                        COUNT(id) as Cantidad
                                                    FROM
                                                        niveles_scoring
                                                    WHERE 
                                                        id_variable = '".$idVariable."'";
                                        $Niveles = $db->select($query);
                                        $Niveles = $Niveles[0];
                                        $Cantidad = $Niveles['Cantidad'];
                                        if($TipoEscala == 1){
                                            if($Cantidad < 2){
                                                // echo 'No estan todos los niveles configurados para la variable ' . $NombreVariable;
                                                break 2;
                                            }
                                        }else{
                                            if($Cantidad < 3){
                                                // echo 'No estan todos los niveles configurados para la variable ' . $NombreVariable;
                                                break 2;
                                            }
                                        }
                                        if($Definida == 1){
                                            $Cuerpo = $Columna;
                                        }else{
                                            if($TipoVariable == 1){
                                                $Tipo = 'DAY';
                                                $Fecha = 'fecha_descarga';
                                            }else{
                                                $Tipo = 'MONTH';
                                                $Fecha = 'NOW()';
                                            }
                                            $Cuerpo = "TIMESTAMPDIFF(".$Tipo.", ".$Columna.", DATE_FORMAT(".$Fecha.",'%Y-%m-%d'))";
                                        }
                                        $query = "SELECT ROUND(AVG(".$Cuerpo."),2) as Total FROM ".$Tabla." WHERE Rut = ".$Rut;
                                        $Resultado = $db->select($query);
                                        $Resultado = $Resultado[0];
                                        $Diferencia = $Resultado['Total'];

                                        if($Diferencia){
                                            $query = "SELECT TOP 1 porcentaje as PorcentajeTotal FROM niveles_scoring WHERE id_variable = '".$idVariable."' ORDER BY ABS(valor - ".$Diferencia.")";
                                            $Total = $db->select($query);
                                            if($Total){
                                                $PorcentajeTotal = $Total[0]['PorcentajeTotal'];
                                                // $idEscala = $Total[0]['IdEscala'];
                                                $PorcentajeNivel = ($PorcentajeTotal * $Porcentaje) / 100;
                                                // $TotalPorcentaje += $PorcentajeNivel;
                                                $PorcentajeScoring = ($PorcentajeTotal * $Scoring) / 100;
                                                $query = "INSERT INTO resultados_scoring (rut,resultado_scoring,total_scoring,resultado_porcentaje,total_porcentaje,variable,fecha) VALUES ('".$Rut."','".$PorcentajeScoring."','".$Scoring."','".$PorcentajeNivel."','".$Porcentaje."','".$NombreVariable."', NOW())";
                                                $db->query($query);
                                                // $TotalScoring += $PorcentajeScoring;
                                                // echo "<p>El total del scoring para la Variable ".$NombreVariable." es: ".$PorcentajeScoring. " para la escala ".$idEscala." y el porcentaje ".$PorcentajeTotal."</p>";
                                            }
                                        }else{
                                            // echo 'La columna '.$Columna.' de la tabla '.$Tabla.' no esta configurada para el Rut '.$Rut;
                                            // break 2;
                                        }
                                    }
                                }
                                // return $TotalScoring;
                            }
                        }
                        // else{
                        //     echo 'No estan todas las variables configuradas';
                        // }
                    }
                    // else{
                    //  echo 'No estan todas los porcentajes configuradas';
                    // }
                }
            }
        }
        function getScoring($Rut){
            $db = new DB();
            $query = "  SELECT
                            SUM(resultado_porcentaje) as Scoring
                        FROM
                            resultados_scoring
                        WHERE 
                            rut = '".$Rut."'
                        AND
                            fecha = DATE_FORMAT(NOW(),'%Y-%m-%d')";
            $Total = $db->select($query);
            if($Total){
                $ToReturn = $Total[0]['Scoring'];
            }else{
                $ToReturn = 0;
            }
            return $ToReturn;
        }
        function getDetalleScoring($Rut){
            $db = new DB();
            $query = "  SELECT
                            *
                        FROM
                            resultados_scoring
                        WHERE 
                            rut = '".$Rut."'
                        AND
                            fecha = DATE_FORMAT(NOW(),'%Y-%m-%d')";
            $ToReturn = $db->select($query);
            if(!$ToReturn){
                $ToReturn = array();
            }
            return $ToReturn;
        }
    }
?>