<?php

    /*
    ** Clase para mantener los periodos de las gestiones
    */

    class PeriodoGestion{

        /*
        ** Inserta en BD el periodo para cedente o para foco
        */
        public function creaPeriodo($fechaInicio, $fechaTermino, $Descripcion, $idCedente){


            $db = new Db();

            $fechaInicio = DateTime::createFromFormat('Y/m/d', $fechaInicio)->format('Y-m-d');
            $fechaTermino = DateTime::createFromFormat('Y/m/d', $fechaTermino)->format('Y-m-d');

            if ($idCedente != ""){

                $SqlInsertPeriodo = "INSERT INTO Periodo_Gestion_Cedente (Cedente, Fecha_Inicio, Fecha_Termino, descripcion) VALUES ('$idCedente', '$fechaInicio', '$fechaTermino', '$Descripcion')";
                

                // else{
                //     $SqlInsertPeriodo = "INSERT INTO Periodo_Gestion_Foco (Fecha_Inicio, Fecha_Termino, descripcion) VALUES ('$fechaInicio', '$fechaTermino', '$Descripcion')";
                // }

                $InsertPeriodo = $db -> query($SqlInsertPeriodo);

                if($InsertPeriodo){
                    $ToReturn = true;
                }else{
                    $ToReturn = false;
                }
            }else{
                $ToReturn = false;
            }

            return $ToReturn;      
        }   

        /*
        ** Elimina periodo de BD 
        ** $tipo puede tener dos valores: Foco o Cedente, pues este indica en que tabla eliminarel periodo
        */ 

        public function eliminaPeriodo($tipo, $idPeriodo){

            $db = new Db();

            // if ($tipo == "Foco"){
            // $tabla = "Periodo_Gestion_Foco"; 
            // $id = "id_periodo_foco";      
            // }else{
            $tabla = "Periodo_Gestion_Cedente";
            $id = "id_periodo_cedente";
            // } 

            $SqlEliminarPeriodo = "delete from ".$tabla." where ".$id." = ".$idPeriodo;
            $DeletePeriodo = $db -> query($SqlEliminarPeriodo);

            if($DeletePeriodo){
                $ToReturn = true;
            }else{
                $ToReturn = false;
            }

            return $ToReturn;
        }  

        public function listaPeriodo($idCedente){

            $db = new Db();
            $periodosArray = array();
        
            // if($idCedente == ""){
            //     $SqlPeriodo = "select * from Periodo_Gestion_Foco";
            //     $idPeriodo = "id_periodo_foco";
            // }else{
            $SqlPeriodo = "select * from Periodo_Gestion_Cedente where Cedente = ".$idCedente;
            $idPeriodo = "id_periodo_cedente";
            // } 

            $Periodos = $db->select($SqlPeriodo);
            if($Periodos !== false){
                foreach($Periodos as $Periodo){
                    $Array = array();
                    $Array['fechaInicio'] = $Periodo['Fecha_Inicio'];
                    $Array['fechaTermino'] = $Periodo['Fecha_Termino'];
                    if(isset($Periodo['descripcion'])){
                        $Array['Descripcion'] = $Periodo['descripcion'];
                    }else{
                        $Array['Descripcion'] = '';
                    }
                    $Array['Actions'] = $Periodo[$idPeriodo];            
                    array_push($periodosArray,$Array);
                }
            }

            return $periodosArray;
        }  
    }
?>