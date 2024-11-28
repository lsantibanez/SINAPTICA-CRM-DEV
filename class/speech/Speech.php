<?php
    class Speech{
        function __construct(){
        }

        function getPalabrasTableList(){
            $db = new DB();
            $SqlPalabras = "select * from Palabras_speech where Id_Cedente='".$_SESSION["cedente"]."'";
            $Palabras = $db->select($SqlPalabras);
            return $Palabras;
        }
        function CantidadSinonimos($idPalabra){
            $db = new DB();
            $SqlCantSinonimos = "select count(*) CantSinonimos from Sinonimos_Palabras_speech where id_palabra='".$idPalabra."'";
            $CantSinonimos = $db->select($SqlCantSinonimos);
            return $CantSinonimos[0]["CantSinonimos"];
        }
        function addPalabra($NombreMetrica,$Grupo,$ValorMetrica,$PesoGrupo,$Veces){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlInsert = "insert into Palabras_speech (NombreMetrica,Grupo,ValorMetrica,PesoGrupo,Veces,Id_Cedente) values ('".$NombreMetrica."','".$Grupo."','".$ValorMetrica."','".$PesoGrupo."','".$Veces."','".$_SESSION["cedente"]."')";
            $Insert = $db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getSinonimosTableList($idPalabra){
            $db = new DB();
            $SqlSinonimos = "select * from Sinonimos_Palabras_speech where id_palabra = '".$idPalabra."'";
            $Sinonimos = $db->select($SqlSinonimos);
            return $Sinonimos;
        }
        function addSinonimo($Nombre,$idPalabra){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlInsert = "insert into Sinonimos_Palabras_speech (Nombre,id_palabra) values ('".$Nombre."','".$idPalabra."')";
            $Insert = $db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function deletePalabra($idPalabra){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlDelete = "delete from Palabras_speech where id = '".$idPalabra."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $SqlDelete = "delete from Sinonimos_Palabras_speech where id_palabra = '".$idPalabra."'";
                $Delete = $db->query($SqlDelete);
                if($Delete){
                    $ToReturn["result"] = true;
                }
            }
            return $ToReturn;
        }
        function deleteSinonimo($idSinonimo){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlDelete = "delete from Sinonimos_Palabras_speech where id = '".$idSinonimo."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getPalabra($idPalabra){
            $db = new DB();
            $SqlPalabra = "select * from Palabras_speech where id='".$idPalabra."'";
            $Palabra = $db->select($SqlPalabra);
            return $Palabra[0];
        }
        function updatePalabra($idPalabra,$NombreMetrica,$Grupo,$ValorMetrica,$PesoGrupo,$Veces){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlUpdate = "update Palabras_speech set NombreMetrica='".$NombreMetrica."',Grupo='".$Grupo."',ValorMetrica='".$ValorMetrica."',PesoGrupo='".$PesoGrupo."',Veces='".$Veces."' where id='".$idPalabra."'";
            $Update = $db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getSinonimo($idSinonimo){
            $db = new DB();
            $SqlSinonimo = "select * from Sinonimos_Palabras_speech where id='".$idSinonimo."'";
            $Sinonimo = $db->select($SqlSinonimo);
            return $Sinonimo[0];
        }
        function updateSinonimo($idSinonimo,$Nombre){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlUpdate = "update Sinonimos_Palabras_speech set Nombre='".$Nombre."' where id='".$idSinonimo."'";
            $Update = $db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getMonthsFromTranscriptions(){
            $db = new DB();
            $SqlMonths = "select
                                MONTH(gestion_ult_trimestre.fecha_gestion) as Month,
                                YEAR(gestion_ult_trimestre.fecha_gestion) as Year
                            from
                                Transcripciones_speech
                                    inner join gestion_ult_trimestre on gestion_ult_trimestre.id_gestion = Transcripciones_speech.id_gestion
                            GROUP BY
                                MONTH(gestion_ult_trimestre.fecha_gestion),
                                YEAR(gestion_ult_trimestre.fecha_gestion)";
            $Months = $db->select($SqlMonths);
            return $Months;
        }
        function getDistribuidoresTableList($Mes){
            $Fecha = $Mes;
            $Mes = date("m",strtotime($Fecha));
            $Ano = date("Y",strtotime($Fecha));
            $db = new DB();
            $SqlDistribuidores = "select
                                        Distribuidores_speech.nombre,
                                        Distribuidores_speech.id,
                                        Distribuidores_speech.Id_Cedente
                                    from
                                        Distribuidores_speech
                                            INNER JOIN Transcripciones_speech on Transcripciones_speech.id_distribuidor = Distribuidores_speech.id
                                            inner join gestion_ult_trimestre on gestion_ult_trimestre.id_gestion = Transcripciones_speech.id_gestion
                                    where
                                        Id_Cedente='".$_SESSION["cedente"]."' AND
                                        MONTH(gestion_ult_trimestre.fecha_gestion) = '".$Mes."' AND
                                        YEAR(gestion_ult_trimestre.fecha_gestion) = '".$Ano."'
                                    GROUP BY
                                        Distribuidores_speech.nombre,
                                        Distribuidores_speech.id,
                                        Distribuidores_speech.Id_Cedente";
            $Distribuidores = $db->select($SqlDistribuidores);
            return $Distribuidores;
        }
        function CantidadTranscripciones_Distribuidor($idDistribuidor,$Mes){
            $Fecha = $Mes;
            $Mes = date("m",strtotime($Fecha));
            $Ano = date("Y",strtotime($Fecha));
            $db = new DB();
            $SqlCantTranscripciones = "select
                                            count(*) as Cantidad
                                        from
                                            Transcripciones_speech
                                                inner join gestion_ult_trimestre on gestion_ult_trimestre.id_gestion = Transcripciones_speech.id_gestion
                                        where
                                        Transcripciones_speech.id_distribuidor='".$idDistribuidor."' AND
                                        MONTH(gestion_ult_trimestre.fecha_gestion) = '".$Mes."' AND
                                        YEAR(gestion_ult_trimestre.fecha_gestion) = '".$Ano."'";
            $CantTranscripciones = $db->select($SqlCantTranscripciones);
            return $CantTranscripciones[0]["Cantidad"];
        }
        function GetReporteSemanalByDistribuidor($idDistribuidor,$Mes){
            $ToReturn = array();
            $Fecha = $Mes;
            $Mes = date("m",strtotime($Fecha));
            $Ano = date("Y",strtotime($Fecha));
            $db = new DB();
            $SqlCantTranscripcionesByWeek = "select
                                                WEEK(gestion_ult_trimestre.fecha_gestion) as Week,
                                                count(*) as Cantidad
                                            from
                                                Transcripciones_speech
                                                    inner join gestion_ult_trimestre on gestion_ult_trimestre.id_gestion = Transcripciones_speech.id_gestion
                                            where
                                                Transcripciones_speech.id_distribuidor='".$idDistribuidor."' AND
                                                MONTH(gestion_ult_trimestre.fecha_gestion) = '".$Mes."' AND
                                                YEAR(gestion_ult_trimestre.fecha_gestion) = '".$Ano."'
                                            GROUP BY
                                                WEEK(gestion_ult_trimestre.fecha_gestion)";
            $CantTranscripcionesByWeek = $db->select($SqlCantTranscripcionesByWeek);
            $Semanas = getSemanasMes($Ano,$Mes);
            $WeeksToReturn = array();
            foreach($Semanas as $Semana){
                $ArrayTmp = array();
                $ArrayTmp["Semana"] = $Semana["WeekTxt"];
                $Cantidad = 0;
                foreach($CantTranscripcionesByWeek as $Week){
                    if($Week["Week"] == $Semana["Week"]){
                        $Cantidad = $Week["Cantidad"];
                    }
                }
                $ArrayTmp["Cantidad"] = $Cantidad;
                array_push($WeeksToReturn,$ArrayTmp);
            }
            return $WeeksToReturn;
        }
        function getTranscripcionesTableList($idDistribuidor,$Mes){
            $Fecha = $Mes;
            $Mes = date("m",strtotime($Fecha));
            $Ano = date("Y",strtotime($Fecha));
            $db = new DB();
            $SqlTranscripciones = "SELECT
                                        Transcripciones_speech.id AS Transcripcion,
                                        gestion_ult_trimestre.fechahora AS FechaHora,
                                        GROUP_CONCAT(Palabras_speech.NombreMetrica,' ') AS PalabrasClaves,
                                        (   SELECT COUNT(*) 
                                            FROM Palabras_Claves_Transcripcion_speech 
                                            WHERE id_transcripcion = Transcripciones_speech.id) 
                                        AS PalabrasEncontradas
                                    FROM
                                        Distribuidores_speech
                                            INNER JOIN Transcripciones_speech ON Transcripciones_speech.id_distribuidor = Distribuidores_speech.id
                                            INNER JOIN gestion_ult_trimestre ON gestion_ult_trimestre.id_gestion = Transcripciones_speech.id_gestion
                                            LEFT JOIN Palabras_Claves_Transcripcion_speech ON Palabras_Claves_Transcripcion_speech.id_transcripcion = Transcripciones_speech.id
                                            LEFT JOIN Palabras_speech ON Palabras_speech.id = Palabras_Claves_Transcripcion_speech.id_palabra
                                    WHERE
                                        Distribuidores_speech.Id_Cedente = '".$_SESSION["cedente"]."' AND
                                        MONTH(gestion_ult_trimestre.fecha_gestion) = '".$Mes."' AND
                                        YEAR(gestion_ult_trimestre.fecha_gestion) = '".$Ano."' AND
                                        Distribuidores_speech.id = '".$idDistribuidor."'
                                    GROUP BY
                                        Transcripciones_speech.id";
            $Transcripciones = $db->select($SqlTranscripciones);
            return $Transcripciones;
        }
        function getTranscripcion($idTranscripcion){
            $db = new DB();
            $SqlTranscripcion = "select
                                    Transcripciones_speech.Transcripcion as Transcripcion,
                                    gestion_ult_trimestre.url_grabacion as URL,
                                    gestion_ult_trimestre.nombre_grabacion as NombreGrabacion
                                from
                                    Transcripciones_speech
                                        inner join gestion_ult_trimestre on gestion_ult_trimestre.id_gestion = Transcripciones_speech.id_gestion
                                where
                                    id='".$idTranscripcion."'";
            $Transcripcion = $db->select($SqlTranscripcion);
            return $Transcripcion[0];
        }
        function GetReporteTranscripcionesPorDistribuidores($Mes){
            $db = new DB();
            $Fecha = $Mes;
            $Mes = date("m",strtotime($Fecha));
            $Ano = date("Y",strtotime($Fecha));
            $SqlDistribuidores = "select
                                        Distribuidores_speech.nombre as Distribuidor,
                                        count(*) as Transcripciones
                                    from
                                        Distribuidores_speech
                                            INNER JOIN Transcripciones_speech on Transcripciones_speech.id_distribuidor = Distribuidores_speech.id
                                            inner join gestion_ult_trimestre on gestion_ult_trimestre.id_gestion = Transcripciones_speech.id_gestion
                                    where
                                        Id_Cedente='".$_SESSION["cedente"]."' AND
                                        MONTH(gestion_ult_trimestre.fecha_gestion) = '".$Mes."' AND
                                        YEAR(gestion_ult_trimestre.fecha_gestion) = '".$Ano."'
                                    GROUP BY
                                        Distribuidores_speech.nombre";
            $Distribuidores = $db->select($SqlDistribuidores);
            return $Distribuidores;
        }
        function getPorcentajeCumplimiento($idTranscripcion){
            $db = new DB();
            $SqlPorcentaje = "SELECT
                                    AVG(Porcentaje) as Porcentaje
                                from
                                    (
                                    select
                                        sum(PesoGrupo) Porcentaje
                                    from
                                        Transcripciones_speech
                                            inner join Palabras_Claves_Transcripcion_speech on Palabras_Claves_Transcripcion_speech.id_transcripcion = Transcripciones_speech.id
                                            inner join Palabras_speech on Palabras_speech.id = Palabras_Claves_Transcripcion_speech.id_palabra
                                    where
                                        Transcripciones_speech.id = '".$idTranscripcion."'
                                    GROUP BY
                                        Palabras_speech.Grupo
                                    ) grupos";
            $Porcentaje = $db->select($SqlPorcentaje);
            if(count($Porcentaje) > 0){
                $Porcentaje = $Porcentaje[0]["Porcentaje"];
                if($Porcentaje == ""){
                    $Porcentaje = 0;
                }
            }else{
                $Porcentaje = 0;
            }
            return $Porcentaje;
        }
    }
?>