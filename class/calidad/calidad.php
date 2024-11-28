<?php
    class Calidad
    {
        public $dir;
        public $dirTmp;

        public $Filename;
        public $Date;
        public $Hora;
        public $Phone;
        public $Cartera;
        public $User;
        public $UrlGrabacion;

        public $Id_Evaluacion;
        public $Evaluacion_Final;
        public $Aspectos_Fortalecer;
        public $Aspectos_Corregir;
        public $Compromiso_Ejecutivo;

        public $TipoCierre;

        public $Id_Personal;
        public $Id_Usuario;
        public $Id_Grabacion;


        public $Description;
        public $Esperado;
        public $Ponderacion;
        public $Nota;
        public $CalificacionPonderada;
        public $Observacion;
        public $Resumen;

        public $startDate;
        public $endDate;

        public $Id_Mandante;
        public $Id_Cedente;

        public $EvaluatedColum;
        public $EvaluatedValue;

        public $Id_Cierre;

        public $Tipificacion;

        public $NotaMaximaEvaluacion;
        private $db;

        function __construct()
        {
            $this->db = new Db();
            $this->dir = "../../Records/";
            $this->dirTmp = "../../Records/Tmp/";
            // $this->dirTmp = $_SERVER['DOCUMENT_ROOT'] . "/foco/Records/Tmp/";
            $this->Id_Usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario']: "";
            $this->Id_Mandante = isset($_SESSION['mandante']) ? $_SESSION['mandante'] : "";
            $this->Id_Cedente = isset($_SESSION['cedente']) ? $_SESSION['cedente']: "";
            if(isset($_SESSION['MM_UserGroup'])){
                if($this->isUserMandante()){
                    switch($_SESSION['MM_UserGroup']){
                        case 2:
                            $this->EvaluatedColum = ",bySupervisorMandante";
                            $this->EvaluatedValue = ",1";
                        break;
                        case 4:
                            $this->EvaluatedColum = ",byEjecutivoMandante";
                            $this->EvaluatedValue = ",1";
                        break;
                        case 6:
                            $this->EvaluatedColum = ",byCalidadMandante";
                            $this->EvaluatedValue = ",1";
                        break;
                    }
                }else{
                    switch($_SESSION['MM_UserGroup']){
                        case 2:
                            $this->EvaluatedColum = ",bySupervisorSystem";
                            $this->EvaluatedValue = ",1";
                        break;
                        case 4:
                            $this->EvaluatedColum = ",byEjecutivoSystem";
                            $this->EvaluatedValue = ",1";
                        break;
                        case 6:
                            $this->EvaluatedColum = ",byCalidadSystem";
                            $this->EvaluatedValue = ",1";
                        break;
                    }
                }
            }
            if(class_exists("DB"))
            {
                $FocoConfig = $this->getFocoConfig();
                $this->NotaMaximaEvaluacion = $FocoConfig['NotaMaximaEvaluacion'];
            }
        }

        function getRecords()
        {
            //$db = new Db();
            $RecordsArray = array();
            $Cont = 0;
            $WhereTipificacion = $this->Tipificacion != "" ? " and gestion_ult_trimestre.Id_TipoGestion='".$this->Tipificacion."' " : "";
            $SqlRecord = "select
                            grabacion_2.id,
                            grabacion_2.Nombre_Grabacion as Filename,
                            gestion_ult_trimestre.fechahora as Date,
                            grabacion_2.Cartera as Cartera,
                            grabacion_2.Usuario as User,
                            grabacion_2.Telefono as Phone,
                            grabacion_2.Estado,
                            gestion_ult_trimestre.url_grabacion,
                            Tipo_Contacto.Nombre as Tipificacion,
                            CASE WHEN ISNULL((select id from evaluaciones where Id_Usuario = '".$this->Id_Usuario."' and Id_Grabacion = grabacion_2.id LIMIT 1)) THEN '' ELSE 'Evaluada' END as Status,
                            (select CASE WHEN IpServidorDiscado <> '' AND IpServidorDiscadoAux <> '' THEN REPLACE(gestion_ult_trimestre.url_grabacion,IpServidorDiscado,IpServidorDiscadoAux) ELSE gestion_ult_trimestre.url_grabacion END from (select IpServidorDiscado,IpServidorDiscadoAux from fireConfig) tb1) as Listen,
                            grabacion_2.id as Evaluar,
                            grabacion_2.id as Imprimir
                        from
                            grabacion_2
                                inner join Cedente on Cedente.Nombre_Cedente = grabacion_2.Cartera
                                inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                                inner join gestion_ult_trimestre on gestion_ult_trimestre.id_gestion = grabacion_2.id_gestion
                                inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion
                        where
                            grabacion_2.usuario = '".$this->User."' and
                            mandante_cedente.Id_Mandante = '".$this->Id_Mandante."' and
                            grabacion_2.Fecha BETWEEN '".$this->startDate."' and '".$this->endDate."'
                            ".$WhereTipificacion."
                        group by
                            grabacion_2.id,
                            grabacion_2.Nombre_Grabacion,
                            grabacion_2.Fecha,
                            grabacion_2.Cartera,
                            grabacion_2.Usuario,
                            grabacion_2.Telefono,
                            grabacion_2.Estado,
                            gestion_ult_trimestre.url_grabacion,
                            Tipo_Contacto.Nombre
                        order by
                            Fecha";
		    $Records = $this->db->select($SqlRecord);
            /*foreach($Records as $Record){
                $this->Id_Grabacion = $Record['id'];
                $RecordArrayTmp = array();
                $RecordArrayTmp["Filename"] = utf8_encode($Record["Nombre_Grabacion"]);
                $RecordArrayTmp["Date"] = utf8_encode($Record["Fecha"]);
                $RecordArrayTmp["Cartera"] = utf8_encode($Record["Cartera"]);
                $RecordArrayTmp["User"] = utf8_encode($Record["Usuario"]);
                $RecordArrayTmp["Phone"] = $Record["Telefono"];
                //$RecordArrayTmp["Listen"] = $this->dir.$this->getRutaGrabaciones($Record["Nombre_Grabacion"])."/".$Record["Nombre_Grabacion"];
                $RecordArrayTmp["Listen"] = $this->getFinalUrlGrabacion($Record["url_grabacion"]);
                $RecordArrayTmp["Status"] = $this->hasEvaluation() ? "Evaluada" : "";//$Record["Estado"] == "1" ? "Evaluada" : "";
                $RecordArrayTmp["Evaluar"] = $Record["id"];
                $RecordArrayTmp["Imprimir"] = $Record["id"];
                $RecordArrayTmp["Tipificacion"] = utf8_encode($Record["Contacto"]);
                $RecordsArray[$Cont] = $RecordArrayTmp;
                $Cont++;
            }*/
            return $Records;
        }

        function getTipificacionGrabaciones()
        {
            //$db = new Db();
            $SqlTipificacion = "SELECT 
                                    gestion_ult_trimestre.Id_TipoGestion as id,
                                    Tipo_Contacto.Nombre as Contacto 
                                FROM 
                                    grabacion_2 
                                        INNER JOIN 
                                            Cedente on Cedente.Nombre_Cedente = grabacion_2.Cartera 
                                        INNER JOIN 
                                            mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente 
                                        INNER JOIN 
                                            gestion_ult_trimestre on gestion_ult_trimestre.id_gestion = grabacion_2.id_gestion
                                        INNER JOIN 
                                            Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion 
                                WHERE 
                                    grabacion_2.usuario = '".$this->User."' AND 
                                        mandante_cedente.Id_Mandante = '".$this->Id_Mandante."' AND 
                                        grabacion_2.Fecha BETWEEN '".$this->startDate."' AND 
                                        '".$this->endDate."' 
                                GROUP BY 
                                    gestion_ult_trimestre.Id_TipoGestion, 
                                    Tipo_Contacto.Nombre 
                                ORDER BY 
                                    Tipo_Contacto.Nombre";
		    $Tipificacion = $this->db->select($SqlTipificacion);
            return $Tipificacion;
        }
        function getRecordList(){
            //$db = new Db();
            $SqlRecord = "select * from grabacion_2 order by Fecha";
		    $Records = $this->db->select($SqlRecord);
            return $Records;
        }
        public function InsertRecordsToDataBase($WithAll = false){

            $Cedentes = $this->getCedenteArray();
            //$db = new Db();
            $SqlGestionesGrabaciones = "SELECT * FROM gestion_ult_trimestre WHERE url_grabacion <> '' AND sox = '0'";
            $GestionesGrabaciones = $this->db->select($SqlGestionesGrabaciones);
            foreach((array) $GestionesGrabaciones as $Gestion){
                $idGestion = $Gestion["id_gestion"];
                $URL = $Gestion["url_grabacion"];
                $UrlArray = explode("/",$URL);
                $File = $UrlArray[count($UrlArray) - 1];
                $Filename = $File;
                if($WithAll){
                    $Filename = $Gestion["nombre_grabacion"]."-all";
                }
                //echo $Filename."<br>";
                /* $Name = substr($Filename,0,strpos($Filename,"."));
                $Extension = substr($Filename,strpos($Filename,"."),strlen($Filename));
                $ArrayDataTmp = explode("_",substr($Name,strpos($Name,"-") + 1));
                $DataTmp1 = $ArrayDataTmp[0];
                $Phone = $ArrayDataTmp[1];
                $Cartera = $ArrayDataTmp[2];
                $DataTmp2 = substr($ArrayDataTmp[3],strpos($ArrayDataTmp[3],"-") + 1,strlen($ArrayDataTmp[3])); */
                $Cartera = $Gestion["cedente"];
                if(strlen($Cartera) == 1){
                    $Cartera = "00".$Gestion["cedente"];
                }
                if(strlen($Cartera) == 2){
                    $Cartera = "0".$Gestion["cedente"];
                }
                $this->Filename = $Filename;
                $this->Date = date("Ymd",strtotime($Gestion["fecha_gestion"]));
                $this->User = $Gestion["nombre_ejecutivo"];
                $this->Phone = $Gestion["fono_discado"];
                $this->UrlGrabacion = $URL;
                if(isset($Cedentes[$Cartera])){
                    $Cartera = $Cedentes[$Cartera];
                    $this->Cartera = $Cartera;
                    $this->addRecord($idGestion);
                }else{
                    //echo "No paso: ".$Cartera." - ".$Filename."<br>";
                }
            }
        }

        function getCedenteArray()
        {
            $ToReturn = array();
            //$db = new Db();
            $SqlCedentes = "select Cedente.Nombre_Cedente as NombreCedente, mandante_cedente.Lista_Vicidial as Campanas from mandante_cedente inner join Cedente on Cedente.Id_Cedente = mandante_cedente.Id_Cedente";
            $Cedentes = $this->db->select($SqlCedentes);
            foreach((array) $Cedentes as $Cedente){
                $NombreCedente = utf8_encode($Cedente['NombreCedente']);
                $Campanas = $Cedente['Campanas'];
                $ArrayCampanas = explode(",",$Campanas);
                if(count($ArrayCampanas) > 0){
                    foreach($ArrayCampanas as $Campana){
                        $NuevaCampana = $Campana;
                        if(strlen($NuevaCampana) == 1){
                            $NuevaCampana = "00".$Campana;
                        }
                        if(strlen($NuevaCampana) == 2){
                            $NuevaCampana = "0".$Campana;
                        }
                        $ToReturn[$NuevaCampana] = $NombreCedente;
                    }
                }
            }
            return $ToReturn;
        }

        function addRecord($idGestion)
        {
            //$db = new Db();
            $ToReturn = false;
            $SqlInsertRecord = "INSERT IGNORE INTO grabacion_2 (Nombre_Grabacion, Fecha, Cartera, Usuario, Telefono,url_grabacion,id_gestion) VALUES ('".$this->Filename."','".$this->Date."','".utf8_decode($this->Cartera)."','".$this->User."','".$this->Phone."','".$this->UrlGrabacion."','".$idGestion."') ON DUPLICATE KEY UPDATE Nombre_Grabacion=VALUES(Nombre_Grabacion), url_grabacion=VALUES(url_grabacion), id_gestion=VALUES(id_gestion)";
            $InsertRecord = $this->db->query($SqlInsertRecord);
            if($InsertRecord !== false){
                $ToReturn = true;
                $SqlUpdate = "UPDATE gestion_ult_trimestre SET sox = '1' WHERE id_gestion = '".$idGestion."'";
                $Update = $this->db->query($SqlUpdate);
            }else{
                $ToReturn = false;
            }
            return $ToReturn;
        }

        function AddEvaluation($ErrorCritico,$idErrorCritico,$ObservacionEvaluacion)
        {
            //$db = new Db();
            $ToReturn = false;
            $Cedente = "";
            $Nivel = $_SESSION["MM_UserGroup"];
            $Cedente = $this->getCedenteFromGrabacion($this->Id_Grabacion);
            /*switch($Nivel){
                case '4':
                $Cedente = $this->getIdCedenteFromGrabacion($this->Id_Grabacion);
                break;
                default:
                $Cedente = $_SESSION["cedente"];
                break;
            }*/
            $nombreSupervisor = $this->getNombreSupervisor($this->Id_Personal);
            $SqlUpdateLastEvaluation = "update evaluaciones set lastEvaluation='0' where Id_Grabacion='".$this->Id_Grabacion."' and Id_Usuario='".$this->Id_Usuario."'";
            $UpdateLastEvaluation = $this->db->query($SqlUpdateLastEvaluation);
            $SqlInsertEvaluation = "insert into evaluaciones (Id_Personal, Id_Usuario, Id_Grabacion, Evaluacion_Final, Fecha_Evaluacion, Id_Cedente,lastEvaluation,errorCritico,id_errorCritico,observacion,supervisor".$this->EvaluatedColum.") values('".$this->Id_Personal."','".$this->Id_Usuario."','".$this->Id_Grabacion."','".$this->Evaluacion_Final."',".$this->getFechaEvaluacion($this->Id_Grabacion).",'".$Cedente."','1','".$ErrorCritico."','".$idErrorCritico."','".$ObservacionEvaluacion."','".$nombreSupervisor."'".$this->EvaluatedValue.")";
            $InsertEvaluation = $this->db->query($SqlInsertEvaluation);
            if($InsertEvaluation !== false){
                $ToReturn = $this->getLastEvaluationAdded();
                $this->contadorEvaluaciones();
            }else{
                $ToReturn = false;
            }
            return $this->getLastEvaluationAdded();
        }
        
        function getNombreSupervisor($idEjecutivo){
            $ToReturn = "";
            //$db = new Db();
            $PersonalClass = new Personal();
            $PersonalClass->id = $idEjecutivo;
            $Ejecutivo = $PersonalClass->getPersonal();
            if(count($Ejecutivo) > 0){
                $Ejecutivo = $Ejecutivo[0];
                $idSupervisor = $Ejecutivo["id_supervisor"];
                if($idSupervisor != ""){
                    if($idSupervisor != "0"){
                        $PersonalClass->id = $idSupervisor;
                        $Supervisor = $PersonalClass->getPersonal();
                        $ToReturn = $Supervisor[0]["Nombre"];
                    }
                }
            }
            return $ToReturn;
        }
        function getFechaEvaluacion($idGrabacion){
            //$db = new Db();
            $ToReturn = "";
            $SqlFecha = "select Fecha from grabacion_2 where id='".$idGrabacion."'";
            $Fecha = $this->db->select($SqlFecha);
            $Fecha = $Fecha[0]["Fecha"];
            $ActualMonth = date('m');
            $GrabacionMonth = date('m',strtotime($Fecha));
            $ActualYear = date('y');
            $GrabacionYear = date('y',strtotime($Fecha));
            $ToReturn = "'".date('Y-m-t',strtotime($Fecha))."'";
            if($ActualYear == $GrabacionYear){
                if($ActualMonth == $GrabacionMonth){
                    $ToReturn = "NOW()";
                }
            }
            return $ToReturn;
        }
        function getIdCedenteFromGrabacion($IdGrabacion){
            $ToReturn = "";
            //$db = new Db();
            $SqlCedente = "select Cedente.Id_Cedente as cedente from grabacion_2 inner join Cedente on Cedente.Nombre_Cedente = grabacion_2.Cartera where grabacion_2.id='".$IdGrabacion."'";
		    $Cedentes = $this->db->select($SqlCedente);
            $ToReturn = $Cedentes[0]["cedente"];
            return $ToReturn;
        }
        function getLastEvaluationAdded(){
            $ToReturn = false;
            //$db = new Db();
            $SqlEvaluation = "select max(id) as id from evaluaciones where Id_Usuario = '".$this->Id_Usuario."' and Id_Grabacion = '".$this->Id_Grabacion."' and Id_Personal = '".$this->Id_Personal."' LIMIT 1";
		    $Evaluations = $this->db->select($SqlEvaluation);
            $Evaluation = $Evaluations[0]["id"];
            return $Evaluation;
        }
        function hasEvaluation(){
            $ToReturn = false;
            //$db = new Db();
            $SqlEvaluation = "select * from evaluaciones where Id_Usuario = '".$this->Id_Usuario."' and Id_Grabacion = '".$this->Id_Grabacion."'";
		    $Evaluations = $this->db->select($SqlEvaluation);
            if(count($Evaluations) > 0){
                $ToReturn = true;
            }
            return $ToReturn;
        }
        function getEvaluation($idUsuario = ""){
            if($idUsuario == ""){
                $idUsuario = $this->Id_Usuario;
            }
            //$db = new Db();
            $SqlEvaluation = "select * from evaluaciones where Id_Grabacion = '".$this->Id_Grabacion."' and Id_Usuario = '".$idUsuario."' and lastEvaluation='1'";
		    $Evaluations = $this->db->select($SqlEvaluation);
            return $Evaluations;
        }
        function getAllEvaluations(){
            //$db = new Db();
            $SqlEvaluation = "select P.Nombre as Evaluador, P.id_usuario as idUsuario, E.id as idEvaluacion, SUM(R.Nota) as Nota from evaluaciones E inner join Personal P on P.id_usuario = E.Id_Usuario inner join respuesta_opciones_afirmaciones_calidad R on R.Id_Evaluacion = E.id where Id_Grabacion = '".$this->Id_Grabacion."' and lastEvaluation='1' GROUP BY E.id";
		    $Evaluations = $this->db->select($SqlEvaluation);
            return $Evaluations;
        }
        function getEvaluationByUser(){
            //$db = new Db();
            $SqlEvaluation = "select evaluaciones.*, grabacion_2.Nombre_Grabacion, errores_criticos_calidad.Descripcion as descErrorCritico, Personal.Nombre as nombreEvaluador from evaluaciones inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion inner join Personal on Personal.id_usuario = evaluaciones.Id_Usuario left join errores_criticos_calidad on errores_criticos_calidad.id = evaluaciones.id_errorCritico where evaluaciones.Id_Grabacion = '".$this->Id_Grabacion."' and evaluaciones.Id_Usuario = '".$this->Id_Usuario."' and lastEvaluation='1'";
		    $Evaluations = $this->db->select($SqlEvaluation);
            return $Evaluations;
        }
/*function getEvaluationDetails(){
    //$db = new Db();
    $EvaluationsArray = array();
    $Cont = 0;
    $SqlEvaluation = "select * from detalle_evaluaciones where Id_Evaluacion = '".$this->Id_Evaluacion."' order by resumen ASC";
    $Evaluations = $this->db->select($SqlEvaluation);
    foreach($Evaluations as $Evaluation){
        $EvaluationArray = array();
        $EvaluationArray['Nombre'] = $Evaluation["resumen"];
        $EvaluationArray['Descripcion'] = $Evaluation["Descripcion"];
        $EvaluationArray['Esperado'] = ($Evaluation["Esperado"]);
        $EvaluationArray['Ponderacion'] = number_format($Evaluation["Ponderacion"], 2, '.', '');
        $EvaluationArray['Nota'] = number_format($Evaluation["Nota"], 2, '.', '');
        $EvaluationArray['CalificacionPonderada'] = number_format(($Evaluation["Ponderacion"] * $Evaluation["Nota"]) / 100, 2,'.','');
        $EvaluationArray['ID'] = "";
        $EvaluationArray['Actions'] = "";
        $EvaluationsArray[$Cont] = $EvaluationArray;
        $Cont++;
    }
    return $EvaluationsArray;
}*/
        function getEvaluationTemplate($idMandante,$idCedente,$idPauta=""){
            //$db = new Db();
            $EvaluationsArray = array();
            $Cont = 0;
            $PautaWhere = $idPauta == "" ? " AND contenedor_competencias.Id_TipoContacto='0' " : " AND contenedor_competencias.id='".$idPauta."' ";
            $SqlEvaluation = "SELECT
                                    competencias.id,
                                    competencias.nombre,
                                    competencias.tag,
                                    competencias.descripcion,
                                    competencias.ponderacion,
                                    GROUP_CONCAT(descripcion_simple)
                                    AS PalabrasClaves
                                FROM
                                    competencias_calidad competencias
                                    INNER JOIN contenedor_competencias_calidad contenedor_competencias on contenedor_competencias.id = competencias.id_contenedor
                                    INNER JOIN contenedor_competencias_calidad_Cedente contenedor_competencias_Cedente on contenedor_competencias_Cedente.id_contenedor = contenedor_competencias.id
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = contenedor_competencias_Cedente.Id_Cedente
                                    INNER JOIN dimensiones_competencias_calidad dimensiones on dimensiones.id_competencia = competencias.id
                                    INNER JOIN afirmaciones_dimensiones_competencias_calidad afirmaciones on afirmaciones.id_dimension = dimensiones.id
                                WHERE
                                    -- mandante_cedente.Id_Mandante = '".$idMandante."' and
                                    mandante_cedente.Id_Cedente = '".$idCedente."'
                                    ".$PautaWhere."
                                GROUP BY
                                    competencias.id,
                                    competencias.nombre,
                                    competencias.tag,
                                    competencias.descripcion,
                                    competencias.ponderacion
                                ORDER BY
                                    competencias.nombre";
		    $Evaluations = $this->db->select($SqlEvaluation);
            foreach($Evaluations as $Evaluation){
                $EvaluationArray = array();
                $EvaluationArray['Nombre'] = utf8_encode($Evaluation["nombre"]);
                $EvaluationArray['Tag'] = utf8_encode($Evaluation["tag"]);
                $EvaluationArray['Descripcion'] = utf8_encode($Evaluation["PalabrasClaves"]);
                $EvaluationArray['Esperado'] = utf8_encode($Evaluation["descripcion"]);
                $EvaluationArray['Ponderacion'] = number_format($Evaluation["ponderacion"], 2, '.', '');
                $EvaluationArray['Nota'] = number_format(0, 2, '.', '');
                $EvaluationArray['ID'] = $Evaluation["id"];
                $EvaluationsArray[$Cont] = $EvaluationArray;
                $Cont++;
            }
            return $EvaluationsArray;
        }
        function getEvaluationTemplateByPerfil($idPauta,$idMandante){
            //$db = new Db();
            $EvaluationsArray = array();
            $Cont = 0;
            
            $SqlEvaluation = "  SELECT 
                                    competencias_calidad.id, 
                                    competencias_calidad.nombre, 
                                    competencias_calidad.Esperado, 
                                    competencias_calidad.descripcion, 
                                    competencias_calidad.ponderacion, 
                                    competencias_calidad.tag 
                                FROM 
                                    competencias_calidad
                                        INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = competencias_calidad.id_contenedor
                                        INNER JOIN contenedor_competencias_calidad_Cedente contenedor_competencias_Cedente on contenedor_competencias_Cedente.id_contenedor = contenedor_competencias_calidad.id
                                        INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = contenedor_competencias_Cedente.Id_Cedente
                                        INNER JOIN dimensiones_competencias_calidad on competencias_calidad.id  = dimensiones_competencias_calidad.id_competencia
                                        INNER JOIN afirmaciones_dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                        INNER JOIN respuesta_opciones_afirmaciones_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                WHERE
                                    contenedor_competencias_calidad.id='".$idPauta."' AND
                                    mandante_cedente.Id_Mandante='".$idMandante."'
                                GROUP BY 
                                    competencias_calidad.id, 
                                    competencias_calidad.nombre, 
                                    competencias_calidad.Esperado, 
                                    competencias_calidad.descripcion, 
                                    competencias_calidad.ponderacion, 
                                    competencias_calidad.tag
                                ORDER BY 
                                    competencias_calidad.nombre";

            $Evaluations = $this->db->select($SqlEvaluation);
            
            if($Evaluations){
                foreach($Evaluations as $Evaluation){
                    $EvaluationArray = array();
                    $EvaluationArray['Nombre'] = utf8_encode($Evaluation["nombre"]);
                    $EvaluationArray['Descripcion'] = utf8_encode($Evaluation["descripcion"]);
                    $EvaluationArray['Esperado'] = utf8_encode($Evaluation["Esperado"]);
                    $EvaluationArray['Ponderacion'] = number_format($Evaluation["ponderacion"], 2, '.', '');
                    $EvaluationArray['Nota'] = number_format(0, 2, '.', '');
                    $EvaluationArray['CalificacionPonderada'] = number_format(0, 2, '.', '');
                    $EvaluationArray['ID'] = "";
                    $EvaluationArray['Actions'] = "";
                    $EvaluationsArray[$Cont] = $EvaluationArray;
                    $Cont++;
                }
            }
            return $EvaluationsArray;
        }
        function deleteEvaluationDetails(){
            //$db = new Db();
            $ToReturn = false;
            $SqlDeleteEvaluacionDetail = "delete from respuesta_opciones_afirmaciones_calidad where Id_Evaluacion = ".$this->Id_Evaluacion;
            $DeleteEvaluacionDetail = $this->db->query($SqlDeleteEvaluacionDetail);
            if($DeleteEvaluacionDetail !== false){
                $ToReturn = true;
            }else{
                $ToReturn = false;
            }
            return $ToReturn;
        }
        function addEvaluationDetails($Competencias){
            //$db = new Db();
            foreach($Competencias as $Competencia){
                if($Competencia){
                    foreach($Competencia as $Afirmacion){
                        $ArrayAfirmacion = explode("|",$Afirmacion);
                        $idAfirmacion = $ArrayAfirmacion[0];
                        $notaAfirmacion = number_format($ArrayAfirmacion[1],5);
                        $valorAfirmacion = $ArrayAfirmacion[2];
                        $SqlInsertAfirmacion = "insert into respuesta_opciones_afirmaciones_calidad (Id_Evaluacion, id_afirmacion, Id_Mandante, Valor, Nota) values('".$this->Id_Evaluacion."', '".$idAfirmacion."', '".$this->Id_Mandante."', '".$valorAfirmacion."', '".$notaAfirmacion."')";
                        $InsertAfirmacion = $this->db->query($SqlInsertAfirmacion);
                    }
                }
            }
        }
        function updateEvaluation($ErrorCritico,$idErrorCritico,$ObservacionEvaluacion){
            //$db = new Db();
            $ToReturn = false;
            if(isset($_SESSION["Autenticated"])){
                $this->getEvaluationResumeToAudit($this->Id_Grabacion,$this->Id_Usuario,"Actualización");
                $SqlUpdateEvaluation = "update evaluaciones set errorCritico='".$ErrorCritico."', id_errorCritico='".$idErrorCritico."', observacion='".$ObservacionEvaluacion."' where Id_Grabacion='".$this->Id_Grabacion."' and Id_Usuario = '".$this->Id_Usuario."' AND lastEvaluation='1'";
                $UpdateEvaluation = $this->db->query($SqlUpdateEvaluation);
                $ToReturn = $this->getEvaluationID();
                unset($_SESSION["Autenticated"]);
            }
            return $ToReturn;
        }
        function getEvaluationID(){
            //$db = new Db();
            $SqlEvaluation = "select id from evaluaciones where Id_Grabacion = '".$this->Id_Grabacion."' and Id_Usuario = '".$this->Id_Usuario."' and lastEvaluation='1'";
		    $Evaluations = $this->db->select($SqlEvaluation);
            return $Evaluations[0]["id"];
        }
        function getEvaluationResumeToAudit($idGrabacion,$idUsuario,$tipoAutorizacion){
            //$db = new Db();
            $ToReturn = array();
            $SqlEvaluacion = "SELECT * FROM evaluaciones WHERE Id_Grabacion='".$idGrabacion."' and Id_Usuario = '".$idUsuario."' AND lastEvaluation='1'";
            $Evaluacion = $this->db->select($SqlEvaluacion);
            if(count($Evaluacion) > 0){
                $Evaluacion = $Evaluacion[0];
                $idEvaluacion = $Evaluacion["id"];
                $errorCritico = $Evaluacion["errorCritico"];
                $errorCriticoDesc = "";
                if(($errorCritico != "") && ($errorCritico != "0")){
                    $idErrorCritico = $Evaluacion["id_errorCritico"];
                    $SqlErrorCritico = "SELECT * FROM errores_criticos_calidad WHERE id='".$idErrorCritico."'";
                    $ErrorCritico = $this->db->select($SqlErrorCritico);
                    if(count($ErrorCritico) > 0){
                        $ErrorCritico = $ErrorCritico[0];
                        $errorCriticoDesc = $ErrorCritico["Descripcion"];
                    }
                }
                $idUsuarioAdministrador = $_SESSION["Autenticated"];
                $SqlInsertAuditoria = "INSERT INTO auditoria_calidad (idUsuario, idAdministrador, idEvaluacion, errorCritico, tipoAutorizacion, fechaAutorizacion) VALUES ('".$idUsuario."', '".$idUsuarioAdministrador."', '".$idEvaluacion."', '".$errorCriticoDesc."', '".$tipoAutorizacion."', NOW())";
                $idAuditoria = $this->db->insert($SqlInsertAuditoria);
                if($idAuditoria){
                    $SqlCompetencias = "SELECT
                                            E.id as idEvaluacion,
                                            E.Fecha_Evaluacion as fechaEvaluacion,
                                            CC.nombre as Competencia,
                                            ADCC.nombre as Afirmacion,
                                            OACC.nombre as Opcion,
                                            SUM(ROAC.Nota) as Nota
                                        FROM
                                            foco.evaluaciones E
                                                INNER JOIN foco.Cedente Ced on Ced.Id_Cedente = E.Id_Cedente
                                                INNER JOIN foco.respuesta_opciones_afirmaciones_calidad ROAC on ROAC.Id_Evaluacion = E.id
                                                INNER JOIN foco.afirmaciones_dimensiones_competencias_calidad ADCC on ADCC.id = ROAC.id_afirmacion
                                                LEFT JOIN foco.opciones_afirmaciones_competencias_calidad OACC on OACC.valor = ROAC.Valor AND OACC.id_afirmacion = ADCC.id
                                                INNER JOIN foco.dimensiones_competencias_calidad DCC on DCC.id = ADCC.id_dimension
                                                INNER JOIN foco.competencias_calidad CC on CC.id = DCC.id_competencia
                                        WHERE
                                            E.id='".$idEvaluacion."'
                                        GROUP BY
                                            E.id,
                                            CC.id";
                    $Competencias = $this->db->select($SqlCompetencias);
                    foreach($Competencias as $Competencia){
                        $Competencias = $Competencia["Competencia"];
                        $Nota = $Competencia["Nota"];
                        
                        $SqlInsert = "INSERT INTO evaluaciones_auditoria_calidad (idAuditoria, competencia, notaCompetencia) VALUES ('".$idAuditoria."', '".$Competencias."', '".$Nota."')";
                        $Insert = $this->db->query($SqlInsert);
                    }
                }
            }
        }
        function getEvaluations_Managment(){
            //$db = new Db();
            $EvaluationsArray = array();
            $Cont = 0;
            $SqlEvaluation = "select * from mantenedor_evaluaciones order by id";
		    $Evaluations = $this->db->select($SqlEvaluation);
            foreach($Evaluations as $Evaluation){
                $EvaluationArray = array();
                $EvaluationArray['Descripcion'] = utf8_encode($Evaluation["Descripcion"]);
                $EvaluationArray['Ponderacion'] = number_format($Evaluation["Ponderacion"], 2, '.', '');
                $EvaluationArray['Actions'] = $Evaluation["id"];
                $EvaluationsArray[$Cont] = $EvaluationArray;
                $Cont++;
            }
            return $EvaluationsArray;
        }
        function AddEvaluation_Managment(){
            //$db = new Db();
            $ToReturn = false;
            $SqlInsertEvaluation = "insert into mantenedor_evaluaciones (Descripcion, Ponderacion) values('".$this->Description."','".$this->Ponderacion."')";
            $InsertEvaluation = $this->db->query($SqlInsertEvaluation);
            if($InsertEvaluation !== false){
                $ToReturn = $this->db->getLastID("mantenedor_evaluaciones");
            }else{
                $ToReturn = false;
            }
            return $this->db->getLastID("mantenedor_evaluaciones");
        }
        function updateEvaluation_Managment(){
            //$db = new Db();
            $ToReturn = false;
            $SqlUpdateEvaluation = "update mantenedor_evaluaciones set Descripcion = '".$this->Description."', Ponderacion = '".$this->Ponderacion."' where id='".$this->Id_Evaluacion."' ";
            $UpdateEvaluation = $this->db->query($SqlUpdateEvaluation);
            if($UpdateEvaluation !== false){
                $ToReturn = true;
            }else{
                $ToReturn = false;
            }
            return $ToReturn;
        }
        function deleteEvaluation_Managment(){
            //$db = new Db();
            $ToReturn = false;
            $SqlDeleteEvaluacion = "delete from mantenedor_evaluaciones where id = ".$this->Id_Evaluacion;
            $DeleteEvaluacion = $this->db->query($SqlDeleteEvaluacion);
            if($DeleteEvaluacion !== false){
                $ToReturn = true;
            }else{
                $ToReturn = false;
            }
            return $ToReturn;
        }
        function getCarteraList(){
            //$db = new Db();
            $SqlCartera = "select distinct Cartera from grabacion_2 INNER JOIN Cedente on Cedente.Nombre_Cedente = grabacion_2.Cartera INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente INNER JOIN mandante on mandante.id = mandante_cedente.Id_Mandante where mandante.id = '".$this->Id_Mandante."' order by grabacion_2.Cartera";
		    $Carteras = $this->db->select($SqlCartera);
            return $Carteras;
        }
        function getEvaluationsFromRecords($Records){
            //$db = new Db();
            $SqlRecord = "select * from evaluaciones where Id_Grabacion in (".$Records.") and Id_Usuario = '".$this->Id_Usuario."' and lastEvaluation='1' order by id";
		    $Records = $this->db->select($SqlRecord);
            return $Records;
        }
        function getRecordListEvaluadosAjax($Periodo = ""){
            //$db = new Db();
            $PersonalClass = new Personal();
            $PersonalClass->Username = $this->User;
            $Id_Personal = $PersonalClass->getPersonalIDFromUsername();
            $RecordsArray = array();
            $Cont = 0;
            $WhereTipificacion = $this->Tipificacion != "" ? " and gestion_ult_trimestre.Id_TipoGestion='".$this->Tipificacion."' " : "";
            $Desde = date('Ym01',strtotime($Periodo));
            $Hasta = date('Ymt',strtotime($Desde));
            $Nivel = $_SESSION["MM_UserGroup"];
            $WhereCedente = "";
            switch($Nivel){
                case '4':
                break;
                default:
                //$WhereCedente = " Cedente.Id_Cedente = '".$this->Id_Cedente."' and ";
                break;
            }
            $HaveCierre = $this->HizoCierre($Periodo);
            $WhereEvaluacionesCierres = "";
            if($HaveCierre){
                $WhereEvaluacionesCierres = " and find_in_set(evaluaciones.id,(select group_concat(Id_Evaluaciones) from cierre_evaluaciones where Id_Usuario = '".$this->Id_Usuario."' and Id_Personal = '".$Id_Personal."' and Id_Evaluaciones <> '')) <= 0 ";

                //$WhereEvaluacionesCierres = " and evaluaciones.id NOT IN (SELECT * from STRING_SPLIT((SELECT Id_Evaluaciones FROM cierre_evaluaciones where Id_Usuario = '".$this->Id_Usuario."' and Id_Personal = '".$Id_Personal."' and Id_Evaluaciones <> ''),','))";
            }
            $SqlRecord = "select
                            grabacion_2.id,
                            grabacion_2.Nombre_Grabacion as Filename,
                            grabacion_2.Fecha as Date,
                            grabacion_2.Cartera as Cartera,
                            grabacion_2.Usuario as User,
                            grabacion_2.Telefono as Phone,
                            grabacion_2.Estado,
                            gestion_ult_trimestre.url_grabacion,
                            Tipo_Contacto.Nombre as Tipificacion,
                            CASE WHEN ISNULL((select id from evaluaciones where Id_Usuario = '".$this->Id_Usuario."' and Id_Grabacion = grabacion_2.id LIMIT 1)) THEN '' ELSE 'Evaluada' END as Status,
                            (select CASE WHEN IpServidorDiscado <> '' AND IpServidorDiscadoAux <> '' THEN REPLACE(gestion_ult_trimestre.url_grabacion,IpServidorDiscado,IpServidorDiscadoAux) ELSE gestion_ult_trimestre.url_grabacion END from (select IpServidorDiscado,IpServidorDiscadoAux from fireConfig) tb1) as Listen,
                            grabacion_2.id as Evaluar,
                            grabacion_2.id as Imprimir
                        from evaluaciones
                            inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion
                            inner join Cedente on Cedente.Id_Cedente = evaluaciones.Id_Cedente
                            inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                            inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                            inner join gestion_ult_trimestre on gestion_ult_trimestre.id_gestion = grabacion_2.id_gestion
                            inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion
                        where
                            mandante.id = '".$this->Id_Mandante."' and
                            ".$WhereCedente."
                            grabacion_2.Usuario = '".$this->User."' and
                            grabacion_2.Fecha BETWEEN '".$Desde."' and '".$Hasta."' /*and
                            evaluaciones.Id_Usuario='".$this->Id_Usuario."'*/
                            ".$WhereEvaluacionesCierres."
                            ".$WhereTipificacion."
                        group by
                            grabacion_2.id,
                            grabacion_2.Nombre_Grabacion,
                            grabacion_2.Fecha,
                            grabacion_2.Cartera,
                            grabacion_2.Usuario,
                            grabacion_2.Telefono,
                            Tipo_Contacto.Nombre,
                            gestion_ult_trimestre.url_grabacion";
            $Records = $this->db->select($SqlRecord);
            /*foreach($Records as $Record){
                $this->Id_Grabacion = $Record['id'];
                $RecordArrayTmp = array();
                $RecordArrayTmp["Filename"] = $Record["Nombre_Grabacion"];
                $RecordArrayTmp["Date"] = $Record["Fecha"];
                $RecordArrayTmp["Cartera"] = utf8_encode($Record["Cartera"]);
                $RecordArrayTmp["User"] = $Record["Usuario"];
                $RecordArrayTmp["Phone"] = $Record["Telefono"];
                //$RecordArrayTmp["Listen"] = $this->dir.$this->getRutaGrabaciones($Record["Nombre_Grabacion"])."/".$Record["Nombre_Grabacion"];
                $RecordArrayTmp["Listen"] = $this->getFinalUrlGrabacion($Record["UrlGrabacion"]);
                $RecordArrayTmp["Status"] = $this->hasEvaluation() ? "Evaluada" : "";//$Record["Estado"] == "1" ? "Evaluada" : "";
                $RecordArrayTmp["Evaluar"] = $Record["id"];
                $RecordArrayTmp["Imprimir"] = $Record["id"];
                $RecordArrayTmp["Tipificacion"] = $Record["Tipificacion"];
                $RecordsArray[$Cont] = $RecordArrayTmp;
                $Cont++;
            }*/
            return $Records;
        }
        function getTipificacionGrabacionesEvaluadas($Periodo){
            //$db = new Db();
            $Desde = date('Ym01',strtotime($Periodo));
            $Hasta = date('Ymt',strtotime($Desde));
            $SqlTipificacion = "select
                            gestion_ult_trimestre.Id_TipoGestion as id,
                            Tipo_Contacto.Nombre as Tipificacion
                        from evaluaciones
                            inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion
                            inner join Cedente on Cedente.Id_Cedente = evaluaciones.Id_Cedente
                            inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                            inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                            inner join gestion_ult_trimestre on gestion_ult_trimestre.id_gestion = grabacion_2.id_gestion
                            inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion
                        where
                            mandante.id = '".$this->Id_Mandante."' and
                            grabacion_2.Usuario = '".$this->User."' and
                            grabacion_2.Fecha BETWEEN '".$Desde."' and '".$Hasta."'
                        group by
                            gestion_ult_trimestre.Id_TipoGestion,
                            Tipo_Contacto.Nombre";
            $Tipificacion = $this->db->select($SqlTipificacion);
            return $Tipificacion;
        }
        function isUserMandante(){
            $ToReturn = false;
            //$db = new Db();
            $SqlUser = "select * from Usuarios where id = '".$this->Id_Usuario."'";
		    $Users = $this->db->select($SqlUser);
            if($Users){
                foreach($Users as $User){
                    if($User["mandante"] != ""){
                        $ToReturn = true;
                    }
                }  
            }
            
            return $ToReturn;
        }
        function Empiezo(){
            $ToReturn = false;
            //$db = new Db();
            $SqlMandante = "select * from mandante where id = '".$this->Id_Mandante."'";
            $Mandantes = $this->db->select($SqlMandante);
            if($Mandantes){
                foreach($Mandantes as $Mandante){
                    if($this->isUserMandante()){
                        if($Mandante["Empieza"] == "1"){
                            $ToReturn = true;
                        }
                    }else{
                        if($Mandante["Empieza"] == "0"){
                            $ToReturn = true;
                        }
                    }
                }
            }
            return $ToReturn;
        }
        function PuedeHacerCierreDeProceso($Periodo = ""){
            $ToReturn = false;
            //$db = new Db();
            $Desde = date('Ym01',strtotime($Periodo));
            $Hasta = date('Ymt',strtotime($Desde));
            $SqlEvaluation = "select
                                    grabacion_2.id as id,
                                    grabacion_2.Nombre_Grabacion as Nombre_Grabacion,
                                    grabacion_2.Fecha as Fecha,
                                    grabacion_2.Cartera as Cartera,
                                    grabacion_2.Usuario as Usuario,
                                    grabacion_2.Telefono as Telefono
                                from evaluaciones
                                    inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion
                                    inner join Cedente on Cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                                    inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                                where
                                    evaluaciones.Id_Usuario = '".$this->Id_Usuario."' and
                                    mandante.id = '".$this->Id_Mandante."' and
                                    grabacion_2.Usuario = '".$this->User."' and
                                    grabacion_2.Fecha BETWEEN '".$Desde."' and '".$Hasta."'
                                group by
                                    grabacion_2.id,
                                    grabacion_2.Nombre_Grabacion,
                                    grabacion_2.Fecha,
                                    grabacion_2.Cartera,
                                    grabacion_2.Usuario,
                                    grabacion_2.Telefono";
		    $Evaluations = $this->db->select($SqlEvaluation);
            if(count($Evaluations) > 0){
                $ToReturn = true;
            }
            return $ToReturn;
        }
        function CierreDeProceso($Periodo){
            $Evaluations = $this->GetEvaluatedEvaluations($Periodo);
            $this->InsertCierreDeProceso($Evaluations,$Periodo);
        }
        function GetEvaluatedEvaluations($Periodo){
            $Desde = date('Ym01',strtotime($Periodo));
            $Hasta = date('Ymt',strtotime($Desde));
            //$db = new Db();
            $PersonalClass = new Personal();
            $PersonalClass->Username = $this->User;
            $Id_Personal = $PersonalClass->getPersonalIDFromUsername();
            $HaveCierre = $this->HizoCierre($Periodo);
            $WhereEvaluacionesCierres = "";
            if($HaveCierre){
                $WhereEvaluacionesCierres = " and find_in_set(evaluaciones.id,(select group_concat(Id_Evaluaciones) from cierre_evaluaciones where Id_Usuario = '".$this->Id_Usuario."' and Id_Personal = '".$Id_Personal."' and Id_Evaluaciones <> '')) <= 0 ";

                //$WhereEvaluacionesCierres = " and evaluaciones.id NOT IN (SELECT * from STRING_SPLIT((SELECT Id_Evaluaciones FROM cierre_evaluaciones where Id_Usuario = '".$this->Id_Usuario."' and Id_Personal = '".$Id_Personal."' and Id_Evaluaciones <> ''),','))";
            }
            $SqlEvaluation = "select
                                    grabacion_2.id as Id_Grabacion,
                                    grabacion_2.Nombre_Grabacion,
                                    grabacion_2.Cartera,
                                    grabacion_2.Usuario,
                                    grabacion_2.Telefono,
                                    evaluaciones.id
                                from evaluaciones
                                    inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion
                                    inner join Cedente on Cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                                    inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                                where
                                    evaluaciones.Id_Usuario = '".$this->Id_Usuario."' and
                                    mandante.id = '".$this->Id_Mandante."' and
                                    grabacion_2.Usuario = '".$this->User."' and
                                    grabacion_2.Fecha BETWEEN '".$Desde."' and '".$Hasta."' and
                                    evaluaciones.lastEvaluation='1'
                                    ".$WhereEvaluacionesCierres."
                                group by
                                    evaluaciones.id,
                                    grabacion_2.id,
                                    grabacion_2.Nombre_Grabacion,
                                    grabacion_2.Cartera,
                                    grabacion_2.Usuario,
                                    grabacion_2.Telefono";
		    $Evaluations = $this->db->select($SqlEvaluation);
            return $Evaluations;
        }
        function InsertCierreDeProceso($Evaluations,$Periodo){
            //$db = new Db();
            $PersonalClass = new Personal();
            $PersonalClass->Username = $this->User;
            $Id_Personal = $PersonalClass->getPersonalIDFromUsername();
            $ToReturn = false;
            $Nota = 0;
            $Ponderacion = 0;
            $CalfPonderada = 0;
            $Id_Evaluaciones = "";
            foreach($Evaluations as $Evaluation){
                $this->Id_Evaluacion = $Evaluation['id'];
                $Id_Evaluaciones = $Id_Evaluaciones ."". $Evaluation['id'].",";
                $ArrayResumenDetalle = $this->GetDetalleEvaluaciones_Resumen();
                if($ArrayResumenDetalle){
                    $Nota += $ArrayResumenDetalle["Nota"];
                }
            }
            $Id_Evaluaciones = substr($Id_Evaluaciones,0,strlen($Id_Evaluaciones) - 1);
            $Nota = $Nota / count($Evaluations);
            $Fecha = "'".date('Y-m-t',strtotime($Periodo))."'";
            $ActualMonth = date('m');
            $ActualYear = date('y');
            $PeriodoMonth = date('m', strtotime($Periodo));
            $PeriodoYear = date('y', strtotime($Periodo));
            if($ActualYear == $PeriodoYear){
                if($ActualMonth == $PeriodoMonth){
                    $Fecha = "NOW()";
                }
            }
            $SqlInsertCierre = "insert into cierre_evaluaciones (Id_Evaluaciones,tipo_cierre,Nota,Ponderacion,Calf_Ponderada,Id_Usuario,Id_Mandante,Id_Cedente,Id_Personal,Aspectos_Fortalecer,Aspectos_Corregir,Compromiso_Ejecutivo,fecha) values('".$Id_Evaluaciones."','".$this->TipoCierre."','".$Nota."','".$Ponderacion."','".$CalfPonderada."','".$this->Id_Usuario."','".$this->Id_Mandante."','".$this->Id_Cedente."','".$Id_Personal."','".$this->Aspectos_Fortalecer."','".$this->Aspectos_Corregir."','".$this->Compromiso_Ejecutivo."',".$Fecha.")";
            $InsertCierre = $this->db->query($SqlInsertCierre);
        }
        function GetDetalleEvaluaciones_Resumen(){
            $ToReturn = array();
            //$db = new Db();
            $Query = "select
                        AVG(Nota) as Nota,
                    from
                        respuesta_opciones_afirmaciones_calidad
                    where
                        Id_Evaluacion = '".$this->Id_Evaluacion."'";
            $EvaluationResume = $this->db->select($Query);
            if($EvaluationResume){
                foreach($EvaluationResume as $Evaluation){
                    $ToReturn["Nota"] = $Evaluation["Nota"];
                }
            }
            return $ToReturn;
        }
        function HizoCierre($Periodo = ""){
            $ToReturn = false;
            //$db = new Db();
            $PersonalClass = new Personal();
            $PersonalClass->Username = $this->User;
            $Id_Personal = $PersonalClass->getPersonalIDFromUsername();
            $Query = "select
                            id
                        from
                            cierre_evaluaciones
                        where
                            Id_Usuario = '".$this->Id_Usuario."' and
                            Id_Mandante = '".$this->Id_Mandante."' and
                            Id_Personal = '".$Id_Personal."' and
                            year(fecha) = year('".$Periodo."') and
                            month(fecha) = month('".$Periodo."') and
                            tipo_cierre = '1'";
            $Evaluations = $this->db->select($Query);
            if(count($Evaluations) > 0){
                $ToReturn = true;
            }
            return $ToReturn;
        }
        function getCierres($Month){
            //$db = new Db();
            $PersonalClass = new Personal();
            $PersonalClass->Username = $this->User;
            $Id_Personal = $PersonalClass->getPersonalIDFromUsername();
            $CierresArray = array();
            $Cont = 0;
            $Desde = date('Ym01',strtotime($Month));
            $Hasta = date('Ymt',strtotime($Desde));
            $SqlCierre = "select
                            *
                        from cierre_evaluaciones
                        where
                            Id_Mandante = '".$this->Id_Mandante."' and
                            Id_Usuario = '".$this->Id_Usuario."' and
                            Id_Personal = '".$Id_Personal."' and
                            fecha BETWEEN '".$Desde."' and '".$Hasta."'";
		    $Cierres = $this->db->select($SqlCierre);
            foreach($Cierres as $Cierre){
                $this->Id_Grabacion = $Cierre['id'];
                $CierreArrayTmp = array();
                $Nota = number_format($this->getNotaFromCierre($Cierre['id']),2);
                $Perfil = $this->getPerfilEjecutivoByNota($Nota);
                if($Perfil){
                    $CierreArrayTmp["PerfilEjecutivo"] = $Perfil["nombre"];
                }else{
                    $CierreArrayTmp["PerfilEjecutivo"] = '';
                }
                $CierreArrayTmp["NotaPeriodo"] = $Nota;
                $CierreArrayTmp["TipoCierre"] = $Cierre["tipo_cierre"];
                $CierreArrayTmp["Date"] = $Cierre["fecha"];
                $CierreArrayTmp["Visualizar"] = $Cierre["id"];
                $CierreArrayTmp["Imprimir"] = $Cierre["id"];
                $CierresArray[$Cont] = $CierreArrayTmp;
                $Cont++;
            }
            return $CierresArray;
        }
        function getCierre(){
            //$db = new Db();
            $SqlCierre = "select * from cierre_evaluaciones where id = '".$this->Id_Cierre."'";
		    $Cierres = $this->db->select($SqlCierre);
            return $Cierres;
        }
        function getEvaluationDetailsCierre($Evaluations){
            //$db = new Db();
            $EvaluationsArray = array();
            $Cont = 0;
            $SqlEvaluation = "select
                                    evaluaciones.id,
                                    grabacion_2.Nombre_Grabacion as Grabacion,
                                    AVG(respuesta_opciones_afirmaciones_calidad.valor) as Nota,
                                    grabacion_2.url_grabacion as UrlGrabacion
                                from evaluaciones
                                    inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    inner join grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion
                                where
                                    evaluaciones.id in(".$Evaluations.")
                                group by
                                    evaluaciones.id,
                                    grabacion_2.Nombre_Grabacion,
                                    grabacion_2.url_grabacion
                                order by 
                                    evaluaciones.id,
                                    grabacion_2.Nombre_Grabacion";
		    $Evaluations = $this->db->select($SqlEvaluation);
            foreach($Evaluations as $Evaluation){
                $EvaluationArray = array();
                $EvaluationArray['Nombre_Grabacion'] = $Evaluation["Grabacion"];
                //$EvaluationArray['Grabacion'] = $this->dir.$this->getRutaGrabaciones($Evaluation["Grabacion"])."/".$Evaluation["Grabacion"];//$this->dir.$Evaluation["Grabacion"];
                $EvaluationArray["Grabacion"] = $this->getFinalUrlGrabacion($Evaluation["UrlGrabacion"]);
                $EvaluationArray['Nota'] = number_format($Evaluation["Nota"], 2, '.', '');
                $EvaluationsArray[$Cont] = $EvaluationArray;
                $Cont++;
            }
            return $EvaluationsArray;
        }
        function getGeneralGraphDataByUserType($UserType,$Mandante,$Pauta,$Ejecutivo,$Type){
            $Headers = "";
            $Columns = "";
            $RestaMes = "";
            $CantXAxis = 0;
            switch($Type){
                case 'Mes':
                    $Headers = "WEEK(evaluaciones.Fecha_Evaluacion) as Week";
                    $Columns = "WEEK(evaluaciones.Fecha_Evaluacion)";
                    $RestaMes = "0";
                    $CantXAxis = 4;
                break;
                case 'Historico':
                    $Headers = "year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month";
                    $Columns = "year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion)";
                    $RestaMes = "5";
                    $CantXAxis = 5;
                break;
            }
            $WhereSoloActivos = $Ejecutivo == "" ? " and Personal.Activo = '1' " : "";
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Personal.Id_Personal = '".$Ejecutivo."'" : "";
            $ByUser = "";
            switch($UserType){
                case '1':
                    //Calidad Sistema
                    $ByUser = "byCalidadSystem";
                break;
                case '2':
                    //Calidad Mandante
                    $ByUser = "byCalidadMandante";
                break;
                case '3':
                    //Ejecutivo Sistema
                    $ByUser = "byEjecutivoSystem";
                break;
                case '4':
                    //Ejecutivo Mandantte
                    $ByUser = "byEjecutivoMandante";
                break;
                case '5':
                break;
                case '6':
                break;
            }
            //$db = new Db();
            $DateArray = $this->getDateFromServer();
            $Now = $DateArray["date"];
            $Now = new DateTime($Now);
            $Now->modify('last day of this month');
            $Now = $Now->format('Ymd');
            $SixMonthsAgo = strtotime ( '-'.$RestaMes.' months' , strtotime ( $Now ) ) ;
            $SixMonthsAgo = date ( 'Ym01' , $SixMonthsAgo );
            $Array = array();
            $Cont = 0;
            $Month = 1;
            $Inicio = date("Ymd",strtotime ('+0 months',strtotime($SixMonthsAgo)));
            $fechainicial = new DateTime($Inicio);
            $fechafinal = new DateTime($Now);
            $diferencia = $fechainicial->diff($fechafinal);
            
            switch($Type){
                case 'Mes':
                    $Cant = 4;
                break;
                case 'Historico':
                    $Cant = ( $diferencia->y * 12 ) + $diferencia->m;
                break;
            }

            $Meses = array();
            $MesActual = date("Ym01",strtotime($Inicio));
            $YearActual = date("Y",strtotime($Inicio));
            $CantEmpty = 0;
            /* for($i=1;$i<=$Cant;$i++){
                $DataArray = array();
                $DataArray[0] = $Month;
                $DataArray[1] = 0;
                //$DataArray[2] = date("Ym",strtotime($MesActual));
                //$Array[$Cont] = $DataArray;
                //array_push($Meses,$DataArray);
                switch($Type){
                    case 'Mes':
                        $Index = $i;
                    break;
                    case 'Historico':
                        $Index = date("Ym",strtotime($MesActual));
                    break;
                }

                $Meses[$Index] = $DataArray;
                $Month++;
                $MesActual = strtotime ( '+1 months' , strtotime ( $MesActual ) ) ;
                $MesActual = date ( 'Ym01' , $MesActual );   
            } */

            $SqlEvaluation = "select
                                    ".$Headers.", ROUND(AVG(valor),2) as Nota
                                from evaluaciones
                                    INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = mandante_cedente.Id_Cedente
                                    INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor
                                    INNER JOIN Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                where
                                    CAST(Fecha_Evaluacion AS DATE) BETWEEN '".$SixMonthsAgo."' and '".$Now."' and
                                    ".$ByUser." = 1 and evaluaciones.lastEvaluation='1' and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta." ".$WhereSoloActivos."
                                GROUP by ".$Columns."
                                ORDER BY ".$Columns;
                                
		    if($UserType == "1"){
                //echo $SqlEvaluation;
                //echo $Month;
            }
            $Evaluations = $this->db->select($SqlEvaluation);

            for($i=1;$i<=$Cant;$i++){
                $DataArray = array();
                $DataArray[0] = $Month;
                $DataArray[1] = 0;
                switch($Type){
                    case 'Mes':
                        $Index = $i;
                    break;
                    case 'Historico':
                        $Index = date("Ym",strtotime($MesActual));
                    break;
                }

                $Meses[$Index] = $DataArray;
                $Month++;
                $MesActual = strtotime ( '+1 months' , strtotime ( $MesActual ) ) ;
                $MesActual = date ( 'Ym01' , $MesActual );   
            }

            if(count($Evaluations) > 0){
                $Cant = count($Evaluations);
                switch($Type){
                    case 'Mes':
                        $CantEmpty = 0;
                    break;
                    case 'Historico':
                        $CantEmpty = 6 - $Cant;
                    break;
                }
                if($UserType == "1"){
                    //print_r($Array);
                }
                $Index = 1;
                if($Evaluations){
                    foreach($Evaluations as $Evaluation){
                        
                        switch($Type){
                            case "Mes":
                                $MonthDB = $MesActual;  
                                $YearDB = $YearActual;
                                if(!isset($Meses[$Index])){
                                    $Meses[$Index][0] = $Month;
                                    $Meses[$Index][1] = $Evaluation["Nota"];
                                }else{
                                    //$Meses[$Index][0] = $Month;
                                    $Meses[$Index][1] = $Evaluation["Nota"];
                                }
                                /* $Meses[$Index][0] = $Month;
                                $Meses[$Index][1] = $Evaluation["Nota"]; */
                            break;
                            case "Historico":
                                $MonthDB = strlen($Evaluation["Month"]) > 1 ? $Evaluation["Month"] : "0".$Evaluation["Month"];
                                $YearDB = $Evaluation["Year"];
                                if(!isset($Meses[$YearDB.$MonthDB])){
                                    $Meses[$YearDB.$MonthDB][0] = $Month;
                                    $Meses[$YearDB.$MonthDB][1] = $Evaluation["Nota"];
                                }else{
                                    //$Meses[$YearDB.$MonthDB][0] = $Month;
                                    $Meses[$YearDB.$MonthDB][1] = $Evaluation["Nota"];
                                }
                                /* $Meses[$YearDB.$MonthDB][0] = $Month;
                                $Meses[$YearDB.$MonthDB][1] = $Evaluation["Nota"]; */
                            break;
                        }
                        
                        $DataArray = array();
                        $DataArray[0] = $Month;
                        $DataArray[1] = $Evaluation["Nota"];
                        $Array[$Cont] = $DataArray;
                        $Month++;
                        $Cont++;
                        $Index++;
                    }
                }
            }else{
                for($i=1;$i<=$CantXAxis;$i++){
                    $DataArray = array();
                    $DataArray[0] = $Month;
                    $DataArray[1] = 0;
                    $Array[$Cont] = $DataArray;
                    if($i < $CantEmpty){
                        $Month++;
                        $Cont++;
                    }
                }
            }
            
            switch($Type){
                case 'Mes':
                    $CantEmpty = 0;
                break;
                case 'Historico':
                    $CantEmpty = 6 - $Cant;
                break;
            }
            $MesesTmp = array();
            foreach($Meses as $Mes){
                array_push($MesesTmp,$Mes);
            }
            /* if($UserType == "1"){
                print_r($Meses);
                print_r($MesesTmp);
            } */
            return $MesesTmp;
        }
        function getGeneralByEvaluationGraphDataByUserType($UserType,$Mandante,$Pauta,$Ejecutivo){
            
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Personal.Id_Personal = '".$Ejecutivo."'" : "";

            $WhereSoloActivos = $Ejecutivo == "" ? " and Personal.Activo = '1' " : "";

            $ByUser = "";
            $UserTypeName = "";
            switch($UserType){
                case '1':
                    //Calidad Sistema
                    $ByUser = "byCalidadSystem";
                    $UserTypeName = "calidad";
                break;
                case '2':
                    //Calidad Mandante
                    $ByUser = "byCalidadMandante";
                    $UserTypeName = "empresa";
                break;
                case '3':
                    //Ejecutivo Sistema
                    $ByUser = "byEjecutivoSystem";
                    $UserTypeName = "ejecutivo";
                break;
                case '4':
                    //Ejecutivo Mandantte
                    $ByUser = "byEjecutivoMandante";
                    $UserTypeName = "";
                break;
                case '5':
                break;
                case '6':
                break;
            }
            //$db = new Db();
            $DateArray = $this->getDateFromServer();
            $Now = $DateArray["date"];
            $Now = new DateTime($Now);
            $Now->modify('last day of this month');
            $Now = $Now->format('Ymd');
            $SixMonthsAgo = strtotime ( '-6 months' , strtotime ( $Now ) ) ;
            $SixMonthsAgo = date ( 'Ymd' , $SixMonthsAgo );
            $Array = array();
            $Cont = 0;
            $Month = 1;
            $SqlEvaluation = "select
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(valor),2) as Nota, competencias_calidad.nombre as Resumen
                                from evaluaciones
                                    INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                    INNER JOIN dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                    INNER JOIN competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    INNER JOIN Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                    INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = mandante_cedente.Id_Cedente
                                    INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor
                                where
                                    YEAR(Fecha_Evaluacion) = YEAR((select MAX(Fecha_Evaluacion) from evaluaciones where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")) and
                                    MONTH(Fecha_Evaluacion) = MONTH((select MAX(Fecha_Evaluacion) from evaluaciones where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")) and
                                    ".$ByUser." = 1 and evaluaciones.lastEvaluation='1' and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta." ".$WhereSoloActivos."
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion), competencias_calidad.nombre
                                ORDER BY year(evaluaciones.Fecha_Evaluacion) ASC, MONTH(evaluaciones.Fecha_Evaluacion) ASC, competencias_calidad.nombre ASC";
		    $Evaluations = $this->db->select($SqlEvaluation);
            if(count($Evaluations) > 0){
                foreach($Evaluations as $Evaluation){
                    $DataArray = array();
                    $DataArray["Evaluacion"] = utf8_encode($Evaluation["Resumen"]);
                    $DataArray["Nota"] = $Evaluation["Nota"];
                    $DataArray["UserTypeName"] = $UserTypeName;
                    $Array[$Cont] = $DataArray;
                    $Month++;
                    $Cont++;
                }
            }else{
                $SqlEvaluation = "SELECT
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(valor),2) as Nota, competencias_calidad.nombre as Resumen
                                FROM evaluaciones
                                    INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                    INNER JOIN dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                    INNER JOIN competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = mandante_cedente.Id_Cedente
                                    INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor
                                where
                                    YEAR(Fecha_Evaluacion) = YEAR((select MAX(Fecha_Evaluacion) from evaluaciones where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")) and
                                    MONTH(Fecha_Evaluacion) = MONTH((select MAX(Fecha_Evaluacion) from evaluaciones where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")) and
                                    ".$ByUser." = 1 and evaluaciones.lastEvaluation='1' and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta."
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion), competencias_calidad.nombre
                                ORDER BY year(evaluaciones.Fecha_Evaluacion) ASC, MONTH(evaluaciones.Fecha_Evaluacion) ASC, competencias_calidad.nombre ASC";
                //$Evaluations = $this->db->select($SqlEvaluation);
                for($i=1;$i<=6;$i++){
                    $DataArray = array();
                    $DataArray["Evaluacion"] = "";
                    $DataArray["Nota"] = 0;
                    $DataArray["UserTypeName"] = $UserTypeName;
                    $Array[$Cont] = $DataArray;
                    $Month++;
                    $Cont++;
                }
            }
            return $Array;
        }
        function getByEvaluationGraphDataByUserType($UserType,$Mandante,$Pauta,$Ejecutivo,$Type){

            $Headers = "";
            $Columns = "";
            $RestaMes = "";
            $WhereNow = "";
            $CantXAxis = 0;
            switch($Type){
                case 'Mes':
                    $Headers = "WEEK(evaluaciones.Fecha_Evaluacion) as Week";
                    $Columns = "WEEK(evaluaciones.Fecha_Evaluacion)";
                    $RestaMes = "0";
                    $CantXAxis = 4;
                break;
                case 'Historico':
                    $Headers = "year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month";
                    $Columns = "year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion)";
                    $RestaMes = "6";
                    $CantXAxis = 6;
                break;
            }

            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Personal.Id_Personal = '".$Ejecutivo."'" : "";

            $WhereSoloActivos = $Ejecutivo == "" ? " and Personal.Activo = '1' " : "";

            $ByUser = "";
            $UserTypeName = "";
            switch($UserType){
                case '1':
                    //Calidad Sistema
                    $ByUser = "byCalidadSystem";
                    $UserTypeName = "calidad";
                break;
                case '2':
                    //Calidad Mandante
                    $ByUser = "byCalidadMandante";
                    $UserTypeName = "empresa";
                break;
                case '3':
                    //Ejecutivo Sistema
                    $ByUser = "byEjecutivoSystem";
                    $UserTypeName = "ejecutivo";
                break;
                case '4':
                    //Ejecutivo Mandantte
                    $ByUser = "byEjecutivoMandante";
                    $UserTypeName = "";
                break;
                case '5':
                break;
                case '6':
                break;
            }
            //$db = new Db();
            $DateArray = $this->getDateFromServer();
            $Now = $DateArray["date"];
            $Now = new DateTime($Now);
            $Now->modify('last day of this month');
            $Now = $Now->format('Ymd');
            $SixMonthsAgo = strtotime ( '-'.$RestaMes.' months' , strtotime ( $Now ) ) ;
            $SixMonthsAgo = date ( 'Ym01' , $SixMonthsAgo );
            $Array = array();
            $Cont = 0;
            $Month = 1;
            $SqlEvaluation = "select
                                    ".$Headers.", ROUND(AVG(valor),2) as Nota, competencias_calidad.nombre as Resumen
                                from evaluaciones
                                    INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                    INNER JOIN dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                    INNER JOIN competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    INNER JOIN Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                    INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = mandante_cedente.Id_Cedente
                                    INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor
                                where
                                    CAST(Fecha_Evaluacion AS DATE) BETWEEN '".$SixMonthsAgo."' and '".$Now."' and
                                    ".$ByUser." = 1 and evaluaciones.lastEvaluation='1' and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta." ".$WhereSoloActivos." 
                                GROUP by ".$Columns.", competencias_calidad.nombre
                                ORDER BY competencias_calidad.nombre ASC, ".$Columns;
            /*$SqlEvaluation = "select
                                    year(evaluaciones.Fecha_Evaluacion) as Year, MONTH(evaluaciones.Fecha_Evaluacion) as Month, ROUND(AVG(valor),2) as Nota, competencias_calidad.nombre as Resumen
                                from evaluaciones
                                    INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                    INNER JOIN dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                    INNER JOIN competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where
                                    Fecha_Evaluacion BETWEEN '".$SixMonthsAgo."' and '".$Now."' and
                                    ".$ByUser." = 1 ".$WhereEjecutivo." ".$WhereMandante."
                                GROUP by year(evaluaciones.Fecha_Evaluacion), MONTH(evaluaciones.Fecha_Evaluacion), competencias_calidad.nombre
                                ORDER BY competencias_calidad.nombre ASC, year(evaluaciones.Fecha_Evaluacion) DESC, MONTH(evaluaciones.Fecha_Evaluacion) DESC";*/
            $Evaluations = $this->db->select($SqlEvaluation);
            $EvaluationResumen = "";
            $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
            $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
            if(count($Evaluations) > 0){
                if($ByUser == "byCalidadSystem"){
                //echo "<br><br><br><br>";
                }
                //$EvaluationResumen = "";
                $Months = $this->MonthsIndex();
                
                foreach($Evaluations as $Evaluation){
                    if($EvaluationResumen != utf8_encode($Evaluation["Resumen"])){
                        $Month = 6;
                        $Cont = 0;
                        $EvaluationResumen = utf8_encode($Evaluation["Resumen"]);
                    }
                    $DataArray = array();
                    switch($Type){
                        case 'Mes':
                            $Month = $Cont + 1;//$Evaluation["Week"];
                            $YearDB = $Evaluation["Week"];
                        break;
                        case 'Historico':
                            $YearDB = $Evaluation["Year"];
                            $MonthDB = strlen($Evaluation["Month"]) == 2 ? $Evaluation["Month"] : "0".$Evaluation["Month"];
                            $Month = $Months[$YearDB."_".$MonthDB];
                        break;
                    }
                    $cadena = utf8_encode($Evaluation["Resumen"]);
                    $cadena = utf8_decode($cadena);
                    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
                    $cadena = utf8_encode($cadena);
                    $DataArray[0] = $Month;
                    $DataArray[1] = $Evaluation["Nota"];
                    //$DataArray[2] = $YearDB;
                    if($ByUser == "byCalidadSystem"){
                    //print_r($DataArray);
                    }
                    
                    $Array[$cadena][$Cont] = $DataArray;
                    $Month--;
                    $Cont++;
                }
            }else{
                $Evaluations = $this->getEvaluationTemplateByPerfil($Pauta,$Mandante);
                
                foreach($Evaluations as $Evaluation){
                    for($i=1;$i<=$CantXAxis;$i++){
                        if($EvaluationResumen != $Evaluation["Nombre"]){
                            $Month = 1;
                            $Cont = 0;
                            $EvaluationResumen = $Evaluation["Nombre"];
                        }
                        $cadena = $Evaluation["Nombre"];
                        $cadena = utf8_decode($cadena);
                        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
                        $cadena = utf8_encode($cadena);
                        $DataArray = array();
                        $DataArray[0] = $Month;
                        $DataArray[1] = 0;
                        $Array[$cadena][$Cont] = $DataArray;
                        $Month++;
                        $Cont++;
                    }
                    
                }
            }
            if($ByUser == "byCalidadSystem"){
            /*print_r($DataArray);
            echo "<br><br><br><br>";*/
            }
            return $Array;
        }
        function getDateFromServer($Separator = ""){
            //$db = new Db();
            $SqlDate = "select DATE_FORMAT(NOW(),'%H:%i:%s') as hour, DATE_FORMAT(NOW(),'%Y/%m/%d') as date";
		    $Dates = $this->db->select($SqlDate);
            return $Dates[0];
        }
        function haveEvaluationThisMonth($UserType,$Mandante,$Ejecutivo){
            $WhereMandante = $Mandante != "" ? " and mandante_cedente.Id_Mandante='".$Mandante."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";

            $ByUser = "";
            $UserTypeName = "";
            switch($UserType){
                case '1':
                    //Calidad Sistema
                    $ByUser = "byCalidadSystem";
                    $UserTypeName = "calidad";
                break;
                case '2':
                    //Calidad Mandante
                    $ByUser = "byCalidadMandante";
                    $UserTypeName = "empresa";
                break;
                case '3':
                    //Ejecutivo Sistema
                    $ByUser = "byEjecutivoSystem";
                    $UserTypeName = "ejecutivo";
                break;
                case '4':
                    //Ejecutivo Mandantte
                    $ByUser = "byEjecutivoMandante";
                    $UserTypeName = "";
                break;
                case '5':
                break;
                case '6':
                break;
            }
            //$db = new Db();
            $SqlEvaluations = "select * from evaluaciones 
                                    INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where year(evaluaciones.Fecha_Evaluacion) = year(NOW()) and month(evaluaciones.Fecha_Evaluacion) = month(NOW()) and ".$ByUser." = 1 and evaluaciones.lastEvaluation='1' ".$WhereEjecutivo." ".$WhereMandante." ";
            if($ByUser == "byEjecutivoSystem"){
                 $SqlEvaluations;
            }
            $Evaluations = $this->db->select($SqlEvaluations);
            $ToReturn = false;
            if(count($Evaluations) > 0){
                $ToReturn = true;
            }
            return $ToReturn;
        }
        function getRankingData($Mandante,$Cedente,$Periodo){
            $WhereCedente = $Cedente != "" ? " and mandante_cedente.Id_Cedente='".$Cedente."'" : "";
            if($Periodo != ""){
                $Desde = date('Ym01',strtotime($Periodo));
                $Hasta = date('Ymt',strtotime($Desde));
                $WherePeriodo = " and CAST(evaluaciones.Fecha_Evaluacion AS DATE) between '".$Desde."' and '".$Hasta."' ";
            }else{
                $WherePeriodo = "";
            }
            //$db = new Db();
            $SqlRanking = "SELECT
                                Personal.nombre as Nombre,
                                round(avg(respuesta_opciones_afirmaciones_calidad.valor),2) as Nota
                            from
                                evaluaciones
                                    inner join Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                    inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                            where
                                mandante_cedente.Id_Mandante = '".$Mandante."' and 
                                Personal.Activo = '1'and 
                                evaluaciones.lastEvaluation='1'
                                ".$WhereCedente."
                                ".$WherePeriodo."
                            group by
                                Personal.Id_Personal,
                                Personal.nombre
                            order by
                                Personal.Nombre";
            $Ranking = $this->db->select($SqlRanking);
            return $Ranking;
        }
        function MonthsIndex(){
            $DateArray = $this->getDateFromServer();
            $Now = $DateArray["date"];
            $Now = new DateTime($Now);
            $Now->modify('first day of this month');
            $Now = $Now->format('Ymd');
            $SixMonthsAgo_strtotime = strtotime ( '-6 months' , strtotime ( $Now ) ) ;
            $SixMonthsAgo = date ( 'Ymd' , $SixMonthsAgo_strtotime );
            $Month = date ( 'm' , $SixMonthsAgo );
            $Months = array();
            for($i=1;$i<=6;$i++){
                $SixMonthsAgo_strtotime = strtotime ( '+1 months' , strtotime ( $SixMonthsAgo ) ) ;
                $Year = date ( 'Y' , $SixMonthsAgo_strtotime );
                $Month = date ( 'm' , $SixMonthsAgo_strtotime );
                $Months[$Year."_".$Month] = $i;
                $SixMonthsAgo = date ( 'Ymd' , $SixMonthsAgo_strtotime );
            }
            return $Months;
        }
        function getCierresByMonthsAndYears(){
            //$db = new Db();
            $ToReturn = array();
            $Months = array();
            $Months[1] = "Enero";
            $Months[2] = "Febrero";
            $Months[3] = "Marzo";
            $Months[4] = "Abril";
            $Months[5] = "Mayo";
            $Months[6] = "Junio";
            $Months[7] = "Julio";
            $Months[8] = "Agosto";
            $Months[9] = "Septiembre";
            $Months[10] = "Octubre";
            $Months[11] = "Noviembre";
            $Months[12] = "Diciembre";

            $SqlCierres = "select
                                month(fecha) as Month,
                                year(fecha) as Year
                            from
                                cierre_evaluaciones
                            where
                                Id_Usuario='".$this->Id_Usuario."' and 
                                Id_Mandante='".$_SESSION['mandante']."'
                            group by
                                month(fecha),
                                year(fecha)
                            order by
                                month(fecha),
                                year(fecha) DESC";
            $Cierres = $this->db->select($SqlCierres);
            foreach($Cierres as $Cierre){
                $ArrayTmp = array();
                $ArrayTmp["Month"] = $Cierre["Month"];
                $ArrayTmp["MonthText"] = $Months[$Cierre["Month"]];
                $ArrayTmp["Year"] = $Cierre["Year"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getEvaluacionesByMonthsAndYears(){
            //$db = new Db();
            $ToReturn = array();
            $Months = array();
            $Months[1] = "Enero";
            $Months[2] = "Febrero";
            $Months[3] = "Marzo";
            $Months[4] = "Abril";
            $Months[5] = "Mayo";
            $Months[6] = "Junio";
            $Months[7] = "Julio";
            $Months[8] = "Agosto";
            $Months[9] = "Septiembre";
            $Months[10] = "Octubre";
            $Months[11] = "Noviembre";
            $Months[12] = "Diciembre";

            $SqlEvaluaciones = "select
                                    month(Fecha_Evaluacion) as Month,
                                    year(Fecha_Evaluacion) as Year
                                from
                                    evaluaciones
                                where
                                    Id_Personal='".$this->Id_Personal."'
                                group by
                                    month(Fecha_Evaluacion),
                                    year(Fecha_Evaluacion)
                                order by
                                    month(Fecha_Evaluacion),
                                    year(Fecha_Evaluacion) DESC";
            $Evaluaciones = $this->db->select($SqlEvaluaciones);
            foreach($Evaluaciones as $Evaluacion){
                $ArrayTmp = array();
                $ArrayTmp["Month"] = $Evaluacion["Month"];
                $ArrayTmp["MonthText"] = $Months[$Evaluacion["Month"]];
                $ArrayTmp["Year"] = $Evaluacion["Year"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getEvaluacionesByMonthsAndYearsAndMandanteAndCedente(){
            //$db = new Db();
            $ToReturn = array();
            $Months = array();
            $Months[1] = "Enero";
            $Months[2] = "Febrero";
            $Months[3] = "Marzo";
            $Months[4] = "Abril";
            $Months[5] = "Mayo";
            $Months[6] = "Junio";
            $Months[7] = "Julio";
            $Months[8] = "Agosto";
            $Months[9] = "Septiembre";
            $Months[10] = "Octubre";
            $Months[11] = "Noviembre";
            $Months[12] = "Diciembre";

            $SqlEvaluaciones = "select
                                    month(Fecha_Evaluacion) as Month,
                                    year(Fecha_Evaluacion) as Year
                                from
                                    evaluaciones
                                        inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where
                                    mandante_cedente.Id_Mandante='".$_SESSION["mandante"]."' AND
                                    mandante_cedente.Id_Cedente='".$_SESSION["cedente"]."'
                                group by
                                    month(Fecha_Evaluacion),
                                    year(Fecha_Evaluacion)
                                order by
                                    month(Fecha_Evaluacion),
                                    year(Fecha_Evaluacion) DESC";
            $Evaluaciones = $this->db->select($SqlEvaluaciones);
            foreach($Evaluaciones as $Evaluacion){
                $ArrayTmp = array();
                $ArrayTmp["Month"] = $Evaluacion["Month"];
                $ArrayTmp["MonthText"] = $Months[$Evaluacion["Month"]];
                $ArrayTmp["Year"] = $Evaluacion["Year"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getCierreEjecutivos($Month){
            $ToReturn = array();
            //$db = new Db();
            $Desde = date('Ym01',strtotime($Month));
            $Hasta = date('Ymt',strtotime($Desde));
            $SqlCierres = "select
                                cierre_evaluaciones.*,
                                pexterno.Nombre as Ejecutivo,
                                (select cierre_evaluaciones.Aspectos_Fortalecer from cierre_evaluaciones inner join Usuarios on Usuarios.id = cierre_evaluaciones.Id_Usuario inner join Personal p on p.Id_Personal = cierre_evaluaciones.Id_Personal where cierre_evaluaciones.Id_Mandante='".$_SESSION['mandante']."' and Usuarios.nivel='6' and Usuarios.mandante='' and p.Id_Personal = pexterno.Id_Personal and fecha between '".$Desde."' and '".$Hasta."') as AFCalidad,
                                (select cierre_evaluaciones.Aspectos_Corregir from cierre_evaluaciones inner join Usuarios on Usuarios.id = cierre_evaluaciones.Id_Usuario inner join Personal p on p.Id_Personal = cierre_evaluaciones.Id_Personal where cierre_evaluaciones.Id_Mandante='".$_SESSION['mandante']."' and Usuarios.nivel='6' and Usuarios.mandante='' and p.Id_Personal = pexterno.Id_Personal and fecha between '".$Desde."' and '".$Hasta."') as ACCalidad,
                                (select cierre_evaluaciones.Compromiso_Ejecutivo from cierre_evaluaciones inner join Usuarios on Usuarios.id = cierre_evaluaciones.Id_Usuario inner join Personal p on p.Id_Personal = cierre_evaluaciones.Id_Personal where cierre_evaluaciones.Id_Mandante='".$_SESSION['mandante']."' and Usuarios.nivel='6' and Usuarios.mandante='' and p.Id_Personal = pexterno.Id_Personal and fecha between '".$Desde."' and '".$Hasta."') as CECalidad
                            from
                                cierre_evaluaciones
                                inner join Usuarios on Usuarios.id = cierre_evaluaciones.Id_Usuario
                                inner join Personal pexterno on pexterno.Id_Personal = cierre_evaluaciones.Id_Personal
                            where
                                cierre_evaluaciones.Id_Mandante='".$_SESSION['mandante']."' and
                                Usuarios.nivel='4' and
                                fecha between '".$Desde."' and '".$Hasta."'
                            order by
                                pexterno.Nombre";
            $Cierres = $this->db->select($SqlCierres);
            $Cont = 1;
            foreach($Cierres as $Cierre){
                $ArrayTmp = array();
                $ArrayTmp["Number"] = $Cont;
                $ArrayTmp["Ejecutivo"] = $Cierre["Ejecutivo"];
                $ArrayTmp["AspectosFortalecer"] = "<p style='text-align:center;font-weight:bold;width:100%'>Ejecutivo</p>".$Cierre["Aspectos_Fortalecer"]."<p style='text-align:center;font-weight:bold;width:100%'>Calidad</p>".$Cierre["AFCalidad"];
                $ArrayTmp["AspectosCorregir"] = "<p style='text-align:center;font-weight:bold;width:100%'>Ejecutivo</p>".$Cierre["Aspectos_Corregir"]."<p style='text-align:center;font-weight:bold;width:100%'>Calidad</p>".$Cierre["ACCalidad"];
                $ArrayTmp["CompromisoEjecutivo"] = "<p style='text-align:center;font-weight:bold;width:100%'>Ejecutivo</p>".$Cierre["Compromiso_Ejecutivo"]."<p style='text-align:center;font-weight:bold;width:100%'>Calidad</p>".$Cierre["CECalidad"];
                $ArrayTmp["Accion"] = $Cierre["Id_Personal"];
                $Cont++;
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
/* function getCompetencias(){
    $ToReturn = array();
    //$db = new Db();
    $SqlCompetencias = "select id,resumen from mantenedor_evaluaciones order by resumen";
    $Competencias = $this->db->select($SqlCompetencias);
    foreach($Competencias as $Competencia){
        $ArrayTmp = array();
        $ArrayTmp["id"] = $Competencia["id"];
        $ArrayTmp["Resumen"] = $Competencia["resumen"];
        array_push($ToReturn,$ArrayTmp);
    }
    return $ToReturn;
} */
        function getModulos($Competencia){
            $ToReturn = array();
            //$db = new Db();
            $SqlModulos = "select id,nombre from modulos_plan_accion where id_competencia='".$Competencia."' order by nombre";
            $Modulos = $this->db->select($SqlModulos);
            foreach($Modulos as $Modulo){
                $ArrayTmp = array();
                $ArrayTmp["id"] = $Modulo["id"];
                $ArrayTmp["Nombre"] = $Modulo["nombre"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getTopicos($Modulo){
            $ToReturn = array();
            //$db = new Db();
            $SqlTopicos = "select id,nombre from topicos_modulos_plan_accion where id_modulo='".$Modulo."' order by nombre";
            $Topicos = $this->db->select($SqlTopicos);
            foreach($Topicos as $Topico){
                $ArrayTmp = array();
                $ArrayTmp["id"] = $Topico["id"];
                $ArrayTmp["Nombre"] = $Topico["nombre"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function addPlan($Competencia,$Modulo,$Topico,$Ejecutivo,$Month){
            $ToReturn = array();
            //$db = new Db();
            $ActualMonth = date('m');
            $PeriodoMonth = date('m',strtotime($Month));
            $ActualYear = date('y');
            $PeriodoYear = date('y',strtotime($Month));
            $Date = "'".date('Y-m-t',strtotime($Month))."'";
            if($ActualYear == $PeriodoYear){
                if($ActualMonth == $PeriodoMonth){
                    $Date = "NOW()";
                }
            }
            $SqlInsert = "insert into plan_accion_ejecutivo (Id_Usuario,id_competencia,id_modulo,id_topico,Id_Personal,fecha,Id_Mandante) values('".$this->Id_Usuario."','".$Competencia."','".$Modulo."','".$Topico."','".$Ejecutivo."',".$Date.",'".$this->Id_Mandante."')";
            $Insert = $this->db->query($SqlInsert);
            if($Insert){
                $ToReturn['result'] = true;
                $ToReturn['id'] = $this->getLastPlanInserted($Ejecutivo);
            }else{
                $ToReturn['result'] = false;
            }
            return $ToReturn;
        }
        function getLastPlanInserted($Ejecutivo){
            $ToReturn = "";
            //$db = new Db();
            $SqlLast = "select id from plan_accion_ejecutivo where Id_Mandante='".$this->Id_Mandante."' and Id_Personal='".$Ejecutivo."' order by id desc LIMIT 1";
            $Last = $this->db->select($SqlLast);
            $ToReturn = $Last[0]["id"];
            return $ToReturn;
        }
        function getPlans($Ejecutivo,$Month){
            $ToReturn = array();
            //$db = new Db();
            $Desde = date('Ym01',strtotime($Month));
            $Hasta = date('Ymt',strtotime($Desde));
            $SqlPlans = "select
                            mantenedor_evaluaciones.resumen as Competencia,
                            modulos_plan_accion.nombre as Modulo,
                            topicos_modulos_plan_accion.nombre as Topico,
                            plan_accion_ejecutivo.id
                        from
                            plan_accion_ejecutivo
                            inner join mantenedor_evaluaciones on mantenedor_evaluaciones.id = plan_accion_ejecutivo.id_competencia
                            inner join modulos_plan_accion on modulos_plan_accion.id = plan_accion_ejecutivo.id_modulo
                            inner join topicos_modulos_plan_accion on topicos_modulos_plan_accion.id = plan_accion_ejecutivo.id_topico
                        where
                            plan_accion_ejecutivo.Id_Personal='".$Ejecutivo."' and
                            plan_accion_ejecutivo.Id_Mandante='".$this->Id_Mandante."' and
                            plan_accion_ejecutivo.fecha between '".$Desde."' and '".$Hasta."'
                        order by
                            mantenedor_evaluaciones.resumen,
                            modulos_plan_accion.nombre,
                            topicos_modulos_plan_accion.nombre";
            $Plans = $this->db->select($SqlPlans);
            foreach($Plans as $Plan){
                $ArrayTmp = array();
                $ArrayTmp["Competencia"] = utf8_encode($Plan["Competencia"]);
                $ArrayTmp["Modulo"] = utf8_encode($Plan["Modulo"]);
                $ArrayTmp["Topico"] = utf8_encode($Plan["Topico"]);
                $ArrayTmp["Accion"] = $Plan["id"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function deletePlan($ID){
            $ToReturn = array();
            $ToReturn["result"] = false;
            //$db = new Db();
            $SqlDelete = "delete from plan_accion_ejecutivo where id='".$ID."'";
            $Delete = $this->db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            $ToReturn["query"] = $SqlDelete;
            return $ToReturn;
        }
        function canAddPlan($Ejecutivo,$Competencia,$Modulo,$Topico,$Month){
            $ToReturn = array();
            $ToReturn["result"] = true;
            //$db = new Db();
            $Desde = date('Ym01',strtotime($Month));
            $Hasta = date('Ymt',strtotime($Desde));
            $SqlPlan = "select * from plan_accion_ejecutivo where id_competencia='".$Competencia."' and id_modulo='".$Modulo."' and id_topico='".$Topico."' and Id_Personal='".$Ejecutivo."' and fecha between '".$Desde."' and '".$Hasta."'";
            $Plan = $this->db->select($SqlPlan);
            if(count($Plan) > 0){
                $ToReturn["result"] = false;
            }
            $ToReturn["query"] = $SqlPlan;
            return $ToReturn;
        }
        function getPeriodoCierreEjecutivos(){
            //$db = new Db();
            $SqlPeriodos = "select
                                Month(cierre_evaluaciones.fecha) as Month, Year(cierre_evaluaciones.fecha) as Year
                            from
                                cierre_evaluaciones
                                inner join Usuarios on Usuarios.id = cierre_evaluaciones.Id_Usuario
                            where
                                cierre_evaluaciones.Id_Mandante='".$this->Id_Mandante."' and
                                Usuarios.nivel='4'
                            GROUP BY
                                Month(cierre_evaluaciones.fecha), 
                                Year(cierre_evaluaciones.fecha),
                                cierre_evaluaciones.fecha
                            ORDER BY
                                fecha DESC";
            $Periodos = $this->db->select($SqlPeriodos);
            return $Periodos;
        }
        function cellsToMergeByColsRow($start = NULL, $end = NULL, $row = NULL){
            $merge = 'A1:A1';
            if($start && $end && $row){
                $start = PHPExcel_Cell::stringFromColumnIndex($start);
                $end = PHPExcel_Cell::stringFromColumnIndex($end);
                $merge = "$start{$row}:$end{$row}";
            }
            return $merge;
        }
        function DownloadInformePeriodo($Periodo,$TipoBusqueda){
            $ToReturn = "";
            //$db = new Db();
            $Desde = date('Ym01',strtotime($Periodo));
            $Hasta = date('Ymt',strtotime($Desde));

            $fileName = "Informe Periodo";

            $objPHPExcel = new PHPExcel();
            ob_start();
            $objPHPExcel->
                getProperties()
                    ->setCreator("CRM Sinaptica")
                    ->setLastModifiedBy("CRM Sinaptica");
            
            $objPHPExcel->removeSheetByIndex(
                $objPHPExcel->getIndex(
                    $objPHPExcel->getSheetByName('Worksheet')
                )
            );

            $styleAlignHorizontal = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );

            $NextSheet = 0;

            $WhereCedente = "";
            switch($TipoBusqueda){
                case "cartera":
                    $WhereCedente = " AND mandante_cedente.Id_Cedente='".$_SESSION["cedente"]."'";
                break;
                case "mandante":
                    $WhereCedente = " AND mandante_cedente.Id_Mandante='".$_SESSION["mandante"]."'";
                break;
            }

            $sqlPautasConEvaluaciones = "select
                                            contenedor_competencias_calidad.id,
                                            contenedor_competencias_calidad.nombreContenedor,
                                            contenedor_competencias_calidad.Id_TipoContacto
                                        from
                                            evaluaciones
                                                inner join contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente
                                                inner join contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor
                                                inner join competencias_calidad on competencias_calidad.id_contenedor = contenedor_competencias_calidad.id
                                                inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id_competencia = competencias_calidad.id
                                                inner join afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id_dimension = dimensiones_competencias_calidad.id
                                                inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.id_afirmacion = afirmaciones_dimensiones_competencias_calidad.id
                                                inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                        WHERE
                                            CAST(evaluaciones.Fecha_Evaluacion AS DATE) between '".$Desde."' and '".$Hasta."' AND
                                            evaluaciones.Id_Usuario='".$_SESSION["id_usuario"]."'
                                            ".$WhereCedente."
                                        GROUP BY
                                            contenedor_competencias_calidad.id,
                                            contenedor_competencias_calidad.nombreContenedor,
                                            contenedor_competencias_calidad.Id_TipoContacto";
            $PautasConEvaluaciones = $this->db->select($sqlPautasConEvaluaciones);
            
            foreach($PautasConEvaluaciones as $Pauta){
                $objPHPExcel->createSheet($NextSheet);
                $objPHPExcel->setActiveSheetIndex($NextSheet);
                $objPHPExcel->getActiveSheet()->setTitle('Pauta '.$Pauta["nombreContenedor"]);
                
                $objPHPExcel->setActiveSheetIndex($NextSheet);
                
                $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow(0,2,"Evaluacion")
                        ->setCellValueByColumnAndRow(1,2,"Grabación")
                        ->setCellValueByColumnAndRow(2,2,"Url Grabación")
                        ->setCellValueByColumnAndRow(3,2,"Ejecutivo")
                        ->setCellValueByColumnAndRow(4,2,"Fecha")
                        ->setCellValueByColumnAndRow(5,2,"Evaluador")
                        ->setCellValueByColumnAndRow(6,2,"Acción");     

                $sqlCompetencias = "select
                                        competencias_calidad.id,
                                        competencias_calidad.id_contenedor,
                                        competencias_calidad.nombre,
                                        competencias_calidad.Esperado,
                                        competencias_calidad.descripcion,
                                        competencias_calidad.ponderacion,
                                        competencias_calidad.tag
                                    from
                                        contenedor_competencias_calidad
                                            INNER JOIN contenedor_competencias_calidad_Cedente contenedor_competencias_Cedente on contenedor_competencias_Cedente.id_contenedor = contenedor_competencias_calidad.id
                                            INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = contenedor_competencias_Cedente.Id_Cedente
                                            INNER JOIN competencias_calidad on competencias_calidad.id_contenedor = contenedor_competencias_calidad.id
                                    where
                                        contenedor_competencias_calidad.id='".$Pauta["id"]."'
                                        ".$WhereCedente."
                                    GROUP BY
                                        competencias_calidad.id,
                                        competencias_calidad.id_contenedor,
                                        competencias_calidad.nombre,
                                        competencias_calidad.Esperado,
                                        competencias_calidad.descripcion,
                                        competencias_calidad.ponderacion,
                                        competencias_calidad.tag";
                $Competencias = $this->db->select($sqlCompetencias);

                $ContCols = 7;
                
                $objPHPExcel->
                    setActiveSheetIndex($NextSheet)->
                        mergeCells($this->cellsToMergeByColsRow($ContCols,count($Competencias) + $ContCols - 1,1));
                
                $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($ContCols,1,"COMPETENCIAS");
                
                foreach($Competencias as $Competencia){
                    $objPHPExcel->
                        setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow($ContCols,2,utf8_encode($Competencia["nombre"]));
                    $ContCols++;
                }
                $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($ContCols,2,"TOTAL");
                
                $ContCols++;
                
                $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($ContCols,2,"Error Critico");
                
                $ContCols++;
                
                $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($ContCols,2,"Descripción Error Critico");
                
                $ContCols++;
                
                $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($ContCols,2,"Observación");
                
                $sqlEvaluaciones = "select
                                        E.id AS Evaluacion,
                                        G.id AS Grabacion,
                                        G.url_grabacion AS Url_Grabacion,
                                        P.Nombre AS Ejecutivo,
                                        E.Fecha_Evaluacion AS Fecha_Evaluacion,
                                        U.nombre AS Calidad,
                                        CASE WHEN E.lastEvaluation = '1' THEN 'Nota Final' ELSE 'Re-Evaluacion' END AS Accion,
                                        CASE WHEN E.errorCritico = '1' THEN 'Si' ELSE 'No' END AS ErrorCritico,
                                        errores_criticos_calidad.Descripcion as DescErrorCritico,
                                        E.observacion as Observacion
                                    FROM
                                        evaluaciones E
                                            inner join Personal P on P.Id_Personal = E.Id_Personal
                                            inner join Usuarios U on U.id = E.Id_Usuario
                                            inner join grabacion_2 G on G.id = E.Id_Grabacion
                                            inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = E.id
                                            inner join afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                            inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                            inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                            inner join contenedor_competencias_calidad on contenedor_competencias_calidad.id = competencias_calidad.id_contenedor
                                            left join errores_criticos_calidad on errores_criticos_calidad.id = E.id_errorCritico
                                            inner join mandante_cedente on mandante_cedente.Id_Cedente = E.Id_Cedente
                                    WHERE
                                        CAST(E.Fecha_Evaluacion AS DATE) between '".$Desde."' and '".$Hasta."' and
                                        E.byCalidadSystem = '1' AND
                                        E.Id_Usuario='".$_SESSION["id_usuario"]."' AND
                                        contenedor_competencias_calidad.id = '".$Pauta["id"]."'
                                        ".$WhereCedente."
                                    GROUP BY
                                        E.id,
                                        G.id,
                                        G.url_grabacion,
                                        P.Nombre,
                                        E.Fecha_Evaluacion,
                                        U.nombre,
                                        E.lastEvaluation,
                                        E.errorCritico,
                                        errores_criticos_calidad.Descripcion";
                $Evaluaciones = $this->db->select($sqlEvaluaciones);
                $Row = 3;
                foreach($Evaluaciones as $Evaluacion){
                    $objPHPExcel->
                        setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow(0,$Row,$Evaluacion["Evaluacion"])
                            ->setCellValueByColumnAndRow(1,$Row,$Evaluacion["Grabacion"])
                            ->setCellValueByColumnAndRow(2,$Row,$Evaluacion["Url_Grabacion"])
                            ->setCellValueByColumnAndRow(3,$Row,$Evaluacion["Ejecutivo"])
                            ->setCellValueByColumnAndRow(4,$Row,date("d/m/Y",strtotime($Evaluacion["Fecha_Evaluacion"])))
                            ->setCellValueByColumnAndRow(5,$Row,$Evaluacion["Calidad"])
                            ->setCellValueByColumnAndRow(6,$Row,$Evaluacion["Accion"]);
                    $ContCols = 7;
                    $NotaTotal = 0;
                    foreach($Competencias as $Competencia){
                        $WhereCedente = "";
                        switch($TipoBusqueda){
                            case "cartera":
                                $WhereCedente = " AND mandante_cedente.Id_Cedente='".$_SESSION["cedente"]."'";
                            break;
                            case "mandante":
                                $WhereCedente = " AND mandante_cedente.Id_Mandante='".$_SESSION["mandante"]."'";
                            break;
                        }
                        $sqlNotas = "select
                                        SUM(Nota) as Nota
                                    from
                                        (select DISTINCT
                                            R.Nota,
                                            A.id
                                        FROM
                                            respuesta_opciones_afirmaciones_calidad R
                                                inner join afirmaciones_dimensiones_competencias_calidad A on A.id = R.id_afirmacion
                                                inner join dimensiones_competencias_calidad D on D.id = A.id_dimension
                                                inner join competencias_calidad C on C.id = D.id_competencia
                                                INNER JOIN mandante_cedente on mandante_cedente.Id_Mandante = R.Id_Mandante
                                        WHERE
                                            R.Id_Evaluacion='".$Evaluacion["Evaluacion"]."' AND
                                            C.id='".$Competencia["id"]."'
                                            ".$WhereCedente."
                                        ) tb1";
                        $Notas = $this->db->select($sqlNotas);
                        $objPHPExcel->
                            setActiveSheetIndex($NextSheet)
                                ->setCellValueByColumnAndRow($ContCols,$Row,$Notas[0]["Nota"]);
                        
                        $NotaTotal += $Notas[0]["Nota"];
                        $ContCols++;
                    }
                    $objPHPExcel->
                            setActiveSheetIndex($NextSheet)
                                ->setCellValueByColumnAndRow($ContCols,$Row,number_format(($NotaTotal),2));
                    
                    $ContCols++;

                    $objPHPExcel->
                        setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow($ContCols,$Row,$Evaluacion["ErrorCritico"]);

                    $ContCols++;

                    $objPHPExcel->
                        setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow($ContCols,$Row,$Evaluacion["DescErrorCritico"]);

                    $ContCols++;
                    
                    $objPHPExcel->
                        setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow($ContCols,$Row,$Evaluacion["Observacion"]);
                    $Row++;
                }
                $NextSheet++;
            }
            
            //$NextSheet++;

            $objPHPExcel->createSheet($NextSheet);
            $objPHPExcel->setActiveSheetIndex($NextSheet);
            $objPHPExcel->getActiveSheet()->setTitle("EVALUACIONES POR EVALUADOR");
                
            $objPHPExcel->setActiveSheetIndex($NextSheet);

            $MaxCantNotas = 0;

            $SqlCedentes = "SELECT DISTINCT
                                Cedente.*
                            FROM
                                Cedente
                                    INNER JOIN evaluaciones on evaluaciones.Id_Cedente = Cedente.Id_Cedente
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                            WHERE
                                CAST(evaluaciones.Fecha_Evaluacion AS DATE) between '".$Desde."' and '".$Hasta."' AND
                                evaluaciones.Id_Usuario = '".$_SESSION["id_usuario"]."'
                                ".$WhereCedente;
            $Cedentes = $this->db->select($SqlCedentes);
            foreach($Cedentes as $Cedente){
                $idCedente = $Cedente["Id_Cedente"];
                $SqlPersonas = "SELECT
                                     Id_Personal
                                FROM
                                    evaluaciones
                                WHERE
                                    CAST(evaluaciones.Fecha_Evaluacion AS DATE) between '".$Desde."' and '".$Hasta."' AND
                                    Id_Cedente = '".$idCedente."' AND
                                    Id_Usuario = '".$_SESSION["id_usuario"]."'";
                $Personas = $this->db->select($SqlPersonas);
                foreach($Personas as $Persona){
                    $idPersona = $Persona["Id_Personal"];
                    $SqlCantNotas = "SELECT
                                        COUNT(*) as Cantidad
                                    FROM
                                        evaluaciones
                                    WHERE
                                        Id_Cedente = '".$idCedente."' AND
                                        Id_Personal = '".$idPersona."' AND
                                        Id_Usuario = '".$_SESSION["id_usuario"]."'";
                    $CantNotas = $this->db->select($SqlCantNotas);
                    if(count($CantNotas) > 0){
                        if($CantNotas[0]["Cantidad"] > $MaxCantNotas){
                            $MaxCantNotas = $CantNotas[0]["Cantidad"];
                        }
                    }
                    
                }
            }
            
            $Row = 1;
            $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow(0,$Row,"Cartera")
                    ->setCellValueByColumnAndRow(1,$Row,"Rut")
                    ->setCellValueByColumnAndRow(2,$Row,"Nombre de Ejecutivo");

            $Cont = 3;
            for($i=1; $i<=$MaxCantNotas; $i++){
                $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($Cont,$Row,"Nota ".$i);
                $Cont++;
            }
            $indexColumnaPromedio = $Cont;
            $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($indexColumnaPromedio,$Row,"Promedio");
            $Row++;

            foreach($Cedentes as $Cedente){
                $idCedente = $Cedente["Id_Cedente"];
                $nombreCedente = $Cedente["Nombre_Cedente"];
                $SqlPersonas = "SELECT
                                    Personal.Id_Personal,
                                    Personal.Nombre,
                                    Personal.Rut
                                FROM
                                    evaluaciones
                                        INNER JOIN Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                WHERE
                                    CAST(evaluaciones.Fecha_Evaluacion AS DATE) between '".$Desde."' and '".$Hasta."' AND
                                    evaluaciones.Id_Cedente = '".$idCedente."' AND
                                    evaluaciones.Id_Usuario = '".$_SESSION["id_usuario"]."'
                                GROUP BY
                                    Personal.Id_Personal,
                                    Personal.Nombre,
                                    Personal.Rut
                                ORDER BY
                                    Personal.Nombre";
                $Personas = $this->db->select($SqlPersonas);
                foreach($Personas as $Persona){
                    $idPersona = $Persona["Id_Personal"];
                    $nombrePersona = $Persona["Nombre"];
                    $rutPersona = $Persona["Rut"];
                    $objPHPExcel->
                        setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow(0,$Row,$nombreCedente)
                            ->setCellValueByColumnAndRow(1,$Row,$rutPersona)
                            ->setCellValueByColumnAndRow(2,$Row,$nombrePersona);
                    $SqlNotas = "SELECT
                                        E.id,
                                        SUM(R.Nota) as Nota
                                    FROM
                                        evaluaciones E
                                            INNER JOIN respuesta_opciones_afirmaciones_calidad R on R.Id_Evaluacion = E.id
                                    WHERE
                                        CAST(E.Fecha_Evaluacion AS DATE) between '".$Desde."' and '".$Hasta."' AND
                                        E.Id_Cedente = '".$idCedente."' AND
                                        E.Id_Personal = '".$idPersona."' AND
                                        E.Id_Usuario = '".$_SESSION["id_usuario"]."'
                                    GROUP BY
                                        E.id
                                    ORDER BY
                                        E.id";
                    $Notas = $this->db->select($SqlNotas);
                    $Col = 3;
                    $SumNotas = 0;
                    foreach($Notas as $Nota){
                        $objPHPExcel->
                            setActiveSheetIndex($NextSheet)
                                ->setCellValueByColumnAndRow($Col,$Row,$Nota["Nota"]);
                        $SumNotas = $SumNotas + $Nota["Nota"];
                        $Col++;
                    }
                    $PromedioNota = ($SumNotas / count($Notas));
                    $objPHPExcel->
                            setActiveSheetIndex($NextSheet)
                                ->setCellValueByColumnAndRow($indexColumnaPromedio,$Row,$PromedioNota);
                    $Row++;
                }
            }

            $objPHPExcel->setActiveSheetIndex(0);


            header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$fileName.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();
			$response =  array(
                'filename' => $fileName,
				'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
			);

            return $response;
        }
        function DownloadInformeGeneral($TipoBusqueda){
            $ToReturn = "";
            //$db = new Db();

            $Months = array();
            $Months[1] = "Enero";
            $Months[2] = "Febrero";
            $Months[3] = "Marzo";
            $Months[4] = "Abril";
            $Months[5] = "Mayo";
            $Months[6] = "Junio";
            $Months[7] = "Julio";
            $Months[8] = "Agosto";
            $Months[9] = "Septiembre";
            $Months[10] = "Octubre";
            $Months[11] = "Noviembre";
            $Months[12] = "Diciembre";

            $LastMonth = date('Ym01',strtotime(date("Ymd")));
            $LastMonthNumber = intval(date("m",strtotime($LastMonth)));
            $EndLastMonth = date('Ymt',strtotime($LastMonth));

            $SecondMonth = date('Ym01',strtotime('-1 month',strtotime($LastMonth)));
            $SecondMonthNumber = intval(date("m",strtotime($SecondMonth)));
            $EndSecondMonth = date('Ymt',strtotime($SecondMonth));

            $FirstMonth = date('Ym01',strtotime('-1 month',strtotime($SecondMonth)));
            $FirstMonthNumber = intval(date("m",strtotime($FirstMonth)));
            $EndFirstMonth = date('Ymt',strtotime($FirstMonth));

            $fileName = "Informe General";

            $objPHPExcel = new PHPExcel();
            ob_start();
            $objPHPExcel->
                getProperties()
                    ->setCreator("CRM Sinaptica")
                    ->setLastModifiedBy("CRM Sinaptica");
            
            $objPHPExcel->removeSheetByIndex(
                $objPHPExcel->getIndex(
                    $objPHPExcel->getSheetByName('Worksheet')
                )
            );

            $styleAlignHorizontal = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );

            $NextSheet = 0;

            $objPHPExcel->createSheet($NextSheet);
            $objPHPExcel->setActiveSheetIndex($NextSheet);
            $objPHPExcel->getActiveSheet()->setTitle("EVOLUCION MENSUAL DE NOTAS");
                
            $objPHPExcel->setActiveSheetIndex($NextSheet);
            
            $Row = 1;
            $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow(0,$Row,"Ejecutivo")
                    ->setCellValueByColumnAndRow(1,$Row,$Months[$FirstMonthNumber])
                    ->setCellValueByColumnAndRow(2,$Row,$Months[$SecondMonthNumber])
                    ->setCellValueByColumnAndRow(3,$Row,$Months[$LastMonthNumber]);

            $Row++;

            $WhereCedente = "";
            switch($TipoBusqueda){
                case "cartera":
                    $WhereCedente = " AND mandante_cedente.Id_Cedente='".$_SESSION["cedente"]."'";
                break;
                case "mandante":
                    $WhereCedente = " AND mandante_cedente.Id_Mandante='".$_SESSION["mandante"]."'";
                break;
            }

            $sqlEjecutivos = "SELECT
                                P.Id_Personal as idPersonal,
                                P.Nombre as nombrePersonal
                            FROM
                                evaluaciones E
                                    inner join Personal P on P.Id_Personal = E.Id_Personal
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = E.Id_Cedente
                            WHERE
                                CAST(E.Fecha_Evaluacion AS DATE) BETWEEN '".$FirstMonth."' and '".$EndLastMonth."'
                                ".$WhereCedente."
                            GROUP BY
                                P.Id_Personal,
                                P.Nombre
                            ORDER BY
                                P.Nombre";
            $Ejecutivos = $this->db->select($sqlEjecutivos);
            foreach($Ejecutivos as $Ejecutivo){
                $idPersonal = $Ejecutivo["idPersonal"];
                $nombrePersonal = $Ejecutivo["nombrePersonal"];
                $SqlFirstNote = "SELECT
                                    (SUM(TE.Nota) / count(*)) as Nota
                                FROM
                                    (SELECT E.id as idEvaluacion, SUM(R.Nota) as Nota, P.Id_Personal as idPersonal FROM evaluaciones E inner join respuesta_opciones_afirmaciones_calidad R on R.Id_Evaluacion = E.id inner join Personal P on P.Id_Personal = E.Id_Personal inner join mandante_cedente on mandante_cedente.Id_Cedente = E.Id_Cedente WHERE CAST(E.Fecha_Evaluacion AS DATE) BETWEEN '".$FirstMonth."' and '".$EndFirstMonth."' and P.Id_Personal='".$idPersonal."' ".$WhereCedente." GROUP BY E.id,P.Id_Personal) TE
                                        inner join evaluaciones E on E.id = TE.idEvaluacion
                                        inner join Personal P on P.Id_Personal = E.Id_Personal
                                        inner join mandante_cedente on mandante_cedente.Id_Cedente = E.Id_Cedente
                                WHERE
                                    1 = 1
                                    ".$WhereCedente."
                                GROUP BY
                                    TE.idPersonal";
                $FirstNote = $this->db->select($SqlFirstNote);
                $FirstNote = count($FirstNote) > 0 ? number_format($FirstNote[0]["Nota"],2) : "-";
                $SqlSecondNote = "SELECT
                                    (SUM(TE.Nota) / count(*)) as Nota
                                FROM
                                    (SELECT E.id as idEvaluacion, SUM(R.Nota) as Nota, P.Id_Personal as idPersonal FROM evaluaciones E inner join respuesta_opciones_afirmaciones_calidad R on R.Id_Evaluacion = E.id inner join Personal P on P.Id_Personal = E.Id_Personal inner join mandante_cedente on mandante_cedente.Id_Cedente = E.Id_Cedente WHERE CAST(E.Fecha_Evaluacion AS DATE) BETWEEN '".$SecondMonth."' and '".$EndSecondMonth."' and P.Id_Personal='".$idPersonal."' ".$WhereCedente." GROUP BY E.id,P.Id_Personal) TE
                                        inner join evaluaciones E on E.id = TE.idEvaluacion
                                        inner join Personal P on P.Id_Personal = E.Id_Personal
                                        inner join mandante_cedente on mandante_cedente.Id_Cedente = E.Id_Cedente
                                WHERE
                                    1 = 1
                                    ".$WhereCedente."
                                GROUP BY
                                    TE.idPersonal";
                $SecondNote = $this->db->select($SqlSecondNote);
                $SecondNote = count($SecondNote) > 0 ? number_format($SecondNote[0]["Nota"],2) : "-";
                $SqlLastNote = "SELECT
                                    (SUM(TE.Nota) / count(*)) as Nota
                                FROM
                                    (SELECT E.id as idEvaluacion, SUM(R.Nota) as Nota, P.Id_Personal as idPersonal FROM evaluaciones E inner join respuesta_opciones_afirmaciones_calidad R on R.Id_Evaluacion = E.id inner join Personal P on P.Id_Personal = E.Id_Personal inner join mandante_cedente on mandante_cedente.Id_Cedente = E.Id_Cedente WHERE CAST(E.Fecha_Evaluacion AS DATE) BETWEEN '".$LastMonth."' and '".$EndLastMonth."' and P.Id_Personal='".$idPersonal."' ".$WhereCedente." GROUP BY E.id,P.Id_Personal) TE
                                        inner join evaluaciones E on E.id = TE.idEvaluacion
                                        inner join Personal P on P.Id_Personal = E.Id_Personal
                                        inner join mandante_cedente on mandante_cedente.Id_Cedente = E.Id_Cedente
                                WHERE
                                    1 = 1
                                    ".$WhereCedente."
                                GROUP BY
                                    TE.idPersonal";
                $LastNote = $this->db->select($SqlLastNote);
                $LastNote = count($LastNote) > 0 ? number_format($LastNote[0]["Nota"],2) : "-";

                $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow(0,$Row,$nombrePersonal)
                        ->setCellValueByColumnAndRow(1,$Row,$FirstNote)
                        ->setCellValueByColumnAndRow(2,$Row,$SecondNote)
                        ->setCellValueByColumnAndRow(3,$Row,$LastNote);
                $Row++;
            }
            
            $NextSheet++;

            $objPHPExcel->createSheet($NextSheet);
            $objPHPExcel->setActiveSheetIndex($NextSheet);
            $objPHPExcel->getActiveSheet()->setTitle("RANKING DE CALIDAD");
                
            $objPHPExcel->setActiveSheetIndex($NextSheet);
            
            $Row = 1;
            $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow(0,$Row,"Ejecutivo")
                    ->setCellValueByColumnAndRow(1,$Row,"Promedio")
                    ->setCellValueByColumnAndRow(2,$Row,"Mes Anterior")
                    ->setCellValueByColumnAndRow(3,$Row,"Mes Actual");
            $Row++;

            $SqlEjecutivos = "select
                                P.Id_Personal as idPersonal,
                                P.Nombre as nombreEjecutivo
                            from
                                evaluaciones E
                                    inner join Personal P on P.Id_Personal = E.Id_Personal
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = E.Id_Cedente
                            WHERE
                                CAST(E.Fecha_Evaluacion AS DATE) BETWEEN '".$SecondMonth."' and '".$EndLastMonth."'
                                ".$WhereCedente."
                            GROUP BY
                                P.Id_Personal,
                                P.Nombre
                            ORDER BY
                                P.Nombre";
            $Ejecutivos = $this->db->select($SqlEjecutivos);
            foreach($Ejecutivos as $Ejecutivo){
                $idPersonal = $Ejecutivo["idPersonal"];
                $NombreEjecutivo = $Ejecutivo["nombreEjecutivo"];
                $SqlPreviousNote = "SELECT
                                    (SUM(TE.Nota) / count(*)) as Nota
                                FROM
                                    (SELECT E.id as idEvaluacion, SUM(R.Nota) as Nota, P.Id_Personal as idPersonal FROM evaluaciones E inner join respuesta_opciones_afirmaciones_calidad R on R.Id_Evaluacion = E.id inner join Personal P on P.Id_Personal = E.Id_Personal inner join mandante_cedente on mandante_cedente.Id_Cedente = E.Id_Cedente WHERE CAST(E.Fecha_Evaluacion AS DATE) BETWEEN '".$SecondMonth."' and '".$EndSecondMonth."' and P.Id_Personal='".$idPersonal."' ".$WhereCedente." GROUP BY E.id,P.Id_Personal) TE
                                        inner join evaluaciones E on E.id = TE.idEvaluacion
                                        inner join Personal P on P.Id_Personal = E.Id_Personal
                                        inner join mandante_cedente on mandante_cedente.Id_Cedente = E.Id_Cedente
                                WHERE
                                    1 = 1
                                    ".$WhereCedente."
                                GROUP BY
                                    TE.idPersonal";
                $PreviousNote = $this->db->select($SqlPreviousNote);
                $PreviousNote = count($PreviousNote) > 0 ? number_format($PreviousNote[0]["Nota"],2) : "-";

                $SqlActualNote = "SELECT
                                    (SUM(TE.Nota) / count(*)) as Nota
                                FROM
                                    (SELECT E.id as idEvaluacion, SUM(R.Nota) as Nota, P.Id_Personal as idPersonal FROM evaluaciones E inner join respuesta_opciones_afirmaciones_calidad R on R.Id_Evaluacion = E.id inner join Personal P on P.Id_Personal = E.Id_Personal inner join mandante_cedente on mandante_cedente.Id_Cedente = E.Id_Cedente WHERE CAST(E.Fecha_Evaluacion AS DATE) BETWEEN '".$LastMonth."' and '".$EndLastMonth."' and P.Id_Personal='".$idPersonal."' ".$WhereCedente." GROUP BY E.id,P.Id_Personal) TE
                                        inner join evaluaciones E on E.id = TE.idEvaluacion
                                        inner join Personal P on P.Id_Personal = E.Id_Personal
                                        inner join mandante_cedente on mandante_cedente.Id_Cedente = E.Id_Cedente
                                WHERE
                                    1 = 1
                                    ".$WhereCedente."
                                GROUP BY
                                    TE.idPersonal";
                $ActualNote = $this->db->select($SqlActualNote);
                $ActualNote = count($ActualNote) > 0 ? number_format($ActualNote[0]["Nota"],2) : "-";

                if(($PreviousNote == "-") || ($ActualNote == "-")){
                    if($PreviousNote == "-"){
                        $PreviousNote = 0;
                    }
                    if($ActualNote == "-"){
                        $ActualNote = 0;
                    }
                    $AverageNote = ($PreviousNote + $ActualNote);
                }else{
                    $AverageNote = ($PreviousNote + $ActualNote) / 2;
                }

                $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow(0,$Row,$NombreEjecutivo)
                    ->setCellValueByColumnAndRow(1,$Row,$AverageNote)
                    ->setCellValueByColumnAndRow(2,$Row,$PreviousNote)
                    ->setCellValueByColumnAndRow(3,$Row,$ActualNote);
                $Row++;
            }

            $objPHPExcel->setActiveSheetIndex(0);


            header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$fileName.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();
			$response =  array(
                'filename' => $fileName,
				'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
			);

            return $response;
        }
        function getEvaluacionesFromCierre($idEvaluaciones,$Periodo,$TipoCierre,$Personal){
            //$db = new Db();
            $WhereEvaluaciones = "";
            $WherePeriodo = "";
            $WherePersonal = "";
            $WhereMandante = "";
            switch($TipoCierre){
                case '0':
                    $WhereEvaluaciones = " and evaluaciones.id IN (".$idEvaluaciones.") ";
                break;
                case '1':
                    $Desde = date("Ym01",strtotime($Periodo));
                    $Hasta = date("Ymt",strtotime($Desde));
                    $WherePeriodo = " and CAST(evaluaciones.Fecha_Evaluacion AS DATE) between '".$Desde."' and '".$Hasta."'";
                    $WherePersonal = " and evaluaciones.Id_Personal = '".$Personal."' ";
                    $WhereMandante = " and mandante_cedente.Id_Mandante = '".$_SESSION['mandante']."' ";
                break;
            }
            $TipoUsuario = $this->getTipoUsuario($_SESSION['id_usuario']);
            $SqlEvaluaciones = "SELECT 
                                    grabacion_2.Nombre_Grabacion, round(AVG(respuesta_opciones_afirmaciones_calidad.valor),2) as Nota, grabacion_2.Fecha as Fecha_Grabacion, grabacion_2.Cartera as Cedente, evaluaciones.Fecha_Evaluacion as Fecha_Evaluacion
                                FROM
                                    evaluaciones
                                        INNER JOIN grabacion_2 ON grabacion_2.id = evaluaciones.Id_Grabacion
                                        INNER JOIN respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                        INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                WHERE
                                    evaluaciones.".$TipoUsuario." = '1'
                                    ".$WhereEvaluaciones."
                                    ".$WherePeriodo."
                                    ".$WherePersonal."
                                    ".$WhereMandante."
                                GROUP BY 
                                    evaluaciones.id,
                                    grabacion_2.Nombre_Grabacion, 
                                    grabacion_2.Fecha, 
                                    grabacion_2.Cartera, 
                                    evaluaciones.Fecha_Evaluacion";
            $Evaluaciones = $this->db->select($SqlEvaluaciones);
            return $Evaluaciones;
        }
        function getTipoUsuario($idUsuario){
            $ToReturn = "";
            //$db = new Db();
            $SqlUsuario = "select * from Usuarios where id = '".$idUsuario."'";
            $Usuario = $this->db->select($SqlUsuario);
            if(count($Usuario) > 0){
                $Usuario = $Usuario[0];
                $Nivel = $Usuario["nivel"];
                $MandanteUsuario = $Usuario["mandante"];
                switch($Nivel){
                    case '1':
                        //Administrador
                    break;
                    case '2':
                        if($MandanteUsuario == ""){
                            $ToReturn = "bySupervisorSystem";
                        }else{
                            $ToReturn = "bySupervisorMandante";
                        }
                    break;
                    case '3':
                    break;
                    case '4':
                        if($MandanteUsuario == ""){
                            $ToReturn = "byEjecutivoSystem";
                        }else{
                            $ToReturn = "byEjecutivoMandante";
                        }
                    break;
                    case '5':
                    break;
                    case '6':
                        if($MandanteUsuario == ""){
                            $ToReturn = "byCalidadSystem";
                        }else{
                            $ToReturn = "byCalidadMandante";
                        }
                    break;
                }
            }
            return $ToReturn;
        }
        function getNotesGroupedByCompetencias($Id_Evaluaciones,$Id_Personal){
            //$db = new Db();
            $SqlCompetencias = "select
                                    competencias_calidad.id as idCompetencia,
                                    competencias_calidad.nombre as Competencia,
                                    ROUND(AVG(respuesta_opciones_afirmaciones_calidad.valor),2) as Nota,
                                    ROUND(SUM(respuesta_opciones_afirmaciones_calidad.Nota),2) as NotaPonderada
                                from
                                    evaluaciones
                                        inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                        inner join afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                        inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                        inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                where
                                    evaluaciones.Id_Personal='".$Id_Personal."' and
                                    evaluaciones.id in (".$Id_Evaluaciones.") and evaluaciones.lastEvaluation='1'
                                group by
                                    competencias_calidad.id,
                                    competencias_calidad.nombre";
            $Competencias = $this->db->select($SqlCompetencias);
            return $Competencias;
        }
        function getCierreEjecutivos_InformeCierre($Month,$Id_Personal){
            $ToReturn = array();
            //$db = new Db();
            $Desde = date('Ym01',strtotime($Month));
            $Hasta = date('Ymt',strtotime($Desde));
            $SqlCierres = "select
                                cierre_evaluaciones.*
                            from
                                cierre_evaluaciones
                                inner join Usuarios on Usuarios.id = cierre_evaluaciones.Id_Usuario
                            where
                                cierre_evaluaciones.Id_Mandante='".$_SESSION['mandante']."' and
                                Usuarios.nivel='4' and
                                cierre_evaluaciones.Id_Personal='".$Id_Personal."' and
                                fecha between '".$Desde."' and '".$Hasta."'";
            $Cierres = $this->db->select($SqlCierres);
            $Cont = 1;
            foreach($Cierres as $Cierre){
                $ArrayTmp = array();
                $ArrayTmp["AspectosFortalecer"] = $Cierre["Aspectos_Fortalecer"];
                $ArrayTmp["AspectosCorregir"] = $Cierre["Aspectos_Corregir"];
                $ArrayTmp["CompromisoEjecutivo"] = $Cierre["Compromiso_Ejecutivo"];
                $Cont++;
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function selectDimensionesByCompetencia($Competencia){
            //$db = new Db();
            $SqlDimensiones = "select
                                    dimensiones_competencias_calidad.id as idDimension,
                                    dimensiones_competencias_calidad.nombre as Dimension,
                                    dimensiones_competencias_calidad.ponderacion as Ponderacion
                                from
                                    dimensiones_competencias_calidad
                                        inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                where
                                    competencias_calidad.id='".$Competencia."'
                                order by
                                competencias_calidad.nombre";
            $Dimensiones = $this->db->select($SqlDimensiones);
            return $Dimensiones;
        }
        function selectAfirmacionesByDimension($Dimension){
            //$db = new Db();
            $SqlAfirmaciones = "select
                                    afirmaciones_dimensiones_competencias_calidad.id as idAfirmacion,
                                    afirmaciones_dimensiones_competencias_calidad.nombre as Afirmacion,
                                    afirmaciones_dimensiones_competencias_calidad.ponderacion as Ponderacion
                                from
                                    afirmaciones_dimensiones_competencias_calidad
                                        inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                        inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                where
                                    dimensiones_competencias_calidad.id='".$Dimension."'
                                order by
                                dimensiones_competencias_calidad.id";
            $Afirmaciones = $this->db->select($SqlAfirmaciones);
            return $Afirmaciones;
        }
        function selectOpcionesAfirmacionesByAfirmacion($Afirmacion){
            //$db = new Db();
            $SqlOpciones = "select
                                opciones_afirmaciones_competencias_calidad.id as idOpcion,
                                opciones_afirmaciones_competencias_calidad.nombre as Opcion,
                                opciones_afirmaciones_competencias_calidad.valor as Valor
                            from
                                opciones_afirmaciones_competencias_calidad
                                    inner join afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = opciones_afirmaciones_competencias_calidad.id_afirmacion
                                    inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                    inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                            where
                                afirmaciones_dimensiones_competencias_calidad.id='".$Afirmacion."'
                            order by
                            opciones_afirmaciones_competencias_calidad.valor";
            $Opciones = $this->db->select($SqlOpciones);
            return $Opciones;
        }
        function getRespuestasAfirmacionesByCompetenciaAndEvaluacion($Competencia,$idEvaluacion){
            //$db = new Db();
            $SqlRespuestas = "select
                                    afirmaciones.id as idAfirmacion,
                                    respuestas.nota as Nota,
                                    respuestas.Valor as Valor
                                from
                                    competencias_calidad competencias
                                        inner join dimensiones_competencias_calidad dimensiones on dimensiones.id_competencia = competencias.id
                                        inner join afirmaciones_dimensiones_competencias_calidad afirmaciones on afirmaciones.id_dimension = dimensiones.id
                                        left join respuesta_opciones_afirmaciones_calidad respuestas on respuestas.id_afirmacion = afirmaciones.id
                                where
                                    respuestas.Id_Evaluacion = '".$idEvaluacion."' and
                                    competencias.id='".$Competencia."'
                                order by
                                    dimensiones.id";
            $Respuestas = $this->db->select($SqlRespuestas);
            return $Respuestas;
        }
        function getPeriodosEvaluacionesByMonthsAndYears($Mandante,$Cedente){
            $WhereCedente = $Cedente != "" ? " and mandante_cedente.Id_Cedente = '".$Cedente."'" : "";
            //$db = new Db();
            $ToReturn = array();
            $Months = array();
            $Months[1] = "Enero";
            $Months[2] = "Febrero";
            $Months[3] = "Marzo";
            $Months[4] = "Abril";
            $Months[5] = "Mayo";
            $Months[6] = "Junio";
            $Months[7] = "Julio";
            $Months[8] = "Agosto";
            $Months[9] = "Septiembre";
            $Months[10] = "Octubre";
            $Months[11] = "Noviembre";
            $Months[12] = "Diciembre";

            $SqlEvaluaciones = "select
                                    month(Fecha_Evaluacion) as Month,
                                    year(Fecha_Evaluacion) as Year
                                from
                                    evaluaciones
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                where
                                    mandante_cedente.Id_Mandante = '".$Mandante."'
                                    ".$WhereCedente."
                                group by
                                    month(Fecha_Evaluacion),
                                    year(Fecha_Evaluacion),
                                    Fecha_Evaluacion
                                order by
                                    Fecha_Evaluacion DESC";
            $Evaluaciones = $this->db->select($SqlEvaluaciones);
            foreach($Evaluaciones as $Evaluacion){
                $ArrayTmp = array();
                $ArrayTmp["Month"] = strlen($Evaluacion["Month"]) == 1 ? "0".$Evaluacion["Month"] : $Evaluacion["Month"];
                $ArrayTmp["MonthText"] = $Months[$Evaluacion["Month"]];
                $ArrayTmp["Year"] = $Evaluacion["Year"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getNotesByWeekAndEjecutivo($Periodo){
            //$db = new Db();
            
            $DateArray = $this->getDateFromServer();
            $Periodo = new DateTime($Periodo);
            $Periodo->modify('first day of this month');
            $Periodo = $Periodo->format('Ymd');
            $Desde = strtotime ('-3 months',strtotime($Periodo));
            $Desde = date('Ymd',$Desde);
            $Hasta = date('Ymt',strtotime($Periodo));

            $ArrayEjecutivos = array();
            $ArraySemanas = array();

            $SqlEjecutivos = "select
                                Personal.Id_Personal,
                                Personal.Nombre as Ejecutivo,
                                YEAR(evaluaciones.Fecha_Evaluacion) as Year,
                                MONTH(evaluaciones.Fecha_Evaluacion) as Month,
                                DATEPART(week,evaluaciones.Fecha_Evaluacion) as Week,
                                MAX(FORMAT(evaluaciones.Fecha_Evaluacion,'yyyy-MM-dd')) as Date,
                                ROUND(AVG(respuesta_opciones_afirmaciones_calidad.Valor),2) as Note
                            from
                                evaluaciones
                                    inner join Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                    inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                            where
                                mandante_cedente.Id_Mandante = '".$this->Id_Mandante."' and
                                byCalidadSystem = 1 and
                                CAST(evaluaciones.Fecha_Evaluacion AS DATE) between '".$Desde."' and '".$Hasta."' and
                                evaluaciones.lastEvaluation='1'
                            group by
                                Personal.Id_Personal,
                                Personal.Nombre,
                                YEAR(evaluaciones.Fecha_Evaluacion),
                                MONTH(evaluaciones.Fecha_Evaluacion),
                                DATEPART(week,evaluaciones.Fecha_Evaluacion),
                                evaluaciones.Fecha_Evaluacion
                            order by
                                YEAR(evaluaciones.Fecha_Evaluacion),
                                MONTH(evaluaciones.Fecha_Evaluacion),
                                DATEPART(week,evaluaciones.Fecha_Evaluacion)";
            $Ejecutivos = $this->db->select($SqlEjecutivos);
            if(count($Ejecutivos) > 0){
                $PrimeraFecha = $Ejecutivos[0]["Date"];
                $PrimeraFecha = date("Ym01",strtotime($PrimeraFecha));
                $PrimeraFecha = new DateTime($PrimeraFecha);
                $UltimaFecha = $Ejecutivos[count($Ejecutivos) - 1]["Date"];
                $UltimaFecha = date("Ymt",strtotime($UltimaFecha));
                $UltimaFecha = new DateTime($UltimaFecha);

                $Diferencia = $PrimeraFecha->diff($UltimaFecha);

                $Year = $PrimeraFecha->format("Y");
                $Month = $PrimeraFecha->format("m");
                for($i=0; $i<=$Diferencia->m; $i++){
                    $Semanas = $this->getSemanasMes($Year,$Month);
                    $ArraySemanas[$Month] = array();
                    $ArraySemanas[$Month] = $Semanas;
                    $Month = $PrimeraFecha->modify('+1 month')->format("m");
                }
                foreach($Ejecutivos as $Ejecutivo){
                    $Id_Personal = $Ejecutivo["Id_Personal"];
                    $NombreEjecutivo = $Ejecutivo["Ejecutivo"];
                    $Year = $Ejecutivo["Year"];
                    $Month = strlen($Ejecutivo["Month"]) > 1 ? $Ejecutivo["Month"] : "0".$Ejecutivo["Month"];
                    $Week = strlen($Ejecutivo["Week"]) > 1 ? $Ejecutivo["Week"] : "0".$Ejecutivo["Week"];
                    $Date = $Ejecutivo["Date"];
                    $Note = $Ejecutivo["Note"];
                    if(!isset($ArrayEjecutivos[$Id_Personal])){
                        $ArrayEjecutivos[$Id_Personal] = array();
                        $ArrayEjecutivos[$Id_Personal] = $ArraySemanas;
                        $ArrayEjecutivos[$Id_Personal]["Ejecutivo"] = utf8_encode($NombreEjecutivo);
                    }
                    if(!isset($ArrayEjecutivos[$Id_Personal][$Month][$Week])){
                        /*$CantWeeks = count($ArrayEjecutivos[$Id_Personal][$Month]) + 1;
                        $ArrayEjecutivos[$Id_Personal][$Month][$Week]["WeekTxt"] = "Semana ". $CantWeeks;
                        $ArrayEjecutivos[$Id_Personal][$Month][$Week]["Week"] = $Week;
                        $ArrayEjecutivos[$Id_Personal][$Month][$Week]["Note"] = 0;*/
                    }
                    if(isset($ArrayEjecutivos[$Id_Personal][$Month][$Week])){
                        $nota = $ArrayEjecutivos[$Id_Personal][$Month][$Week]["Note"];
                        $nota = number_format($nota + $Note, 2);
                        $ArrayEjecutivos[$Id_Personal][$Month][$Week]["Note"] = $nota;   
                    }
                }
            }


            /*echo "<table>";
                echo "<tr>";
                    echo "<td>";
                        echo "Ejecutivo";
                    echo "</td>";
                    foreach($ArraySemanas as $Meses){
                        foreach($Meses as $Mes){
                            //print_r($Mes);
                            echo "<td>";
                                echo $Mes["WeekTxt"];
                            echo "</td>";
                        }
                    }
                echo "</tr>";
            echo "</table>";*/
            /*echo "<pre>";
            print_r($ArrayEjecutivos);
            echo "</pre>";*/
            $ToReturn = array();
            $ToReturn["Ejecutivos"] = $ArrayEjecutivos;
            $ToReturn["Semanas"] = $ArraySemanas;
            return $ToReturn;
        }
        function getSemanasMes($Year,$Month){
            $DateStrToTime = strtotime($Year.$Month."01");
            $Month = date('m',$DateStrToTime);
            $CantidadDias = date('t',$DateStrToTime);
            $Semanas = array();
            $Semana = "";
            $ContSemanas = 1;
            for($i=1; $i<=$CantidadDias; $i++){
                $Day = $i > 9 ? $i : "0".$i;
                //$DateStrToTime = strtotime($Year.$Month.$Day);
                $Week = date("W",mktime(0,0,0,$Month,$Day,$Year));
                if($Semana != $Week){
                    $ArrayTmp = array();
                    $ArrayTmp["WeekTxt"] = "Semana ".$ContSemanas;
                    $ArrayTmp["Week"] = $Week;
                    $ArrayTmp["Note"] = 0;
                    $Semana = $Week;
                    $ContSemanas++;
                    $Semanas[$Semana] = array();
                    $Semanas[$Semana] = $ArrayTmp;
                    //array_push($Semanas,$ArrayTmp);
                }
            }
            /*echo "<pre>";
            print_r($Semanas);
            echo "</pre>";*/
            return $Semanas;
        }
        function getNotesByMonthAndEjecutivo($Periodo){
            //$db = new Db();
            
            $DateArray = $this->getDateFromServer();
            $Periodo = new DateTime($Periodo);
            $Periodo->modify('first day of this month');
            $Periodo = $Periodo->format('Ymd');
            $Desde = strtotime ('-3 months',strtotime($Periodo));
            $Desde = date('Ymd',$Desde);
            $Hasta = date('Ymt',strtotime($Periodo));

            $ArrayEjecutivos = array();
            $ArrayMeses = array();

            $SqlEjecutivos = "select
                                Personal.Id_Personal,
                                Personal.Nombre as Ejecutivo,
                                YEAR(evaluaciones.Fecha_Evaluacion) as Year,
                                MONTH(evaluaciones.Fecha_Evaluacion) as Month,
                                MAX(FORMAT(evaluaciones.Fecha_Evaluacion,'yyyy-MM-dd')) as Date,
                                ROUND(AVG(respuesta_opciones_afirmaciones_calidad.Valor),2) as Note
                            from
                                evaluaciones
                                    inner join Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                    inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                            where
                                mandante_cedente.Id_Mandante = '".$this->Id_Mandante."' and
                                byCalidadSystem = 1 and
                                CAST(evaluaciones.Fecha_Evaluacion AS DATE) between '".$Desde."' and '".$Hasta."' and
                                evaluaciones.lastEvaluation='1'
                            group by
                                Personal.Id_Personal,
                                Personal.Nombre,
                                YEAR(evaluaciones.Fecha_Evaluacion),
                                MONTH(evaluaciones.Fecha_Evaluacion),
                                evaluaciones.Fecha_Evaluacion
                            order by
                                YEAR(evaluaciones.Fecha_Evaluacion),
                                MONTH(evaluaciones.Fecha_Evaluacion)";
            $Ejecutivos = $this->db->select($SqlEjecutivos);
            if(count($Ejecutivos) > 0){
                $PrimeraFecha = $Ejecutivos[0]["Date"];
                $PrimeraFecha = date("Ym01",strtotime($PrimeraFecha));
                $PrimeraFecha = new DateTime($PrimeraFecha);
                $UltimaFecha = $Ejecutivos[count($Ejecutivos) - 1]["Date"];
                $UltimaFecha = date("Ymt",strtotime($UltimaFecha));
                $UltimaFecha = new DateTime($UltimaFecha);

                $Diferencia = $PrimeraFecha->diff($UltimaFecha);

                $Year = $PrimeraFecha->format("Y");
                $Month = $PrimeraFecha->format("m");
                for($i=0; $i<=$Diferencia->m; $i++){
                    $ArrayTmp = array();
                    $ArrayTmp["Year"] = $Year;
                    $ArrayTmp["Month"] = $Month;
                    $ArrayTmp["Note"] = 0;
                    $ArrayMeses[$Month] = array();
                    $ArrayMeses[$Month] = $ArrayTmp;
                    $Month = $PrimeraFecha->modify('+1 month')->format("m");
                }
                foreach($Ejecutivos as $Ejecutivo){
                    $Id_Personal = $Ejecutivo["Id_Personal"];
                    $NombreEjecutivo = $Ejecutivo["Ejecutivo"];
                    $Year = $Ejecutivo["Year"];
                    $Month = strlen($Ejecutivo["Month"]) > 1 ? $Ejecutivo["Month"] : "0".$Ejecutivo["Month"];
                    $Date = $Ejecutivo["Date"];
                    $Note = $Ejecutivo["Note"];
                    if(!isset($ArrayEjecutivos[$Id_Personal])){
                        $ArrayEjecutivos[$Id_Personal] = array();
                        $ArrayEjecutivos[$Id_Personal] = $ArrayMeses;
                        $ArrayEjecutivos[$Id_Personal]["Ejecutivo"] = utf8_encode($NombreEjecutivo);
                    }
                    if(isset($ArrayEjecutivos[$Id_Personal][$Month])){
                        $nota = $ArrayEjecutivos[$Id_Personal][$Month]["Note"];
                        $nota = number_format($nota + $Note, 2);
                        $ArrayEjecutivos[$Id_Personal][$Month]["Note"] = $nota;   
                    }
                }
            }
            /*echo "<pre>";
            print_r($ArrayEjecutivos);
            echo "</pre>";*/
            $ToReturn = array();
            $ToReturn["Ejecutivos"] = $ArrayEjecutivos;
            $ToReturn["Meses"] = $ArrayMeses;
            return $ToReturn;
        }
        function getAspectosIndividualesByEvaluacion($idEvaluacion, $TipoAspecto = "Corregir", $Competencia = ""){
            //$db = new Db();
            $WhereCompetencia = $Competencia != "" ? " competencias_calidad.id='".$Competencia."' and " : "";
            $OperadorAspecto = "";
            switch($TipoAspecto){
                case 'Corregir':
                    $OperadorAspecto = "<=";
                break;
                case 'Fortalecer':
                    $OperadorAspecto = ">";
                break;
            }
            $SqlAspectos = "select
                                opciones_afirmaciones_competencias_calidad.descripcion_caracteristica as Aspecto
                            from
                                respuesta_opciones_afirmaciones_calidad
                                inner join opciones_afirmaciones_competencias_calidad on opciones_afirmaciones_competencias_calidad.id_afirmacion = respuesta_opciones_afirmaciones_calidad.id_afirmacion and opciones_afirmaciones_competencias_calidad.valor = respuesta_opciones_afirmaciones_calidad.Valor
                                inner join afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                            where
                                ".$WhereCompetencia."
                                Id_Evaluacion in (".$idEvaluacion.") and 
                                respuesta_opciones_afirmaciones_calidad.Valor ".$OperadorAspecto." afirmaciones_dimensiones_competencias_calidad.corte";
            $Aspectos = $this->db->select($SqlAspectos);
            return $Aspectos;
        }
        function NotaPromedioByPersonal($idPersonal, $Mandante = "",$Periodo){
            //$db = new Db();
            $Hasta = date("Ymt",strtotime($Periodo));
            $WhereMandante = $Mandante != "" ? " mandante_cedente.Id_Mandante='".$Mandante."' and " : "";
            $SqlPromedio = "select
                                AVG(respuesta_opciones_afirmaciones_calidad.Valor) as Nota
                            from
                                evaluaciones
                                    inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                            where
                                ".$WhereMandante."
                                evaluaciones.byCalidadSystem = 1 and 
                                evaluaciones.lastEvaluation='1' and
                                evaluaciones.Id_Personal='".$idPersonal."'and
                                CAST(evaluaciones.Fecha_Evaluacion AS DATE) <= '".$Hasta."'";
            $Promedio = $this->db->select($SqlPromedio);
            $Promedio = $Promedio[0]["Nota"];
            return $Promedio;
        }
        function getPerfilEjecutivoByNota($Nota){
            //$db = new Db();
            $SqlPerfil = "select * from corte_nivel_ejecutivo_calidad where $Nota between notaMin and notaMax";
            $Perfil = $this->db->select($SqlPerfil);
            if($Perfil){
                $Perfil = $Perfil[0];
            }else{
                $Perfil = '';
            }
            return $Perfil;
        }
        function getAspectosPromediosByEvaluacion($idEvaluacion, $TipoAspecto = "Corregir", $Competencia = ""){
            //$db = new Db();
            $WhereCompetencia = $Competencia != "" ? " competencias_calidad.id='".$Competencia."' and " : "";
            $OperadorAspecto = "";
            switch($TipoAspecto){
                case 'Corregir':
                    $OperadorAspecto = "<=";
                break;
                case 'Fortalecer':
                    $OperadorAspecto = ">";
                break;
            }
            $SqlAspectos = "select
                                opciones_afirmaciones_competencias_calidad.descripcion_caracteristica as Aspecto,
                                afirmaciones_dimensiones_competencias_calidad.corte as Corte,
                                avg(respuesta_opciones_afirmaciones_calidad.Valor) as Nota
                            from
                                respuesta_opciones_afirmaciones_calidad
                                inner join opciones_afirmaciones_competencias_calidad on opciones_afirmaciones_competencias_calidad.id_afirmacion = respuesta_opciones_afirmaciones_calidad.id_afirmacion and opciones_afirmaciones_competencias_calidad.valor = respuesta_opciones_afirmaciones_calidad.Valor
                                inner join afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                            where
                                ".$WhereCompetencia."
                                Id_Evaluacion in (".$idEvaluacion.") 
                            group by
                                afirmaciones_dimensiones_competencias_calidad.id,
                                opciones_afirmaciones_competencias_calidad.descripcion_caracteristica,
                                afirmaciones_dimensiones_competencias_calidad.corte
                            having 
                                avg(respuesta_opciones_afirmaciones_calidad.Valor) ".$OperadorAspecto." afirmaciones_dimensiones_competencias_calidad.corte";
            $Aspectos = $this->db->select($SqlAspectos);
            return $Aspectos;
        }
        function getAspectosByEvaluacion($idEvaluacion){
            //$db = new Db();
            $SqlAspectos = "select
                                idAfirmacion,
                                Cantidad,
                                Aspecto,
                                CASE WHEN Nota <= Corte THEN 'Corregir' ELSE 'Fortalecer' END as Accion
                            from
                                (select 
                                    opciones_afirmaciones_competencias_calidad.id as idOpcion,
                                    opciones_afirmaciones_competencias_calidad.descripcion_caracteristica as Aspecto,
                                    afirmaciones_dimensiones_competencias_calidad.id as idAfirmacion,
                                    afirmaciones_dimensiones_competencias_calidad.corte as Corte,
                                    avg(respuesta_opciones_afirmaciones_calidad.Valor) as Nota,
                                    count(*) as Cantidad
                                from
                                    respuesta_opciones_afirmaciones_calidad
                                    inner join opciones_afirmaciones_competencias_calidad on opciones_afirmaciones_competencias_calidad.id_afirmacion = respuesta_opciones_afirmaciones_calidad.id_afirmacion and opciones_afirmaciones_competencias_calidad.valor = respuesta_opciones_afirmaciones_calidad.Valor
                                    inner join afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                    inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                    inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                                WHERE
                                    Id_Evaluacion in (".$idEvaluacion.") 
                                GROUP BY
                                    opciones_afirmaciones_competencias_calidad.id,
                                    opciones_afirmaciones_competencias_calidad.descripcion_caracteristica,
                                    afirmaciones_dimensiones_competencias_calidad.id,
                                    afirmaciones_dimensiones_competencias_calidad.corte) tb1
                            order by
                                idAfirmacion,
                                Cantidad DESC";
            $Aspectos = $this->db->select($SqlAspectos);
            return $Aspectos;
        }
        function getNotasByEvaluationsAndDateGroupedByCompetencia($idPersonal,$Periodo){
            //$db = new Db();
            $Desde = date("Ym01",strtotime($Periodo));
            $Hasta = date("Ymt",strtotime($Periodo));
            $TipoUsuario = $this->getTipoUsuario($_SESSION['id_usuario']);
            $SqlNotas = "select
                            competencias_calidad.tag as Competencia,
                            year(evaluaciones.Fecha_Evaluacion) as Year,
                            month(evaluaciones.Fecha_Evaluacion) as Month,
                            ROUND(avg(respuesta_opciones_afirmaciones_calidad.Valor),2) as Nota
                        from
                            evaluaciones
                                inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                                inner join afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id = respuesta_opciones_afirmaciones_calidad.id_afirmacion
                                inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id = afirmaciones_dimensiones_competencias_calidad.id_dimension
                                inner join competencias_calidad on competencias_calidad.id = dimensiones_competencias_calidad.id_competencia
                        where
                            evaluaciones.".$TipoUsuario." = '1' and 
                            evaluaciones.lastEvaluation='1' and 
                            CAST(evaluaciones.Fecha_Evaluacion AS DATE) between DATEADD(MONTH, -2, '".$Desde."') and '".$Hasta."' and
                            evaluaciones.Id_Personal = '".$idPersonal."'
                        group by
                            competencias_calidad.id,
                            competencias_calidad.tag,
                            year(evaluaciones.Fecha_Evaluacion),
                            month(evaluaciones.Fecha_Evaluacion),
                            evaluaciones.Fecha_Evaluacion
                        order by
                            year(evaluaciones.Fecha_Evaluacion) DESC,
                            month(evaluaciones.Fecha_Evaluacion) DESC";                        
            $Notas = $this->db->select($SqlNotas);
            return $Notas;
        }
        function getNotaFromCierre($idCierre){
            //$db = new Db();
            $this->Id_Cierre = $idCierre;
            $Cierre = $this->getCierre($idCierre);
            $Cierre = $Cierre[0];
            $TipoCierre = $Cierre["tipo_cierre"];
            $WherePeriodo = "";
            $WherePersonal = "";
            $WhereMandante = "";
            $WhereEvaluaciones = "";
            switch($TipoCierre){
                case '0':
                    $WhereEvaluaciones = " respuesta_opciones_afirmaciones_calidad.Id_Evaluacion IN (".$Cierre["Id_Evaluaciones"].") ";
                break;
                case '1':
                    $Periodo = $Cierre["fecha"];
                    $Desde = date("Ym01",strtotime($Periodo));
                    $Hasta = date("Ymt",strtotime($Desde));
                    $WherePeriodo = " CAST(evaluaciones.Fecha_Evaluacion AS DATE) between '".$Desde."' and '".$Hasta."'";
                    $WherePersonal = " and evaluaciones.Id_Personal = '".$Cierre["Id_Personal"]."' ";
                    $WhereMandante = " and Id_Mandante = '".$_SESSION['mandante']."' ";
                break;
            }
            $SqlNota = "select
                            AVG(Valor) Nota
                        from
                            respuesta_opciones_afirmaciones_calidad
                                inner join evaluaciones on evaluaciones.id = respuesta_opciones_afirmaciones_calidad.Id_Evaluacion
                        where
                            ".$WhereEvaluaciones."
                            ".$WherePeriodo."
                            ".$WherePersonal."
                            ".$WhereMandante."
                            ";
            $Nota = $this->db->select($SqlNota);
            return $Nota[0]["Nota"];
        }
        function getPerfilByUserType($UserType,$Pauta,$Ejecutivo){
            $WhereSoloActivos = $Ejecutivo == "" ? " and Personal.Activo = '1' " : "";
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Personal.Id_Personal = '".$Ejecutivo."'" : "";
            $ByUser = "";
            switch($UserType){
                case '1':
                    //Calidad Sistema
                    $ByUser = "byCalidadSystem";
                break;
                case '2':
                    //Calidad Mandante
                    $ByUser = "byCalidadMandante";
                break;
                case '3':
                    //Ejecutivo Sistema
                    $ByUser = "byEjecutivoSystem";
                break;
                case '4':
                    //Ejecutivo Mandantte
                    $ByUser = "byEjecutivoMandante";
                break;
                case '5':
                break;
                case '6':
                break;
            }
            //$db = new Db();
            $SqlNota = "select
                            ROUND(AVG(respuesta_opciones_afirmaciones_calidad.Valor),2) as Nota
                        from
                            evaluaciones
                            inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.Id_Evaluacion = evaluaciones.id
                            inner join mandante_cedente on mandante_cedente.Id_Mandante = respuesta_opciones_afirmaciones_calidad.Id_Mandante
                            inner join Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                            inner join contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = mandante_cedente.Id_Cedente
                            inner join contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor
                        where
                            ".$ByUser." = 1 and 
                            evaluaciones.lastEvaluation='1'
                            ".$WhereSoloActivos." ".$WherePauta." ".$WhereEjecutivo." ";
            $Nota = $this->db->select($SqlNota);
            $Nota = $Nota[0]["Nota"];
            if($Nota){
                $Perfil = $this->getPerfilEjecutivoByNota($Nota);  
            }else{
                $Perfil = '';
            }
            
            return $Perfil;
        }
        function getTotalEjecutivosPauta($Mandante,$Pauta,$Ejecutivo){
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Personal.Id_Personal = '".$Ejecutivo."'" : "";
            $WhereSoloActivos = $Ejecutivo == "" ? " and Personal.Activo = '1' " : "";
            $ToReturn = "";
            //$db = new Db();
            $SqlTotalEjecutivos = "select
                                    count(*) as TotalEjecutivos
                                from
                                    (select
                                        Personal.Id_Personal
                                    from
                                        evaluaciones
                                            inner join Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                            inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                            inner join contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = mandante_cedente.Id_Cedente
                                            inner join contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor
                                    where
                                        1 = 1 AND
                                        mandante_cedente.Id_Mandante='".$Mandante."'
                                        ".$WhereSoloActivos."
                                        ".$WherePauta."
                                        ".$WhereEjecutivo."
                                    group by
                                        Personal.Id_Personal) Ejecutivos";
            $TotalEjecutivos = $db ->select($SqlTotalEjecutivos);
            $ToReturn = $TotalEjecutivos[0]["TotalEjecutivos"]."|";
            return $ToReturn;
        }
        function getRutaGrabaciones($NombreArchivo){
            //20170725-185812_995883252_013_mrivero-all.mp3
            $ArrayNombreArchivo = explode("-",$NombreArchivo);
            $Fecha = $ArrayNombreArchivo[0];
            $ArrayNombreArchivo = explode("_",$ArrayNombreArchivo[1]);
            $Hora = $ArrayNombreArchivo[0];
            $Fono = $ArrayNombreArchivo[1];
            $Lista = $ArrayNombreArchivo[2];
            $ArrayNombreArchivo = explode("-",$ArrayNombreArchivo[3]);
            $Usuario = $ArrayNombreArchivo[0];
            return $Lista."/".$Fecha."/".$Usuario;
        }
        function getNotaMaximaEvaluacion(){
            return $this->NotaMaximaEvaluacion;
        }
        function updateNotaMaximaEvaluacion($NotaMaximaEvaluacion){
            //$db = new Db();
            $ToReturn = false;
            $SqlUpdate = "update fireConfig set NotaMaximaEvaluacion = '".$NotaMaximaEvaluacion."'";
            $Update = $this->db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getCompetencias($idPauta){
            //$db = new Db();
            $EvaluationsArray = array();
            $Cont = 0;
            $SqlEvaluation = "select
                                    competencias.id,
                                    competencias.nombre,
                                    competencias.tag,
                                    competencias.descripcion,
                                    competencias.ponderacion
                                from
                                    competencias_calidad competencias
                                WHERE
                                    id_contenedor='".$idPauta."'
                                group by
                                    competencias.id,
                                    competencias.nombre,
                                    competencias.tag,
                                    competencias.descripcion,
                                    competencias.ponderacion
                                order by
                                    competencias.nombre";
		    $Evaluations = $this->db->select($SqlEvaluation);
            foreach($Evaluations as $Evaluation){
                $EvaluationArray = array();
                $EvaluationArray['Nombre'] = utf8_encode($Evaluation["nombre"]);
                $EvaluationArray['Tag'] = utf8_encode($Evaluation["tag"]);
                $EvaluationArray['Descripcion'] = utf8_encode($Evaluation["descripcion"]);
                $EvaluationArray['Ponderacion'] = number_format($Evaluation["ponderacion"], 2, '.', '');
                $EvaluationArray['ID'] = $Evaluation["id"];
                $EvaluationsArray[$Cont] = $EvaluationArray;
                $Cont++;
            }
            return $EvaluationsArray;
        }
        function getDimensiones($idCompetencia){
            //$db = new Db();
            $DimensionesArray = array();
            $Cont = 0;
            $SqlDimensiones = "select
                                    dimensiones.id as id,
                                    dimensiones.id_competencia as competencia,
                                    dimensiones.nombre as nombre,
                                    dimensiones.ponderacion as ponderacion
                                from
                                    dimensiones_competencias_calidad dimensiones
                                where
                                    dimensiones.id_competencia='".$idCompetencia."'
                                group by
                                    dimensiones.id,
                                    dimensiones.id_competencia,
                                    dimensiones.nombre,
                                    dimensiones.ponderacion
                                order by
                                    dimensiones.nombre";
		    $Dimensiones = $this->db->select($SqlDimensiones);
            foreach($Dimensiones as $Dimension){
                $DimensionArray = array();
                $DimensionArray['Nombre'] = utf8_encode($Dimension["nombre"]);
                $DimensionArray['Ponderacion'] = number_format($Dimension["ponderacion"], 2, '.', '');
                $DimensionArray['ID'] = $Dimension["competencia"]."_".$Dimension["id"];
                $DimensionesArray[$Cont] = $DimensionArray;
                $Cont++;
            }
            return $DimensionesArray;
        }
        function getAfirmaciones($idDimension){
            //$db = new Db();
            $AfirmacionesArray = array();
            $Cont = 0;
            $SqlAfirmaciones = "select
                                    afirmaciones.id as id,
                                    afirmaciones.id_dimension as dimension,
                                    afirmaciones.nombre as nombre,
                                    afirmaciones.ponderacion as ponderacion,
                                    afirmaciones.descripcion_simple as descripcion_simple,
                                    afirmaciones.corte as corte
                                from
                                    afirmaciones_dimensiones_competencias_calidad afirmaciones
                                where
                                    afirmaciones.id_dimension='".$idDimension."'
                                group by
                                    afirmaciones.id,
                                    afirmaciones.id_dimension,
                                    afirmaciones.nombre,
                                    afirmaciones.ponderacion,
                                    afirmaciones.descripcion_simple,
                                    afirmaciones.corte
                                order by
                                    afirmaciones.nombre";
		    $Afirmaciones = $this->db->select($SqlAfirmaciones);
            foreach($Afirmaciones as $Afirmacion){
                $AfirmacionArray = array();
                $AfirmacionArray['Nombre'] = utf8_encode($Afirmacion["nombre"]);
                $AfirmacionArray['Ponderacion'] = number_format($Afirmacion["ponderacion"], 2, '.', '');
                $AfirmacionArray['DescripcionSimple'] = utf8_encode($Afirmacion["descripcion_simple"]);
                $AfirmacionArray['Corte'] = utf8_encode($Afirmacion["corte"]);
                $AfirmacionArray['ID'] = $Afirmacion["dimension"]."_".$Afirmacion["id"];
                $AfirmacionesArray[$Cont] = $AfirmacionArray;
                $Cont++;
            }
            return $AfirmacionesArray;
        }
        function getOpcionesAfirmaciones($idAfirmacion){
            //$db = new Db();
            $OpcionesArray = array();
            $Cont = 0;
            $SqlOpciones = "select
                                    opciones.id as id,
                                    opciones.id_afirmacion as afirmacion,
                                    opciones.nombre as nombre,
                                    opciones.valor as valor,
                                    opciones.descripcion_caracteristica as descripcion_caracteristica
                                from
                                    opciones_afirmaciones_competencias_calidad opciones
                                where
                                    opciones.id_afirmacion='".$idAfirmacion."'
                                group by
                                    opciones.id,
                                    opciones.id_afirmacion,
                                    opciones.nombre,
                                    opciones.valor,
                                    opciones.descripcion_caracteristica
                                order by
                                    opciones.nombre";
		    $Opciones = $this->db->select($SqlOpciones);
            foreach($Opciones as $Opcion){
                $OpcionArray = array();
                $OpcionArray['Nombre'] = utf8_encode($Opcion["nombre"]);
                $OpcionArray['Valor'] = number_format($Opcion["valor"], 2, '.', '');
                $OpcionArray['DescripcionCaracteristica'] = utf8_encode($Opcion["descripcion_caracteristica"]);
                $OpcionArray['ID'] = $Opcion["afirmacion"]."_".$Opcion["id"];
                $OpcionesArray[$Cont] = $OpcionArray;
                $Cont++;
            }
            return $OpcionesArray;
        }
        function SaveCompetencia($idPauta,$Nombre,$Descripcion,$Ponderacion,$Tag){
            $Nombre = utf8_decode($Nombre);
            $Descripcion = utf8_decode($Descripcion);
            $Tag = utf8_decode($Tag);
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlInsert = "insert into competencias_calidad (id_contenedor,nombre,descripcion,ponderacion,tag) values ('".$idPauta."','".$Nombre."','".$Descripcion."','".$Ponderacion."','".$Tag."')";
            $Insert = $this->db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function DeleteCompetencia($idCompetencia){
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlRespuestas = " select * from respuesta_opciones_afirmaciones_calidad 
                                inner join afirmaciones_dimensiones_competencias_calidad on respuesta_opciones_afirmaciones_calidad.id_afirmacion = afirmaciones_dimensiones_competencias_calidad.id 
                                inner join dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id_dimension = dimensiones_competencias_calidad.id
                                where dimensiones_competencias_calidad.id_competencia ='".$idCompetencia."'";
            
            $Respuestas = $this->db->select($SqlRespuestas);

            if(!$Respuestas){
                $SqlDimensiones = "select * from dimensiones_competencias_calidad where id_competencia ='".$idCompetencia."'";
                $Dimensiones = $this->db->select($SqlDimensiones);
                foreach($Dimensiones as $Dimension){

                    $SqlAfirmaciones = "select * from afirmaciones_dimensiones_competencias_calidad where id_dimension ='".$Dimension['id']."'";
                    $Afirmaciones = $this->db->select($SqlAfirmaciones);

                    foreach($Afirmaciones as $Afirmacion){
                        $SqlDelete= "delete from opciones_afirmaciones_competencias_calidad where id_afirmacion ='".$Afirmacion['id']."'";
                        $Delete = $this->db->query($SqlDelete);

                        $SqlDelete= "delete from afirmaciones_dimensiones_competencias_calidad where id ='".$Afirmacion['id']."'";
                        $Delete = $this->db->query($SqlDelete);
                    }

                    $SqlDelete= "delete from dimensiones_competencias_calidad where id ='".$Dimension['id']."'";
                    $Delete = $this->db->query($SqlDelete);
                }

                $SqlDelete = "delete from competencias_calidad where id='".$idCompetencia."'";
                $Delete = $this->db->query($SqlDelete);

                if($Delete){
                    $ToReturn["result"] = true;
                }
            }else{
                $ToReturn["Message"] = "Esta competencia no puede ser eliminada debido a que posee evaluaciones";
            }

            return $ToReturn;
        }
        function GetCompetencia($idCompetencia){
            //$db = new Db();
            $ToReturn = array();
            $SqlCompetencia = "select * from competencias_calidad where id='".$idCompetencia."'";
            $Competencia = $this->db->select($SqlCompetencia);
            $Competencia = $Competencia[0];
            $ToReturn["Nombre"] = utf8_encode($Competencia["nombre"]);
            $ToReturn["Descripcion"] = utf8_encode($Competencia["descripcion"]);
            $ToReturn["Ponderacion"] = $Competencia["ponderacion"];
            $ToReturn["Tag"] = utf8_encode($Competencia["tag"]);
            return $ToReturn;
        }
        function UpdateCompetencia($idCompetencia,$Nombre,$Descripcion,$Ponderacion,$Tag){
            $Nombre = utf8_decode($Nombre);
            $Descripcion = utf8_decode($Descripcion);
            $Tag = utf8_decode($Tag);
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlUpdate = "update competencias_calidad set nombre='".$Nombre."', descripcion='".$Descripcion."', ponderacion='".$Ponderacion."', tag='".$Tag."' where id='".$idCompetencia."'";
            $Update = $this->db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function SaveDimension($Nombre,$Ponderacion,$idCompetencia){
            $Nombre = utf8_decode($Nombre);
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlInsert = "insert into dimensiones_competencias_calidad (nombre,ponderacion,id_competencia) values ('".$Nombre."','".$Ponderacion."','".$idCompetencia."')";
            $Insert = $this->db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function DeleteDimension($idDimension){
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["result"] = false;

            $SqlRespuestas = " select * from respuesta_opciones_afirmaciones_calidad 
            inner join afirmaciones_dimensiones_competencias_calidad on respuesta_opciones_afirmaciones_calidad.id_afirmacion = afirmaciones_dimensiones_competencias_calidad.id 
            inner join dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id_dimension = dimensiones_competencias_calidad.id
            where dimensiones_competencias_calidad.id ='".$idDimension."'";

            $Respuestas = $this->db->select($SqlRespuestas);

            if(!$Respuestas){
                $SqlAfirmaciones = "select * from afirmaciones_dimensiones_competencias_calidad where id_dimension ='".$idDimension."'";
                $Afirmaciones = $this->db->select($SqlAfirmaciones);
                foreach($Afirmaciones as $Afirmacion){
                    $SqlDelete= "delete from opciones_afirmaciones_competencias_calidad where id_afirmacion ='".$Afirmacion['id']."'";
                    $Delete = $this->db->query($SqlDelete);

                    $SqlDelete= "delete from afirmaciones_dimensiones_competencias_calidad where id ='".$Afirmacion['id']."'";
                    $Delete = $this->db->query($SqlDelete);
                }
                $SqlDelete = "delete from dimensiones_competencias_calidad where id='".$idDimension."'";
                $Delete = $this->db->query($SqlDelete);
                if($Delete){
                    $ToReturn["result"] = true;
                }
            }else{
                $ToReturn["Message"] = "Esta dimension no puede ser eliminada debido a que posee evaluaciones";
            }
            return $ToReturn;
        }
        function GetDimension($idDimension){
            //$db = new Db();
            $ToReturn = array();
            $SqlDimension = "select * from dimensiones_competencias_calidad where id='".$idDimension."'";
            $Dimension = $this->db->select($SqlDimension);
            $Dimension = $Dimension[0];
            $ToReturn["Nombre"] = utf8_encode($Dimension["nombre"]);
            $ToReturn["Ponderacion"] = $Dimension["ponderacion"];
            return $ToReturn;
        }
        function UpdateDimension($idDimension,$Nombre,$Ponderacion){
            $Nombre = utf8_decode($Nombre);
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlUpdate = "update dimensiones_competencias_calidad set nombre='".$Nombre."', ponderacion='".$Ponderacion."' where id='".$idDimension."'";
            $Update = $this->db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function SaveAfirmacion($Nombre,$Ponderacion,$DescripcionSimple,$Corte,$idDimension){
            $Nombre = utf8_decode($Nombre);
            $DescripcionSimple = utf8_decode($DescripcionSimple);
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlInsert = "insert into afirmaciones_dimensiones_competencias_calidad (nombre,ponderacion,descripcion_simple,corte,id_dimension) values ('".$Nombre."','".$Ponderacion."','".$DescripcionSimple."','".$Corte."','".$idDimension."')";
            $Insert = $this->db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function DeleteAfirmacion($idAfirmacion){
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlRespuestas = " select * from respuesta_opciones_afirmaciones_calidad where id_afirmacion ='".$idAfirmacion."'";

            $Respuestas = $this->db->select($SqlRespuestas);

            if(!$Respuestas){
                $SqlDelete= "delete from opciones_afirmaciones_competencias_calidad where id_afirmacion ='".$idAfirmacion."'";
                $Delete = $this->db->query($SqlDelete);
                $SqlDelete = "delete from afirmaciones_dimensiones_competencias_calidad where id='".$idAfirmacion."'";
                $Delete = $this->db->query($SqlDelete);
                if($Delete){
                    $ToReturn["result"] = true;
                }
            }else{
                $ToReturn["Message"] = "Esta afirmación no puede ser eliminada debido a que posee evaluaciones";
            }
            return $ToReturn;
        }
        function GetAfirmacion($idAfirmacion){
            //$db = new Db();
            $ToReturn = array();
            $SqlAfirmacion = "select * from afirmaciones_dimensiones_competencias_calidad where id='".$idAfirmacion."'";
            $Afirmacion = $this->db->select($SqlAfirmacion);
            $Afirmacion = $Afirmacion[0];
            $ToReturn["Nombre"] = utf8_encode($Afirmacion["nombre"]);
            $ToReturn["Ponderacion"] = $Afirmacion["ponderacion"];
            $ToReturn["DescripcionSimple"] = utf8_encode($Afirmacion["descripcion_simple"]);
            $ToReturn["Corte"] = $Afirmacion["corte"];
            return $ToReturn;
        }
        function UpdateAfirmacion($idAfirmacion,$Nombre,$Ponderacion,$DescripcionSimple,$Corte){
            $Nombre = utf8_decode($Nombre);
            $DescripcionSimple = utf8_decode($DescripcionSimple);
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlUpdate = "update afirmaciones_dimensiones_competencias_calidad set nombre='".$Nombre."', ponderacion='".$Ponderacion."', descripcion_simple='".$DescripcionSimple."', corte='".$Corte."' where id='".$idAfirmacion."'";
            $Update = $this->db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function SaveOpcionAfirmacion($Nombre,$Valor,$DescripcionCaracteristica,$idAfirmacion){
            $Nombre = utf8_decode($Nombre);
            $DescripcionCaracteristica = utf8_decode($DescripcionCaracteristica);
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlInsert = "insert into opciones_afirmaciones_competencias_calidad (nombre,valor,descripcion_caracteristica,id_afirmacion) values ('".$Nombre."','".$Valor."','".$DescripcionCaracteristica."','".$idAfirmacion."')";
            $Insert = $this->db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function DeleteOpcionAfirmacion($OpcionAfirmacion){
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["result"] = false;

            $SqlRespuestas = " select * from respuesta_opciones_afirmaciones_calidad 
            inner join afirmaciones_dimensiones_competencias_calidad on respuesta_opciones_afirmaciones_calidad.id_afirmacion = afirmaciones_dimensiones_competencias_calidad.id 
            inner join opciones_afirmaciones_competencias_calidad on opciones_afirmaciones_competencias_calidad.id_afirmacion = afirmaciones_dimensiones_competencias_calidad.id
            where opciones_afirmaciones_competencias_calidad.id ='".$OpcionAfirmacion."'";

            $Respuestas = $this->db->select($SqlRespuestas);

            if(!$Respuestas){
                $SqlDelete = "delete from opciones_afirmaciones_competencias_calidad where id='".$OpcionAfirmacion."'";
                $Delete = $this->db->query($SqlDelete);
                if($Delete){
                    $ToReturn["result"] = true;
                }
            }else{
                $ToReturn["Message"] = "Esta opción no puede ser eliminada debido a que posee evaluaciones";
            }

            return $ToReturn;
        }
        function GetOpcionAfirmacion($idOpcionAfirmacion){
            //$db = new Db();
            $ToReturn = array();
            $SqlOpcionAfirmacion = "select * from opciones_afirmaciones_competencias_calidad where id='".$idOpcionAfirmacion."'";
            $OpcionAfirmacion = $this->db->select($SqlOpcionAfirmacion);
            $OpcionAfirmacion = $OpcionAfirmacion[0];
            $ToReturn["Nombre"] = utf8_encode($OpcionAfirmacion["nombre"]);
            $ToReturn["Valor"] = $OpcionAfirmacion["valor"];
            $ToReturn["DescripcionCaracteristica"] = utf8_encode($OpcionAfirmacion["descripcion_caracteristica"]);
            return $ToReturn;
        }
        function UpdateOpcionAfirmacion($idOpcionAfirmacion,$Nombre,$Valor,$DescripcionCaracteristica){
            $Nombre = utf8_decode($Nombre);
            $DescripcionCaracteristica = utf8_decode($DescripcionCaracteristica);
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlUpdate = "update opciones_afirmaciones_competencias_calidad set nombre='".$Nombre."', valor='".$Valor."', descripcion_caracteristica='".$DescripcionCaracteristica."' where id='".$idOpcionAfirmacion."'";
            $Update = $this->db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }

        function getFocoConfig(){
            //$db = new Db();
            $SqlFocoConfig = "SELECT * FROM fireConfig WHERE id = 1 LIMIT 1";
            $FocoConfig = $this->db->select($SqlFocoConfig);
            return $FocoConfig[0];
        }
        function getGrabaciones($startDate,$endDate,$Ejecutivo,$Tipificacion,$Telefono,$Rut){
            //$db = new Db();
            $RecordsArray = array();
            $Cont = 0;
            $WhereTipificacion = $Tipificacion != "" ? " and gestion_ult_trimestre.Id_TipoGestion='".$Tipificacion."' " : "";
            $WhereTelefono = $Telefono != "" ? " and gestion_ult_trimestre.fono_discado='".$Telefono."' " : "";
            $WhereRut = $Rut != "" ? " and gestion_ult_trimestre.rut_cliente='".$Rut."' " : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and gestion_ult_trimestre.nombre_ejecutivo='".$Ejecutivo."' " : "";
            $WhereDate = $startDate != "" ? " and gestion_ult_trimestre.fecha_gestion BETWEEN '".$startDate."' and '".$endDate."' " : "";
            $SqlRecord = "
                        select
                            Tipo_Contacto.Nombre as Contacto,
                            grabacion_2.id,
                            grabacion_2.Nombre_Grabacion as Filename,
                            grabacion_2.Fecha as Date,
                            grabacion_2.Cartera as Cartera,
                            grabacion_2.Usuario as User,
                            grabacion_2.Telefono as Phone,
                            gestion_ult_trimestre.rut_cliente as Rut,
                            Tipo_Contacto.Nombre as Tipificacion,
                            (select CASE WHEN IpServidorDiscado <> '' AND IpServidorDiscadoAux <> '' THEN REPLACE(gestion_ult_trimestre.url_grabacion,IpServidorDiscado,IpServidorDiscadoAux) ELSE gestion_ult_trimestre.url_grabacion END from (select IpServidorDiscado,IpServidorDiscadoAux from fireConfig) tb1) as Listen
                        from
                        grabacion_2
                            inner join Cedente on Cedente.Nombre_Cedente = grabacion_2.Cartera
                            inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                            inner join gestion_ult_trimestre on gestion_ult_trimestre.id_gestion = grabacion_2.id_gestion
                            inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion
                        where
                            mandante_cedente.Id_Mandante = '".$this->Id_Mandante."'
                            ".$WhereTipificacion."
                            ".$WhereTelefono."
                            ".$WhereRut."
                            ".$WhereEjecutivo."
                            ".$WhereDate."
                        order by
                            Fecha";
		    $Records = $this->db->select($SqlRecord);
            /*foreach($Records as $Record){
                $this->Id_Grabacion = $Record['id'];
                $RecordArrayTmp = array();
                $RecordArrayTmp["Filename"] = utf8_encode($Record["Nombre_Grabacion"]);
                $RecordArrayTmp["Date"] = utf8_encode($Record["Fecha"]);
                $RecordArrayTmp["Cartera"] = utf8_encode($Record["Cartera"]);
                $RecordArrayTmp["User"] = utf8_encode($Record["Usuario"]);
                $RecordArrayTmp["Phone"] = $Record["Telefono"];
                $RecordArrayTmp["Rut"] = $Record["rut_cliente"];
                $RecordArrayTmp["Listen"] = $this->getFinalUrlGrabacion($Record["url_grabacion"]);
                $RecordArrayTmp["Tipificacion"] = utf8_encode($Record["Contacto"]);
                $RecordsArray[$Cont] = $RecordArrayTmp;
                $Cont++;
            }*/
            return $Records;
        }
        function getFinalUrlGrabacion($UrlGrabacion){
            //$db = new Db();
            $sqlFocoConfig = "select IpServidorDiscado, IpServidorDiscadoAux from fireConfig";
            $FocoConfig = $this->db->select($sqlFocoConfig);
            $IpServidorDiscado = $FocoConfig[0]["IpServidorDiscado"];
            $IpServidorDiscadoAux = $FocoConfig[0]["IpServidorDiscadoAux"];
            if(($IpServidorDiscadoAux != "") && ($IpServidorDiscado != "")){
                $UrlGrabacion = str_replace($IpServidorDiscado,$IpServidorDiscadoAux,$UrlGrabacion);
            }
            return $UrlGrabacion;
        }
        function CanEvaluate(){
            $ToReturn = false;
            //$db = new Db();
            $SqlMandante = "select * from mandante where id = '".$this->Id_Mandante."'";
            $Mandantes = $this->db->select($SqlMandante);
            if($Mandantes){
                foreach($Mandantes as $Mandante){
                    if($Mandante["have360Evaluation"] == "1"){
                        $ToReturn = true;
                    }
                }
            }
            return $ToReturn;
        }
        function getObjeciones($idGrabacion){
            $ToReturn = array();
            //$db = new Db();
            $sqlObjeciones = "select
                    objeciones_calidad.*,
                    Personal.Nombre as Usuario
                from
                    objeciones_calidad
                        inner join Personal on Personal.id_usuario = objeciones_calidad.id_usuario
                where
                    id_mandante='".$this->Id_Mandante."' and
                    id_grabacion='".$idGrabacion."'";
            $Objeciones = $this->db->select($sqlObjeciones);
            foreach($Objeciones as $Objecion){
                $ArrayTmp = array();
                $ArrayTmp["Objecion"] = $Objecion["Objecion"];
                $ArrayTmp["notaObjetada"] = $Objecion["notaObjetada"];
                $ArrayTmp["fechaObjecion"] = $Objecion["fechaObjecion"];
                $ArrayTmp["nombreUsuario"] = $Objecion["Usuario"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function saveObjecion($idGrabacion,$idPersonal,$Objecion,$Tipo){
            $ToReturn = array();
            $ToReturn["result"] = true;
            //$db = new Db();

            $Fecha = date("Ymd h:i:s");
            
            $sqlInsert = "insert into objeciones_calidad (id_grabacion,Id_Personal,id_usuario,id_mandante,id_cedente,Objecion,tipo_comentario,notaObjetada,fechaObjecion) values('".$idGrabacion."','".$idPersonal."','".$_SESSION["id_usuario"]."','".$_SESSION["mandante"]."','".$_SESSION["cedente"]."','".$Objecion."','".$Tipo."','".$this->getLastNoteFromCalidadSystem($idGrabacion)."',NOW())";
            $Insert = $this->db->query($sqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getLastNoteFromCalidadSystem($idGrabacion){
            //$db = new Db();
            $sqlNota = "select SUM(Nota) as Nota from respuesta_opciones_afirmaciones_calidad inner join evaluaciones on evaluaciones.id = respuesta_opciones_afirmaciones_calidad.Id_Evaluacion where evaluaciones.id in (select id from evaluaciones where Id_Grabacion='".$idGrabacion."' and byCalidadSystem='1' and lastEvaluation = '1' order by Fecha_Evaluacion DESC)";
            $Nota = $this->db->select($sqlNota);
            if(count($Nota) > 0){
                $Nota = $Nota[0]["Nota"];
            }else{
                $Nota = 0;
            }
            return $Nota;
        }
        function buscarGrabacionEvaluacion($idEvaluacion){
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["Records"] = array();
            $ToReturn["Personal"] = array();
            $SqlRecord = "select

                            grabacion_2.id,
                            grabacion_2.Nombre_Grabacion as Filename,
                            gestion_ult_trimestre.fechahora as Date,
                            grabacion_2.Cartera as Cartera,
                            grabacion_2.Usuario as User,
                            grabacion_2.Telefono as Phone,
                            grabacion_2.Estado,
                            gestion_ult_trimestre.url_grabacion,
                            Tipo_Contacto.Nombre as Tipificacion,
                            CASE WHEN ISNULL((select id from evaluaciones where Id_Usuario = '".$this->Id_Usuario."' and Id_Grabacion = grabacion_2.id LIMIT 1)) THEN '' ELSE 'Evaluada' END as Status,
                            (select CASE WHEN IpServidorDiscado <> '' AND IpServidorDiscadoAux <> '' THEN REPLACE(gestion_ult_trimestre.url_grabacion,IpServidorDiscado,IpServidorDiscadoAux) ELSE gestion_ult_trimestre.url_grabacion END from (select IpServidorDiscado,IpServidorDiscadoAux from fireConfig) tb1) as Listen,
                            grabacion_2.id as Evaluar,
                            grabacion_2.id as Imprimir

                        from
                            grabacion_2
                                inner join evaluaciones on evaluaciones.Id_Grabacion = grabacion_2.id
                                inner join Cedente on Cedente.Nombre_Cedente = grabacion_2.Cartera
                                inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                                inner join gestion_ult_trimestre on gestion_ult_trimestre.id_gestion = grabacion_2.id_gestion
                                inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion
                        where
                            evaluaciones.id='".$idEvaluacion."'
                        group by
                            grabacion_2.id,
                            grabacion_2.Nombre_Grabacion,
                            grabacion_2.Fecha,
                            grabacion_2.Cartera,
                            grabacion_2.Usuario,
                            grabacion_2.Telefono,
                            grabacion_2.Estado,
                            gestion_ult_trimestre.url_grabacion,
                            Tipo_Contacto.Nombre
                        order by
                            Fecha";
            $Records = $this->db->select($SqlRecord);
            $ToReturn["Records"] = $Records;
            $SqlPersonal = "SELECT Personal.* from evaluaciones inner join Personal on Personal.Id_Personal = evaluaciones.Id_Personal where evaluaciones.id = '".$idEvaluacion."'";
            $Personal = $this->db->select($SqlPersonal);
            if(count($Personal) > 0){
                $ToReturn["Personal"]["nombreUsuario"] = $Personal[0]["Nombre_Usuario"];
                $ToReturn["Personal"]["nombrePersonal"] = utf8_encode($Personal[0]["Nombre"]);
            }
            /*foreach($Records as $Record){
                $this->Id_Grabacion = $Record['id'];
                $RecordArrayTmp = array();
                $RecordArrayTmp["Filename"] = utf8_encode($Record["Nombre_Grabacion"]);
                $RecordArrayTmp["Date"] = utf8_encode($Record["Fecha"]);
                $RecordArrayTmp["Cartera"] = utf8_encode($Record["Cartera"]);
                $RecordArrayTmp["User"] = utf8_encode($Record["Usuario"]);
                $RecordArrayTmp["Phone"] = $Record["Telefono"];
                //$RecordArrayTmp["Listen"] = $this->dir.$this->getRutaGrabaciones($Record["Nombre_Grabacion"])."/".$Record["Nombre_Grabacion"];
                $RecordArrayTmp["Listen"] = $this->getFinalUrlGrabacion($Record["url_grabacion"]);
                $RecordArrayTmp["Status"] = $this->hasEvaluation() ? "Evaluada" : "";//$Record["Estado"] == "1" ? "Evaluada" : "";
                $RecordArrayTmp["Evaluar"] = $Record["id"];
                $RecordArrayTmp["Imprimir"] = $Record["id"];
                $RecordArrayTmp["Tipificacion"] = utf8_encode($Record["Contacto"]);
                array_push($ToReturn,$RecordArrayTmp);
            }*/
            return $ToReturn;
        }
        function getCedenteFromGrabacion($idGrabacion){
            $ToReturn = "";
            //$db = new Db();
            $sqlCedente = "SELECT
                                gestion_ult_trimestre.cedente
                            FROM
                                gestion_ult_trimestre
                                    INNER join grabacion_2 on grabacion_2.id_gestion = gestion_ult_trimestre.id_gestion
                            WHERE
                                grabacion_2.id = '".$idGrabacion."'";
            $Cedente = $this->db->select($sqlCedente);
            if(count($Cedente) > 0){
                $ToReturn = $Cedente[0]["cedente"];
            }
            return $ToReturn;
        }
        function getGrabacionFromEvaluacion($idEvaluacion){
            $ToReturn = "";
            //$db = new Db();
            $sqlCedente = "SELECT
                                Id_Grabacion
                            FROM
                                evaluaciones
                            WHERE
                                id = '".$idEvaluacion."'";
            $Cedente = $this->db->select($sqlCedente);
            if(count($Cedente) > 0){
                $ToReturn = $Cedente[0]["Id_Grabacion"];
            }
            return $ToReturn;
        }
        function getPautas(){
            //$db = new Db();
            $PautasArray = array();
            $Cont = 0;
            $SqlPauta = "select
                            *
                        from
                            contenedor_competencias_calidad contenedor_competencias
                        order by
                            contenedor_competencias.nombreContenedor";
		    $Pautas = $this->db->select($SqlPauta);
            foreach($Pautas as $Pauta){
                $PautaArray = array();
                $TipoContacto = $this->getTipoContacto($Pauta["Id_TipoContacto"]) == "" ? "Sistema" : $this->getTipoContacto($Pauta["Id_TipoContacto"]); 
                $PautaArray['Nombre'] = utf8_encode($Pauta["nombreContenedor"]);
                $PautaArray['TipoContacto'] = utf8_encode($TipoContacto);
                $PautaArray['seleccion'] = $Pauta["id"];
                $PautaArray['ID'] = $Pauta["id"];
                $PautasArray[$Cont] = $PautaArray;
                $Cont++;
            }
            return $PautasArray;
        }
        function getTipoContactos(){
            //$db = new Db();
            $sqlTipoContactos = "select
                                *
                            from
                                Tipo_Contacto
                            where
                                mundo = '1'";
            $TipoContactos = $this->db->select($sqlTipoContactos);
            return $TipoContactos;
        }
        function SavePauta($tipoPauta,$nombrePauta){
            $ToReturn = array();
            $ToReturn["result"] = false;
            //$db = new Db();
            $sqlInsert = "insert into contenedor_competencias_calidad (nombreContenedor,Id_TipoContacto) values('".$nombrePauta."','".$tipoPauta."')";
            $Insert = $this->db->query($sqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function GetPauta($idPauta){
            $ToReturn = array();
            //$db = new Db();
            $sqlPautas = "select
                            *
                        from
                            contenedor_competencias_calidad
                        where
                            id='".$idPauta."'";
            $Pautas = $this->db->select($sqlPautas);
            foreach($Pautas as $Pauta){
                $ToReturn["nombrePauta"] = $Pauta["nombreContenedor"];
                $ToReturn["tipoContacto"] = $Pauta["Id_TipoContacto"];
            }
            return $ToReturn;
        }
        function UpdatePauta($idPauta,$idContacto,$nombrePauta){
            $ToReturn = array();
            $ToReturn["result"] = false;
            //$db = new Db();
            $sqlUpdate = "update contenedor_competencias_calidad set Id_TipoContacto='".$idContacto."', nombreContenedor='".$nombrePauta."' where id='".$idPauta."'";
            $Update = $this->db->query($sqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getAfirmacionesByCompetencia($Competencia){
            //$db = new Db();
            $SqlRespuestas = "select
                                    afirmaciones.id as idAfirmacion,
                                    '0' as Nota,
                                    '0' as Valor
                                from
                                    competencias_calidad competencias
                                        inner join dimensiones_competencias_calidad dimensiones on dimensiones.id_competencia = competencias.id
                                        inner join afirmaciones_dimensiones_competencias_calidad afirmaciones on afirmaciones.id_dimension = dimensiones.id
                                where
                                    competencias.id='".$Competencia."'
                                order by
                                    dimensiones.id";
            $Respuestas = $this->db->select($SqlRespuestas);
            return $Respuestas;
        }
        function getClasificaciones_Notas(){
            $ToReturn = array();
            //$db = new Db();
            $sqlClasificaciones = "select * from corte_nivel_ejecutivo_calidad";
            $Clasificaciones = $this->db->select($sqlClasificaciones);
            foreach($Clasificaciones as $Clasificacion){
                $ArrayTmp = array();
                $ArrayTmp["Nombre"] = utf8_encode($Clasificacion["nombre"]);
                $ArrayTmp["NotaDesde"] = $Clasificacion["notaMin"];
                $ArrayTmp["NotaHasta"] = $Clasificacion["notaMax"];
                $ArrayTmp["Descripcion"] = utf8_encode($Clasificacion["descripcion"]);
                $ArrayTmp["Accion"] = $Clasificacion["id"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function SaveClasificacion($nombreClasificacion,$notaDesde,$notaHasta,$descripcionClasificacion){
            $ToReturn = array();
            $ToReturn["result"] = false;
            //$db = new Db();
            $sqlInsert = "insert into corte_nivel_ejecutivo_calidad (nombre,notaMin,notaMax,descripcion) values('".$nombreClasificacion."','".$notaDesde."','".$notaHasta."','".$descripcionClasificacion."')";
            $Insert = $this->db->query($sqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function GetClasificacion($idClasificacion){
            $ToReturn = array();
            //$db = new Db();
            $sqlClasificacion = "select * from corte_nivel_ejecutivo_calidad where id='".$idClasificacion."'";
            $Clasificacion = $this->db->select($sqlClasificacion);
            $ToReturn["nombreClasificacion"] = utf8_encode($Clasificacion[0]["nombre"]);
            $ToReturn["notaDesde"] = $Clasificacion[0]["notaMin"];
            $ToReturn["notaHasta"] = $Clasificacion[0]["notaMax"];
            $ToReturn["Descripcion"] = utf8_encode($Clasificacion[0]["descripcion"]);
            return $ToReturn;
        }
        function UpdateClasificacion($idClasificacion,$nombreClasificacion,$notaDesde,$notaHasta,$descripcionClasificacion){
            $ToReturn = array();
            $ToReturn["result"] = false;
            //$db = new Db();
            $sqlUpdate = "update corte_nivel_ejecutivo_calidad set nombre='".$nombreClasificacion."', notaMin='".$notaDesde."', notaMax='".$notaHasta."', descripcion='".$descripcionClasificacion."' where id='".$idClasificacion."'";
            $Update = $this->db->query($sqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function DeleteClasificacion($idClasificacion){
            $ToReturn = array();
            $ToReturn["result"] = false;
            //$db = new Db();
            $sqlDelete = "DELETE FROM corte_nivel_ejecutivo_calidad where id='".$idClasificacion."'";
            $Delete = $this->db->query($sqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getIdContactoFromGrabacion($idGrabacion){
            $ToReturn = array();
            $ToReturn["result"] = false;
            //$db = new Db();
            $sqlContacto = "SELECT
                                gestion_ult_trimestre.Id_TipoGestion as idContacto
                            FROM
                                grabacion_2
                                    inner join gestion_ult_trimestre on gestion_ult_trimestre.id_gestion = grabacion_2.id_gestion
                            WHERE
                                grabacion_2.id = '".$idGrabacion."'";
            $Contacto = $this->db->select($sqlContacto);
            if(count($Contacto) > 0){
                $ToReturn["result"] = true;
                $ToReturn["value"] = $Contacto[0]["idContacto"];
            }
            return $ToReturn;
        }
        function getPautaFromTipoContacto($idCedente,$idContacto){
            $ToReturn = array();
            $ToReturn["result"] = false;
            //$db = new Db();

            $WhereContacto = "";
            $HaveContacto = $this->havePautaIdTipoContacto($idCedente,$idContacto);
            if($HaveContacto){
                $WhereContacto = " AND contenedor_competencias_calidad.Id_TipoContacto='".$idContacto."' ";
            }else{
                $WhereContacto = " AND contenedor_competencias_calidad.Id_TipoContacto='0' ";
            }

            $sqlContacto = "select
                                contenedor_competencias_calidad.id as idPauta
                            from
                                contenedor_competencias_calidad
                                    inner join contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.id_contenedor = contenedor_competencias_calidad.id
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = contenedor_competencias_calidad_Cedente.Id_Cedente
                            WHERE
                                mandante_cedente.Id_Cedente='".$idCedente."'
                                ".$WhereContacto."
                                ";
            $Contacto = $this->db->select($sqlContacto);
            if(count($Contacto) > 0){
                $ToReturn["result"] = true;
                $ToReturn["value"] = $Contacto[0]["idPauta"];
            }
            return $ToReturn;
        }
        function getPautasMandante($idMandante){
            //$db = new Db();
            $sqlPautas = "select
                                contenedor_competencias_calidad.*
                            from
                                contenedor_competencias_calidad
                                    inner join contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.id_contenedor = contenedor_competencias_calidad.id
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = contenedor_competencias_calidad_Cedente.Id_Cedente
                                    inner join competencias_calidad on competencias_calidad.id_contenedor = contenedor_competencias_calidad.id
                                    inner join dimensiones_competencias_calidad on dimensiones_competencias_calidad.id_competencia = competencias_calidad.id
                                    inner join afirmaciones_dimensiones_competencias_calidad on afirmaciones_dimensiones_competencias_calidad.id_dimension = dimensiones_competencias_calidad.id
                                    inner join respuesta_opciones_afirmaciones_calidad on respuesta_opciones_afirmaciones_calidad.id_afirmacion = afirmaciones_dimensiones_competencias_calidad.id
                            WHERE
                                mandante_cedente.Id_Mandante='".$idMandante."'
                            GROUP BY
                                contenedor_competencias_calidad.id,
                                contenedor_competencias_calidad.nombreContenedor,
                                contenedor_competencias_calidad.Id_TipoContacto";
            $Pautas = $this->db->select($sqlPautas);
            return $Pautas;
        }
        function getPersonalEjecutivosPauta($idPauta){
            //$db = new Db();
            $sqlEjecutivos = "select
                                Personal.Id_Personal,
                                Personal.Nombre
                            from
                                evaluaciones
                                    inner join Personal on Personal.Id_Personal = evaluaciones.Id_Personal
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    inner join contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente
                                    inner join contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor
                            where
                                mandante_cedente.Id_Mandante='".$this->Id_Mandante."' and
                                contenedor_competencias_calidad.id='".$idPauta."'
                            GROUP BY
                                Personal.Id_Personal,
                                Personal.Nombre";
            $Ejecutivos = $this->db->select($sqlEjecutivos);
            return $Ejecutivos;
        }
        function getTipoContacto($idContacto){
            $ToReturn = "";
            //$db = new Db();
            $sqlTipoContacto = "select Nombre as TipoContacto from Tipo_Contacto where Id_TipoContacto='".$idContacto."'";
            $TipoContacto = $this->db->select($sqlTipoContacto);
            if(count($TipoContacto) > 0){
                $ToReturn = $TipoContacto[0]["TipoContacto"];
            }
            return $ToReturn;
        }
        function getCedentesMandante(){
            $ToReturn = array();
            //$db = new Db();
            /* $sqlCedentes = "select
                                Ced.Id_Cedente,
                                Ced.Nombre_Cedente,
                                (select GROUP_CONCAT(contenedor_competencias_calidad.nombreContenedor) FROM contenedor_competencias_calidad inner join contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.id_contenedor = contenedor_competencias_calidad.id inner join mandante_cedente on mandante_cedente.Id_Cedente = contenedor_competencias_calidad_Cedente.Id_Cedente where mandante_cedente.Id_Mandante='".$_SESSION["mandante"]."' and mandante_cedente.Id_Cedente = Ced.Id_Cedente group by contenedor_competencias_calidad.nombreContenedor order by contenedor_competencias_calidad.nombreContenedor) as Pautas
                            from
                                Cedente Ced
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = Ced.Id_Cedente
                            where
                                mandante_cedente.Id_Mandante='".$_SESSION["mandante"]."'"; */
            $sqlCedentes = "select
                                Ced.Id_Cedente,
                                Ced.Nombre_Cedente,
                                (select GROUP_CONCAT(contenedor_competencias_calidad.nombreContenedor) FROM contenedor_competencias_calidad inner join contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.id_contenedor = contenedor_competencias_calidad.id inner join mandante_cedente on mandante_cedente.Id_Cedente = contenedor_competencias_calidad_Cedente.Id_Cedente where mandante_cedente.Id_Mandante='".$_SESSION["mandante"]."' and mandante_cedente.Id_Cedente = Ced.Id_Cedente order by contenedor_competencias_calidad.nombreContenedor) as Pautas
                            from
                                Cedente Ced
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = Ced.Id_Cedente
                            where
                                mandante_cedente.Id_Mandante='".$_SESSION["mandante"]."'";
            $Cedentes = $this->db->select($sqlCedentes);
            foreach($Cedentes as $Cedente){
                $ArratTmp = array();
                $ArrayTmp["Cedente"] = utf8_encode($Cedente["Nombre_Cedente"]);
                $ArrayTmp["Pautas"] = utf8_encode($Cedente["Pautas"]);
                $ArrayTmp["Accion"] = $Cedente["Id_Cedente"];
                array_push($ToReturn, $ArrayTmp);
            }
            return $ToReturn;
        }
        function getPautasCedentes($idCedente){
            $ToReturn = array();
            //$db = new Db();
            $sqlCedentes = "select
                                contenedor_competencias_calidad.nombreContenedor,
                                contenedor_competencias_calidad_Cedente.id as idContenedor_Cedente
                            from
                                contenedor_competencias_calidad
                                    inner join contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.id_contenedor = contenedor_competencias_calidad.id
                            where
                                contenedor_competencias_calidad_Cedente.Id_Cedente='".$idCedente."'";
            $Cedentes = $this->db->select($sqlCedentes);
            foreach($Cedentes as $Cedente){
                $ArratTmp = array();
                $ArrayTmp["Pauta"] = utf8_encode($Cedente["nombreContenedor"]);
                $ArrayTmp["Accion"] = $Cedente["idContenedor_Cedente"];
                array_push($ToReturn, $ArrayTmp);
            }
            return $ToReturn;
        }
        function getPautasWhereNotInCedente($idCedente){
            //$db = new Db();
            $sqlPautas = "select
                            contenedor_competencias_calidad.id,
                            contenedor_competencias_calidad.nombreContenedor
                        from
                            contenedor_competencias_calidad
                        where
                            id not in (select id_contenedor from contenedor_competencias_calidad_Cedente where Id_Cedente='".$idCedente."')";
            $Pautas = $this->db->select($sqlPautas);
            return $Pautas;
        }
        function asignarPautaToCedente($idCedente,$idPauta){
            $ToReturn = array();
            $ToReturn["result"] = false;
            //$db = new Db();
            $sqlInsert = "insert into contenedor_competencias_calidad_Cedente (Id_Cedente,id_contenedor) values ('".$idCedente."','".$idPauta."')";
            $Insert = $this->db->query($sqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function desasignarPautaFromCedente($idContenedorCedente){
            $ToReturn = array();
            $ToReturn["result"] = false;
            //$db = new Db();
            $sqlDelete = "delete from contenedor_competencias_calidad_Cedente where id = '".$idContenedorCedente."'";
            $Delete = $this->db->query($sqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function GetErroresCriticos(){
            //$db = new Db();
            $sqlErrores = "select * from errores_criticos_calidad";
            $Errores = $this->db->select($sqlErrores);
            return $Errores;
        }
        function havePautaIdTipoContacto($Cedente,$idContacto){
            //$db = new Db();
            $ToReturn = false;
            $sqlPautas = "select
                            *
                        from
                            contenedor_competencias_calidad_Cedente
                                inner join contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor
                        where
                            contenedor_competencias_calidad_Cedente.Id_Cedente='".$Cedente."' and
                            contenedor_competencias_calidad.Id_TipoContacto='".$idContacto."'";
            $Pautas = $this->db->select($sqlPautas);
            if(count($Pautas) > 0){
                $ToReturn = true;
            }
            return $ToReturn;
        }
        function GrabacionHaveEvaluation($idGrabacion){
            $ToReturn = false;
            //$db = new Db();
            $sqlEvaluaciones = "select * from evaluaciones where Id_Grabacion='".$idGrabacion."'";
            $Evaluaciones = $this->db->select($sqlEvaluaciones);
            if(count($Evaluaciones) > 0){
                $ToReturn = true;
            }
            return $ToReturn;
        }
        function GetIdPersonalFromEvaluacionGrabacion($idGrabacion){
            //$db = new Db();
            $sqlIdPersonal = "select Id_Personal from evaluaciones where Id_Grabacion='".$idGrabacion."' LIMIT 1";
            $IdPersonal = $this->db->select($sqlIdPersonal);
            return $IdPersonal[0]["Id_Personal"];
        }
        function getErroresCriticosFromCierre($idCierre){
            $ToReturn = array();
            //$db = new Db();
            $sqlErroresCriticos = "select
                                        errores_criticos_calidad.Descripcion as descErrorCritico,
                                        COUNT(*) as Cantidad
                                    from
                                        evaluaciones
                                            left join errores_criticos_calidad on errores_criticos_calidad.id = evaluaciones.id_errorCritico
                                    where
                                        evaluaciones.id in (select Id_Evaluaciones from cierre_evaluaciones where id='".$idCierre."') AND
                                        errores_criticos_calidad.id is not null
                                    group by
                                        errores_criticos_calidad.Descripcion";
            $ErroresCriticos = $this->db->select($sqlErroresCriticos);
            if(count($ErroresCriticos) > 0){
                $ToReturn["result"] = true;
                $ToReturn["Rows"] = $ErroresCriticos;
            }else{
                $ToReturn["result"] = false;
            }
            return $ToReturn;
        }
        function marcarVistaObjecion($idObjecion){
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["result"] = false;
            /* $SqlUpdate = "UPDATE objeciones_calidad set visto = '1' where id='".$idObjecion."'";
            $Update = $this->db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            } */
            $SqlInsert = "INSERT into objeciones_calidad_usuarios (id_objecion,id_usuario,tipo) values ('".$idObjecion."','".$_SESSION["id_usuario"]."','2')";
            $Insert = $this->db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function marcarNoVisibleObjecion($idObjecion){
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["result"] = false;
            /* $SqlUpdate = "UPDATE objeciones_calidad set notificacionVisible = '0',visto = '1' where id='".$idObjecion."'";
            $Update = $this->db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            } */
            $SqlInsert = "INSERT into objeciones_calidad_usuarios (id_objecion,id_usuario,tipo) values ('".$idObjecion."','".$_SESSION["id_usuario"]."','1')";
            $Insert = $this->db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }

        function DownloadInformesGenerales($Periodo){
            $ToReturn = "";
            //$db = new Db();
            $Desde = date('Ym01',strtotime($Periodo));
            $Hasta = date('Ymt',strtotime($Desde));

            $fileName = "Informes Generales ".$Desde." - ".$Hasta;

            $objPHPExcel = new PHPExcel();
            ob_start();
            $objPHPExcel->
                getProperties()
                    ->setCreator("CRM Sinaptica")
                    ->setLastModifiedBy("CRM Sinaptica");
            
            $objPHPExcel->removeSheetByIndex(
                $objPHPExcel->getIndex(
                    $objPHPExcel->getSheetByName('Worksheet')
                )
            );

            $styleAlignHorizontal = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );

            $NextSheet = 0;

            $objPHPExcel->createSheet($NextSheet);
            $objPHPExcel->setActiveSheetIndex($NextSheet);
            $objPHPExcel->getActiveSheet()->setTitle("EVALUACIONES POR CARTERA");
                
            $objPHPExcel->setActiveSheetIndex($NextSheet);
            
            $Row = 1;

            $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow(0,$Row,"Mandante")
                        ->setCellValueByColumnAndRow(1,$Row,"Cartera")
                        ->setCellValueByColumnAndRow(2,$Row,"Codigo RECSA")
                        ->setCellValueByColumnAndRow(3,$Row,"Evaluaciones");
            $Row++;
            
            $SqlEvaluaciones = "SELECT
                                    mandante.nombre as Mandante,
                                    Cedente.Nombre_Cedente as Cartera,
                                    Cedente.Alias as CodigoCartera,
                                    count(*) as Grabaciones
                                FROM
                                    evaluaciones E
                                        INNER JOIN Cedente on Cedente.Id_Cedente = E.Id_Cedente
                                        INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                                        INNER JOIN mandante on mandante.id = mandante_cedente.Id_Mandante
                                WHERE
                                    CAST(E.Fecha_Evaluacion AS DATE) between '".$Desde."' and '".$Hasta."'
                                GROUP BY
                                    mandante.nombre,
                                    Cedente.Nombre_Cedente
                                ORDER BY
                                    Cedente.Alias";
            $Evaluaciones = $this->db->query($SqlEvaluaciones);
            foreach($Evaluaciones as $Evaluacion){
                $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow(0,$Row,$Evaluacion["Mandante"])
                        ->setCellValueByColumnAndRow(1,$Row,$Evaluacion["Cartera"])
                        ->setCellValueByColumnAndRow(2,$Row,$Evaluacion["CodigoCartera"])
                        ->setCellValueByColumnAndRow(3,$Row,$Evaluacion["Grabaciones"]);
                $Row++;
            }

            $objPHPExcel->setActiveSheetIndex(0);


            header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$fileName.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
            $objWriter->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();
			$response =  array(
                'filename' => $fileName,
				'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
			);

            return $response;
        }
        function getEvaluacionesSemanalesPorEvaluador(){
            //$db = new Db();
            $SqlEvaluadores = "SELECT
                                    UPPER(P.Nombre) as nombreEvaluador,
                                    CASE WHEN CES.cantidadEvaluaciones IS NULL THEN '0' ELSE CES.cantidadEvaluaciones END as Cantidad,
                                    P.Id_Personal as Accion
                                FROM
                                    Personal P
                                        INNER JOIN Usuarios U ON U.Id_Personal = P.Id_Personal
                                        LEFT JOIN cantidadEvauacionesSemanales_calidad CES ON CES.Id_Personal = P.Id_Personal
                                WHERE
                                    U.nivel = '6'
                                ORDER BY
                                    P.Nombre";
            $Evaluadores = $this->db->select($SqlEvaluadores);
            return $Evaluadores;
        }
        function updateEvaluacionesSemanalesPorEvaluador($idPersonal,$cantidadEvaluaciones){
            $ToReturn = array();
            $ToReturn["result"] = false;
            //$db = new Db();
            $SqlInsert = "INSERT INTO cantidadEvauacionesSemanales_calidad (Id_Personal,cantidadEvaluaciones) values ('".$idPersonal."','".$cantidadEvaluaciones."') ON DUPLICATE KEY UPDATE cantidadEvaluaciones=VALUES(cantidadEvaluaciones)";
            $Insert = $this->db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getTipoContactoEvaluacionesAutomaticas(){
            //$db = new Db();
            $SqlTiposContacto = "SELECT
                                    TC.Nombre AS TipoContacto,
                                    CASE WHEN TCEA.duracionMin IS NULL OR TCEA.duracionMin = 0 THEN '-' ELSE CONCAT(TCEA.duracionMin,' Seg.') END AS DuracionMin,
	                                CASE WHEN TCEA.duracionMax IS NULL OR TCEA.duracionMax = 0 THEN '-' ELSE CONCAT(TCEA.duracionMax,' Seg.') END AS DuracionMax,
                                    TCEA.id AS Accion,
                                    TCEA.Id_TipoContacto as idTipoContacto
                                FROM
                                    tipoContacto_evaluaciones_automaticas_calidad TCEA
                                        INNER JOIN Tipo_Contacto TC ON TCEA.Id_TipoContacto = TC.Id_TipoContacto
                                ORDER BY
                                    TC.Nombre";
            $TiposContacto = $this->db->select($SqlTiposContacto);
            return $TiposContacto;
        }
        function deleteTipoContactoEvaluacionesAutomaticas($idTipoContacto){
            $ToReturn = array();
            $ToReturn["result"] = false;
            //$db = new Db();
            $SqlDelete = "DELETE FROM tipoContacto_evaluaciones_automaticas_calidad WHERE id='".$idTipoContacto."'";
            $Delete = $this->db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getTipoContactoEvaluacionesAutomaticasNoConfigurados(){
            //$db = new Db();

            $SqlTiposContactos = "SELECT
                                        TC.Id_TipoContacto as idTipoContacto,
                                        TC.Nombre as TipoContacto
                                    FROM
                                        Tipo_Contacto TC
                                            LEFT JOIN tipoContacto_evaluaciones_automaticas_calidad TCEA on TCEA.Id_TipoContacto = TC.Id_TipoContacto
                                    WHERE
                                        TCEA.id IS NULL
                                    ORDER BY
                                        TC.Nombre";
            $TiposContacto = $this->db->select($SqlTiposContactos);
            return $TiposContacto;
        }
        function configurarTipoContactoEvaluacionesAutomaticas($idTipoContacto,$duracionMin,$duracionMax){
            $ToReturn = array();
            $ToReturn["result"] = false;
            //$db = new Db();
            $SqlInsert = "INSERT INTO tipoContacto_evaluaciones_automaticas_calidad (Id_TipoContacto,duracionMin,duracionMax) values ('".$idTipoContacto."','".$duracionMin."','".$duracionMax."')";
            $Insert = $this->db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getSemanasMesEvaluacionesAutomaticas($Fecha){

            //$db = new Db();

            if($Fecha == ""){
                $Fecha = date("Ymd");
            }else{
                $Fecha = date("Ymd",strtotime($Fecha));
            }

            $AnoActual = date("Y",strtotime($Fecha));
            $MesActual = date("m",strtotime($Fecha));
            $DiaActual = date("d",strtotime($Fecha));
            $DiasDelMes = date("t",strtotime($Fecha));

            $Semanas = array();

            for($i=1;$i<=$DiasDelMes;$i++){
                $DiaFecha = $i < 10 ? "0".$i : $i;

                $Fecha = $AnoActual."-".$MesActual."-".$DiaFecha;
                $Semana = date("W",strtotime($Fecha));
                $SemanaInt = (Int) $Semana;
                if(!isset($Semanas[$SemanaInt])){
                    $Semanas[$SemanaInt] = array();
                    $Semanas[$SemanaInt]["Semana"] = $Semana;
                    $Semanas[$SemanaInt]["FechaInicio"] = $Fecha;
                    $Semanas[$SemanaInt]["FechaFin"] = "";
                    $Semanas[$SemanaInt]["EjecutivosSinEvaluacion"] = 0;
                    $Semanas[$SemanaInt]["Actual"] = false;
                    
                    $SemanaAnterior = $SemanaInt - 1;

                    if(isset($Semanas[$SemanaAnterior])){
                        $DiaAnterior = ((Int) $DiaFecha) - 1;
                        $DiaAnterior = $DiaAnterior < 10 ? "0".$DiaAnterior : $DiaAnterior;
                        $FechaAnterior = $AnoActual."-".$MesActual."-".$DiaAnterior;
                        $Semanas[$SemanaAnterior]["FechaFin"] = $FechaAnterior;
                    }
                }
                if(!$Semanas[$SemanaInt]["Actual"]){
                    $Semanas[$SemanaInt]["Actual"] = $DiaFecha == $DiaActual ? true : false;
                }
                if($i == $DiasDelMes){
                    $Semanas[$SemanaInt]["FechaFin"] = $Fecha;
                }
            }
            
            $ToReturn = array();
            foreach($Semanas as $Semana){
                $NumeroSemana = (Int) $Semana["Semana"];
                $FechaInicio = $Semana["FechaInicio"];
                $FechaFin = $Semana["FechaFin"];
                $Actual = $Semana["Actual"];
                
                $ArrayTmp = array();
                $ArrayTmp["Semana"] = $NumeroSemana;
                $ArrayTmp["FechaInicio"] = $FechaInicio;
                $ArrayTmp["FechaFin"] = $FechaFin;
                $ArrayTmp["EjecutivosSinEvaluacion"] = 0;//$this->getCantidadEjecutivosSinEvaluaciones($FechaInicio,$FechaFin);
                $ArrayTmp["Actual"] = $Actual;

                $ArrayDiasSemana = array();
                $fechaTmp = $FechaInicio;
                while(strtotime($fechaTmp) <= strtotime($FechaFin)){
                    array_push($ArrayDiasSemana,(Int) date("d",strtotime($fechaTmp)));
                    $fechaTmp = date("Ymd",strtotime($fechaTmp."+ 1 days"));
                }
                $ArrayTmp["DiasSemana"] = $ArrayDiasSemana;

                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getCantidadEjecutivosSinEvaluaciones($FechaInicio,$FechaFin){
            //$db = new Db();
            $SqlEjecutivos = "SELECT
                                        P.*
                                    FROM
                                        Personal P
                                            INNER JOIN grabacion_2 Gra ON Gra.Usuario = P.Nombre_Usuario
                                            INNER JOIN Cedente Ced ON Ced.Nombre_Cedente = Gra.Cartera
                                            INNER JOIN mandante_cedente MC ON MC.Id_Cedente = Ced.Id_Cedente
                                    WHERE
                                        Gra.Fecha BETWEEN '".$FechaInicio."' AND '".$FechaFin."' AND
                                        MC.Id_Mandante = '".$_SESSION["mandante"]."'
                                        AND P.Id_Personal NOT IN (SELECT evaluaciones.Id_Personal FROM evaluaciones INNER JOIN grabacion_2 on grabacion_2.id = evaluaciones.Id_Grabacion INNER JOIN Cedente ON Cedente.Nombre_Cedente = grabacion_2.Cartera INNER JOIN mandante_cedente ON mandante_cedente.Id_Cedente = Cedente.Id_Cedente WHERE grabacion_2.Fecha BETWEEN '".$FechaInicio."' AND '".$FechaFin."' AND mandante_cedente.Id_Mandante = '".$_SESSION["mandante"]."')
                                    GROUP BY
                                        Gra.Usuario
                                    ORDER BY
                                        P.Nombre";
            $Ejecutivos = $this->db->select($SqlEjecutivos);
            return count($Ejecutivos);
        }
        function getUsuariosSemanaEvaluacionesAutomaticas($FechaInicio,$FechaFin){
            //$db = new Db();

            /* $SqlUsuarios = "SELECT
                                P.Nombre_Usuario as idEjecutivo,
                                TRIM(UPPER(P.Nombre)) as nombreEjecutivo,
                                (SELECT count(*) FROM evaluaciones E INNER JOIN grabacion_2 ON grabacion_2.id = E.Id_Grabacion INNER JOIN Cedente ON Cedente.Nombre_Cedente = grabacion_2.Cartera INNER JOIN mandante_cedente ON mandante_cedente.Id_Cedente = Cedente.Id_Cedente WHERE grabacion_2.Fecha BETWEEN '".$FechaInicio."' AND '".$FechaFin."' AND mandante_cedente.Id_Mandante = '".$_SESSION["mandante"]."' AND E.Id_Personal = P.Id_Personal) as cantidadEvaluaciones
                            FROM
                                Personal P
                                    INNER JOIN grabacion_2 Gra ON Gra.Usuario = P.Nombre_Usuario
                                    INNER JOIN Cedente Ced ON Ced.Nombre_Cedente = Gra.Cartera
                                    INNER JOIN mandante_cedente MC ON MC.Id_Cedente = Ced.Id_Cedente
                            WHERE
                                Gra.Fecha BETWEEN '".$FechaInicio."' AND '".$FechaFin."' AND
                                MC.Id_Mandante = '".$_SESSION["mandante"]."'
                            GROUP BY
                                Gra.Usuario
                            ORDER BY
                                P.Nombre"; */

            $SqlUsuarios = "SELECT
                                P.Nombre_Usuario as nombreUsuario,
                                UPPER(P.Nombre) as nombreEjecutivo,
                                Ges.Id_TipoGestion as idTipoGestion,
                                count(E.id) as cantidadEvaluaciones
                            FROM
                                Personal P
                                    INNER JOIN grabacion_2 Gra ON Gra.Usuario = P.Nombre_Usuario
                                    INNER JOIN gestion_ult_trimestre Ges ON Ges.id_gestion = Gra.id_gestion
                                    INNER JOIN Cedente Ced ON Ced.Nombre_Cedente = Gra.Cartera
                                    INNER JOIN mandante_cedente MC ON MC.Id_Cedente = Ced.Id_Cedente
                                    LEFT JOIN evaluaciones E ON E.Id_Grabacion = Gra.id
                                    INNER JOIN Tipo_Contacto TC ON TC.Id_TipoContacto = Ges.Id_TipoGestion
                            WHERE
                                Gra.Fecha BETWEEN '".$FechaInicio."' AND '".$FechaFin."' AND
                                MC.Id_Mandante = '".$_SESSION["mandante"]."' AND
                                P.Nombre_Usuario <> ''
                            GROUP BY
                                P.Nombre_Usuario,
                                Ges.Id_TipoGestion
                            ORDER BY
                                P.Nombre,
                                TC.Nombre";

            $Usuarios = $this->db->select($SqlUsuarios);

            $ConfTiposContactos = $this->getTipoContactoEvaluacionesAutomaticas();

            $ArrayUsuarios = array();
            $Otros = array();
            $Total = array();
            foreach($Usuarios as $Usuario){
                $nombreUsuario = $Usuario["nombreUsuario"];
                $nombreEjecutivo = $Usuario["nombreEjecutivo"];
                $idTipoGestion = $Usuario["idTipoGestion"];
                $cantidadEvaluaciones = $Usuario["cantidadEvaluaciones"];

                if(!isset($ArrayUsuarios[$nombreUsuario])){
                    $ArrayUsuarios[$nombreUsuario] = array();
                    $ArrayUsuarios[$nombreUsuario]["idEjecutivo"] = $nombreUsuario;
                    $ArrayUsuarios[$nombreUsuario]["nombreEjecutivo"] = $nombreEjecutivo;
                    $ArrayUsuarios[$nombreUsuario]["Accion"] = $nombreUsuario;
                    $ArrayUsuarios[$nombreUsuario]["Otros"] = 0;
                    $ArrayUsuarios[$nombreUsuario]["Total"] = 0;

                    $Otros[$nombreUsuario] = array();
                    $Total[$nombreUsuario] = 0;
                }
                foreach($ConfTiposContactos as $Contacto){
                    $idContacto = $Contacto["idTipoContacto"];
                    if($idTipoGestion == $idContacto){
                        $ArrayUsuarios[$nombreUsuario][$idContacto] = $cantidadEvaluaciones;
                        $ArrayUsuarios[$nombreUsuario]["Total"] += $cantidadEvaluaciones;
                        if(isset($Otros[$nombreUsuario][$idTipoGestion])){
                            unset($Otros[$nombreUsuario][$idTipoGestion]);
                        }
                    }else{
                        if((!isset($Otros[$nombreUsuario][$idTipoGestion])) && (!isset($ArrayUsuarios[$nombreUsuario][$idTipoGestion]))){
                            $Otros[$nombreUsuario][$idTipoGestion] = $cantidadEvaluaciones;
                        }
                    }
                    $Total[$nombreUsuario] += $cantidadEvaluaciones;
                }

            }
            foreach($Otros as $Key => $Values){
                foreach($Values as $Value){
                    $ArrayUsuarios[$Key]["Otros"] += $Value;
                }
                $ArrayUsuarios[$Key]["Total"] += $ArrayUsuarios[$Key]["Otros"];
            }
            $Usuarios = array();
            foreach($ArrayUsuarios as $Usuario){
                $ArrayTmp = array();
                foreach($Usuario as $Key => $Columna){
                    $ArrayTmp[$Key] = $Columna;
                }
                array_push($Usuarios,$ArrayTmp);
            }
            $ToReturn = array();
            $ToReturn["AccionIndex"] = ((count($ConfTiposContactos) + 5) - 1); // ((TipoContactosConfigurados + CantidadDeColumnas Estaticas) - 1)
            $ToReturn["Data"] = $Usuarios;
            $ToReturn["Header"] = array();

                array_push($ToReturn["Header"],"Codigo");
                array_push($ToReturn["Header"],"Nombre");
                
                foreach($ConfTiposContactos as $Contacto){
                    array_push($ToReturn["Header"],$Contacto["TipoContacto"]);
                }

                array_push($ToReturn["Header"],"Otros");
                array_push($ToReturn["Header"],"Total");
                array_push($ToReturn["Header"],"Accion");
            
            $ToReturn["Columns"] = array();
                
                $ArrayTmp = array();
                $ArrayTmp["data"] = "idEjecutivo";
                array_push($ToReturn["Columns"],$ArrayTmp);

                $ArrayTmp = array();
                $ArrayTmp["data"] = "nombreEjecutivo";
                array_push($ToReturn["Columns"],$ArrayTmp);
                
                foreach($ConfTiposContactos as $Contacto){
                    $ArrayTmp = array();
                    $ArrayTmp["data"] = $Contacto["idTipoContacto"];
                    array_push($ToReturn["Columns"],$ArrayTmp);
                }

                $ArrayTmp = array();
                $ArrayTmp["data"] = "Otros";
                array_push($ToReturn["Columns"],$ArrayTmp);

                $ArrayTmp = array();
                $ArrayTmp["data"] = "Total";
                array_push($ToReturn["Columns"],$ArrayTmp);

                $ArrayTmp = array();
                $ArrayTmp["data"] = "Accion";
                array_push($ToReturn["Columns"],$ArrayTmp);

            return $ToReturn;
        }
        function getRecordAutomaticas($NombreEjecutivo,$ConfTipoContacto,$Mes,$diasSemana,$idGrabaciones){
            //$db = new Db();
            $ToReturn = array();

            $ConfTipoContacto = $this->getConfContactoEvaluacionesAutomaticasByID($ConfTipoContacto);
            $duracionMin = $ConfTipoContacto["duracionMin"];
            $duracionMax = $ConfTipoContacto["duracionMax"];
            $idTipoContacto = $ConfTipoContacto["Id_TipoContacto"];
            $WhereDuracion = "";
            if(($duracionMax != "") || ($duracionMax == 0)){
                $WhereDuracion = " AND gestion_ult_trimestre.duracion >= '".$duracionMin."' ";
            }else{
                $WhereDuracion = " AND gestion_ult_trimestre.duracion BETWEEN '".$duracionMin."' AND '".$duracionMax."' ";
            }
            $WhereIdGrabaciones = "";
            if(count($idGrabaciones) > 0){
                $idGrabacionesImplode = implode(",",$idGrabaciones);
                $WhereIdGrabaciones = " AND grabacion_2.id not in (".$idGrabacionesImplode.")";
            }

            $SqlRecord = "select
                                grabacion_2.id,
                                grabacion_2.Nombre_Grabacion as Filename,
                                gestion_ult_trimestre.fechahora as Date,
                                grabacion_2.Cartera as Cartera,
                                grabacion_2.Usuario as User,
                                grabacion_2.Telefono as Phone,
                                grabacion_2.Estado,
                                gestion_ult_trimestre.url_grabacion,
                                Tipo_Contacto.Nombre as Tipificacion,
                                P.Nombre as nombreEjecutivo,
                                (SELECT Nombre FROM Personal WHERE Id_Personal = P.id_supervisor) as nombreSupervisor,
                                (select CASE WHEN IpServidorDiscado <> '' AND IpServidorDiscadoAux <> '' THEN REPLACE(gestion_ult_trimestre.url_grabacion,IpServidorDiscado,IpServidorDiscadoAux) ELSE gestion_ult_trimestre.url_grabacion END from (select IpServidorDiscado,IpServidorDiscadoAux from fireConfig) tb1) as Listen
                            from
                                grabacion_2
                                    inner join Cedente on Cedente.Nombre_Cedente = grabacion_2.Cartera
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente
                                    inner join gestion_ult_trimestre on gestion_ult_trimestre.id_gestion = grabacion_2.id_gestion
                                    inner join Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion
                                    inner join Personal P on P.Nombre_Usuario = grabacion_2.Usuario
                                    left join evaluaciones on evaluaciones.Id_Grabacion = grabacion_2.id
                            where
                                evaluaciones.id is null and
                                grabacion_2.usuario = '".$NombreEjecutivo."' and
                                mandante_cedente.Id_Mandante = '".$_SESSION["mandante"]."' and
                                DAY(grabacion_2.Fecha) in (".$diasSemana.") AND
                                MONTH(grabacion_2.Fecha) = '".$Mes."'
                                AND gestion_ult_trimestre.Id_TipoGestion='".$idTipoContacto."'
                                ".$WhereDuracion."
                                ".$WhereIdGrabaciones."
                            ORDER BY
                                RAND()
                            LIMIT 1";
            
            $Record = $this->db->select($SqlRecord);
            if(count($Record) > 0){
                $ToReturn = $Record;
            }
            return $ToReturn;
        }
        function getConfContactoEvaluacionesAutomaticasByID($ConfTipoContacto){
            //$db = new Db();
            $SqlTipoContacto = "SELECT * FROM tipoContacto_evaluaciones_automaticas_calidad WHERE id = '".$ConfTipoContacto."'";
            $TipoContacto = $this->db->select($SqlTipoContacto);
            return $TipoContacto[0];
        }
        function contadorEvaluaciones(){
            //$db = new Db();

            $FechaActual = date("Ymd");
            $MesActual = date("m");
            $AnoActual = date("Y");
            $UltimoDia = date("t");
            $SemanaAnoActual = date("W",strtotime($FechaActual));
            $PrimeraSemana = date("W",strtotime($AnoActual.$MesActual."01"));
            $UltimaSemana = date("W",strtotime($AnoActual.$MesActual.$UltimoDia));
            $SemanaMes = ($SemanaAnoActual - $PrimeraSemana) + 1;

            $SqlEvaluaciones = "select CES.cantidadEvaluaciones as Cantidad from Personal P INNER JOIN cantidadEvauacionesSemanales_calidad CES ON CES.Id_Personal = P.Id_Personal WHERE P.id_usuario='".$_SESSION["id_usuario"]."'";
            $Evaluaciones = $this->db->select($SqlEvaluaciones);
            $Cant = count($Evaluaciones) > 0 ? $Evaluaciones[0]["Cantidad"] : 0;

            $SqlInsert = "INSERT INTO evaluaciones_semanas_calidad (Id_Usuario,Mes,Ano,SemanaAno,SemanaMes,cantEvaluaciones,metaEvaluaciones) values('".$_SESSION["id_usuario"]."','".$MesActual."','".$AnoActual."','".$SemanaAnoActual."','".$SemanaMes."','1','".$Cant."') ON DUPLICATE KEY UPDATE cantEvaluaciones = (cantEvaluaciones + 1), metaEvaluaciones = VALUES(metaEvaluaciones)";
            $Insert = $this->db->query($SqlInsert);
            return $Insert;
        }
        function getPeriodosEvaluacionesSemanales(){
            //$db = new Db();

            $SqlPeriodos = "SELECT
                                Mes,
                                Ano
                            FROM
                                evaluaciones_semanas_calidad ES
                            GROUP BY
                                Mes,Ano
                            ORDER BY
                                Ano DESC,Mes DESC";
            $Periodos = $this->db->select($SqlPeriodos);
            return $Periodos;
        }
        function getPeriodoEvaluacionesSemanales($Mes,$Ano){
            //$db = new Db();
            $ToReturn = array();
            $ToReturn["Header"] = array();
            array_push($ToReturn["Header"],"Nombre");
            $ToReturn["Columns"] = array();
            $ArrayTmp = array();
            $ArrayTmp["data"] = "Nombre";
            array_push($ToReturn["Columns"],$ArrayTmp);
            $ToReturn["Data"] = array();

            $Semanas = $this->getSemanasPeriodoEvaluacionesSemanales($Mes,$Ano);
            foreach($Semanas as $Semana){
                $Texto = "Semana ".$Semana["semanaMes"];
                $TextoMeta = "Meta Semana ".$Semana["semanaMes"];
                array_push($ToReturn["Header"],$Texto);
                array_push($ToReturn["Header"],$TextoMeta);
                $ArrayTmp = array();
                $ArrayTmp["data"] = $Semana["semanaMes"];
                array_push($ToReturn["Columns"],$ArrayTmp);
                $ArrayTmp = array();
                $ArrayTmp["data"] = "meta".$Semana["semanaMes"];
                array_push($ToReturn["Columns"],$ArrayTmp);
            }

            $SqlPeriodos = "SELECT
                                    P.Nombre as Nombre,
                                    ES.Id_Usuario as idUsuario,
                                    GROUP_CONCAT(ES.cantEvaluaciones) as Cantidades,
                                    GROUP_CONCAT(ES.semanaMes) as Semanas
                                FROM
                                    evaluaciones_semanas_calidad ES
                                        INNER JOIN Personal P ON P.id_usuario = ES.Id_Usuario
                                WHERE
                                    ES.Mes = '".$Mes."' AND
                                    ES.Ano = '".$Ano."'
                                GROUP BY
                                    ES.Id_Usuario
                                ORDER BY
                                    P.Nombre,
                                    ES.semanaMes ASC";
            $Periodos = $this->db->select($SqlPeriodos);
            $Usuarios = array();
            foreach($Periodos as $Periodo){
                $Nombre = $Periodo["Nombre"];
                $CantidadesText = $Periodo["Cantidades"];
                $SemanasText = $Periodo["Semanas"];
                $ArrayCantidades = explode(",",$CantidadesText);
                $ArraySemanas = explode(",",$SemanasText);


                $ArrayTmp = array();
                $ArrayTmp["Nombre"] = $Nombre;

                foreach($Semanas as $Semana){
                    $numeroSemana = $Semana["semanaMes"];
                    $Key = array_search($numeroSemana,$ArraySemanas);
                    if($Key !== false){
                        $Cantidad = $ArrayCantidades[$Key];
                        $ArrayTmp[$numeroSemana] = $Cantidad;
                    }else{
                        $ArrayTmp[$numeroSemana] = "0";
                    }
                    $ArrayTmp["meta".$numeroSemana] = $this->getMetaPeriodoByIdUsuario($Mes,$Ano,$numeroSemana,$Periodo["idUsuario"]);
                }
                
                array_push($ToReturn["Data"],$ArrayTmp);
            }
            return $ToReturn;
        }
        function getSemanasPeriodoEvaluacionesSemanales($Mes,$Ano){
            //$db = new Db();
            
            $SqlSemanas = "SELECT
                                semanaMes
                            FROM
                                evaluaciones_semanas_calidad ES
                            WHERE
                                Mes = '".$Mes."' AND
                                Ano = '".$Ano."'
                            GROUP BY
                                semanaMes
                            ORDER BY
                                semanaMEs";
            $Semanas = $this->db->select($SqlSemanas);
            return $Semanas;
        }
        function getMetaPeriodoByIdUsuario($Mes,$Ano,$numeroSemana,$idUsuario){
            //$db = new Db();

            $SqlMeta = "SELECT
                            metaEvaluaciones as Meta
                        FROM
                            evaluaciones_semanas_calidad ES
                        WHERE
                            ES.Mes = '".$Mes."' AND
                            ES.Ano = '".$Ano."' AND
                            ES.semanaMes = '".$numeroSemana."' AND
                            ES.Id_Usuario = '".$idUsuario."'
                        GROUP BY
                            ES.Mes,
                            ES.Ano";
            $Meta = $this->db->select($SqlMeta);
            $Meta = count($Meta) > 0 ? $Meta[0]["Meta"] : 0;
            return $Meta;
        }
    }
?>