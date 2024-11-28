<?php
    class Personal{
        public $id;
        public $Username;
        public $Name;
        public $Rut;
        public $Birthday;
        public $Gender;

        public $startDate;
        public $endDate;
        public $Cartera;

        public $Id_Usuario;
        public $Id_Mandante;
        public $Id_Cedente;

        function __construct(){
            $this->Id_Usuario = $_SESSION['id_usuario'];
            $this->Id_Mandante = $_SESSION['mandante'];
            $this->Id_Cedente = $_SESSION['cedente'];
        }
        function getPersonal(){
            $db = new Db();
            $SqlPersonal = "select * from Personal where Id_Personal = '".$this->id."'";
		    $Personals = $db -> select($SqlPersonal);
            return $Personals;
        }
        function getPersonalList(){
            $db = new Db();
            //$SqlPersonal = "select * from Personal order by Nombre";
            //$SqlPersonal = "select distinct Personal.* from Personal inner join grabacion_2 on grabacion_2.Usuario = Personal.Nombre_Usuario where grabacion_2.Fecha BETWEEN '".$this->startDate."' and '".$this->endDate."' and grabacion_2.Cartera = '".$this->Cartera."' order by Personal.Nombre";
            $SqlPersonal = "select distinct Personal.* from Personal inner join grabacion_2 on grabacion_2.Usuario = Personal.Nombre_Usuario inner join Cedente on Cedente.Nombre_Cedente = grabacion_2.Cartera inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente where grabacion_2.Fecha BETWEEN '".$this->startDate."' and '".$this->endDate."' and mandante_cedente.Id_Mandante = '".$this->Id_Mandante."' order by Personal.Nombre";
		    $Personals = $db -> select($SqlPersonal);
            return $Personals;
        }
        function getPersonalIDFromUsername(){
            $db = new Db();
            $SqlPersonal = "select Id_Personal from Personal where Nombre_Usuario = '".$this->Username."'";
		    $Personals = $db -> select($SqlPersonal);
            return $Personals[0]["Id_Personal"];
        }
        function getPersonalListEvaluadas(){
            /*$fechaDesde = new DateTime();
            $fechaDesde->modify('first day of this month');
            $Desde = $fechaDesde->format('Ymd');
            $fechaHasta = new DateTime();
            $fechaHasta->modify('last day of this month');
            $Hasta = $fechaHasta->format('Ymd');*/
            $db = new Db();
            //$SqlPersonal = "select * from Personal order by Nombre";
            //$SqlPersonal = "select distinct Personal.* from Personal inner join grabacion_2 on grabacion_2.Usuario = Personal.Nombre_Usuario inner join evaluaciones on evaluaciones.Id_Grabacion = grabacion_2.id where grabacion_2.Fecha BETWEEN '".$Desde."' and '".$Hasta."' and grabacion_2.Cartera = '".$this->Cartera."' order by Personal.Nombre";
            //$SqlPersonal = "select distinct Personal.* from Personal inner join grabacion_2 on grabacion_2.Usuario = Personal.Nombre_Usuario inner join evaluaciones on evaluaciones.Id_Grabacion = grabacion_2.id where grabacion_2.Fecha BETWEEN '20170301' and '".$Hasta."' and grabacion_2.Cartera = '".$this->Cartera."' order by Personal.Nombre";
            $SqlPersonal = "select distinct Personal.* from Personal inner join evaluaciones on evaluaciones.Id_Personal = Personal.Id_Personal inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente where mandante_cedente.Id_Mandante='".$this->Id_Mandante."' and Personal.Activo='1' order by Personal.Nombre";
		    $Personals = $db -> select($SqlPersonal);
            return $Personals;
        }
        function getPersonalListCierres($Month){
            $db = new Db();
            $Desde = date('Ymd',strtotime($Month));
            $Hasta = date('Ymt',strtotime($Desde));
            //$SqlPersonal = "select distinct Personal.* from Personal inner join cierre_evaluaciones on cierre_evaluaciones.Id_Personal = Personal.Id_Personal where cierre_evaluaciones.Id_Usuario = '".$this->Id_Usuario."' and cierre_evaluaciones.Id_Cedente = '".$this->Id_Cedente."' and cierre_evaluaciones.fecha BETWEEN '".$this->startDate."' and '".$this->endDate."'";
            $SqlPersonal = "select distinct Personal.* from Personal inner join cierre_evaluaciones on cierre_evaluaciones.Id_Personal = Personal.Id_Personal where cierre_evaluaciones.Id_Usuario = '".$this->Id_Usuario."' and cierre_evaluaciones.Id_Mandante = '".$this->Id_Mandante."' and cierre_evaluaciones.fecha BETWEEN '".$Desde."' and '".$Hasta."' order by Personal.Nombre";
		    $Personals = $db -> select($SqlPersonal);
            return $Personals;
        }
        function getPersonalEjecutivos($idCedente = ""){
            $db = new Db();

            $WhereEmpresa = $idCedente != "" ? " where mandante_cedente.Id_Mandante = '".$idCedente."'" : "";

            //$SqlPersonal = "select Personal.* from Personal inner join Usuarios on Usuarios.usuario = Personal.Nombre_Usuario where Usuarios.nivel='4' ".$WhereEmpresa." order by Personal.Nombre";
            $SqlPersonal = "SELECT DISTINCT Personal.* FROM Personal inner join evaluaciones on evaluaciones.Id_Personal = Personal.Id_Personal INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente ".$WhereEmpresa." order by Personal.Nombre";
		    $Personals = $db -> select($SqlPersonal);
            return $Personals;
        }

        function getHumanComparison_EstadoCivil($Mandante,$Pauta,$Ejecutivo){
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";
            $ToReturn = "";
            $db = new DB();
            $SqlEstadoCivil = "SELECT CONCAT(( ROUND((((SELECT COUNT(*) FROM Personal WHERE id_estado ='2' and Id_Personal IN (SELECT Id_Personal FROM evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor WHERE 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")) / NULLIF((SELECT DISTINCT COUNT(*) FROM Personal WHERE Id_Personal IN (SELECT Id_Personal FROM evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor WHERE 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.") ), 0)) * 100),2)),'|%') AS PromedioEstadoCivil";
            $EstadoCivil = $db ->select($SqlEstadoCivil);
            $ToReturn = $EstadoCivil[0]["PromedioEstadoCivil"];
            return $ToReturn;
        }
        function getHumanComparison_Cargas($Mandante,$Pauta,$Ejecutivo){
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";
            $ToReturn = "";
            $db = new DB();
            $SqlCarga = "select CONCAT(AVG(hijos),'|','Cargas') as Cargas from Personal where Id_Personal in (select Id_Personal from evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")";
            $Carga = $db ->select($SqlCarga);
            $ToReturn = $Carga[0]["Cargas"];
            return $ToReturn;
        }
        function getHumanComparison_Sexo($Mandante,$Pauta,$Ejecutivo){
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";
            $ToReturn = "";
            $db = new DB();
            $SqlSexo = "select CONCAT((ROUND((((select COUNT(*) from Personal where id_sexo='1' and Id_Personal in (select Id_Personal from evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")) / NULLIF((select distinct count(*) from Personal where Id_Personal in (select Id_Personal from evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.") ), 0)) * 100),2)),'|%') as PromedioSexo";
            $Sexo = $db ->select($SqlSexo);
            $ToReturn = $Sexo[0]["PromedioSexo"];
            return $ToReturn;
        }
        function getHumanComparison_Nacionalidad($Mandante,$Pauta,$Ejecutivo){
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";
            $ToReturn = "";
            $db = new DB();
            $SqlNacionalidad = "select CONCAT((ROUND((((select COUNT(*) from Personal where id_nacionalidad='1' and Id_Personal in (select Id_Personal from evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante = '".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")) / NULLIF((select distinct count(*) from Personal where Id_Personal in (select Id_Personal from evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante = '".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.") ), 0)) * 100),2)),'|%') as PromedioNacionalidad";
            $Nacionalidad = $db ->select($SqlNacionalidad);
            $ToReturn = $Nacionalidad[0]["PromedioNacionalidad"];
            return $ToReturn;
        }
        function getHumanComparison_Tipo_Ejecutivo($Mandante,$Pauta,$Ejecutivo){
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";
            $ToReturn = "";
            $db = new DB();
            $SqlTipo_Ejecutivo = "select CONCAT((ROUND((((select COUNT(*) from Personal where id_tipo_ejecutivo='1' and Id_Personal in (select Id_Personal from evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")) / NULLIF((select distinct count(*) from Personal where Id_Personal in (select Id_Personal from evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.") ), 0)) * 100),2)),'|%') as PromedioTipo_Ejecutivo";
            $Tipo_Ejecutivo = $db ->select($SqlTipo_Ejecutivo);
            $ToReturn = $Tipo_Ejecutivo[0]["PromedioTipo_Ejecutivo"];
            return $ToReturn;
        }
        function getHumanComparison_Tipo_Contrato_PlazoFijo($Mandante,$Pauta,$Ejecutivo){
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";
            $ToReturn = "";
            $db = new DB();
            $SqlTipo_Contrato = "select CONCAT((ROUND((((select COUNT(*) from Personal where id_contrato='1' and Id_Personal in (select Id_Personal from evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")) / NULLIF((select distinct count(*) from Personal where Id_Personal in (select Id_Personal from evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.") ), 0)) * 100),2)),'|%') as PromedioTipo_Contrato";
            $Tipo_Contrato = $db ->select($SqlTipo_Contrato);
            $ToReturn = $Tipo_Contrato[0]["PromedioTipo_Contrato"];
            return $ToReturn;
        }
        function getHumanComparison_Tipo_Contrato_Indefinido($Mandante,$Pauta,$Ejecutivo){
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";
            $ToReturn = "";
            $db = new DB();
            $SqlTipo_Contrato = "select CONCAT((ROUND((((select COUNT(*) from Personal where id_contrato='2' and Id_Personal in (select Id_Personal from evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")) / NULLIF((select distinct count(*) from Personal where Id_Personal in (select Id_Personal from evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.") ), 0)) * 100),2)),'|%') as PromedioTipo_Contrato";
            $Tipo_Contrato = $db ->select($SqlTipo_Contrato);
            $ToReturn = $Tipo_Contrato[0]["PromedioTipo_Contrato"];
            return $ToReturn;
        }
        function getHumanComparison_Edad($Mandante,$Pauta,$Ejecutivo){
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";
            $ToReturn = "";
            $db = new DB();
            $SqlEdad = "select CONCAT(ROUND(AVG(TIMESTAMPDIFF(YEAR, Fecha_Nacimiento, DATE_FORMAT(NOW(),'%Y-%m-%d'))),2),'|','años') as Edad from Personal where Id_Personal in (select Id_Personal from evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")";
            $Edad = $db ->select($SqlEdad);
            $ToReturn = $Edad[0]["Edad"];
            return $ToReturn;
        }
        function getHumanComparison_Antiguedad($Mandante,$Pauta,$Ejecutivo){
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";
            $ToReturn = "";
            $db = new DB();
            $SqlAntiguedad = "select CONCAT(ROUND(AVG(TIMESTAMPDIFF(MONTH, fecha_ingreso, DATE_FORMAT(NOW(),'%Y-%m-%d'))),2),'|','Meses') as Antiguedad from Personal where Id_Personal in (select Id_Personal from evaluaciones INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")";
            $Antiguedad = $db ->select($SqlAntiguedad);
            $ToReturn = $Antiguedad[0]["Antiguedad"];
            return $ToReturn;
        }
        function getHumanComparison_Rotacion($Mandante,$Pauta,$Ejecutivo){
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";
            $ToReturn = "";
            $db = new DB();

            $SqlActivos = "select count(*) as Activos from Personal where fecha_termino <= DATE_FORMAT('1970-01-01','%Y-%m-%d') and Id_Personal in (select Id_Personal from evaluaciones inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")";
            $Activos = $db ->select($SqlActivos);
            $Activos = $Activos[0]["Activos"];

            $SqlDespedidos = "select count(*) as Despedidos from Personal where fecha_termino > DATE_FORMAT('1970-01-01','%Y-%m-%d') and id_estatus_egreso='1' and Id_Personal in (select Id_Personal from evaluaciones inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")";
            $Despedidos = $db ->select($SqlDespedidos);
            $Despedidos = $Despedidos[0]["Despedidos"];

            $SqlRenuncia = "select count(*) as Renuncia from Personal where fecha_termino > DATE_FORMAT('1970-01-01','%Y-%m-%d') and id_estatus_egreso='2' and Id_Personal in (select Id_Personal from evaluaciones inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")";
            $Renuncia = $db ->select($SqlRenuncia);
            $Renuncia = $Renuncia[0]["Renuncia"];

            $TotalEgresados = $Despedidos + $Renuncia;

            if($Activos != 0){
                $ToReturn = (($TotalEgresados * 100) / $Activos)."|%";
            }else{
                $ToReturn = "0|%";
            }

            return $ToReturn;
        }
        function getHumanComparison_Despedidos($Mandante,$Pauta,$Ejecutivo){
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";
            $ToReturn = "";
            $db = new DB();

            $SqlActivos = "select count(*) as Activos from Personal where fecha_termino <= DATE_FORMAT('1970-01-01','%Y-%m-%d') and Id_Personal in (select Id_Personal from evaluaciones inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")";
            $Activos = $db ->select($SqlActivos);
            $Activos = $Activos[0]["Activos"];

            $SqlDespedidos = "select count(*) as Despedidos from Personal where fecha_termino > DATE_FORMAT('1970-01-01','%Y-%m-%d') and id_estatus_egreso='1' and Id_Personal in (select Id_Personal from evaluaciones inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")";
            $Despedidos = $db ->select($SqlDespedidos);
            $Despedidos = $Despedidos[0]["Despedidos"];

            if($Activos != 0){
                $ToReturn = (($Despedidos * 100) / $Activos)."|%";
            }else{
                $ToReturn = "0|%";
            }

            return $ToReturn;
        }
        function getHumanComparison_Renuncia($Mandante,$Pauta,$Ejecutivo){
            $WherePauta = $Pauta != "" ? " and contenedor_competencias_calidad.id='".$Pauta."'" : "";
            $WhereEjecutivo = $Ejecutivo != "" ? " and Id_Personal = '".$Ejecutivo."'" : "";
            $ToReturn = "";
            $db = new DB();

            $SqlActivos = "select count(*) as Activos from Personal where fecha_termino <= DATE_FORMAT('1970-01-01','%Y-%m-%d') and Id_Personal in (select Id_Personal from evaluaciones inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")";
            $Activos = $db ->select($SqlActivos);
            $Activos = $Activos[0]["Activos"];

            $SqlRenuncia = "select count(*) as Renuncia from Personal where fecha_termino > DATE_FORMAT('1970-01-01','%Y-%m-%d') and id_estatus_egreso='2' and Id_Personal in (select Id_Personal from evaluaciones inner join mandante_cedente on mandante_cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad_Cedente on contenedor_competencias_calidad_Cedente.Id_Cedente = evaluaciones.Id_Cedente INNER JOIN contenedor_competencias_calidad on contenedor_competencias_calidad.id = contenedor_competencias_calidad_Cedente.id_contenedor where 1=1 and mandante_cedente.Id_Mandante='".$Mandante."' ".$WhereEjecutivo." ".$WherePauta.")";
            $Renuncia = $db ->select($SqlRenuncia);
            $Renuncia = $Renuncia[0]["Renuncia"];

            if($Activos != 0){
                $ToReturn = (($Renuncia * 100) / $Activos)."|%";
            }else{
                $ToReturn = "0|%";
            }
            return $ToReturn;
        }
    }
?>