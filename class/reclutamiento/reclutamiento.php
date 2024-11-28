<?php
    class Reclutamiento{
        public $Username;
        public $Password;
        public $Perfil;
        public $idPregunta;
        public $idUsuario;
        public $idPersonal;
        public $idEmpresa;
        public $CompetenciasMinMax;
        function __construct(){
            $this->CompetenciasMinMax["Max"] = 4.6;
            $this->CompetenciasMinMax["Min"] = 2.8;
        }
        function Login(){
            $ToReturn = false;
            $db = new DB();
            $SqlLogin = "select * from usuarios_reclutamiento where username='".$this->Username."' and password='".$this->Password."'";
            $Login = $db->select($SqlLogin);
            if(count($Login) > 0){
                $ToReturn = array();
                $ToReturn[] = true;
                $_SESSION["idUsuario_reclutamiento"] = $Login[0]["id"];
                $this->idUsuario = $Login[0]["id"];
                $_SESSION["idEmpresa_reclutamiento"] = $Login[0]["id_empresa"];
                $generales = (count($db -> select('SELECT * FROM datos_generales_reclutamiento WHERE IdUsuarioReclutamiento = '.$Login[0]["id"])) > 0) ? true : false;
                $domicilio = (count($db -> select('SELECT * FROM domicilio_reclutamiento WHERE IdUsuarioReclutamiento = '.$Login[0]["id"])) > 0) ? true : false;
                $contactos = (count($db -> select('SELECT * FROM contactos_reclutamiento WHERE IdUsuarioReclutamiento = '.$Login[0]["id"])) > 0) ? true : false;
                $previcionales = (count($db -> select('SELECT * FROM datos_personales_reclutamiento WHERE IdUsuarioReclutamiento = '.$Login[0]["id"])) > 0) ? true : false;
                if ($generales && $domicilio && $contactos && $previcionales) {
                    $ToReturn[] = true;
                }else{
                    $ToReturn[] = false;
                }
            }
            echo json_encode($ToReturn);
        }
        function getPreguntas(){
            $db = new DB();
            $SqlPreguntas = "select * from preguntas_reclutamiento where id_perfil='".$this->getPerfilId()."'";
            $Preguntas = $db->select($SqlPreguntas);
            return $Preguntas;
        }
        function getOpciones(){
            $db = new DB();
            $SqlPreguntas = "select * from opciones_preguntas_reclutamiento where id_pregunta='".$this->idPregunta."'";
            $Preguntas = $db->select($SqlPreguntas);
            return $Preguntas;
        }
        function insertCalificacion($Preguntas,$TestFinalizado){
            $db = new DB();
            $email = new Email();
            $Prueba = $this->getPruebaActiva();
            $Status = $TestFinalizado == "1" ? "1" : "2";
            foreach($Preguntas as $Pregunta){
                $idPregunta = $Pregunta[0];
                $idOpcion = $Pregunta[1];
                $DateArray = $this->getDateFromServer();
                $Date = $DateArray["date"];
                $SQL = "insert into respuestas_opciones_preguntas_reclutamiento (id_pregunta,id_opcion,id_usuario,fecha,id_prueba) values ('".$idPregunta."','".$idOpcion."','".$_SESSION['idUsuario_reclutamiento']."','".$Date."','".$this->getPruebaId()."')";
                $insertRespuestas = $db->query($SQL);
            }
            $SQlUpdatePruebas = "update pruebas_reclutamiento set status = '".$Status."' where id_usuario = '".$_SESSION['idUsuario_reclutamiento']."' and id='".$Prueba["id"]."'";
            $UpdatePruebas = $db->query($SQlUpdatePruebas);
            //$email->SendNotification("Contenido del Correo","Notificacion de Calificacion", "soporte@Soporte.cl");
        }
        function usuarioTienePruebasDisponibles(){
            $ToReturn = false;
            $db = new DB();
            $SqlRespuestas = "select * from pruebas_reclutamiento where id_usuario ='".$_SESSION['idUsuario_reclutamiento']."' and pruebas_reclutamiento.status='0'";
            $Respuestas = $db->select($SqlRespuestas);
            if(count($Respuestas) > 0){
                $ToReturn = true;
            }
            return $ToReturn;
        }
        function getDateFromServer($Separator = ""){
            $db = new Db();
            $SqlDate = "SELECT FORMAT(NOW(),'yyyy".$Separator."MM".$Separator."dd') AS date, FORMAT(NOW(),'H:mm') AS hour";
            $Dates = $db -> select($SqlDate);
            return $Dates[0];
        }
        function getPruebaId(){
            $db = new DB();
            //$SqlPrueba = "select id from pruebas_reclutamiento where id_usuario='".$_SESSION['idUsuario_reclutamiento']."' and status='0'";
            $SqlPrueba = "select pruebas_reclutamiento.*,tipos_test_reclutamiento.time from pruebas_reclutamiento inner join tipos_test_reclutamiento on tipos_test_reclutamiento.id = pruebas_reclutamiento.id_tipotest where id_usuario = '".$_SESSION['idUsuario_reclutamiento']."' and status ='0' order by tipos_test_reclutamiento.prioridad LIMIT 1";
            $Prueba = $db->select($SqlPrueba);
            return $Prueba[0]["id"];
        }
        function getPerfilId(){
            $db = new DB();
            $SqlPerfil = "select id_perfil from pruebas_reclutamiento where id_usuario='".$_SESSION['idUsuario_reclutamiento']."' and status='0'";
            $Perfil = $db->select($SqlPerfil);
            return $Perfil[0]["id_perfil"];
        }
        function getPreguntasCompetencias(){
            $db = new DB();
            $SqlPreguntas = "select preguntas_competencias_reclutamiento.* from preguntas_competencias_reclutamiento inner join competencias_periles_reclutamiento on competencias_periles_reclutamiento.id = preguntas_competencias_reclutamiento.id_competencia inner join perfiles_reclutamiento on perfiles_reclutamiento.id = competencias_periles_reclutamiento.id_perfil where perfiles_reclutamiento.id='".$this->Perfil."' and perfiles_reclutamiento.id_empresa='".$_SESSION['idEmpresa_reclutamiento']."'";
            $Preguntas = $db->select($SqlPreguntas);
            return $Preguntas;
        }
        function getPruebaActiva(){
            $db = new DB();
            //$SqlPrueba = "select * from pruebas_reclutamiento where id_usuario = '".$_SESSION['idUsuario_reclutamiento']."' and status ='0'";
            $SqlPrueba = "select pruebas_reclutamiento.*,tipos_test_reclutamiento.time from pruebas_reclutamiento inner join tipos_test_reclutamiento on tipos_test_reclutamiento.id = pruebas_reclutamiento.id_tipotest where id_usuario = '".$_SESSION['idUsuario_reclutamiento']."' and status ='0' order by tipos_test_reclutamiento.prioridad LIMIT 1";
            $Prueba = $db->select($SqlPrueba);
            return $Prueba[0];
        }
        
        function getOpcionesCompetencias(){
            $db = new DB();
            $SqlPreguntas = "select * from opciones_preguntas_competencias_reclutamiento where id_pregunta='".$this->idPregunta."'";
            $Preguntas = $db->select($SqlPreguntas);
            return $Preguntas;
        }
        function insertCalificacionCompetencias($Preguntas,$TestFinalizado){
            $db = new DB();
            $email = new Email();
            $Status = $TestFinalizado == "1" ? "1" : "2";
            $Prueba = $this->getPruebaActiva();
            foreach($Preguntas as $Pregunta){
                $idPregunta = $Pregunta[0];
                $alto = $Pregunta[3];
                $promedio = $Pregunta[2];
                $bajo = $Pregunta[1];
                $SQL = "insert into respuestas_opciones_preguntas_competencias_reclutamiento (id_pregunta,alto,promedio,bajo,id_usuario,id_prueba) values ('".$idPregunta."','".$alto."','".$promedio."','".$bajo."','".$_SESSION['idUsuario_reclutamiento']."','".$this->getPruebaId()."')";
                $insertRespuestas = $db->query($SQL);
            }
            $SQlUpdatePruebas = "update pruebas_reclutamiento set status = '".$Status."' where id_usuario = '".$_SESSION['idUsuario_reclutamiento']."' and id='".$Prueba["id"]."'";
            $UpdatePruebas = $db->query($SQlUpdatePruebas);
            //$email->SendNotification("Contenido del Correo","Notificacion de Calificacion", "soporte@Soporte.cl");
        }
        function insertCalificacionPersonalidad($Preguntas,$TestFinalizado){
            $db = new DB();
            $email = new Email();
            $Cont = 1;
            $Status = $TestFinalizado == "1" ? "1" : "2";
            $Prueba = $this->getPruebaActiva();
            foreach($Preguntas as $Pregunta){
                $Opciones = $Pregunta;
                foreach($Opciones as $Opcion){
                    $ID = $Opcion[0];
                    $Valor = $Opcion[1];
                    $SQL = "insert into respuestas_preguntas_personalidad_reclutamiento (pregunta,opcion,respuesta,id_usuario,id_prueba) values ('".$Cont."','".$ID."','".$Valor."','".$_SESSION['idUsuario_reclutamiento']."','".$this->getPruebaId()."')";
                    $insertRespuestas = $db->query($SQL);
                }
                $Cont++;
            }
            $SQlUpdatePruebas = "update pruebas_reclutamiento set status = '".$Status."' where id_usuario = '".$_SESSION['idUsuario_reclutamiento']."' and id='".$Prueba["id"]."'";
            $UpdatePruebas = $db->query($SQlUpdatePruebas);
            //$email->SendNotification("Contenido del Correo","Notificacion de Calificacion", "soporte@Soporte.cl");
        }
        ////////////////////////////////////
        function getPerfilesByDate($startDate,$endDate){
            $db = new DB();
            $SqlPerfil = "select perfiles_reclutamiento.id, perfiles_reclutamiento.nombre from perfiles_reclutamiento inner join pruebas_reclutamiento on pruebas_reclutamiento.id_perfil = perfiles_reclutamiento.id where perfiles_reclutamiento.id_empresa='".$_SESSION['mandante']."' and pruebas_reclutamiento.fecha BETWEEN '".$startDate."' and '".$endDate."' Group by perfiles_reclutamiento.id, perfiles_reclutamiento.nombre";
            $Perfiles = $db->select($SqlPerfil);
            return $Perfiles;
        }
        function getAspirantesByDateAndPerfil($startDate,$endDate,$Perfil){
            $db = new DB();
            $WherePerfil = $Perfil == "" ? "" : "and pruebas_reclutamiento.id_perfil = '".$Perfil."'";
            $SqlAspirante = "select datos_generales_reclutamiento.IdUsuarioReclutamiento as idUsuario, concat(datos_generales_reclutamiento.Nombres,' ',datos_generales_reclutamiento.Apellidos) as Nombre_Completo from pruebas_reclutamiento inner join usuarios_reclutamiento on usuarios_reclutamiento.id = pruebas_reclutamiento.id_usuario inner join datos_generales_reclutamiento on datos_generales_reclutamiento.IdUsuarioReclutamiento = usuarios_reclutamiento.id where pruebas_reclutamiento.fecha BETWEEN '".$startDate."' and '".$endDate."' and pruebas_reclutamiento.id_empresa='".$_SESSION['mandante']."' ".$WherePerfil." group by datos_generales_reclutamiento.IdUsuarioReclutamiento, datos_generales_reclutamiento.Nombres, datos_generales_reclutamiento.Apellidos";
            $Aspirantes = $db->select($SqlAspirante);
            return $Aspirantes;
        }
        /*
          *** Muestra todas las pruebas de un aspirante
        */
        function getPruebasUsuario($startDate,$endDate,$Perfil,$Aspirante){
            $ToReturn = array();
            $db = new DB();
            $WherePerfil = $Perfil == "" ? "" : "and pruebas_reclutamiento.id_perfil = '".$Perfil."'";
            $WhereAspirante = $Aspirante == "" ? "" : "and pruebas_reclutamiento.id_usuario='".$Aspirante."'";
            $SqAspirantes = "Select CONCAT(datos_generales_reclutamiento.Nombres,' ',datos_generales_reclutamiento.Apellidos) as Nombre, datos_generales_reclutamiento.Correo, pruebas_reclutamiento.id_usuario FROM pruebas_reclutamiento inner join datos_generales_reclutamiento on datos_generales_reclutamiento.IdUsuarioReclutamiento = pruebas_reclutamiento.id_usuario WHERE fecha BETWEEN '".$startDate."' and '".$endDate."' ".$WhereAspirante." ".$WherePerfil." group by pruebas_reclutamiento.id_usuario, datos_generales_reclutamiento.Nombres, datos_generales_reclutamiento.Apellidos, datos_generales_reclutamiento.Correo";
            $Aspirantes = $db->select($SqAspirantes);
            foreach($Aspirantes as $Aspirante){
                $ArrayTmp = array();
                $ArrayTmp["Nombre"] = $Aspirante["Nombre"];
                $ArrayTmp["Correo"] = $Aspirante["Correo"];
                $ArrayTmp["Pruebas"] = $Aspirante["id_usuario"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getCalificacionesAspirantes($startDate,$endDate,$Perfil,$Aspirante){
            $ToReturn = array();
            $db = new DB();
            $WherePerfil = $Perfil == "" ? "" : "and pruebas_reclutamiento.id_perfil = '".$Perfil."'";
            $WhereAspirante = $Aspirante == "" ? "" : "and pruebas_reclutamiento.id_usuario='".$Aspirante."'";
            //$SqlCalificacion = "select pruebas_reclutamiento.id as idPrueba, CONCAT(datos_generales_reclutamiento.Nombres,' ',datos_generales_reclutamiento.Apellidos) as NombreCompleto, AVG(opciones_preguntas_reclutamiento.ponderacion) as PromedioCalificacion, AVG(preguntas_reclutamiento.calf_minima) as PromedioCalfMinima from pruebas_reclutamiento inner join datos_generales_reclutamiento on datos_generales_reclutamiento.IdUsuarioReclutamiento = pruebas_reclutamiento.id_usuario inner join respuestas_opciones_preguntas_reclutamiento on respuestas_opciones_preguntas_reclutamiento.id_prueba = pruebas_reclutamiento.id inner join preguntas_reclutamiento on preguntas_reclutamiento.id = respuestas_opciones_preguntas_reclutamiento.id_pregunta inner join opciones_preguntas_reclutamiento on opciones_preguntas_reclutamiento.id = respuestas_opciones_preguntas_reclutamiento.id_opcion where pruebas_reclutamiento.fecha BETWEEN '".$startDate."' and '".$endDate."' ".$WhereAspirante." ".$WherePerfil." GROUP BY pruebas_reclutamiento.id order by pruebas_reclutamiento.fecha DESC";
            $SqlPrueba = "Select pruebas_reclutamiento.*, tipos_test_reclutamiento.nombre as nombreTest FROM pruebas_reclutamiento inner join tipos_test_reclutamiento on tipos_test_reclutamiento.id = pruebas_reclutamiento.id_tipotest WHERE fecha BETWEEN '".$startDate."' and '".$endDate."' ".$WhereAspirante." ".$WherePerfil;
            $Pruebas = $db->select($SqlPrueba);
            foreach($Pruebas as $Prueba){
                $ArrayTmp = array();
                $idTipoTest = $Prueba['id_tipotest'];
                switch($idTipoTest){
                    case '1':
                        $SqlRespuestas = "select AVG(opciones_preguntas_reclutamiento.ponderacion) as Calificacion, AVG(preguntas_reclutamiento.calf_minima) as CalfMinima from opciones_preguntas_reclutamiento inner join respuestas_opciones_preguntas_reclutamiento on respuestas_opciones_preguntas_reclutamiento.id_opcion = opciones_preguntas_reclutamiento.id inner join preguntas_reclutamiento on preguntas_reclutamiento.id = respuestas_opciones_preguntas_reclutamiento.id_pregunta where respuestas_opciones_preguntas_reclutamiento.id_prueba='".$Prueba['id']."'";
                        $Respuestas = $db->select($SqlRespuestas);
                        foreach($Respuestas as $Respuesta){
                            $ArrayTmp["PromedioCalificacion"] = $Respuesta["Calificacion"];
                            $ArrayTmp["PromedioCalfMinima"] = $Respuesta["CalfMinima"];
                        }
                        $ArrayTmp["nombreAspirante"] = $this->getNombreAspirante($Prueba["id_usuario"]);
                        $ArrayTmp['nombrePrueba'] = utf8_encode($Prueba["nombreTest"]);
                        $ArrayTmp['fecha'] = $Prueba["fecha"];
                        $ArrayTmp['estatus'] = $Prueba["status"];
                        $ArrayTmp["idPrueba"] = $Prueba['id'];
                    break;
                    case '2':
                        $SqlRespuestas = "select * from respuestas_opciones_preguntas_competencias_reclutamiento where id_prueba='".$Prueba['id']."'";
                        $Respuestas = $db->select($SqlRespuestas);
                        $Calificacion = 0;
                        if(count($Respuestas) > 0){
                            $CalfAprobacion = count($Respuestas) * $this->CompetenciasMinMax["Max"];
                            $CalfReprobacion = count($Respuestas) * $this->CompetenciasMinMax["Min"];
                            $CalfMinima = ($CalfAprobacion + $CalfReprobacion) / 2;
                            foreach($Respuestas as $Respuesta){
                                $Alto = $Respuesta["alto"]; // 100%
                                $Promedio = $Respuesta["promedio"] * 0.75; // 75%
                                $Bajo = $Respuesta["bajo"] * 0.10; // 10%
                                $Calificacion += $Alto + $Promedio + $Bajo;
                            }
                        }else{
                            $SqlResultado = "select count(*) as CantPreguntas from preguntas_competencias_reclutamiento inner join competencias_periles_reclutamiento on competencias_periles_reclutamiento.id = preguntas_competencias_reclutamiento.id_competencia where competencias_periles_reclutamiento.id_perfil='".$Prueba["id_perfil"]."'";
                            $Resultados = $db->select($SqlResultado);
                            foreach($Resultados as $Resultado){
                                $CalfMinima = ((($Resultado["CantPreguntas"] * floatval($this->CompetenciasMinMax["Max"])) + ($Resultado["CantPreguntas"] * $this->CompetenciasMinMax["Min"])) / 2);
                            }
                        }
                        $ArrayTmp["nombreAspirante"] = $this->getNombreAspirante($Prueba["id_usuario"]);
                        $ArrayTmp['nombrePrueba'] = utf8_encode($Prueba["nombreTest"]);
                        $ArrayTmp['fecha'] = $Prueba["fecha"];
                        $ArrayTmp['estatus'] = $Prueba["status"];
                        $ArrayTmp["PromedioCalificacion"] = $Calificacion;
                        $ArrayTmp["PromedioCalfMinima"] = $CalfMinima;
                        $ArrayTmp["idPrueba"] = $Prueba['id'];
                        if($Calificacion > $CalfMinima){
                            //Aprobo
                        }else{
                            //Reprobo
                        }
                    break;
                    case '3':
                        $ArrayTmp["nombreAspirante"] = $this->getNombreAspirante($Prueba["id_usuario"]);
                        $ArrayTmp['nombrePrueba'] = utf8_encode($Prueba["nombreTest"]);
                        $ArrayTmp['fecha'] = $Prueba["fecha"];
                        $ArrayTmp['estatus'] = $Prueba["status"];
                        $ArrayTmp["PromedioCalificacion"] = $this->getPatronNumberPatronText($Prueba['id'],false);
                        $ArrayTmp["PromedioCalfMinima"] = $this->getPatronNumberPatronText($Prueba['id']);
                        $ArrayTmp["idPrueba"] = $Prueba['id'];
                    break;
                }
                //$ArrayTmp["Test"] = $idTipoTest; PROBAR DESPUESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getCalificacionesByDateAndPerfilAndAspirante($startDate,$endDate,$Perfil,$Aspirante){
            $ToReturn = array();
            $db = new DB();
            $WherePerfil = $Perfil == "" ? "" : "and pruebas_reclutamiento.id_perfil = '".$Perfil."'";
            $WhereAspirante = $Aspirante == "" ? "" : "and pruebas_reclutamiento.id_usuario='".$Aspirante."'";
            //$SqlCalificacion = "select pruebas_reclutamiento.id as idPrueba, CONCAT(datos_generales_reclutamiento.Nombres,' ',datos_generales_reclutamiento.Apellidos) as NombreCompleto, AVG(opciones_preguntas_reclutamiento.ponderacion) as PromedioCalificacion, AVG(preguntas_reclutamiento.calf_minima) as PromedioCalfMinima from pruebas_reclutamiento inner join datos_generales_reclutamiento on datos_generales_reclutamiento.IdUsuarioReclutamiento = pruebas_reclutamiento.id_usuario inner join respuestas_opciones_preguntas_reclutamiento on respuestas_opciones_preguntas_reclutamiento.id_prueba = pruebas_reclutamiento.id inner join preguntas_reclutamiento on preguntas_reclutamiento.id = respuestas_opciones_preguntas_reclutamiento.id_pregunta inner join opciones_preguntas_reclutamiento on opciones_preguntas_reclutamiento.id = respuestas_opciones_preguntas_reclutamiento.id_opcion where pruebas_reclutamiento.fecha BETWEEN '".$startDate."' and '".$endDate."' ".$WhereAspirante." ".$WherePerfil." GROUP BY pruebas_reclutamiento.id order by pruebas_reclutamiento.fecha DESC";
            $SqlPrueba = "Select * FROM pruebas_reclutamiento WHERE fecha BETWEEN '".$startDate."' and '".$endDate."' ".$WhereAspirante." ".$WherePerfil." and id IN ( SELECT MAX(id) FROM pruebas_reclutamiento group by id_usuario)";
            $Pruebas = $db->select($SqlPrueba);
            foreach($Pruebas as $Prueba){
                $ArrayTmp = array();
                $idTipoTest = $Prueba['id_tipotest'];
                switch($idTipoTest){
                    case '1':
                        $SqlRespuestas = "select AVG(opciones_preguntas_reclutamiento.ponderacion) as Calificacion, AVG(preguntas_reclutamiento.calf_minima) as CalfMinima from opciones_preguntas_reclutamiento inner join respuestas_opciones_preguntas_reclutamiento on respuestas_opciones_preguntas_reclutamiento.id_opcion = opciones_preguntas_reclutamiento.id inner join preguntas_reclutamiento on preguntas_reclutamiento.id = respuestas_opciones_preguntas_reclutamiento.id_pregunta where respuestas_opciones_preguntas_reclutamiento.id_prueba='".$Prueba['id']."'";
                        $Respuestas = $db->select($SqlRespuestas);
                        foreach($Respuestas as $Respuesta){
                            $ArrayTmp["PromedioCalificacion"] = $Respuesta["Calificacion"];
                            $ArrayTmp["PromedioCalfMinima"] = $Respuesta["CalfMinima"];
                        }
                        $ArrayTmp["NombreCompleto"] = $this->getNombreAspirante($Prueba["id_usuario"]);
                        $ArrayTmp["idPrueba"] = $Prueba['id'];
                    break;
                    case '2':
                        $SqlRespuestas = "select * from respuestas_opciones_preguntas_competencias_reclutamiento where id_prueba='".$Prueba['id']."'";
                        $Respuestas = $db->select($SqlRespuestas);
                        $Calificacion = 0;
                        if(count($Respuestas) > 0){
                            $CalfAprobacion = count($Respuestas) * $this->CompetenciasMinMax["Max"];
                            $CalfReprobacion = count($Respuestas) * $this->CompetenciasMinMax["Min"];
                            $CalfMinima = ($CalfAprobacion + $CalfReprobacion) / 2;
                            foreach($Respuestas as $Respuesta){
                                $Alto = $Respuesta["alto"]; // 100%
                                $Promedio = $Respuesta["promedio"] * 0.75; // 75%
                                $Bajo = $Respuesta["bajo"] * 0.10; // 10%
                                $Calificacion += $Alto + $Promedio + $Bajo;
                            }
                        }else{
                            $SqlResultado = "select count(*) as CantPreguntas from preguntas_competencias_reclutamiento inner join competencias_periles_reclutamiento on competencias_periles_reclutamiento.id = preguntas_competencias_reclutamiento.id_competencia where competencias_periles_reclutamiento.id_perfil='".$Prueba["id_perfil"]."'";
                            $Resultados = $db->select($SqlResultado);
                            foreach($Resultados as $Resultado){
                                $CalfMinima = ((($Resultado["CantPreguntas"] * floatval($this->CompetenciasMinMax["Max"])) + ($Resultado["CantPreguntas"] * $this->CompetenciasMinMax["Min"])) / 2);
                            }
                        }
                        $ArrayTmp["NombreCompleto"] = $this->getNombreAspirante($Prueba["id_usuario"]);
                        $ArrayTmp["PromedioCalificacion"] = $Calificacion;
                        $ArrayTmp["PromedioCalfMinima"] = $CalfMinima;
                        $ArrayTmp["idPrueba"] = $Prueba['id'];
                        if($Calificacion > $CalfMinima){
                            //Aprobo
                        }else{
                            //Reprobo
                        }
                    break;
                    case '3':
                        $ArrayTmp["NombreCompleto"] = $this->getNombreAspirante($Prueba["id_usuario"]);
                        $ArrayTmp["PromedioCalificacion"] = $this->getPatronNumberPatronText($Prueba['id'],false);
                        $ArrayTmp["PromedioCalfMinima"] = $this->getPatronNumberPatronText($Prueba['id']);
                        $ArrayTmp["idPrueba"] = $Prueba['id'];
                    break;
                }
                $ArrayTmp["Test"] = $idTipoTest;
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getNombreAspirante($idUsuario){
            $db = new DB();
            $SqlAspirante = "select CONCAT(Nombres,' ',Apellidos) as NombreCompleto from datos_generales_reclutamiento where IdUsuarioReclutamiento='".$idUsuario."'";
            $Aspirantes = $db->select($SqlAspirante);
            return $Aspirantes[0]["NombreCompleto"];
        }
        function getGraphData($Prueba){
            $ToReturn = array();
            $ArrayPreguntas = array();
            $ArrayCalificacion = array();
            $ArrayCalificacionMinima = array();
            $db = new DB();
            $Prueba = $this->getPruebaByID($Prueba);
            $Prueba = $Prueba[0];
            switch($Prueba["id_tipotest"]){
                case '1':
                    $SqlCalificacionPreguntas = "select opciones_preguntas_reclutamiento.ponderacion, preguntas_reclutamiento.pregunta, preguntas_reclutamiento.calf_minima from opciones_preguntas_reclutamiento inner join respuestas_opciones_preguntas_reclutamiento on respuestas_opciones_preguntas_reclutamiento.id_opcion = opciones_preguntas_reclutamiento.id inner join preguntas_reclutamiento on preguntas_reclutamiento.id = opciones_preguntas_reclutamiento.id_pregunta where respuestas_opciones_preguntas_reclutamiento.id_prueba='".$Prueba["id"]."' order by preguntas_reclutamiento.id";
                    $Preguntas = $db->select($SqlCalificacionPreguntas);
                    foreach($Preguntas as $Pregunta){
                        array_push($ArrayCalificacion,$Pregunta["ponderacion"]);
                        array_push($ArrayCalificacionMinima,$Pregunta["calf_minima"]);
                        array_push($ArrayPreguntas,utf8_encode($Pregunta["pregunta"]));
                    }
                break;
                case '2':
                    $SqlCompetencias = "select * from competencias_periles_reclutamiento where id_empresa='".$_SESSION['mandante']."' and id_perfil='".$Prueba['id_perfil']."' order by competencia";
                    $Competencias = $db->select($SqlCompetencias);
                    foreach($Competencias as $Competencia){
                        $SqlResultado = "select sum(alto) as alto, sum(promedio*0.75) as promedio, sum(bajo*0.1) as bajo, (sum(alto) + sum(promedio*0.75) + sum(bajo*0.1)) as resultado, count(*) as CantPreguntas from respuestas_opciones_preguntas_competencias_reclutamiento inner join preguntas_competencias_reclutamiento on preguntas_competencias_reclutamiento.id = respuestas_opciones_preguntas_competencias_reclutamiento.id_pregunta where id_competencia='".$Competencia["id"]."' and id_prueba='".$Prueba['id']."'";
                        $Resultados = $db->select($SqlResultado);
                        if($Resultados[0]["CantPreguntas"] > 0){
                            foreach($Resultados as $Resultado){
                                $CalfMinima = ((($Resultado["CantPreguntas"] * floatval($this->CompetenciasMinMax["Max"])) + ($Resultado["CantPreguntas"] * $this->CompetenciasMinMax["Min"])) / 2);
                                array_push($ArrayCalificacion,$Resultado["resultado"]);
                                array_push($ArrayCalificacionMinima,$CalfMinima);
                                array_push($ArrayPreguntas,utf8_encode($Competencia["competencia"]));
                            }
                        }else{
                            $SqlResultado = "select count(*) as CantPreguntas from preguntas_competencias_reclutamiento where id_competencia='".$Competencia["id"]."'";
                            $Resultados = $db->select($SqlResultado);
                            foreach($Resultados as $Resultado){
                                $CalfMinima = ((($Resultado["CantPreguntas"] * floatval($this->CompetenciasMinMax["Max"])) + ($Resultado["CantPreguntas"] * $this->CompetenciasMinMax["Min"])) / 2);
                                array_push($ArrayCalificacionMinima,$CalfMinima);
                                array_push($ArrayPreguntas,utf8_encode($Competencia["competencia"]));
                                array_push($ArrayCalificacion,0);
                            }
                        }
                    }
                break;
                case '3':
                    $patronText = $this->getPatronNumberPatronText($Prueba['id']);
                    $Result = $this->getResultadoPatron($patronText);
                    $ToReturn["Patron"] = ("Patrón del ".$patronText);
                    $ToReturn["Emociones"] = utf8_encode($Result["E"]);
                    $ToReturn["Meta"] = utf8_encode($Result["M"]);
                    $ToReturn["Juzga"] = utf8_encode($Result["J"]);
                    $ToReturn["Influye"] = utf8_encode($Result["I"]);
                    $ToReturn["Valores"] = utf8_encode($Result["S"]);
                    $ToReturn["Abusa"] = utf8_encode($Result["A"]);
                    $ToReturn["Presion"] = utf8_encode($Result["B"]);
                    $ToReturn["Teme"] = utf8_encode($Result["T"]);
                    $ToReturn["Eficaz"] = utf8_encode($Result["SE"]);
                    $ToReturn["Observacion"] = array();
                    $ToReturn["Observacion"][0] = utf8_encode($Result["O1"]);
                    $ToReturn["Observacion"][1] = utf8_encode($Result["O2"]);
                    $ToReturn["Observacion"][2] = utf8_encode($Result["O3"]);
                break;
            }
            switch($Prueba["id_tipotest"]){
                case '1':
                case '2':
                    $ToReturn["Preguntas"] = $ArrayPreguntas;
                    $ToReturn["Calificacion"] = $ArrayCalificacion;
                    $ToReturn["CalificacionMinima"] = $ArrayCalificacionMinima;
                break;
                case '3':
                break;
                default:
                break;
            }
            $ToReturn["Test"] = $Prueba['id_tipotest'];
            return $ToReturn;
        }
        function getPruebasActivas(){
            $db = new DB();
            $SqlPruebasActivas = "select CONCAT(datos_generales_reclutamiento.Nombres,' ',datos_generales_reclutamiento.Apellidos) as Nombre, perfiles_reclutamiento.nombre as Perfil, tipos_test_reclutamiento.nombre as Test, datos_generales_reclutamiento.Correo as Correo, datos_generales_reclutamiento.Telefono as Telefono, pruebas_reclutamiento.id as Prueba from pruebas_reclutamiento inner join usuarios_reclutamiento on usuarios_reclutamiento.id = pruebas_reclutamiento.id_usuario inner join perfiles_reclutamiento on perfiles_reclutamiento.id = pruebas_reclutamiento.id_perfil inner join datos_generales_reclutamiento on datos_generales_reclutamiento.IdUsuarioReclutamiento = usuarios_reclutamiento.id inner join tipos_test_reclutamiento on tipos_test_reclutamiento.id = pruebas_reclutamiento.id_tipotest where pruebas_reclutamiento.id_empresa='".$_SESSION['mandante']."' and pruebas_reclutamiento.status='0'";
            $PruebasActivas = $db->select($SqlPruebasActivas);
            return $PruebasActivas;
        }
        function getAspirantesSinPruebasActivas(){
            $db = new DB();
            $SqlUsuariosPruebasActivas = "select distinct usuarios_reclutamiento.id as idUsuario, CONCAT(datos_generales_reclutamiento.Nombres,' ',datos_generales_reclutamiento.Apellidos) as Nombre_Completo from usuarios_reclutamiento inner join datos_generales_reclutamiento on datos_generales_reclutamiento.IdUsuarioReclutamiento = usuarios_reclutamiento.id where usuarios_reclutamiento.id_empresa = '".$_SESSION['mandante']."'";
            $UsuariosPruebasActivas = $db->select($SqlUsuariosPruebasActivas);
            return $UsuariosPruebasActivas;
        }
        function getPerfiles(){
            $db = new DB();
            $SqlPerfiles = "select * from perfiles_reclutamiento where perfiles_reclutamiento.id_empresa = '".$_SESSION['mandante']."'";
            $Perfiles = $db->select($SqlPerfiles);
            return $Perfiles;
        }
        function getTests(){
            $db = new DB();
            $SqlTests = "select * from tipos_test_reclutamiento order by nombre";
            $Tests = $db->select($SqlTests);
            return $Tests;
        }
        function crearPrueba($idUsuario,$idPerfil,$idTest){
            $ToReturn = array();
            $ToReturn["result"] = "0";
            $db = new DB();
            $EmailClass = new Email();
            $Insert = false;
            foreach($idTest as $Test){
                $SqlInsert = "insert into pruebas_reclutamiento (id_usuario,id_perfil,id_tipotest,id_empresa,fecha) values('".$idUsuario."','".$idPerfil."','".$Test."','".$_SESSION['mandante']."',NOW())";
                $Insert = $db->query($SqlInsert);
            }
            if($Insert){
                $AspiranteData = $this->getAspiranteData($idUsuario);
                foreach($AspiranteData as $Data){
                    $Nombre = $Data["Nombre"];
                    $Usuario = $Data["Usuario"];
                    $Clave = $Data["Clave"];
                    $Html = "Estimado ".$Nombre." usted ha sido seleccionado para el proceso de reclutamiento por lo que deber&aacute; entrar responder una prueba antes de pasar a ser selecionado.
                            <br><br>
                            Para hacer la prueba debe entrar a la siguiente pagina: http://foco.Soporte.cl/reclutamiento con los siguientes datos de conexi&oacute;n:
                            <br>
                            Usuario: ".$Usuario."
                            <br>
                            Contrase&ntilde;a: ".$Clave;
                    $Subject = "Proceso de reclutamiento Foco-Estrategico";
                    $Correo = $Usuario;
                    $FromName = "Foco-Estrat&eacute;gico";
                    //$EmailClass->SendNotification($Html,$Subject,$Correo,$FromName);
                    $ToReturn["result"] = "1";
                }
                //$ToReturn["result"] = "1";
            }
            return $ToReturn;
        }
        function getPruebaByID($idPrueba){
             $db = new DB();
             $SqlPrueba = "select * from pruebas_reclutamiento where id='".$idPrueba."'";
             $Pruebas = $db->select($SqlPrueba);
             return $Pruebas;
        }
        function getAspiranteTableList(){
            $db = new DB();
            $SqlAspiranteTableList = "select
                                        CONCAT(datos_generales_reclutamiento.Nombres,' ',datos_generales_reclutamiento.Apellidos) as Nombre,
                                        datos_generales_reclutamiento.Correo as Correo,
                                        datos_generales_reclutamiento.Telefono as Telefono,
                                        datos_generales_reclutamiento.IdUsuarioReclutamiento as IdUsuarioReclutamiento,
                                        usuarios_reclutamiento.Password as Clave
                                    from
                                        datos_generales_reclutamiento inner join usuarios_reclutamiento on usuarios_reclutamiento.id = datos_generales_reclutamiento.IdUsuarioReclutamiento
                                    where
                                        Status='1'";
            $AspiranteTableList = $db->select($SqlAspiranteTableList);
            return $AspiranteTableList;
        }
        function crearAspirante($Nombres,$Apellidos,$Telefono,$Correo){
            $ToReturn = array();
            $ToReturn["result"] = "0";
            $db = new DB();
            if(!$this->ValidarCorreoElectronico($Correo)){
                $SqlInsertUsuario = "insert into usuarios_reclutamiento (Username,Password,id_empresa) values('".$Correo."','".$this->generaPass()."','".$_SESSION['mandante']."')";
                $InsertUsuario = $db->query($SqlInsertUsuario);
                if($InsertUsuario){
                    $SqlInsertDatosGenerales = "insert into datos_generales_reclutamiento (IdUsuarioReclutamiento,Nombres,Apellidos,Telefono,Correo,FechaNacimiento) values('".$this->getIdUsuarioAspirante($Correo)."','".$Nombres."','".$Apellidos."','".$Telefono."','".$Correo."','1970-01-01')";
                    $InsertDatosGenerales = $db->query($SqlInsertDatosGenerales);
                    if($InsertDatosGenerales){
                        $ToReturn["result"] = "1"; //Usuario Registrado
                        $ToReturn["idUsuario"] = $this->getIdUsuarioAspirante($Correo);
                    }
                }
            }else{
                $ToReturn["result"] = "2"; //Correo ya Existe
            }
            return $ToReturn;
        }
        function ValidarCorreoElectronico($Correo){
            $db = new DB();
            $ToReturn = false;
            $SqlCorreo = "select * from usuarios_reclutamiento where Username='".$Correo."'";
            $Correo = $db->select($SqlCorreo);
            if(count($Correo) > 0){
                $ToReturn = true;
            }
            return $ToReturn;
        }
        function getIdUsuarioAspirante($Correo){
            $db = new DB();
            $ToReturn = "";
            $SqlCorreo = "select * from usuarios_reclutamiento where Username='".$Correo."'";
            $Correo = $db->select($SqlCorreo);
            $ToReturn = $Correo[0]["id"];
            return $ToReturn;
        }
        function generaPass(){
            //Se define una cadena de caractares. Te recomiendo que uses esta.
            $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
            //Obtenemos la longitud de la cadena de caracteres
            $longitudCadena=strlen($cadena);

            //Se define la variable que va a contener la contraseña
            $pass = "";
            //Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
            $longitudPass=10;

            //Creamos la contraseña
            for($i=1 ; $i<=$longitudPass ; $i++){
                //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
                $pos=rand(0,$longitudCadena-1);

                //Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
                $pass .= substr($cadena,$pos,1);
            }
            return $pass;
        }
        function getAspiranteData($idUsuario){
            $db = new DB();
            $ToReturn = "";
            $SqlUsuario = "select CONCAT(datos_generales_reclutamiento.Nombres,' ',datos_generales_reclutamiento.Apellidos) as Nombre, usuarios_reclutamiento.Username as Usuario, usuarios_reclutamiento.Password as Clave from usuarios_reclutamiento inner join datos_generales_reclutamiento on datos_generales_reclutamiento.IdUsuarioReclutamiento = usuarios_reclutamiento.id where usuarios_reclutamiento.id='".$idUsuario."'";
            $Usuario = $db->select($SqlUsuario);
            return $Usuario;
        }
        function getCantPreguntasPersonalidad(){
            $db = new DB();
            $ToReturn = 0;
            $SqlCantPreguntas = "select count(pregunta) as Cantidad from (select pregunta from preguntas_personalidad_reclutamiento group by pregunta) tb1";
            $CantPreguntas = $db->select($SqlCantPreguntas);
            $ToReturn = $CantPreguntas[0]["Cantidad"];

            return $ToReturn;
        }
        function getPreguntasPersonalidad($Pregunta){
            $db = new DB();
            $ToReturn = "";
            $SqlOpciones = "select * from preguntas_personalidad_reclutamiento where pregunta='".$Pregunta."' order by id";
            $Opciones = $db->select($SqlOpciones);
            $ToReturn = $Opciones;

            return $ToReturn;
        }
        function getPatronNumberPatronText($Prueba,$getPatronText = true){
            $db = new DB();
            $SqlRespuestas = "select p.Valor as Letra, SUM(r.respuesta) as Valor from respuestas_preguntas_personalidad_reclutamiento r inner join preguntas_personalidad_reclutamiento p on p.id = r.opcion where r.id_prueba='".$Prueba."' GROUP BY valor order by p.Valor";
            $Respuestas = $db->select($SqlRespuestas);
            $ToReturn = "0000";
            foreach($Respuestas as $Respuesta){
                $Letra = strtoupper($Respuesta["Letra"]);
                $Valor = $Respuesta["Valor"];

                switch($Letra){
                    case 'D':
                        if($Valor < -7){
                            $ToReturn[0] = "1";
                        }else{
                            if(($Valor < -3) && ($Valor > -8)){
                                $ToReturn[0] = "2";
                            }else{
                                if(($Valor < 0) && ($Valor > -4)){
                                    $ToReturn[0] = "3";
                                }else{
                                    if(($Valor < 2) && ($Valor > -1)){
                                        $ToReturn[0] = "4";
                                    }else{
                                        if(($Valor > 1) && ($Valor < 5)){
                                            $ToReturn[0] = "5";
                                        }else{
                                            if(($Valor > 4) && ($Valor < 9)){
                                                $ToReturn[0] = "6";
                                            }else{
                                                if($Valor > 8){
                                                    $ToReturn[0] = "7";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    break;
                    case 'I':
                        if($Valor < -7){
                            $ToReturn[1] = "1";
                        }else{
                            if(($Valor < -3) && ($Valor > -8)){
                                $ToReturn[1] = "2";
                            }else{
                                if(($Valor < -1) && ($Valor > -4)){
                                    $ToReturn[1] = "3";
                                }else{
                                    if(($Valor < 2) && ($Valor > -2)){
                                        $ToReturn[1] = "4";
                                    }else{
                                        if(($Valor > 1) && ($Valor < 4)){
                                            $ToReturn[1] = "5";
                                        }else{
                                            if(($Valor > 3) && ($Valor < 7)){
                                                $ToReturn[1] = "6";
                                            }else{
                                                if($Valor > 6){
                                                    $ToReturn[1] = "7";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    break;
                    case 'S':
                        if($Valor < -10){
                            $ToReturn[2] = "1";
                        }else{
                            if(($Valor < -6) && ($Valor > -11)){
                                $ToReturn[2] = "2";
                            }else{
                                if(($Valor < -3) && ($Valor > -7)){
                                    $ToReturn[2] = "3";
                                }else{
                                    if(($Valor < 0) && ($Valor > -4)){
                                        $ToReturn[2] = "4";
                                    }else{
                                        if(($Valor > -1) && ($Valor < 3)){
                                            $ToReturn[2] = "5";
                                        }else{
                                            if(($Valor > 2) && ($Valor < 8)){
                                                $ToReturn[2] = "6";
                                            }else{
                                                if($Valor > 7){
                                                    $ToReturn[2] = "7";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    break;
                    case 'C':
                        if($Valor < -5){
                            $ToReturn[3] = "1";
                        }else{
                            if(($Valor < -2) && ($Valor > -6)){
                                $ToReturn[3] = "2";
                            }else{
                                if(($Valor < 0) && ($Valor > -7)){
                                    $ToReturn[3] = "3";
                                }else{
                                    if(($Valor < 3) && ($Valor > -1)){
                                        $ToReturn[3] = "4";
                                    }else{
                                        if(($Valor > 2) && ($Valor < 5)){
                                            $ToReturn[3] = "5";
                                        }else{
                                            if(($Valor > 4) && ($Valor < 9)){
                                                $ToReturn[3] = "6";
                                            }else{
                                                if($Valor > 8){
                                                    $ToReturn[3] = "7";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    break;
                }
            }
            if($getPatronText){
                $SqlPatronText = "select patronTexto from patrones_personalidad_reclutamiento where patronNumero='".$ToReturn."'";
                $PatronText = $db->select($SqlPatronText);
                if(count($PatronText) > 0){
                    $ToReturn = $PatronText[0]["patronTexto"];
                }
            }
            return $ToReturn;
        }
        function getResultadoPatron($PatronText){
            $db = new DB();
            $Num1 = 0;
            $Num2 = 0;
            for($i=1;$i<=2;$i++){
                switch($i){
                    case 1:
                        if($PatronText == "Alentador"){
                            $Num1 = 1;
                        }else{
                            if($PatronText == "Realizador"){
                                $Num1 = 2;
                            }else{
                                if($PatronText == "Perfeccionista"){
                                    $Num1 = 3;
                                }else{
                                    if($PatronText == "Creativo"){
                                        $Num1 = 4;
                                    }else{
                                        if($PatronText == "Objetivo"){
                                            $Num1 = 5;
                                        }else{
                                            if($PatronText == "persuasivo"){
                                                $Num1 = 6;
                                            }else{
                                                if($PatronText == "Promotor"){
                                                    $Num1 = 7;
                                                }else{
                                                    if($PatronText == "Consejero"){
                                                        $Num1 = 8;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    break;
                    case 2:
                        if($PatronText == "Agente"){
                            $Num2 = 9;
                        }else{
                            if($PatronText == "Evaluador"){
                                $Num2 = 10;
                            }else{
                                if($PatronText == "Resolutivo"){
                                    $Num2 = 11;
                                }else{
                                    if($PatronText == "Profesional"){
                                        $Num2 = 12;
                                    }else{
                                        if($PatronText == "Investigador"){
                                            $Num2 = 13;
                                        }else{
                                            if($PatronText == "Orientado a resultados"){
                                                $Num2 = 14;
                                            }else{
                                                if($PatronText == "Especialista"){
                                                    $Num2 = 15;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    break;
                }
            }
            $Numero = $Num1 + $Num2;
            $SqlResultado = "select * from resultado_patron_personalidad_reclutamiento where patronNumber = '".$Numero."'";
            $Resultado = $db->select($SqlResultado);
            return $Resultado[0];
        }
        function deleteAspirante($idUsuario){
            $ToReturn["result"] = "0";
            $db = new DB();
            $sqlPruebas = "select * from pruebas_reclutamiento where id_usuario = '".$idUsuario."'";
            $Pruebas = $db->select($sqlPruebas);
            if(count($Pruebas) > 0){
                $HaveCompletedTests = false;
                foreach($Pruebas as $Prueba){
                    if($Prueba["status"] == "1"){
                        $HaveCompletedTests = true;
                        break;
                    }
                }
                if(!$HaveCompletedTests){
                    $SqlDelete = "delete from contactos_reclutamiento where IdUsuarioReclutamiento='".$idUsuario."'";
                    $Delete = $db->query($SqlDelete);
                    $SqlDelete = "delete from datos_generales_reclutamiento where IdUsuarioReclutamiento='".$idUsuario."'";
                    $Delete = $db->query($SqlDelete);
                    $SqlDelete = "delete from datos_personales_reclutamiento where IdUsuarioReclutamiento='".$idUsuario."'";
                    $Delete = $db->query($SqlDelete);
                    $SqlDelete = "delete from domicilio_reclutamiento where IdUsuarioReclutamiento='".$idUsuario."'";
                    $Delete = $db->query($SqlDelete);
                    $SqlDelete = "delete from usuarios_reclutamiento where id='".$idUsuario."'";
                    $Delete = $db->query($SqlDelete);
                    if($Delete){
                        $ToReturn["result"] = "1";
                    }
                }
            }else{
                $SqlDelete = "delete from contactos_reclutamiento where IdUsuarioReclutamiento='".$idUsuario."'";
                $Delete = $db->query($SqlDelete);
                $SqlDelete = "delete from datos_generales_reclutamiento where IdUsuarioReclutamiento='".$idUsuario."'";
                $Delete = $db->query($SqlDelete);
                $SqlDelete = "delete from datos_personales_reclutamiento where IdUsuarioReclutamiento='".$idUsuario."'";
                $Delete = $db->query($SqlDelete);
                $SqlDelete = "delete from domicilio_reclutamiento where IdUsuarioReclutamiento='".$idUsuario."'";
                $Delete = $db->query($SqlDelete);
                $SqlDelete = "delete from usuarios_reclutamiento where id='".$idUsuario."'";
                $Delete = $db->query($SqlDelete);
                if($Delete){
                    $ToReturn["result"] = "1";
                }
            }
            return $ToReturn;
        }
        function deletePrueba($idPrueba){
            $ToReturn["result"] = "0";
            $db = new DB();
            $sqlPruebas = "select * from pruebas_reclutamiento where id = '".$idPrueba."'";
            $Pruebas = $db->select($sqlPruebas);
            if(count($Pruebas) > 0){
                if($Pruebas[0]["status"] == "0"){
                    $db->query("DELETE FROM pruebas_reclutamiento where id='".$idPrueba."'");
                    $ToReturn['result'] = "1";
                }
            }
            return $ToReturn;
        }
        function getPruebaData($idPrueba){
            $ToReturn = "";
            $db = new DB();

            $SqlPrueba = "select * from pruebas_reclutamiento where id='".$idPrueba."'";
            $Prueba = $db->select($SqlPrueba);
            $ToReturn = $Prueba;

            return $ToReturn;
        }
        function updatePrueba($idPrueba,$idPerfil,$idTest){
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlUpdate = "UPDATE pruebas_reclutamiento set id_perfil='".$idPerfil."', id_tipotest='".$idTest."' where id='".$idPrueba."'";
            $Update = $db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function marcarNoTerminada($Prueba){
            $ToReturn = array();
            $db = new DB();
            $SqlUpdate = "update pruebas_reclutamiento set status='2' where id='".$Prueba["id"]."'";
            $Update = $db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }else{
                $ToReturn["result"] = false;
            }
            $ToReturn["query"] = $SqlUpdate;
            return $ToReturn;
        }
        function getTestReport($startDate,$endDate,$Perfil,$Aspirante){
            $db = new DB();
            $WherePerfil = $Perfil != "" ? " and id_perfil='".$Perfil."' " : "";
            $WhereAspirante = $Aspirante != "" ? " and id_usuario='".$Perfil."' " : "";

            /*$SqlAspirantes = "select pruebas_reclutamiento.id_usuario as idUsuario, CONCAT(datos_generales_reclutamiento.Nombres,' ',datos_generales_reclutamiento.Apellidos) as Nombre, datos_generales_reclutamiento.Correo, datos_generales_reclutamiento.Telefono from pruebas_reclutamiento inner join datos_generales_reclutamiento on datos_generales_reclutamiento.IdUsuarioReclutamiento = pruebas_reclutamiento.id_usuario where fecha between '".$startDate."' and '".$endDate."' ".$WherePerfil." ".$WhereAspirante." group by pruebas_reclutamiento.id_usuario order by datos_generales_reclutamiento.Nombres";*/
            $SqlAspirantes = "  SELECT 
                                    pruebas_reclutamiento.id_usuario as idUsuario,
                                    datos_generales_reclutamiento.* 
                                FROM 
                                    pruebas_reclutamiento 
                                INNER JOIN 
                                    datos_generales_reclutamiento on datos_generales_reclutamiento.IdUsuarioReclutamiento = pruebas_reclutamiento.id_usuario 
                                WHERE 
                                    fecha BETWEEN '".$startDate."' and '".$endDate."' 
                                ".$WherePerfil." 
                                ".$WhereAspirante." 
                                GROUP BY 
                                    pruebas_reclutamiento.id_usuario,
                                    datos_generales_reclutamiento.IdDatosGenerales,
                                    datos_generales_reclutamiento.IdUsuarioReclutamiento,
                                    datos_generales_reclutamiento.Rut,
                                    datos_generales_reclutamiento.Apellidos,
                                    datos_generales_reclutamiento.Nombres,
                                    datos_generales_reclutamiento.Telefono,
                                    datos_generales_reclutamiento.FechaNacimiento,
                                    datos_generales_reclutamiento.Correo,
                                    datos_generales_reclutamiento.Status
                                ORDER BY 
                                    datos_generales_reclutamiento.Nombres";
            $Aspirantes = $db->select($SqlAspirantes);
            $Arrayspirantes = array();
            $lala = "";
            foreach($Aspirantes as $Aspirante){
                $ArrayTmp = array();
                $ArrayTmp["idUsuario"] = $Aspirante["idUsuario"];
                $ArrayTmp["Rut"] = $Aspirante["Rut"];
                $ArrayTmp["Apellidos"] = utf8_encode($Aspirante["Apellidos"]);
                $ArrayTmp["Nombres"] = utf8_encode($Aspirante["Nombres"]);
                $ArrayTmp["Correo"] = $Aspirante["Correo"];
                $ArrayTmp["Telefono"] = $Aspirante["Telefono"];

                $idAspirante = $Aspirante["idUsuario"];
                $SqlPruebas = "SELECT
                                    *
                                FROM
                                    (
                                        SELECT
                                            tipos_test_reclutamiento.id as idTipoTest,
                                            tipos_test_reclutamiento.prioridad,
                                            pruebas_reclutamiento.*
                                        FROM
                                            pruebas_reclutamiento
                                                INNER JOIN tipos_test_reclutamiento on tipos_test_reclutamiento.id = pruebas_reclutamiento.id_tipotest
                                        WHERE
                                            fecha BETWEEN '".$startDate."' and '".$endDate."'
                                            ".$WherePerfil." and
                                            id_usuario = '".$idAspirante."'
                                    ) tb1
                                GROUP BY
                                    id_tipotest, 
                                    idTipoTest, 
                                    prioridad, 
                                    id, 
                                    id_usuario, 
                                    id_empresa, 
                                    id_perfil, 
                                    fecha, 
                                    status
                                ORDER BY
                                prioridad ASC";
                $lala = $SqlPruebas;
                $Pruebas = $db->select($SqlPruebas);
                $ArrayPruebas = array();
                $ContApro = 0;
                $ContRep = 0;

                $ArrayTmp["Pruebas"] = array();
                foreach($Pruebas as $Prueba){
                    $ArrayPruebasTmp = array();

                    $Aprobo = false;
                    switch($Prueba["id_tipotest"]){
                        case '1':
                        break;
                        case '2':
                            $SqlRespuestas = "select * from respuestas_opciones_preguntas_competencias_reclutamiento where id_prueba='".$Prueba['id']."'";
                            $Respuestas = $db->select($SqlRespuestas);
                            $Calificacion = 0;
                            if(count($Respuestas) > 0){
                                $CalfAprobacion = count($Respuestas) * $this->CompetenciasMinMax["Max"];
                                $CalfReprobacion = count($Respuestas) * $this->CompetenciasMinMax["Min"];
                                $CalfMinima = ($CalfAprobacion + $CalfReprobacion) / 2;
                                foreach($Respuestas as $Respuesta){
                                    $Alto = $Respuesta["alto"]; // 100%
                                    $Promedio = $Respuesta["promedio"] * 0.75; // 75%
                                    $Bajo = $Respuesta["bajo"] * 0.10; // 10%
                                    $Calificacion += $Alto + $Promedio + $Bajo;
                                }
                            }
                            $PorcentajeCalificacion = number_format(($Calificacion * 50) / $CalfMinima,2);
                            if($PorcentajeCalificacion >= 60){
                                $ContApro++;
                            }
                            if(($PorcentajeCalificacion >= 50) && ($PorcentajeCalificacion < 60)){
                                $ContApro++; //Con Observacion
                            }
                            if($PorcentajeCalificacion < 50){
                                $ContRep++;
                            }
                            $ArrayPruebasTmp["Calificacion"] = $ContApro > 0 ? "Aprobado" : "Reprobado";
                            $ArrayPruebasTmp["Resultado"] = $PorcentajeCalificacion;
                        break;
                        case '3':
                            $PatronText = $this->getPatronNumberPatronText($Prueba["id"],true);
                            $ResultdoAprobacion = $this->getAprobacionPatronPersonlidad($PatronText);
                            switch($ResultdoAprobacion){
                                case 'A':
                                    $ContApro++;
                                break;
                                case 'AO':
                                    $ContApro++;
                                break;
                                case 'R':
                                    $ContRep++;
                                break;
                            }
                            $ArrayPruebasTmp["Calificacion"] = $ContApro > 0 ? "Aprobado" : "Reprobado";
                            $ArrayPruebasTmp["Resultado"] = $PatronText;
                        break;
                    }
                    $ArrayPruebasTmp["Tipo_Test"] = $Prueba["id_tipotest"];
                    array_push($ArrayTmp["Pruebas"],$ArrayPruebasTmp);
                }
                if(count($Pruebas) > 1){
                    if(($ContApro == 1) && ($ContRep == 1)){
                        $ArrayTmp["Resultado"] = utf8_encode("Aprobado con Observación");
                    }
                    if($ContApro > 1){
                        $ArrayTmp["Resultado"] = "Aprobado";
                    }
                    if($ContRep > 1){
                        $ArrayTmp["Resultado"] = "Rechazado";
                    }
                }else{
                    if($ContApro == 1){
                        $ArrayTmp["Resultado"] = "Aprobado";
                    }
                    if($ContRep == 1){
                        $ArrayTmp["Resultado"] = "Rechazado";
                    }
                }
                array_push($Arrayspirantes,$ArrayTmp);
            }
            
            $fileName = "Prueba";
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
            $NextSheet = 0;

            $objPHPExcel->createSheet($NextSheet);
            $objPHPExcel->setActiveSheetIndex($NextSheet);
            $objPHPExcel->getActiveSheet()->setTitle('Aspirantes');

            $objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getFont()->setSize(11);

            $CamposOrden = $this->getOrdenNotasAspirantesExcelTableList();
            $Tests = $this->getTipoTest();

            $Col = 0;
            $Row = 1;
            foreach($CamposOrden as $Campo){
                $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($Col,$Row,$Campo["Titulo"]);
                $Col++;
            }
            foreach($Tests as $Test){
                $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($Col,$Row,$Test["nombre"]);
                $Col++;
            }
            $objPHPExcel->
            setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow($Col,$Row,"Resultado Final");
            /*$objPHPExcel->
            setActiveSheetIndex($NextSheet)
                    ->setCellValueByColumnAndRow(0,1,"Nombre Completo")
                    ->setCellValueByColumnAndRow(1,1,"Correo")
                    ->setCellValueByColumnAndRow(2,1,"Teléfono")
                    ->setCellValueByColumnAndRow(3,1,"Test de Competencias")
                    ->setCellValueByColumnAndRow(4,1,"Test de Personalidad")
                    ->setCellValueByColumnAndRow(5,1,"RESULTADO");*/
            
            $Row = 2;
            foreach($Arrayspirantes as $Aspirante){
                $ResultadoCompetencias = "";
                $ResultadoPersonalidad = "";
                $ArrayResultadoPruebas = array();
                //print_r($Aspirante["Pruebas"]);
                $conttt = 1;
                foreach($Tests as $Test){
                    $ToReturn = "";
                    $Found = false;
                    foreach($Aspirante["Pruebas"] as $Prueba){
                        $TipoTest = $Prueba["Tipo_Test"];
                        //echo $Test["id"]." - ".$TipoTest;
                        if(!$Found){
                            if($Test["id"] == $TipoTest){
                                switch($TipoTest){
                                    case '2':
                                        //Competencias
                                        $ToReturn = $Prueba["Resultado"]."%";
                                        $Calificacion = $Prueba["Calificacion"];
                                        $Found = true;
                                    break;
                                    case '3':
                                        //Personalidad
                                        $ToReturn = $Prueba["Resultado"];
                                        $Calificacion = $Prueba["Calificacion"];
                                        $Found = true;
                                    break;
                                }
                            }
                        }
                    }
                    $ArrayResultadoPruebas[$Test["id"]] = array("Resultado" => $ToReturn, "Calificacion" => $Calificacion);
                }
                //print_r($ArrayResultadoPruebas);
                $Col = 0;
                foreach($CamposOrden as $Campo){
                    switch($Campo["Dinamico"]){
                        case "1":
                            $Respuesta = $this->RespuestaCampoDinamicoByUsuarioAndCampo($Aspirante["idUsuario"],$Campo["idCampo"]);
                        break;
                        case "0":
                        $Respuesta = $Aspirante[$Campo["CampoDB"]];
                        break;
                    }
                    $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow($Col,$Row,$Respuesta);
                    $Col++;
                }
                $ContApro = 0;
                $ContRep = 0;
                $ContPruebasRealizadas = 0;
                foreach($ArrayResultadoPruebas as $Prueba){
                    $Resultado = $Prueba["Resultado"];
                    $Calificacion = $Prueba["Calificacion"];
                    if($Resultado != ""){
                        $ContPruebasRealizadas++;
                        switch($Calificacion){
                            case "Aprobado":
                                $ContApro++;
                            break;
                            case "Rechazado":
                                $ContRep++;
                            break;
                        }
                    }
                    $objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow($Col,$Row,$Resultado);
                    $Col++;
                }
                $ResultadoCalificacion = "";
                $Porcentaje = ($ContApro / $ContPruebasRealizadas) * 100;
                if($Porcentaje > 50){
                    $ResultadoCalificacion = "Aprobado";
                }
                if($Porcentaje == 50){
                    $ResultadoCalificacion = "Aprobado con Observación";
                }
                if($Porcentaje < 50){
                    $ResultadoCalificacion = "Reprobado";
                }
                $objPHPExcel->
                setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow($Col,$Row,$ResultadoCalificacion);
                /*$objPHPExcel->
                    setActiveSheetIndex($NextSheet)
                            ->setCellValueByColumnAndRow(0,$Row,$Aspirante["Nombre"])
                            ->setCellValueByColumnAndRow(1,$Row,$Aspirante["Correo"])
                            ->setCellValueByColumnAndRow(2,$Row,$Aspirante["Telefono"].$lala)
                            ->setCellValueByColumnAndRow(3,$Row,$ResultadoCompetencias)
                            ->setCellValueByColumnAndRow(4,$Row,$ResultadoPersonalidad)
                            ->setCellValueByColumnAndRow(5,$Row,$Aspirante["Resultado"]);*/
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
				'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
			);
            return $response;
        }
        function getAprobacionPatronPersonlidad($patronText){
            $ToReturn = "";
            $db = new DB();
            $SqlAprobacion = "select resultado from aprobacion_patron_personalidad_reclutamiento where patron='".$patronText."'";
            $Aprobacion = $db->select($SqlAprobacion);
            return $Aprobacion[0]["resultado"];
        }
        function getTipoTest(){
            $db = new DB();
            $SqlTipoTest = "select * from tipos_test_reclutamiento where id <> 1 order by prioridad";
            $TipoTest = $db->select($SqlTipoTest);
            return $TipoTest;
        }
        function getCampoTableList(){
            $db = new DB();
            $SqlCampoTableList = "select
                                    campos_reclutamiento.id,
                                    campos_reclutamiento.Codigo,
                                    campos_reclutamiento.Titulo,
                                    campos_reclutamiento.ValorEjemplo,
                                    campos_reclutamiento.ValorPredeterminado,
                                    tipos_campos_reclutamiento.Nombre as Tipo,
                                    campos_reclutamiento.Dinamico,
                                    campos_reclutamiento.Mandatorio,
                                    campos_reclutamiento.Deshabilitado,
                                    contenedores_campos_reclutamiento.Nombre as Contenedor
                                from
                                    campos_reclutamiento
                                        inner join contenedores_campos_reclutamiento on contenedores_campos_reclutamiento.id = campos_reclutamiento.id_contenedor
                                        inner join tipos_campos_reclutamiento on tipos_campos_reclutamiento.id = campos_reclutamiento.Tipo";
            $CampoTableList = $db->select($SqlCampoTableList);
            return $CampoTableList;
        }
        function getContenedores(){
            $db = new DB();
            $SqlContenedores = "select
                                    *
                                from
                                    contenedores_campos_reclutamiento";
            $Contenedores = $db->select($SqlContenedores);
            return $Contenedores;
        }
        function getTiposCampos(){
            $db = new DB();
            $SqlContenedores = "select
                                    *
                                from
                                    tipos_campos_reclutamiento";
            $Contenedores = $db->select($SqlContenedores);
            return $Contenedores;
        }
        function CrearCampo($Contenedor,$Codigo,$Titulo,$ValorEjemplo,$ValorPredeterminado,$Tipo,$Mandatorio,$Deshabilitado,$ArrayOpciones){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlInsert = "insert into campos_reclutamiento (id_contenedor,Codigo,Titulo,ValorEjemplo,ValorPredeterminado,Tipo,Dinamico,Mandatorio,Deshabilitado) values ('".$Contenedor."','".$Codigo."','".$Titulo."','".$ValorEjemplo."','".$ValorPredeterminado."','".$Tipo."','1','".$Mandatorio."','".$Deshabilitado."')";
            $idCampo = $db->insert($SqlInsert);
            if($idCampo){
                switch($Tipo){
                    case "3":
                    case "4":
                        $this->AgregarOpcionesCampo($idCampo,$ArrayOpciones);
                    break;
                }
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function ValidacionCodigoAgregar($Codigo){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlValidacion = "select Codigo from campos_reclutamiento where Codigo='".$Codigo."'";
            $Validacion = $db->select($SqlValidacion);
            if(count($Validacion) == 0){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function AgregarOpcionesCampo($idCampo,$Opciones){
            $db = new DB();
            foreach($Opciones as $Opcion){
                $Prioridad = $Opcion["Prioridad"];
                $Texto = $Opcion["Opcion"];
                $Seleccionado = $Opcion["Seleccionado"];
                $SqlInsert = "insert into opciones_campos_reclutamiento (id_campo,Prioridad,Nombre,Seleccionado) values ('".$idCampo."','".$Prioridad."','".$Texto."','".$Seleccionado."')";
                $Insert = $db->query($SqlInsert);
            }
        }
        function getOrdenCampos($idContenedor){
            $ToReturn = array();
            $db = new DB();
            $SqlOrden = "select
                            campos_reclutamiento.*,
                            orden_campos_reclutamiento.Anchura
                        from
                            campos_reclutamiento
                                inner join orden_campos_reclutamiento on orden_campos_reclutamiento.id_campo = campos_reclutamiento.id
                        where
                            campos_reclutamiento.id_contenedor = '".$idContenedor."'
                        order by
                            Prioridad ASC";
            $Orden = $db->select($SqlOrden);
            foreach($Orden as $Campo){
                $ArrayTmp = array();
                $ArrayTmp["idCampo"] = $Campo["id"];
                $ArrayTmp["Codigo"] = $Campo["Codigo"];
                $ArrayTmp["Titulo"] = $Campo["Titulo"];
                $ArrayTmp["ValorEjemplo"] = $Campo["ValorEjemplo"];
                $ArrayTmp["ValorPredeterminado"] = $Campo["ValorPredeterminado"];
                $ArrayTmp["Tipo"] = $Campo["Tipo"];
                $ArrayTmp["Dinamico"] = $Campo["Dinamico"];
                $ArrayTmp["Mandatorio"] = $Campo["Mandatorio"];
                $ArrayTmp["Deshabilitado"] = $Campo["Deshabilitado"];
                $ArrayTmp["Anchura"] = $Campo["Anchura"];
                $ArrayTmp["CampoDB"] = $Campo["CampoDB"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function getOpcionesCampo($idCampo){
            $ToReturn = array();
            $db = new DB();
            $SqlOpciones = "select
                            opciones_campos_reclutamiento.*
                        from
                            opciones_campos_reclutamiento
                        where
                        opciones_campos_reclutamiento.id_campo = '".$idCampo."'
                        order by
                            Prioridad ASC";
            $Opciones = $db->select($SqlOpciones);
            foreach($Opciones as $Opcion){
                $ArrayTmp = array();
                $ArrayTmp["Nombre"] = utf8_encode($Opcion["Nombre"]);
                $ArrayTmp["Seleccionado"] = $Opcion["Seleccionado"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function CamposEstaticos(){
            $db = new DB();
            $SqlCamposRespuestas = "select
                                        campos_reclutamiento.Codigo,
                                        campos_reclutamiento.Tipo,
                                        campos_reclutamiento.CampoDB
                                    from
                                        campos_reclutamiento
                                    where
                                        campos_reclutamiento.Dinamico='0'";
            $CamposRespuestas = $db->select($SqlCamposRespuestas);
            return $CamposRespuestas;
        }
        function RespuestasCamposDinamicos(){
            $db = new DB();
            $SqlCamposRespuestas = "select
                                        campos_reclutamiento.Codigo,
                                        campos_reclutamiento.Tipo,
                                        respuestas_campos_reclutamiento.Valor
                                    from
                                        campos_reclutamiento
                                            left join respuestas_campos_reclutamiento on respuestas_campos_reclutamiento.id_campo = campos_reclutamiento.id
                                    where
                                        respuestas_campos_reclutamiento.id_usuario='".$_SESSION["idUsuario_reclutamiento"]."' and
                                        campos_reclutamiento.Dinamico='1'";
            $CamposRespuestas = $db->select($SqlCamposRespuestas);
            return $CamposRespuestas;
        }
        function EliminarRespuestasCampos(){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlDelete = "delete from respuestas_campos_reclutamiento where id_usuario='".$_SESSION["idUsuario_reclutamiento"]."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function RegistrarRespuestasCampos($Codigo,$Valor){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $Campo = $this->getCampoFromCodigo($Codigo);
            $Campo = $Campo[0];
            $SqlInsert = "insert into respuestas_campos_reclutamiento (id_campo,id_usuario,Valor) values('".$Campo["id"]."','".$_SESSION["idUsuario_reclutamiento"]."','".$Valor."')";
            $Insert = $db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getCampoFromCodigo($Codigo){
            $db = new DB();
            $SqlCampo = "select * from campos_reclutamiento where Codigo='".$Codigo."'";
            $Campo = $db->select($SqlCampo);
            return $Campo;
        }
        function ActualizarCampoEstatico($Campo,$Valor){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlUpdate = "update datos_generales_reclutamiento set ".$Campo."='".$Valor."' where IdUsuarioReclutamiento = '".$_SESSION["idUsuario_reclutamiento"]."'";
            $Update = $db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getCamposSinOrden($idContenedor){
            $db = new DB();
            $SqlCampos = "select
                                campos_reclutamiento.id,
                                campos_reclutamiento.Codigo,
                                tipos_campos_reclutamiento.Nombre as Tipo
                            from
                                campos_reclutamiento
                                    left join orden_campos_reclutamiento on orden_campos_reclutamiento.id_campo = campos_reclutamiento.id
                                    inner join tipos_campos_reclutamiento on tipos_campos_reclutamiento.id = campos_reclutamiento.Tipo
                            where
                                orden_campos_reclutamiento.id is null and
                                campos_reclutamiento.id_contenedor='".$idContenedor."'";
            $Campos = $db->select($SqlCampos);
            return $Campos;
        }
        function getCamposConOrden($idContenedor){
            $db = new DB();
            $WhereContenedor = $idContenedor != "" ? "where campos_reclutamiento.id_contenedor='".$idContenedor."'" : "";
            $SqlCampos = "select
                                campos_reclutamiento.id,
                                campos_reclutamiento.Codigo,
                                tipos_campos_reclutamiento.Nombre as Tipo,
                                orden_campos_reclutamiento.Anchura
                            from
                                campos_reclutamiento
                                    inner join orden_campos_reclutamiento on orden_campos_reclutamiento.id_campo = campos_reclutamiento.id
                                    inner join tipos_campos_reclutamiento on tipos_campos_reclutamiento.id = campos_reclutamiento.Tipo
                            ".$WhereContenedor."
                            order by
                                orden_campos_reclutamiento.Prioridad";
            $Campos = $db->select($SqlCampos);
            return $Campos;
        }
        function deleteOrdenCampos($idContenedor){
            $db = new DB();
            $SqlDelete = "delete from orden_campos_reclutamiento where id_campo in (select id from campos_reclutamiento where id_contenedor='".$idContenedor."')";
            $Delete = $db->query($SqlDelete);
        }
        function agregarOrdenCampos($Campos){
            $db = new DB();
            $Cont = 1;
            foreach($Campos as $Campo){
                $Anchura = $Campo["Anchura"];
                $idCampo = $Campo["Campo"];
                $SqlInsert = "insert into orden_campos_reclutamiento (Prioridad,id_campo,Anchura) values ('".$Cont."','".$idCampo."','".$Anchura."')";
                $Insert = $db->query($SqlInsert);
                $Cont++;
            }
        }
        function getOrdenNotasAspirantesExcelTableList(){
            $db = new DB();
            $SqlOrdenTableList = "select
                                    campos_reclutamiento.id as idCampo,
                                    campos_reclutamiento.Codigo as Campo,
                                    campos_reclutamiento.Dinamico,
                                    campos_reclutamiento.CampoDB,
                                    orden_notas_aspirantes_excel_reclutamiento.id,
                                    orden_notas_aspirantes_excel_reclutamiento.Prioridad,
                                    orden_notas_aspirantes_excel_reclutamiento.Titulo
                                from
                                    orden_notas_aspirantes_excel_reclutamiento
                                        inner join campos_reclutamiento on campos_reclutamiento.id = orden_notas_aspirantes_excel_reclutamiento.id_campo
                                order by
                                    orden_notas_aspirantes_excel_reclutamiento.Prioridad ASC";
            $OrdenTableList = $db->select($SqlOrdenTableList);
            return $OrdenTableList;
        }
        function updatePrioridadOrdenNotasAspirantesExcel($Value,$ID){
            $ToReturn = array();
            $db = new DB();
            $SqlUpdate = "update orden_notas_aspirantes_excel_reclutamiento set Prioridad='".$Value."' where id='".$ID."'";
            $Update = $db->query($SqlUpdate);
            if($Update){
                $ToReturn['result'] = true;
            }else{
                $ToReturn['result'] = false;
            }
            return $ToReturn;
        }
        function deleteOrdenNotasAspirantesExcel($ID){
            $ToReturn = array();
            $db = new DB();
            $SqlDelete = "delete from orden_notas_aspirantes_excel_reclutamiento where id='".$ID."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn['result'] = true;
            }else{
                $ToReturn['result'] = false;
            }
            return $ToReturn;
        }
        function getCamposConOrdenNoSeleccionado(){
            $db = new DB();
            $SqlCampos = "select
                                campos_reclutamiento.id,
                                campos_reclutamiento.Codigo
                            from
                                campos_reclutamiento
                                    inner join orden_campos_reclutamiento on orden_campos_reclutamiento.id_campo = campos_reclutamiento.id
                                    left join orden_notas_aspirantes_excel_reclutamiento on orden_notas_aspirantes_excel_reclutamiento.id_campo = campos_reclutamiento.id
                            where
                                orden_notas_aspirantes_excel_reclutamiento.id is null
                            order by
                                campos_reclutamiento.Codigo";
            $Campos = $db->select($SqlCampos);
            return $Campos;
        }
        function agregarOrdenNotasAspirantesExcel($Titulo,$Campo){
            $ToReturn = array();
            $db = new DB();
            $SqlInsert = "insert into orden_notas_aspirantes_excel_reclutamiento (id_campo,Titulo) values ('".$Campo."','".$Titulo."')";
            $Insert = $db->query($SqlInsert);
            if($Insert){
                $ToReturn['result'] = true;
            }else{
                $ToReturn['result'] = false;
            }
            return $ToReturn;
        }
        function RespuestaCampoDinamicoByUsuarioAndCampo($idUsuario,$idCampo){
            $db = new DB();
            $ToReturn = "";
            $SqlCamposRespuesta = "select
                                        respuestas_campos_reclutamiento.Valor
                                    from
                                        respuestas_campos_reclutamiento
                                    where
                                        respuestas_campos_reclutamiento.id_usuario='".$idUsuario."' and
                                        respuestas_campos_reclutamiento.id_campo='".$idCampo."'";
            $CampoRespuesta = $db->select($SqlCamposRespuesta);
            $ToReturn = count($CampoRespuesta) > 0 ? $CampoRespuesta[0]["Valor"] : "";
            return $ToReturn;
        }
    }
?>