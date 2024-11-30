<?php

include_once(__DIR__.'/../../vendor/autoload.php');
include_once(__DIR__.'/../db/DB.php');
include_once(__DIR__.'/../logs.php');
include_once(__DIR__.'/../../includes/functions/Functions.php');
ini_set('memory_limit', '1024M');

use League\Csv\Writer;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Reporte
{
    public $Id_Usuario;
    public $Id_Mandante;
    public $Id_Cedente;
    private $db;
    private $logs;

    function __construct()
    {
        $this->Id_Usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario']: "";
        $this->Id_Mandante = isset($_SESSION['mandante']) ? $_SESSION['mandante'] : "";
        $this->Id_Cedente = isset($_SESSION['cedente']) ? $_SESSION['cedente']: "";
        $this->db = new Db();
        $this->logs = new Logs();
    }
        
    function getCedentes()
    {
        //$db = new Db();
        $SqlCedentes = "select Cedente.Id_Cedente as idCedente, Cedente.Nombre_Cedente as Nombre from Cedente inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedente.Id_Cedente inner join mandante on mandante_cedente.Id_Mandante = mandante.id where mandante.id='".$this->Id_Mandante."' order by Cedente.Nombre_Cedente";
        $Cedentes = $this->db->select($SqlCedentes);
        return $Cedentes;
    }
    
    function getCarteraField()
    {
        //$db = new Db();
        $SqlCarteras = "select distinct CARTERA as Cartera from Deuda inner join mandante_cedente on mandante_cedente.Id_Cedente = Deuda.Id_Cedente where Id_Mandante='".$this->Id_Mandante."' order by CARTERA";
        $Carteras = $this->db->select($SqlCarteras);
        return $Carteras;
    }
    
    function getTramoField()
    {
        //$db = new Db();
        $SqlTramos = "select distinct Tramo_Dias_Mora as Tramo from Deuda inner join mandante_cedente on mandante_cedente.Id_Cedente = Deuda.Id_Cedente where Id_Mandante='".$this->Id_Mandante."' order by Tramo_Dias_Mora";
        $Tramos = $this->db->select($SqlTramos);
        return $Tramos;
    }

    function getPeriodosMandante()
    {
        //$db = new Db();
        $SqlPeriodos = "select * from Periodo_Mandante where Mandante='".$this->Id_Mandante."' order by id DESC LIMIT 1";
        $Periodos = $this->db->select($SqlPeriodos);
        return $Periodos;
    }

    function getReportData($idCedente,$idPeriodo,$Cartera,$Tramo)
    {
        $WhereCedente = $idCedente != "" ? " and mandante_cedente.Id_Cedente='".$idCedente."' " : "";
        $WhereCartera = $Cartera != "" ? " and Deuda.CARTERA='".$Cartera."' " : "";
        $WhereTramo = $Tramo != "" ? " and Deuda.Tramo_Dias_Mora='".$Tramo."' " : "";
        //$db = new Db();
        $Periodo = $this->getDatesFromPeriodoMandante($idPeriodo);
        $Status = $this->getStatusGestiones($Periodo["startDate"],$Periodo["endDate"]);
        $SqlRuts = "SELECT 
                            rut_cliente,
                            status_name,
                            (SELECT SUM(Deuda) FROM Deuda inner join mandante_cedente on mandante_cedente.Id_Cedente = Deuda.Id_Cedente where Deuda > 0 and mandante_cedente.Id_Mandante='".$this->Id_Mandante."' and Rut=rut_cliente ".$WhereCedente." ".$WhereCartera." ".$WhereTramo.") as SumDeuda,
                            (SELECT count(*) FROM Deuda inner join mandante_cedente on mandante_cedente.Id_Cedente = Deuda.Id_Cedente where Deuda > 0 and mandante_cedente.Id_Mandante='".$this->Id_Mandante."' and Rut=rut_cliente ".$WhereCedente." ".$WhereCartera." ".$WhereTramo.") as CasosDeuda,
                            (SELECT SUM(Monto) from pagos_deudas where Rut=rut_cliente and Mandante in (select Id_Mandante from mandante_cedente where Id_Mandante='".$this->Id_Mandante."' ".$WhereCedente.") and Numero_Operacion in (select Numero_Operacion from Deuda	where Deuda > 0 and Id_Cedente in (select Id_Cedente from mandante_cedente where Id_Mandante='".$this->Id_Mandante."' ".$WhereCedente.") ".$WhereCartera." ".$WhereTramo." )) as SumRecupero,
                            (select count(*) from pagos_deudas where Rut=rut_cliente and Mandante in (select Id_Mandante from mandante_cedente where Id_Mandante='".$this->Id_Mandante."' ".$WhereCedente.") and Numero_Operacion in (select Numero_Operacion from Deuda	where Deuda > 0 and Id_Cedente in (select Id_Cedente from mandante_cedente where Id_Mandante='".$this->Id_Mandante."' ".$WhereCedente.") ".$WhereCartera." ".$WhereTramo." )) as CasosRecupero
                        FROM
                            (select
                                    gestion_ult_trimestre.rut_cliente,gestion_ult_trimestre.status_name, fechahora
                            from gestion_ult_trimestre
                                    inner join Cedentes_Listas on Cedentes_Listas.Id_Lista = gestion_ult_trimestre.cedente
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedentes_Listas.Id_Cedente
                                    inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                            where
                                    fecha_gestion BETWEEN '".$Periodo["startDate"]."' and '".$Periodo["endDate"]."' and
                                    mandante.id='".$this->Id_Mandante."'
                                    ".$WhereCedente."
                            GROUP BY gestion_ult_trimestre.rut_cliente,gestion_ult_trimestre.status_name,fechahora) tb1
                        GROUP BY rut_cliente,status_name
                        HAVING CasosDeuda > 0";
            if($WhereCartera != ""){
                //echo $SqlRuts;
            }
            $Ruts = $this->db->select($SqlRuts);
            //print_r($Status);
            foreach((array) $Ruts as $Rut){
                $ArrayTmp = array();
                $Status[strtoupper($Rut["status_name"])]["SumDeuda"] = $Status[strtoupper($Rut["status_name"])]["SumDeuda"] + $Rut["SumDeuda"];
                $Status[strtoupper($Rut["status_name"])]["CasosDeuda"] = $Status[strtoupper($Rut["status_name"])]["CasosDeuda"] + $Rut["CasosDeuda"];
                $Status[strtoupper($Rut["status_name"])]["SumRecupero"] = $Status[strtoupper($Rut["status_name"])]["SumRecupero"] + $Rut["SumRecupero"];
                $Status[strtoupper($Rut["status_name"])]["CasosRecupero"] = $Status[strtoupper($Rut["status_name"])]["CasosRecupero"] + $Rut["CasosRecupero"];
            }
            $ToReturn = array();
            $Cont = 1;
            $CasosDeuda = 0;
            $SumDeuda = 0;
            $SumRecupero = 0;
            $CasosRecupero = 0;
            $ToReturn[0]["Cont"] = $Cont;
            $ToReturn[0]["Estatus"] = "Sin Gestion";
            $ToReturn[0]["CasosDeuda"] = 0;
            $ToReturn[0]["SumDeuda"] = 0;
            $ToReturn[0]["SumRecupero"] = 0;
            $ToReturn[0]["CasosRecupero"] = 0;
            $ToReturn[0]["MontoRecuperoAvg"] = 0;
            $ToReturn[0]["CasosRecuperoAvg"] = 0;
            foreach($Status as $Tmp){
                $Cont++;
                $CasosDeuda += $Tmp["CasosDeuda"];
                $SumDeuda += $Tmp["SumDeuda"];
                $SumRecupero += $Tmp["SumRecupero"];
                $CasosRecupero += $Tmp["CasosRecupero"];
                $arrayTmp = array();
                $arrayTmp["Cont"] = $Cont;
                $arrayTmp["Estatus"] = $Tmp["Status"];
                $arrayTmp["CasosDeuda"] = $Tmp["CasosDeuda"];
                $arrayTmp["SumDeuda"] = $Tmp["SumDeuda"];
                $arrayTmp["SumRecupero"] = $Tmp["SumRecupero"];
                $arrayTmp["CasosRecupero"] = $Tmp["CasosRecupero"];
                $arrayTmp["MontoRecuperoAvg"] = $arrayTmp["SumDeuda"] > 0 ? round(($arrayTmp["SumRecupero"] / $arrayTmp["SumDeuda"]) * 100,2) : 0;
                $arrayTmp["CasosRecuperoAvg"] = $arrayTmp["CasosDeuda"] > 0 ? round(($arrayTmp["CasosRecupero"] / $arrayTmp["CasosDeuda"]) * 100,2) : 0;
                array_push($ToReturn,$arrayTmp);
            }
            $TotalGestion = $this->getTotalGestion($idCedente,$Cartera,$Tramo);
            $ToReturn[0]["CasosDeuda"] = $TotalGestion["CasosDeuda"] - $CasosDeuda;
            $ToReturn[0]["SumDeuda"] = $TotalGestion["SumaDeuda"] - $SumDeuda;
            $ToReturn[0]["SumRecupero"] = $TotalGestion["SumaRecupero"] - $SumRecupero;
            $ToReturn[0]["CasosRecupero"] = $TotalGestion["CasosRecupero"] - $CasosRecupero;
            $ToReturn[0]["MontoRecuperoAvg"] = $ToReturn[0]["SumDeuda"] > 0 ? round(($ToReturn[0]["SumRecupero"] / $ToReturn[0]["SumDeuda"]) * 100,2) : 0;
            $ToReturn[0]["CasosRecuperoAvg"] = $ToReturn[0]["CasosDeuda"] > 0 ? round(($ToReturn[0]["CasosRecupero"] / $ToReturn[0]["CasosDeuda"]) * 100,2) : 0;
            return $ToReturn;
        }
        function getStatusGestiones($startDate,$endDate){
            //$db = new Db();
            $ToReturn = array();
            $SqlStatus = "select
                                    gestion_ult_trimestre.status_name as Name
                            from gestion_ult_trimestre
                                    inner join Cedentes_Listas on Cedentes_Listas.Id_Lista = gestion_ult_trimestre.cedente
                                    inner join mandante_cedente on mandante_cedente.Id_Cedente = Cedentes_Listas.Id_Cedente
                                    inner join mandante on mandante.id = mandante_cedente.Id_Mandante
                            where
                                    fecha_gestion BETWEEN '".$startDate."' and '".$endDate."' and
                                    mandante.id='".$this->Id_Mandante."'
                                    GROUP BY gestion_ult_trimestre.status_name
                            ORDER BY gestion_ult_trimestre.status_name";
            $Status = $this->db->select($SqlStatus);
            foreach((array) $Status as $Name){
                $ToReturn[strtoupper($Name["Name"])]["Status"] = $Name["Name"];
                $ToReturn[strtoupper($Name["Name"])]["SumDeuda"] = 0;
                $ToReturn[strtoupper($Name["Name"])]["CasosDeuda"] = 0;
                $ToReturn[strtoupper($Name["Name"])]["SumRecupero"] = 0;
                $ToReturn[strtoupper($Name["Name"])]["CasosRecupero"] = 0;
            }
            return $ToReturn;
        }
        function getDatesFromPeriodoMandante($idPeriodo){
            $ToReturn = array();
            //$db = new Db();
            $SqlPeriodo = "select * from Periodo_Mandante where Mandante='".$this->Id_Mandante."' and ID='".$idPeriodo."'";
            $Periodo = $this->db->select($SqlPeriodo);
            $ToReturn["startDate"] = $Periodo[0]["Fecha_Inicio"];
            $ToReturn["endDate"] = $Periodo[0]["Fecha_Termino"] == "0000-00-00" ? date("Y-m-d") : $Periodo[0]["Fecha_Termino"];
            return $ToReturn;
        }
        function getTotalGestion($idCedente,$Cartera,$Tramo){
            $WhereCedente = $idCedente != "" ? " and mandante_cedente.Id_Cedente='".$idCedente."' " : "";
            $WhereCartera = $Cartera != "" ? " and Deuda.CARTERA='".$Cartera."' " : "";
            $WhereTramo = $Tramo != "" ? " and Deuda.Tramo_Dias_Mora='".$Tramo."' " : "";
            $ToReturn = array();
            //$db = new Db();
            $SqlTotalDeuda = "select SUM(Deuda) as Total, count(*) as Cantidad from Deuda where Deuda > 0 and Id_Cedente in (select Id_Cedente from mandante_cedente where Id_Mandante='".$this->Id_Mandante."' ".$WhereCedente." ".$WhereCartera." ".$WhereTramo.")";
            $TotalDeuda = $this->db->select($SqlTotalDeuda);
            $TotalSumaDeuda = $TotalDeuda[0]["Total"];
            $TotalCasosDeuda = $TotalDeuda[0]["Cantidad"];

            $SqlTotalRecupero = "select SUM(Monto) as Total, count(*) as Cantidad from pagos_deudas where Mandante in (select Id_Mandante from mandante_cedente where Id_Mandante='".$this->Id_Mandante."' ".$WhereCedente.") and Numero_Operacion in (select Numero_Operacion from Deuda where Deuda > 0 and Id_Cedente in (select Id_Cedente from mandante_cedente where Id_Mandante='".$this->Id_Mandante."' ".$WhereCedente.") ".$WhereCartera." ".$WhereTramo." )";
            $TotalRecupero = $this->db->select($SqlTotalRecupero);
            $TotalSumaRecupero = $TotalRecupero[0]["Total"];
            $TotalCasosRecupero = $TotalRecupero[0]["Cantidad"];
            $ToReturn["SumaDeuda"] = $TotalSumaDeuda;
            $ToReturn["CasosDeuda"] = $TotalCasosDeuda;
            $ToReturn["SumaRecupero"] = $TotalSumaRecupero;
            $ToReturn["CasosRecupero"] = $TotalCasosRecupero;

            return $ToReturn;
        }
        function getCantidadTipoGestion($datos){
            //$db = new Db();
            $segmento = $datos['segmento'];
            // aquiiiiiiiiiiiiiiiiiii
            $sqlCantidadGestiones = "SELECT count(g.Id_TipoGestion) as cantidad , g.Id_TipoGestion, t.Nombre  FROM gestion_ult_trimestre g , Tipo_Contacto t WHERE g.rut_cliente IN (Select Rut FROM Deuda WHERE Id_Cedente = 47 and Segmento = '".$segmento."') AND g.cedente = 47  and t.Id_TipoContacto = g.Id_TipoGestion group by g.Id_TipoGestion, t.Nombre";
            $totalCantidadGestiones = $this->db->select($sqlCantidadGestiones);
            return $totalCantidadGestiones;
        }
        function getCantidadGestionNivel1($datos){
            //$db = new Db();
            $idCedente = $datos['idCedente'];
            $fechaInicio = $datos['fechaInicio'];
            $fechaFin = $datos['fechaFin'];
            $idTipoGestion = $datos['idTipoGestion'];
            $sqlCantidadGestiones = "SELECT n.Respuesta_N1, COUNT(n.Respuesta_N1) as cantidad, n.Id FROM gestion_ult_semestre_respaldo g, Nivel1 n WHERE g.cedente = '".$idCedente."' and g.fecha_gestion BETWEEN '".$fechaInicio."' and '".$fechaFin."' and g.Id_TipoGestion = '".$idTipoGestion."' and n.Id = g.resultado GROUP BY n.Respuesta_N1, n.Id";
            $totalCantidadGestiones = $this->db->select($sqlCantidadGestiones);
            return $totalCantidadGestiones;
        }
        function getCantidadGestionNivel2($datos){
            //$db = new Db();
            $idCedente = $datos['idCedente'];
            $fechaInicio = $datos['fechaInicio'];
            $fechaFin = $datos['fechaFin'];
            $idTipoGestion = $datos['idTipoGestion'];
            $idNivel1 = $datos['idNivel1'];
            $sqlCantidadGestiones = "SELECT n2.Respuesta_N2, COUNT(n2.Respuesta_N2) as cantidad, n2.id FROM gestion_ult_semestre_respaldo g, Nivel2 n2 WHERE g.cedente =  '".$idCedente."' AND g.fecha_gestion BETWEEN  '".$fechaInicio."' AND  '".$fechaFin."' AND g.resultado =  '".$idNivel1."' AND g.Id_TipoGestion =  '".$idTipoGestion."' AND g.resultado_n2 = n2.id GROUP BY n2.Respuesta_N2, n2.id";
            $totalCantidadGestiones = $this->db->select($sqlCantidadGestiones);
            return $totalCantidadGestiones;
        }
        function getCantidadGestionNivel3($datos){
            //$db = new Db();
            $idCedente = $datos['idCedente'];
            $fechaInicio = $datos['fechaInicio'];
            $fechaFin = $datos['fechaFin'];
            $idTipoGestion = $datos['idTipoGestion'];
            $idNivel2 = $datos['idNivel2'];
            $sqlCantidadGestiones = "SELECT n3.Respuesta_N3, COUNT(n3.Respuesta_N3) as cantidad, n3.id FROM gestion_ult_semestre_respaldo g, Nivel3 n3 WHERE g.cedente =  '".$idCedente."' AND g.fecha_gestion BETWEEN  '".$fechaInicio."' AND  '".$fechaFin."' AND g.resultado_n2 =  '".$idNivel2."' AND g.Id_TipoGestion =  '".$idTipoGestion."' AND g.resultado_n3 = n3.id GROUP BY n3.Respuesta_N3, n3.id"; 
            $totalCantidadGestiones = $this->db->select($sqlCantidadGestiones);
            return $totalCantidadGestiones;
        }
        function getDatosUltimaCarga(){
            //$db = new Db();            
            $sql = "SELECT * FROM Historico_Carga WHERE Id_Cedente = '".$this->Id_Cedente."' ORDER BY fecha DESC LIMIT 1"; 
            $registros = $this->db->select($sql);
            return $registros;
        }
        function getDatosDeudaCarga(){
            //$db = new Db();            
            $sql = "SELECT p.Nombre_Completo, d.* FROM Deuda d, Persona p WHERE d.Id_Cedente = '".$this->Id_Cedente."' and p.Rut = d.Rut"; 
            $deudas = $this->db->select($sql);
            return $deudas;
        }
        function getDeudaMes(){
            //$db = new Db();            
            $sql = "select YEAR(Fecha_Vencimiento) as year, MONTH(Fecha_Vencimiento) as month, SUM(Deuda) as monto from Deuda where Id_Cedente='".$this->Id_Cedente."' group by YEAR(Fecha_Vencimiento), MONTH(Fecha_Vencimiento) order by YEAR(Fecha_Vencimiento) DESC, MONTH(Fecha_Vencimiento) DESC LIMIT 1"; 
            $deudasMes = $this->db->select($sql);
            return $deudasMes;
        }
        function getTotalPorSegmento(){
            //$db = new Db();
            switch ($this->Id_Cedente) {
                case 47:
                    $campo = "Segmento";
                break;
                case 107:
                    $campo = "TipoMora";
                break;
                case 215:
                    $campo = "Tramo_Morosidad";
                break;
                default:
                    $campo = "Rut";
            }
            $sql = "SELECT SUM(Deuda) as total, ".$campo." FROM Deuda WHERE Id_Cedente = '".$this->Id_Cedente."' GROUP BY ".$campo."";
            $totalMontoSegmento = $this->db->select($sql);

            $totalesArray = array();
            foreach((array) $totalMontoSegmento as $registro){
                $Array = array();
                $Array['total'] = $registro["total"]; 
                $Array['segmento'] = $registro[$campo];
                array_push($totalesArray,$Array);
            }
            return $totalesArray;
        }
        function getCasosPorSegmento($datos){
            //$db = new Db();
            $segmento = $datos['segmento'];
            switch ($this->Id_Cedente) {
            case 47:
                $campo = "Segmento";
            break;
            case 107:
                $campo = "TipoMora";
            break;
            case 215:
                $campo = "Tramo_Morosidad";
            break;
            }
            $sql = "SELECT p.Nombre_Completo, SUM( d.Deuda ) AS total, COUNT(d.Numero_Factura) AS cantidadFactura, CASE WHEN SUM( d.Deuda ) > 5000 then 'Monto incidente' else 'Monto no incidente' end as marca FROM Deuda d, Persona p WHERE d.Id_Cedente = '".$this->Id_Cedente."' AND d.".$campo." = '".$segmento."' AND p.Rut = d.Rut GROUP BY d.Rut, p.Nombre_Completo ORDER BY total DESC";
            $totalCasosSegmento = $this->db->select($sql);
            return $totalCasosSegmento;
        }
        function getTotalCompromiso($datos){
            //$db = new Db();

            $segmento = $datos['segmento'];
            $campo = $datos['Campo'];

            $sql = "    SELECT  
                            SUM(monto_comp) as montoCompromiso 
                        FROM (  
                            SELECT 
                                * 
                            FROM (  
                                SELECT 
                                    * 
                                FROM 
                                    gestion_ult_trimestre 
                                WHERE 
                                    rut_cliente IN (
                                        SELECT 
                                            Rut 
                                        FROM 
                                            Deuda 
                                        WHERE 
                                            ".$campo." = '".$segmento."' 
                                        AND 
                                            Id_Cedente = '".$this->Id_Cedente."' 
                                    ) 
                                AND 
                                    cedente = '".$this->Id_Cedente."' 
                                AND Id_TipoGestion = '5') 
                            tb1 
                        GROUP BY rut_cliente) tb2";
            $sql2 = "SELECT SUM(Deuda) AS totalDeuda FROM Deuda WHERE Id_Cedente = '".$this->Id_Cedente."' and ".$campo." = '".$segmento."'";

            $sql3 = "   SELECT 
                            SUM(Deuda) AS montoMora, 
                            Nombre, Id_TipoContacto 
                        FROM 
                            Deuda 
                        INNER JOIN 
                            (   SELECT 
                                    * 
                                FROM (  
                                    SELECT 
                                        Deuda.Rut, 
                                        gestion_ult_trimestre.fechahora, 
                                        Tipo_Contacto.Nombre, 
                                        Tipo_Contacto.Id_TipoContacto 
                                    FROM 
                                        gestion_ult_trimestre 
                                    INNER JOIN 
                                        Deuda ON Deuda.Id_Cedente = gestion_ult_trimestre.cedente
                                    AND 
                                        Deuda.Rut = gestion_ult_trimestre.rut_cliente 
                                    INNER JOIN 
                                        Tipo_Contacto ON Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion 
                                    WHERE 
                                        Deuda.Id_Cedente = '".$this->Id_Cedente."' 
                                    AND 
                                        Deuda.".$campo." = '".$segmento."'
                                ) tb1 
                                GROUP BY 
                                    Rut, fechahora, Nombre, Id_TipoContacto
                            ) tb2 ON tb2.Rut = Deuda.Rut 
                        GROUP BY 
                            Nombre, Id_TipoContacto";

            $totalMontoCompromisoSegmento = $this->db->select($sql3);
            $totalDeudaMontoSegmento = $this->db->select($sql2);

            $montosArray = array();
            $totalMontoGestionadosPorSegmento = 0;
            foreach((array) $totalMontoCompromisoSegmento as $registro){
                $Array = array();
                $Array['nombre'] = utf8_encode($registro["Nombre"]);
                $Array['monto'] = $registro["montoMora"]; 
                $totalMontoGestionadosPorSegmento = $totalMontoGestionadosPorSegmento+$registro["montoMora"];
                $Array['porcentaje'] = round(((100 * $registro["montoMora"])/$totalDeudaMontoSegmento[0]["totalDeuda"]), 2);
                //$Array['idTipoContacto'] = $registro["Id_TipoGestion"];
                array_push($montosArray,$Array);
            }

            $totalMontoSinGestion =  $totalDeudaMontoSegmento[0]["totalDeuda"] - $totalMontoGestionadosPorSegmento;

            $Array['nombre'] = "Sin Gestion";
            $Array['monto'] = $totalMontoSinGestion; 
            if($totalDeudaMontoSegmento[0]["totalDeuda"] != 0){
                $Array['porcentaje'] = round(((100 * $totalMontoSinGestion)/$totalDeudaMontoSegmento[0]["totalDeuda"]), 2);
            }else{
                $Array['porcentaje'] = 0;
            }
            array_push($montosArray,$Array);


           /* $totalMontoCompromisoSegmento = $this->db->select($sql);
            $totalDeudaMontoSegmento = $this->db->select($sql2);

            $array = array();
            $array['montoCompromiso'] = $totalMontoCompromisoSegmento[0]["montoCompromiso"];
            $array['montoDeuda'] = $totalDeudaMontoSegmento[0]["totalDeuda"]; */
           
            return $montosArray;
        }

        function getTotalCasosCompromiso($datos){

            //$db = new Db();

            $segmento = $datos['segmento'];
            $campo = $datos['Campo'];

            $sql = "select count(*) as cantidad from (SELECT Rut FROM Deuda WHERE Id_Cedente = '".$this->Id_Cedente."' and ".$campo." = '".$segmento."' Group by Rut) tb1";
            $sql2 = "SELECT rut_cliente FROM gestion_ult_trimestre WHERE rut_cliente IN ( SELECT Rut FROM Deuda WHERE Id_Cedente  = '".$this->Id_Cedente."' AND ".$campo." ='".$segmento."' ) AND cedente ='".$this->Id_Cedente."' AND Id_TipoGestion =5 GROUP BY rut_cliente";

            $sql3 = "   SELECT 
                            Nombre, 
                            Cantidad 
                        FROM (  
                            SELECT 
                                Id_TipoGestion AS Gestion, 
                                COUNT(*) AS Cantidad 
                            FROM 
                                (   
                                SELECT 
                                    * 
                                FROM (  
                                    SELECT DISTINCT 
                                        gestion_ult_trimestre.id_gestion,
                                        gestion_ult_trimestre.rut_cliente,
                                        gestion_ult_trimestre.Id_TipoGestion
                                    FROM 
                                        gestion_ult_trimestre 
                                    INNER JOIN 
                                        Deuda ON Deuda.Id_Cedente = gestion_ult_trimestre.cedente 
                                    AND Deuda.Rut = gestion_ult_trimestre.rut_cliente 
                                    WHERE 
                                        Deuda.Id_Cedente = '".$this->Id_Cedente."' 
                                        AND Deuda.".$campo." = '".$segmento."') 
                                    tbOrdenadoFecha 
                                    GROUP BY 
                                        rut_cliente,
                                        id_gestion,
                                        Id_TipoGestion) 
                                tbAgrupadoRut 
                            GROUP BY Id_TipoGestion) 
                        tbAgrupadoTipoGestion 
                        INNER JOIN 
                            Tipo_Contacto ON Tipo_Contacto.Id_TipoContacto = tbAgrupadoTipoGestion.Gestion";
            $totalCasosPorTipoContacto = $this->db->select($sql3);
            $gestionArray = array();
            $totalCasosGestionadosPorSegmento = 0;

            $totalCasosSegmento = $this->db->select($sql); // con esto resto y saco casos sin gestionar    

            foreach((array) $totalCasosPorTipoContacto as $registro){
                $Array = array();
                $Array['nombre'] = utf8_encode($registro["Nombre"]);
                $Array['cantidad'] = $registro["Cantidad"]; 
                $totalCasosGestionadosPorSegmento = $totalCasosGestionadosPorSegmento+$registro["Cantidad"];

                if($totalCasosSegmento[0]["cantidad"] != 0){
                    $Array['porcentaje'] = round(((100 * $registro["Cantidad"])/$totalCasosSegmento[0]["cantidad"]), 2); 
                }else{
                    $Array['porcentaje'] = 0;
                } 
                //$Array['idTipoContacto'] = $registro["Id_TipoGestion"];
                array_push($gestionArray,$Array);
            }

            $totalCasosSegmento = $this->db->select($sql); // con esto resto y saco casos sin gestionar           
            $totalCasosSinGestion = $totalCasosSegmento[0]["cantidad"] - $totalCasosGestionadosPorSegmento;

            $Array['nombre'] = "Sin Gestion";
            $Array['cantidad'] = $totalCasosSinGestion; 
            if($totalCasosSegmento[0]["cantidad"] != 0){
                $Array['porcentaje'] = round(((100 * $totalCasosSinGestion)/$totalCasosSegmento[0]["cantidad"]), 2); 
            }else{
                $Array['porcentaje'] = 0;
            } 
            array_push($gestionArray,$Array);

           
            /*$totalCasosSegmento = $this->db->select($sql); // con esto resto y saco casos sin gestionar
            $totalCasosGestion = $this->db->select($sql2); // total casos por tipo gestion 
            $totalCasosGestion = count($totalCasosGestion);

            $array = array();
            $array['casostotal'] = $totalCasosSegmento[0]["cantidad"] - $totalCasosGestion;
            $array['casosgestion'] = $totalCasosGestion; */
           
            return $gestionArray;
        }

        function getMontoCompromisoRangoFecha($datos){
            //$db = new Db();
            $fechaInicio = $datos['inicio'];
            $fechaFin = $datos['fin'];
            $dt = new DateTime($fechaInicio);
            $fechaInicio = $dt->format('Y-m-d');
            $dt = new DateTime($fechaFin);
            $fechaFin = $dt->format('Y-m-d');
            // sin factura
            $sql = "SELECT 
                        YEAR(UltGest.fec_compromiso), 
                        MONTH(UltGest.fec_compromiso), 
                        DAY(UltGest.fec_compromiso) as dias, 
                        SUM(UltGest.monto_comp) as monto 
                    FROM
                        (   SELECT 
                                rut_cliente, fec_compromiso, monto_comp 
                            FROM 
                                (   SELECT 
                                        *
                                    FROM gestion_ult_trimestre 
                                    INNER JOIN 
                                        Tipo_Contacto ON Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion 
                                    WHERE 
                                        fec_compromiso BETWEEN '".$fechaInicio."' AND '".$fechaFin."' 
                                    AND 
                                        Tipo_Contacto.Id_TipoContacto = '5' 
                                    AND 
                                        gestion_ult_trimestre.cedente 
                                    IN (SELECT GROUP_CONCAT(Lista_Vicidial) FROM mandante_cedente WHERE Id_Cedente = '".$_SESSION['cedente']."'))
                                AllGest 
                            GROUP BY AllGest.rut_cliente, AllGest.fec_compromiso, AllGest.monto_comp) 
                        UltGest 
                    GROUP BY 
                        YEAR(UltGest.fec_compromiso), 
                        MONTH(UltGest.fec_compromiso), 
                        DAY(UltGest.fec_compromiso)";
        // muestra el total en pesos de compromisos de todos los dias de un rango de fecha
            // enviar dos campos llamados dias - monto
            // concatenar los dias para que se vea asi Dia 1
            $resultado = $this->db->select($sql);
            $montoDiasArray = array();  

            foreach((array) $resultado as $filas){
                $Array = array();
                $Array['dias'] = $filas["dias"];
                $Array['monto'] = $filas["monto"];
                array_push($montoDiasArray,$Array);
            }
            return $montoDiasArray;
        }

        function getMontoCompromisoPorMes($datos){
            //$db = new Db();
            $mes = $datos['mes'];
            $fechaInicio = "2017-".$mes."-01";
            $fechaFin = "2017-".$mes."-31";
            $dt = new DateTime($fechaInicio);
            $fechaInicio = $dt->format('Y-m-d');
            $dt = new DateTime($fechaFin);
            $fechaFin = $dt->format('Y-m-d');
            // sin factura
            $sql = "SELECT 
                        YEAR(UltGest.fec_compromiso), 
                        MONTH(UltGest.fec_compromiso), 
                        DAY(UltGest.fec_compromiso) as dias, 
                        SUM(UltGest.monto_comp) as monto 
                    FROM    
                        (  
                            SELECT 
                                rut_cliente, fec_compromiso, monto_comp 
                            FROM 
                                (   SELECT 
                                        * 
                                    FROM gestion_ult_trimestre 
                                    INNER JOIN Tipo_Contacto ON Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion 
                                    WHERE 
                                        fec_compromiso BETWEEN '".$fechaInicio."' AND '".$fechaFin."' 
                                    AND 
                                        Tipo_Contacto.Id_TipoContacto = '5' 
                                    AND 
                                        gestion_ult_trimestre.cedente 
                                    IN (SELECT GROUP_CONCAT(Lista_Vicidial) FROM mandante_cedente WHERE Id_Cedente = '".$_SESSION['cedente']."'))
                                AllGest 
                            GROUP BY 
                                AllGest.rut_cliente, AllGest.fec_compromiso, AllGest.monto_comp) 
                        UltGest 
                    GROUP BY 
                        YEAR(UltGest.fec_compromiso), 
                        MONTH(UltGest.fec_compromiso), 
                        DAY(UltGest.fec_compromiso)";
            // muestra el total en pesos de compromisos de todos los dias de un mes
            $resultado = $this->db->select($sql);
            $montoDiasArray = array();  

            foreach((array) $resultado as $filas){
                $Array = array();
                $Array['dias'] = $filas["dias"];
                $Array['monto'] = $filas["monto"];
                array_push($montoDiasArray,$Array);
            }
            return $montoDiasArray;
        }

        function arraySemanas($fechaInicio, $fechaFin, $semana){
            $Array = array();
            $Array['fechaInicio'] = $fechaInicio;
            $Array['fechaFin'] = $fechaFin;
            $Array['semana'] = $semana;
            return $Array;
        }

        function getMontoCompromisoMesSemana($datos){
            //$db = new Db();
            $mes = $datos['mes'];
            $semanasArray = array();  
            // semana 1
            $fechaInicio = "2017-".$mes."-01";
            $fechaFin = "2017-".$mes."-08";
            $semana = $this->arraySemanas($fechaInicio, $fechaFin, 'Semana 1');
            array_push($semanasArray,$semana);
            // semana 2
            $fechaInicio = "2017-".$mes."-09";
            $fechaFin = "2017-".$mes."-16";
            $semana = $this->arraySemanas($fechaInicio, $fechaFin, 'Semana 2');
            array_push($semanasArray,$semana);
            // semana 3
            $fechaInicio = "2017-".$mes."-17";
            $fechaFin = "2017-".$mes."-24";
            $semana = $this->arraySemanas($fechaInicio, $fechaFin, 'Semana 3');
            array_push($semanasArray,$semana);
            // semana 4
            $fechaInicio = "2017-".$mes."-25";
            $fechaFin = "2017-".$mes."-31";
            $semana = $this->arraySemanas($fechaInicio, $fechaFin, 'Semana 4');
            array_push($semanasArray,$semana);

            $montoSemanasArray = array(); 

            foreach($semanasArray as $filas){
                $fechaInicio = $filas['fechaInicio'];
                $fechaFin = $filas['fechaFin'];
                $dt = new DateTime($fechaInicio);
                $fechaInicio = $dt->format('Y-m-d');
                $dt = new DateTime($fechaFin);
                $fechaFin = $dt->format('Y-m-d');
                $sql = "    SELECT 
                                YEAR(UltGest.fec_compromiso), 
                                MONTH(UltGest.fec_compromiso), 
                                DAY(UltGest.fec_compromiso) as dias, 
                                SUM(UltGest.monto_comp) as monto 
                            FROM 
                                (   SELECT 
                                        rut_cliente, fec_compromiso, monto_comp 
                                    FROM 
                                        (   SELECT 
                                                * 
                                            FROM 
                                                gestion_ult_trimestre 
                                            INNER JOIN 
                                                Tipo_Contacto on Tipo_Contacto.Id_TipoContacto = gestion_ult_trimestre.Id_TipoGestion 
                                            WHERE 
                                                fec_compromiso BETWEEN '".$fechaInicio."' and '".$fechaFin."' 
                                            AND 
                                                Tipo_Contacto.Id_TipoContacto = '5' 
                                            AND 
                                                gestion_ult_trimestre.cedente 
                                            IN (SELECT GROUP_CONCAT(Lista_Vicidial) FROM mandante_cedente WHERE Id_Cedente = '".$_SESSION['cedente']."'))
                                        AllGest 
                                    GROUP BY AllGest.rut_cliente, AllGest.fec_compromiso, AllGest.monto_comp)
                                UltGest 
                            GROUP BY YEAR(UltGest.fec_compromiso), 
                            MONTH(UltGest.fec_compromiso), 
                            DAY(UltGest.fec_compromiso)";
                // muestra el total en pesos de compromisos de todos los dias de un mes
                $resultado = $this->db->select($sql);
                $acumulador = 0;
                foreach((array) $resultado as $resu){
                    // acumulo el monto de la semana
                    $acumulador = $acumulador + $resu["monto"];
                }
                $Array = array();
                $Array['semanas'] = $filas['semana'];
                $Array['monto'] = $acumulador;
                array_push($montoSemanasArray,$Array);
            } 

            return $montoSemanasArray;
        }

        function getMontoFacturasVencidas(){
            //$db = new Db();          

            $sql = "SELECT 
                        CASE 
                            WHEN dias <= -1 and dias >= -30  THEN '30 días' 
                            WHEN dias <= -31 and dias >= -60 THEN '30 - 60 días' 
                            WHEN dias <= -61 and dias >= -90 THEN '60 - 90 días' 
                            WHEN dias <= -91 THEN 'mas de 90 días' 
                            ELSE 'noVencidas' 
                        END AS tramo, 
                        SUM(Deuda) as monto
                    FROM (  SELECT 
                                TIMESTAMPDIFF(DAY, Fecha_Vencimiento, NOW()) AS dias, 
                                Deuda 
                            FROM 
                                Deuda 
                            WHERE 
                                Id_Cedente = '".$_SESSION['cedente']."' and Fecha_Vencimiento != '') 
                    AS TABLADIASTRAMO 
                    GROUP BY 
                        CASE 
                            WHEN dias <= -1 and dias >= -30  THEN '30 días' 
                            WHEN dias <= -31 and dias >= -60 THEN '30 - 60 días' 
                            WHEN dias <= -61 and dias >= -90 THEN '60 - 90 días' 
                            WHEN dias <= -91 THEN 'mas de 90 días' 
                            ELSE 'noVencidas' 
                        END";

            $resultado = $this->db->select($sql); 

            $montoVencidas = array();    
            foreach((array) $resultado as $resu){           
                $Array = array();
                $tramo = utf8_encode($resu['tramo']);
                if ($tramo != 'noVencidas'){
                    $Array['tramo'] = $resu['tramo'];
                    $Array['monto'] =  $resu['monto'];
                    array_push($montoVencidas,$Array);
                }
            }           

            $arrayNombreTramo = array('30 días', '30 - 60 días', '60 - 90 días', 'mas de 90 días');

            for($i=0;$i<count($arrayNombreTramo);$i++) {
                //echo $arrayNombreTramo[$i];
                $bandera = 0;
                foreach($montoVencidas as $mon){           
                    
                    if ($mon['tramo'] == $arrayNombreTramo[$i]){
                        $bandera = 1; // 1 si esta
                    }

                    
                }
                if ($bandera == 0){
                    // si entra aca no esta
                    $Array = array();
                    $Array['tramo'] = $arrayNombreTramo[$i];
                    $Array['monto'] =  0;
                    array_push($montoVencidas,$Array);

                }
            }   
            
            return $montoVencidas;
        }

        function getMontoFacturasNoVencidas(){
            //$db = new Db();          

            $sql = "SELECT 
                        CASE 
                            WHEN dias <= 1 and dias >= 30  THEN '30 días' 
                            WHEN dias <= 31 and dias >= 60 THEN '30 - 60 días' 
                            WHEN dias <= 61 and dias >= 90 THEN '60 - 90 días' 
                            WHEN dias <= 91 THEN 'mas de 90 días' 
                            ELSE 'noVencidas' 
                        END AS tramo, 
                        SUM(Deuda) as monto
                    FROM (  SELECT 
                                TIMESTAMPDIFF(DAY, Fecha_Vencimiento, NOW()) AS dias, 
                                Deuda 
                            FROM 
                                Deuda 
                            WHERE 
                                Id_Cedente = '".$_SESSION['cedente']."' and Fecha_Vencimiento != '') 
                    AS TABLADIASTRAMO 
                    GROUP BY 
                        CASE 
                            WHEN dias <= 1 and dias >= 30  THEN '30 días' 
                            WHEN dias <= 31 and dias >= 60 THEN '30 - 60 días' 
                            WHEN dias <= 61 and dias >= 90 THEN '60 - 90 días' 
                            WHEN dias <= 91 THEN 'mas de 90 días' 
                            ELSE 'noVencidas' 
                        END";

            $resultado = $this->db->select($sql); 

            $montoVencidas = array();    
            foreach((array) $resultado as $resu){           
                $Array = array();
                $tramo = utf8_encode($resu['tramo']);
                if ($tramo != 'noVencidas'){
                    $Array['tramo'] = $resu['tramo'];
                    $Array['monto'] =  $resu['monto'];
                    array_push($montoVencidas,$Array);
                }
            }           

            $arrayNombreTramo = array('30 días', '30 - 60 días', '60 - 90 días', 'mas de 90 días');

            for($i=0;$i<count($arrayNombreTramo);$i++) {
                //echo $arrayNombreTramo[$i];
                $bandera = 0;
                foreach($montoVencidas as $mon){           
                    
                    if ($mon['tramo'] == $arrayNombreTramo[$i]){
                        $bandera = 1; // 1 si esta
                    }

                    
                }
                if ($bandera == 0){
                    // si entra aca no esta
                    $Array = array();
                    $Array['tramo'] = $arrayNombreTramo[$i];
                    $Array['monto'] =  0;
                    array_push($montoVencidas,$Array);

                }
            }


            
            return $montoVencidas;
        }

        function getPagosMes($Mes){
            //$db = new Db();
            $Year = date("Y",strtotime($Mes));
            $Month = date("m",strtotime($Mes));
            $ToReturn = array();
            $SqlPagos = "select
                            YEAR(Fecha_Pago) as Year,MONTH(Fecha_Pago) as Month,DAY(Fecha_Pago) as Day, SUM(Monto) as Monto
                        from
                            pagos_deudas
                        where
                            Id_Cedente='".$_SESSION['cedente']."' and
                            YEAR(Fecha_Pago)='".$Year."' AND
                            MONTH(Fecha_Pago)='".$Month."'
                        group by YEAR(Fecha_Pago),MONTH(Fecha_Pago),DAY(Fecha_Pago)
                        order by YEAR(Fecha_Pago),MONTH(Fecha_Pago),DAY(Fecha_Pago)";
            $Pagos = $this->db->select($SqlPagos);
            foreach((array) $Pagos as $Pago){
                $ArrayTmp = array();
                $ArrayTmp["Dia"] = $Pago["Day"];
                $ArrayTmp["Monto"] = $Pago["Monto"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }

        function getMesesRecupero(){
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
            $ToReturn = array();
            $SqlMeses = "select
                            YEAR(Fecha_Pago) as Year,MONTH(Fecha_Pago) as Month
                        from
                            pagos_deudas
                        where
                            Id_Cedente='".$_SESSION['cedente']."'
                        group by YEAR(Fecha_Pago),MONTH(Fecha_Pago)
                        order by YEAR(Fecha_Pago),MONTH(Fecha_Pago)";
            $Meses = $this->db->select($SqlMeses);
            foreach((array) $Meses as $Mes){
                $ArrayTmp = array();
                $ArrayTmp["Year"] = $Mes["Year"];
                $ArrayTmp["Month"] = $Mes["Month"];
                $ArrayTmp["MonthText"] = $Months[$Mes["Month"]];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }

        function getFonoContactosData(){
            //$db = new Db();
            $ToReturn = array();
            $SqlTotalPersonas = "select count(*) as Cant from Persona where FIND_IN_SET('".$_SESSION["cedente"]."',Id_Cedente)";
            $TotalPersonas = $this->db->select($SqlTotalPersonas);
            $TotalPersonas = $TotalPersonas[0]["Cant"];
            $SqlFonoData = "select count(*) as Cant from (select Persona.Rut from fono_cob inner join Persona on fono_cob.Rut = Persona.Rut where FIND_IN_SET('".$_SESSION["cedente"]."',Id_Cedente) group by Persona.Rut) tb1";
            $FonoData = $this->db->select($SqlFonoData);
            $FonoData = $FonoData[0]["Cant"];
            $ToReturn[0]["label"] = "Con Datos";
            $ToReturn[0]["data"] = $FonoData;
            $ToReturn[0]["color"] = "blue";

            $ToReturn[1]["label"] = "Sin Datos";
            $ToReturn[1]["data"] = $TotalPersonas - $FonoData;
            $ToReturn[1]["color"] = "red";
            return $ToReturn;
        }

        function getMailContactosData(){
            //$db = new Db();
            $ToReturn = array();
            $SqlTotalPersonas = "select count(*) as Cant from Persona where FIND_IN_SET('".$_SESSION["cedente"]."',Id_Cedente)";
            $TotalPersonas = $this->db->select($SqlTotalPersonas);
            $TotalPersonas = $TotalPersonas[0]["Cant"];
            $SqlMailData = "select count(*) as Cant from (select Persona.Rut from Mail inner join Persona on Mail.Rut = Persona.Rut where FIND_IN_SET('".$_SESSION["cedente"]."',Id_Cedente) group by Persona.Rut) tb1";
            $MailData = $this->db->select($SqlMailData);
            $MailData = $MailData[0]["Cant"];
            $ToReturn[0]["label"] = "Con Datos";
            $ToReturn[0]["data"] = $MailData;
            $ToReturn[0]["color"] = "blue";

            $ToReturn[1]["label"] = "Sin Datos";
            $ToReturn[1]["data"] = $TotalPersonas - $MailData;
            $ToReturn[1]["color"] = "red";
            return $ToReturn;
        }

        function getSaldoEstadoGestionData(){
            //$db = new Db();
            $ToReturn = array();
            $SqlTotalDeudas = "select SUM(Saldos) as Saldo from Deuda where Id_Cedente='".$_SESSION["cedente"]."'";
            $TotalDeudas = $this->db->select($SqlTotalDeudas);
            $TotalDeudas = $TotalDeudas[0]["Saldo"];
            $SqlDeudaGestionadoData = "select
                                            SUM(Deuda.Saldos) as Saldo
                                        from
                                            Deuda
                                                inner join mandante_cedente on mandante_cedente.Id_Cedente = Deuda.Id_Cedente
                                                inner join gestion_ult_trimestre on gestion_ult_trimestre.rut_cliente = Deuda.Rut
                                                inner join Persona on Persona.Rut = Deuda.Rut
                                        where
                                            Deuda.Id_Cedente = '".$_SESSION["cedente"]."' and
                                            FIND_IN_SET(gestion_ult_trimestre.cedente,mandante_cedente.Lista_Vicidial)";
            $DeudaGestionadoData = $this->db->select($SqlDeudaGestionadoData);
            $DeudaGestionadoData = $DeudaGestionadoData[0]["Saldo"];
            $ToReturn[0]["label"] = "Con Datos";
            $ToReturn[0]["data"] = $DeudaGestionadoData;
            $ToReturn[0]["color"] = "blue";

            $ToReturn[1]["label"] = "Sin Datos";
            $ToReturn[1]["data"] = $TotalDeudas - $DeudaGestionadoData;
            $ToReturn[1]["color"] = "red";
            return $ToReturn;
        }

        function getTipoGestionData($Nivel,$Codigo=""){
            //$db = new Db();
            $ToReturn = array();
            $FechaCarga = $this->getFechaCargaAsignacion();
            switch($Nivel){
                case "1":
                    $SqlTipoGestion = "select
                                            Nivel1.Id as idNivel, Nivel1.Respuesta_N1 as Descripcion, SUM(Deuda.Deuda) as Monto, '' as idAnterior
                                        from
                                            Nivel1
                                                inner join gestion_ult_trimestre on gestion_ult_trimestre.resultado = Nivel1.Id
                                                inner join Deuda on Deuda.Rut = gestion_ult_trimestre.rut_cliente
                                        where
                                            gestion_ult_trimestre.fecha_gestion between '".$FechaCarga."' and NOW() and
                                            FIND_IN_SET('".$_SESSION["cedente"]."',Nivel1.Id_Cedente) and
                                            FIND_IN_SET(gestion_ult_trimestre.cedente,(select GROUP_CONCAT(Lista_Vicidial) from mandante_cedente where Id_Cedente='".$_SESSION["cedente"]."'))
                                        group by
                                            Nivel1.Id,Nivel1.Respuesta_N1";
                break;
                case "2":
                    $SqlTipoGestion = "select
                                            Nivel2.Id as idNivel, Nivel2.Respuesta_N2 as Descripcion, SUM(Deuda.Deuda) as Monto, Nivel2.Id_Nivel1 as idAnterior
                                        from
                                            Nivel2
                                                inner join gestion_ult_trimestre on gestion_ult_trimestre.resultado_n2 = Nivel2.Id
                                                inner join Deuda on Deuda.Rut = gestion_ult_trimestre.rut_cliente
                                        where
                                            gestion_ult_trimestre.fecha_gestion between '".$FechaCarga."' and NOW() and
                                            Nivel2.Id_Nivel1='".$Codigo."' and
                                            FIND_IN_SET(gestion_ult_trimestre.cedente,(select GROUP_CONCAT(Lista_Vicidial) from mandante_cedente where Id_Cedente='".$_SESSION["cedente"]."'))
                                        group by
                                            Nivel2.Id,Nivel2.Respuesta_N2";
                break;
                case "3":
                    $SqlTipoGestion = "select
                                            Nivel3.Id as idNivel, Nivel3.Respuesta_N3 as Descripcion, SUM(Deuda.Deuda) as Monto, '".$Codigo."' as idAnterior
                                        from
                                            Nivel3
                                                inner join gestion_ult_trimestre on gestion_ult_trimestre.resultado_n3 = Nivel3.Id
                                                inner join Deuda on Deuda.Rut = gestion_ult_trimestre.rut_cliente
                                        where
                                            gestion_ult_trimestre.fecha_gestion between '".$FechaCarga."' and NOW() and
                                            Nivel3.Id_Nivel2='".$Codigo."' and
                                            FIND_IN_SET(gestion_ult_trimestre.cedente,(select GROUP_CONCAT(Lista_Vicidial) from mandante_cedente where Id_Cedente='".$_SESSION["cedente"]."'))
                                        group by
                                            Nivel3.Id,Nivel3.Respuesta_N3";
                break;
            }
            $TipoGestion = $this->db->select($SqlTipoGestion);
            //echo $SqlTipoGestion;
            foreach((array) $TipoGestion as $Tipo){
                $ArrayTmp = array();
                $ArrayTmp["label"] = $Tipo["Descripcion"];
                $ArrayTmp["data"] = $Tipo["Monto"];
                $ArrayTmp["idNivel"] = $Tipo["idNivel"];
                $ArrayTmp["Nivel"] = $Nivel == "3" ? "-" : $Nivel + 1;
                $ArrayTmp["idAnterior"] = $Tipo["idAnterior"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }

        function getFechaCargaAsignacion(){
            $ToReturn = date("Ymd");
            //$db = new Db();
            $SqlFechaCarga = "select DATE(fecha) as fecha from Historico_Carga where Id_Cedente='".$_SESSION["cedente"]."' order by id DESC LIMIT 1";
            $SqlFechaCarga = $this->db->select($SqlFechaCarga);
            if(count((array) $SqlFechaCarga) > 0){
                $ToReturn = $SqlFechaCarga[0]["fecha"];
            }
            return $ToReturn;
        }

        function getValoresCampo($Campo){
            $ToReturn = array();
            //$db = new Db();
            $SqlValores = "select ".$Campo." from Deuda group by ".$Campo;
            $Valores = $this->db->select($SqlValores);
            foreach((array) $Valores as $Valor){
                array_push($ToReturn,$Valor[$Campo]);
            }
            return $ToReturn;
        }

        function CrearReporteDeGestionEstructurado($Desde, $Hasta, $idCedente){
            //$db = new Db();
            $Desde = date("Ymd",strtotime($Desde));
            $Hasta = date("Ymd",strtotime($Hasta));
            ob_start();
            $SqlGestiones = "select * from gestion_ult_trimestre where fecha_gestion between '".$Desde."' and '".$Hasta."' and cedente in (select Id_Cedente from mandante_cedente where Id_Cedente='".$idCedente."')";
            $Gestiones = $this->db->select($SqlGestiones);
            $Rows = "";
            foreach((array) $Gestiones as $Gestion)
            {
                $Rut = $Gestion["rut_cliente"];
                $SqlColumnasGestion = "select * from columnas_reporte_gestion_estructurado where Id_Cedente='".$idCedente."' order by Prioridad";
                $ColumnasGestion = $this->db->select($SqlColumnasGestion);
                foreach((array) $ColumnasGestion as $ColumnaGestion)
                {
                    $Tabla = $ColumnaGestion["Tabla"];
                    $Columna = $ColumnaGestion["Columna"];
                    $Formato = $ColumnaGestion["Formato"];
                    $Valor_Columna = $ColumnaGestion["Valor_Columna"];
                    $CantCaracteres = $ColumnaGestion["CantCaracteres"];
                    $PatronFecha = $ColumnaGestion["PatronFecha"];
                    $Relleno = $ColumnaGestion["Relleno"] == "" ? " " : $ColumnaGestion["Relleno"];
                    $Valor = "";
                    $WasValidated = false;
                    switch($Formato){
                        case "estatica":
                            $Valor = $Valor_Columna;
                        break;
                        case "dinamica":
                            switch($Tabla){
                                case "Nivel1":
                                case "Nivel2":
                                case "Nivel3":
                                    if(strpos($Columna,"Respuesta_N") !== FALSE){
                                        switch($Columna){
                                            case "Respuesta_N1":
                                                $Valor = "n1";
                                                $WasValidated = true;
                                            break;
                                            case "Respuesta_N2":
                                                $Valor = "n2";
                                                $WasValidated = true;
                                            break;
                                            case "Respuesta_N3":
                                                $Valor = "n3";
                                                $WasValidated = true;
                                            break;
                                        }
                                    }else{
                                        $IdNivel = "";
                                        switch($Tabla){
                                            case "Nivel1":
                                                $IdNivel = $Gestion["resultado"];
                                            break;
                                            case "Nivel2":
                                                $IdNivel = $Gestion["resultado_n2"];
                                            break;
                                            case "Nivel3":
                                                $IdNivel = $Gestion["resultado_n3"];
                                            break;
                                        }
                                        $SqlValorNivel = "select ".$Columna." as Valor from ".$Tabla." where Id='".$IdNivel."'";
                                        $ValorNivel = $this->db->select($SqlValorNivel);
                                        if(count((array) $ValorNivel) > 0){
                                            $Valor = $ValorNivel[0]["Valor"];
                                        }
                                        $WasValidated = true;
                                    }
                                break;
                                case "fono_cob":
                                case "gestion_ult_trimestre":
                                    switch($Columna){
                                        case "formato_subtel":
                                            $Fono = $Gestion["fono_discado"];
                                            $Fono = "56".substr($Fono,0,1)."-".substr($Fono,1,strlen($Fono) - 1);
                                            $Valor .= $Fono;
                                            $WasValidated = true;
                                        break;
                                        case "fono_discado":
                                            $Fono = $Gestion["fono_discado"];
                                            $Fono = "56".substr($Fono,0,1)."-".substr($Fono,1,strlen($Fono) - 1);
                                            $Valor .= $Fono;
                                            $WasValidated = true;
                                        break;
                                        case "factura":
                                            $Valor = $Gestion["factura"];
                                            $WasValidated = true;
                                        break;
                                        case "fechahora":
                                            $Valor = $Gestion["fechahora"];
                                            $Valor = date($PatronFecha,strtotime($Valor));
                                            $WasValidated = true;
                                        break;
                                        case "codigo_area":
                                            $Fono = $Gestion["fono_discado"];
                                            $Fono = substr($Fono,0,1);
                                            $Valor .= $Fono;
                                            $WasValidated = true;
                                        break;
                                    }
                                break;
                            }
                            if(!$WasValidated){
                                $SqlValorNivel = "select ".$Columna." as Valor from ".$Tabla." WHERE Rut='".$Rut."' LIMIT 1";
                                $ValorNivel = $this->db->select($SqlValorNivel);
                                if(count((array) $ValorNivel) > 0){
                                    $Valor = $ValorNivel[0]["Valor"];
                                }
                            }
                        break;
                        case "concatenada":
                            $Valor = $Valor_Columna;
                            switch($Tabla){
                                case "fono_cob":
                                case "gestion_ult_trimestre":
                                    switch($Columna){
                                        case "formato_subtel":
                                            $Fono = $Gestion["fono_discado"];
                                            $Fono = "56".substr($Fono,0,1)."-".substr($Fono,1,strlen($Fono) - 1);
                                            $Valor .= $Fono;
                                        break;
                                        case "fono_discado":
                                            $Fono = $Gestion["fono_discado"];
                                            $Fono = "56".substr($Fono,0,1)."-".substr($Fono,1,strlen($Fono) - 1);
                                            $Valor .= $Fono;
                                        break;
                                    }
                                break;
                                default:
                                    $SqlValorNivel = "select ".$Columna." as Valor from ".$Tabla." WHERE Rut='".$Rut."' LIMIT 1";
                                    $ValorNivel = $this->db->select($SqlValorNivel);
                                    if(count((array) $ValorNivel) > 0){
                                        $Valor .= $ValorNivel[0]["Valor"];
                                    }
                                break;
                            }
                        break;
                    }
                    $Rows .= str_pad($Valor, $CantCaracteres, $Relleno);
                    //echo $Valor;
                    //echo str_pad($Valor, $CantCaracteres,$Relleno);
                }
                echo $Rows .= "\r\n";
            }
            $Filename = "Reporte de gestiones ".date("dmY H:i:s"). " - Gestiones: ".count((array) $Gestiones);
            header("Content-type: text/plain");
            header('Content-Disposition: attachment; filename="'.$Filename.'.txt"');
            header('Cache-Control: max-age=0');
            $Data = ob_get_contents();
            
            ob_end_clean();
            ini_set("memory_limit","-1");
            return $Data;
        }

        function getReportesOperativoCartera()
        {
            //$db = new Db();
            $query = "SELECT * FROM reportes_operativos WHERE FIND_IN_SET('".$_SESSION["cedente"]."',id_cedente)";
            $ToReturn = $this->db->select($query);
            return $ToReturn;
        }

        function descargarCompromisos()
        {
            //$db = new Db();
            $objPHPExcel = new PHPExcel();
            $fileName = "Compromisos ".date("d_m_Y H_i_s"). " " . $_SESSION['MM_Username'];
            $Rows = "";
            $Rows .= "Rut;Nombre;Fecha_Compromiso;Monto_Compromiso;Deuda;Observacion";
            $query = "SELECT 
                            c.Rut, p.Nombre_Completo as Nombre, c.FechaCompromiso, c.MontoCompromiso, SUM(d.Deuda) as Deuda, g.observacion as Observacion
                        FROM 
                            Agendamiento_Compromiso c
                        INNER JOIN 
                            Deuda d
                        ON
                            d.Rut = c.Rut
                        INNER JOIN
                            Persona p
                        ON
                            c.Rut = p.Rut
                        INNER JOIN
                            gestion_ult_trimestre g
                        ON
                            c.id_gestion = g.id_gestion
                        WHERE
                            g.nombre_ejecutivo = '".$_SESSION['MM_Username']."' 
                        AND 
                            c.fechahora >= DATEADD(DAY, 0, TIMESTAMPDIFF(DAY, 0, CURRENT_TIMESTAMP))
                        AND 
                            c.fechahora <  DATEADD(DAY, 1, TIMESTAMPDIFF(DAY, 0, CURRENT_TIMESTAMP))
                        GROUP BY 
                            c.Rut,
                            p.Nombre_Completo,
                            c.FechaCompromiso,
                            c.MontoCompromiso,
                            g.observacion";
            $Compromisos = $this->db->select($query);
            if($Compromisos){
                foreach((array) $Compromisos as $Compromiso){
                    $Rows .= "\r\n";
                    foreach($Compromiso as $Value){
                        $Rows .= $Value.";";
                    }
                }
            }
            header('Content-Encoding: UTF-8');
            header('Content-Disposition: attachment; filename="'.$fileName.'.csv"');
            header('Cache-Control: max-age=0');
            header('Content-Type: text/csv; charset=UTF-8');
            echo "\xEF\xBB\xBF"; // UTF-8 BOM            
            return $Rows;
        }

        function getGestiones($Telefono,$Rut)
        {
            //$db = new Db();
            $WhereTelefono = $Telefono != "" ? " AND fono_discado = '".$Telefono."' " : "";
            $WhereRut = $Rut != "" ? " AND rut_cliente = '".$Rut."' " : "";
            $query = "SELECT
                            fecha_gestion,
                            nombre_ejecutivo,
                            fono_discado,
                            n1,
                            n2,
                            n3,
                            ( CASE WHEN fec_compromiso = '0000-00-00' THEN '' ELSE fec_compromiso END ) AS fec_compromiso,
                            ( CASE WHEN monto_comp = '0' THEN '' ELSE monto_comp END ) AS monto_comp,
                            observacion,
                            (
                            SELECT
                            CASE
                                    
                                WHEN
                                    IpServidorDiscado <> '' 
                                    AND IpServidorDiscadoAux <> '' THEN
                                        REPLACE ( gestion_ult_trimestre.url_grabacion, IpServidorDiscado, IpServidorDiscadoAux ) ELSE gestion_ult_trimestre.url_grabacion 
                                    END 
                                    FROM
                                        ( SELECT IpServidorDiscado, IpServidorDiscadoAux FROM fireConfig ) tb1 
                                    ) AS Listen 
                                FROM
                                    gestion_ult_trimestre 
                                WHERE
                                    url_grabacion != '' ".$WhereTelefono." ".$WhereRut." 
                            ORDER BY
                            fecha_gestion";
		    $Gestiones = $this->db->select($query);
            return $Gestiones;
        }

        public function getAudioGestiones($data)
        {
            //$whereQuery = "g.url_grabacion != ''";
            $whereQuery = "g.rut_cliente != ''";
            //$db = new Db();
            if (isset($data['proyectos']) && !empty($data['proyectos']) && is_array($data['proyectos'])) {
                $cedentes = implode(',', $data['proyectos']);
                $whereQuery .= " AND g.cedente IN ({$cedentes})";
            } else if(isset($data['cedente']) && !empty($data['cedente'])) {
                $whereQuery .= " AND g.cedente = '{$data['cedente']}'";
            } else {
                return [];
            }

            if (isset($data['fecha_desde']) && !empty($data['fecha_desde']) && isset($data['fecha_hasta']) && !empty($data['fecha_hasta'])) {
                if ($data['fecha_desde'] == $data['fecha_hasta']) {
                    $whereQuery .= " AND g.fecha_gestion = '{$data['fecha_desde']}'";
                } else {
                    $whereQuery .= " AND (g.fecha_gestion BETWEEN '{$data['fecha_desde']}' AND '{$data['fecha_hasta']}')";
                }                
            }

            if(isset($data['rut']) && !empty($data['rut']))  $whereQuery .= " AND g.rut_cliente = '{$data['rut']}'";
            if(isset($data['telefono']) && !empty($data['telefono']))  $whereQuery .= " AND g.fono_discado = '{$data['telefono']}'";
            if(isset($data['fecha_gestion']) && !empty($data['fecha_gestion']))  $whereQuery .= " AND g.fecha_gestion = '{$data['fecha_gestion']}'";

            if(isset($data['nivel_1']) && !empty($data['nivel_1'])) {
                $whereQuery .= " AND n1 = '{$data['nivel_1']}'";
                if(isset($data['nivel_2']) && !empty($data['nivel_2'])) $whereQuery .= " AND n2 = '{$data['nivel_2']}'";
                if(isset($data['nivel_3']) && !empty($data['nivel_3'])) $whereQuery .= " AND n3 = '{$data['nivel_3']}'";
                if(isset($data['nivel_4']) && !empty($data['nivel_4'])) $whereQuery .= " AND n4 = '{$data['nivel_4']}'";
            }         

            $query = "SELECT
                        DATE_FORMAT(g.fechahora,'%d-%m-%Y %r') AS fecha_gestion,
                        g.fecha_gestion AS fecha_gestion_2,
                        DATE_FORMAT(g.hora_gestion,'%H%i%s') AS hora_gestion,
                        g.rut_cliente AS rut,
                        (SELECT Nombre_Cedente FROM Cedente WHERE Id_Cedente = g.cedente LIMIT 1) AS cartera,
                        (SELECT nombre FROM Usuarios WHERE usuario = g.nombre_ejecutivo OR nombre = g.nombre_ejecutivo LIMIT 1) AS nombre_ejecutivo,
                        (SELECT Nombre_Completo AS nombre FROM Persona WHERE Rut = g.rut_cliente LIMIT 1) AS nombre_cliente,
                        g.fono_discado,
                        (SELECT r.Respuesta_N1 FROM `Nivel1` AS r WHERE r.Id_Cedente = g.cedente AND r.id = g.n1 LIMIT 1) AS n1,
                        (SELECT r2.Respuesta_N2 FROM `Nivel2` AS r2 WHERE r2.Id_Nivel1 = g.n1 AND r2.id = g.n2 LIMIT 1) AS n2,
                        (SELECT r3.Respuesta_N3 FROM `Nivel3` AS r3 WHERE r3.Id_Nivel2 = g.n2 AND r3.id = g.n3 LIMIT 1) AS n3,
                        (SELECT r4.Respuesta_N4 FROM `Nivel4` AS r4 WHERE r4.Id_Nivel3 = g.n3 AND r4.id = g.n4 LIMIT 1) AS n4,
                        ( CASE WHEN g.fec_compromiso = '0000-00-00' THEN '' ELSE DATE_FORMAT(g.fec_compromiso,'%d-%m-%Y') END ) AS fec_compromiso,
                        ( CASE WHEN g.monto_comp = '0' THEN '' ELSE CONCAT('$ ',format(g.monto_comp,0,'es_CL')) END ) AS monto_comp,
                        g.observacion,
                        g.url_grabacion AS Listen 
                    FROM
                        gestion_ult_trimestre AS g
                    WHERE
                        {$whereQuery} 
                    ORDER BY g.fecha_gestion";
            
            /**
             *                         (SELECT
             *              CASE WHEN IpServidorDiscado <> '' AND IpServidorDiscadoAux <> '' THEN
             *                   REPLACE (gestion_ult_trimestre.url_grabacion, IpServidorDiscado, IpServidorDiscadoAux) 
             *               ELSE gestion_ult_trimestre.url_grabacion END 
             *           FROM (SELECT IpServidorDiscado, IpServidorDiscadoAux FROM fireConfig) tb1
             *           ) 
             */
            //$this->logs->debug('** Reportería **');
            //$this->logs->debug($query);
            $Gestiones = $this->db->select($query);
            if (count((array) $Gestiones) > 0) {
                $this->logs->debug('Cant: '.count((array) $Gestiones));
                foreach((array) $Gestiones as $key => $gestion) {
                    if (!is_null($Gestiones[$key]['Listen']) && empty($Gestiones[$key]['Listen'])) {
                        $Gestiones[$key]['Listen'] = 'http://sinaptica-crm-dev.test/storage/audios?y='.$gestion['fecha_gestion_2'].'&h='.$gestion['hora_gestion'].'&f='.$gestion['fono_discado'].'&o='.basename($gestion['Listen']);
                        //str_replace(['http://10.10.30.16/RECORDINGS/MP3/','http://10.10.30.14/RECORDINGS/MP3/'],'https://crmgrc.sinaptica.io/storage/audios?y='.$gestion['fecha_gestion'].'&h='.$gestion['hora_gestion'].'&f='.$gestion['fono_discado'], $gestion['Listen']);
                    }
                    //$Gestiones[$key]['Listen'] = str_replace(['http://10.10.30.14','http://192.168.1.31'],'https://agentesbpro2.sinaptica.io', $gestion['Listen']);
                }                
            }
            return $Gestiones;
        }

        public function downloadGestiones($data)
        {
            $csv = Writer::createFromFileObject(new SplTempFileObject());
            $csv->setDelimiter(';'); //->setEnclosure(' ')->setEscape('');
            $Gestiones = $this->getAudioGestiones($data);
            $Gestiones = array_map(function ($item) {
                $item['Listen'] = $item['Listen'].'&d=si';
                // $item['Listen'] = str_replace(['http://10.10.30.16/RECORDINGS/MP3/','http://10.10.30.14/RECORDINGS/MP3/'],'https://crmgrc.sinaptica.io/storage/audios/', $item['Listen']);
                //$item['Listen'] = str_replace(['http://10.10.30.14','http://192.168.1.31'],'https://agentesbpro2.sinaptica.io', $item['Listen']);
                return [
                    'Cartera' => $item['cartera'],
                    'Rut' => $item['rut'],
                    'Nombre cliente' => trim($item['nombre_cliente']),
                    'Teléfono' => $item['fono_discado'],
                    'Fecha gestión' => trim($item['fecha_gestion']),
                    'Nombre ejecutivo' => trim($item['nombre_ejecutivo']),
                    'Tipo gestión' => $item['n1'],
                    'Tipo contacto' => $item['n2'],
                    'Respuesta' => $item['n3'],
                    'Sub respuesta' => $item['n4'],
                    'Fecha compromiso' => $item['fec_compromiso'],
                    'Monto compromiso' => $item['monto_comp'],
                    'URL Audio' => $item['Listen'],
                    'Observación' => str_replace(';','. ',$item['observacion']),
                ];
            }, (array) $Gestiones);
            $csv->insertOne(array_keys($Gestiones[0]));
            $csv->insertAll($Gestiones);
            
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="'.$data['file_name'].'_'.date('d-m-Y').'.csv"');
            $csv->output();
        }

        public function generaGeneral($data)
        {
            try {
                set_time_limit(0);
                $this->logs->debug('generaGeneral');
                //$this->logs->debug($data);
                $Gestiones = $this->__getGestionesGeneral($data);
                //$this->logs->debug($Gestiones);
                $documento = new Spreadsheet();
                $documento->getProperties()
                            ->setCreator("Sinaptica")
                            ->setTitle('Reporte General')
                            ->setDescription('Reporte General de Gestiones');
    
                $hojaDeGestiones = $documento->getActiveSheet();
                $hojaDeGestiones->setTitle("Sabana");
                $hojaDeGestiones->fromArray(array_keys($Gestiones[0]), null, 'A1');
    
                $numeroDeFila = 2;
                //$this->logs->debug('Registros: '.count((array) $Gestiones));
                foreach ((array) $Gestiones AS $Gestion) {
                    $col = 1;
                    foreach ($Gestion as $key => $valor) {
                        $hojaDeGestiones->setCellValueByColumnAndRow($col, $numeroDeFila, $valor);
                        $col++;
                    }
                    $numeroDeFila++;
                }
                //$this->logs->debug('Filas: '.$numeroDeFila);
                
                $fileName = $data['filename'];
                try {
                    $writer = new Xlsx($documento);
                    $this->logs->debug('Crear');
                    $writer->save('/tmp/'.$fileName);
                    $this->logs->debug('/tmp/'.$fileName);
                    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                    header('Content-Disposition: attachment; filename="'.$fileName.'"');
                    header('Cache-Control: must-revalidate');
                    header('Expires: 0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize('/tmp/'.$fileName));
                    $this->logs->debug(filesize('/tmp/'.$fileName));
                    readfile('/tmp/'.$fileName);
                    exit;
                } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $wEx) {
                    $this->logs->error('ERROR: generaGeneral');
                    $this->logs->error($wEx->getMessage());
                    return 'ERROR';
                }
                exit;
            } catch (\Exception $ex) {
                $this->logs->error('ERROR: generaGeneral');
                $this->logs->error($ex->getMessage());
                return 'ERROR';
            }            
        }
        
        public function getExcelCarterizacion($idFocoFactura,$cobrador) {
            //$db = new Db();
            if(isset($bt_id) && $bt_id != ''){           
                $querya = "SELECT dialplan FROM BT_config WHERE id = $bt_id";
                $resulta = $this->db->select($querya);
                foreach((array) $resulta as $row) {    
                    $dialplan = $row['dialplan'];    
                }    
                if(true) {                   
                    $query = " SELECT Rut, Fono, fecha, hora, urlGrabacion, gestion,rama, observacion,duracion
                        FROM BT_conversaciones where idBot = '".$bt_id."' ";
                    $result = $this->db->select($query);
                    return $result;       
                } else {
                    echo 'Error idCarga esta vacio';
                }
            }    
        }

        public function generaContactabilidad($data)
        {
            try {

                $Gestiones = $this->__getContactabilidad($data);            
    
                $documento = new Spreadsheet();
                $documento->getProperties()
                            ->setCreator("Sinaptica")
                            ->setTitle('Contactabilidad')
                            ->setDescription('Reporte de contactabilidad');
                $h = 0;
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ];
    
                $styleArrayTit = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => [
                            'argb' => 'FFA0A0A0',
                        ],
                        'endColor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ];
                
                foreach ($Gestiones as $grupo) {
                    $tit = 'Alta';
                    if ($h == 0) {
                        $sheet = $documento->getActiveSheet();
                        $sheet->setTitle($grupo['proyecto']);
                    } else {
                        $sheet = new Worksheet($documento, $grupo['proyecto']);
                        $documento->addSheet($sheet, $h);
                    }
    
                    //var_dump($grupo);
                    $resultados = $this->__organizeContactabilidad($grupo['hojas']['alta']);
                    if($grupo['proyecto'] == 'IC') {
                        $tit = 'IC';
                        if (count($grupo['hojas']['alta']) == 0) continue;
                        // $resultados = $this->__organizeContactabilidad($grupo['hojas']['baja']);
                        $grupo['hojas']['baja'] = [];
                    }
                    //var_dump($resultados); continue;
    
                    $sheet->mergeCells('A1:C1');
                    $sheet->getStyle('A1:C1')->applyFromArray($styleArray);
                    $sheet->setCellValue("A1", $tit);
                    $sheet->getStyle("A1")->getFont()->setBold(true);
                    $sheet->getStyle("A1")->applyFromArray($styleArrayTit);
                    $sheet->setCellValue("A2", "Etiqueta");
                    $sheet->setCellValue("B2", "Ruts");
                    $sheet->setCellValue("C2", "Saldos");
    
                    $sheet->getColumnDimension('A')->setAutoSize(true);
                    $sheet->getColumnDimension('B')->setAutoSize(true);
                    $sheet->getColumnDimension('C')->setAutoSize(true);
                    $sheet->getRowDimension('1')->setRowHeight(25);
                    
                    $r = 3;
                    foreach ($resultados['rows'] as $row) {
                        $sheet->setCellValue("A{$r}", $row['nombre']);
                        $sheet->getStyle("A{$r}")->getFont()->setBold(true);
                        $sheet->setCellValue("B{$r}", $row['ruts']);
                        $sheet->getStyle("B{$r}")->getNumberFormat()
                             ->setFormatCode('#,##0');
                        $sheet->getStyle("B{$r}")->getFont()->setBold(true);
                        $sheet->setCellValue("C{$r}", (float) $row['total_alta']);
                        $sheet->getStyle("C{$r}")->getNumberFormat()
                             ->setFormatCode('#,##0');
                        $sheet->getStyle("C{$r}")->getFont()->setBold(true);
                        $r++;
                        foreach($row['items'] as $subItem) {
                            $sheet->setCellValue("A{$r}", " - ".$subItem['gestion_n2']);
                            $sheet->setCellValue("B{$r}", $subItem['ruts']);
                            $sheet->getStyle("B{$r}")->getNumberFormat()
                                  ->setFormatCode('#,##0');
                            $sheet->setCellValue("C{$r}", (float) $subItem['total_alta']);
                            $sheet->getStyle("C{$r}")->getNumberFormat()
                                  ->setFormatCode('#,##0');
                            $r++;
                        }                  
                    }
                    $sheet->getStyle('A1:C'.$r)->applyFromArray($styleArray);
                    $sheet->getColumnDimension('D')->setWidth(5);
                    $sheet->getColumnDimension('E')->setWidth(5);
    
                    $sheet->setCellValue("A{$r}", "TOTAL:");
                    $sheet->getStyle("A{$r}")->getFont()->setBold(true);
                    $sheet->setCellValue("B{$r}", $resultados['general']['ruts']);
                    $sheet->getStyle("B{$r}")->getNumberFormat()
                             ->setFormatCode('#,##0');
                    $sheet->getStyle("B{$r}")->getFont()->setBold(true);
                    $sheet->setCellValue("C{$r}",  (float) $resultados['general']['total_alta']);
                    $sheet->getStyle("C{$r}")->getFont()->setBold(true);
                    $sheet->getStyle("C{$r}")->getNumberFormat()
                             ->setFormatCode('#,##0');
    
                    $resultados = [];
                    $resultados = $this->__organizeContactabilidad($grupo['hojas']['baja']);
    
                    if (count((array) $resultados['rows']) > 0) {
                        $sheet->mergeCells('F1:H1');
                        //$sheet->getStyle('F1:H1')->applyFromArray($styleArray);
                        $sheet->setCellValue("F1", "Baja");
                        $sheet->getStyle("F1")->getFont()->setBold(true);
                        $sheet->getStyle("F1")->applyFromArray($styleArrayTit);
                        $sheet->setCellValue("F2", "Etiqueta");
                        $sheet->setCellValue("G2", "Ruts");
                        $sheet->setCellValue("H2", "Saldos");
        
                        $sheet->getColumnDimension('F')->setAutoSize(true);
                        $sheet->getColumnDimension('G')->setAutoSize(true);
                        $sheet->getColumnDimension('H')->setAutoSize(true);
            
                        $r = 3;
                        foreach ($resultados['rows'] as $row) {
                            $sheet->setCellValue("F{$r}", $row['nombre']);
                            $sheet->getStyle("F{$r}")->getFont()->setBold(true);
                            $sheet->setCellValue("G{$r}", $row['ruts']);
                            $sheet->getStyle("G{$r}")->getNumberFormat()
                                 ->setFormatCode('#,##0');
                            $sheet->getStyle("G{$r}")->getFont()->setBold(true);
                            $sheet->setCellValue("H{$r}", (float) $row['total_baja']);
                            $sheet->getStyle("H{$r}")->getNumberFormat()
                                 ->setFormatCode('#,##0');
                            $sheet->getStyle("H{$r}")->getFont()->setBold(true);
                            $r++;
                            foreach($row['items'] as $subItem) {
                                $sheet->setCellValue("F{$r}", " - ".$subItem['gestion_n2']);
                                $sheet->setCellValue("G{$r}", $subItem['ruts']);
                                $sheet->getStyle("G{$r}")->getNumberFormat()
                                      ->setFormatCode('#,##0');
                                $sheet->setCellValue("H{$r}", (float) $subItem['total_baja']);
                                $sheet->getStyle("H{$r}")->getNumberFormat()
                                      ->setFormatCode('#,##0');
                                $r++;
                            }                  
                        }
                        $sheet->getStyle('F1:H'.$r)->applyFromArray($styleArray);
    
                        $sheet->setCellValue("F{$r}", "TOTAL:");
                        $sheet->getStyle("F{$r}")->getFont()->setBold(true);
                        $sheet->setCellValue("G{$r}", $resultados['general']['ruts']);
                        $sheet->getStyle("G{$r}")->getNumberFormat()
                                ->setFormatCode('#,##0');
                        $sheet->getStyle("G{$r}")->getFont()->setBold(true);
                        $sheet->setCellValue("H{$r}",  (float) $resultados['general']['total_baja']);
                        $sheet->getStyle("H{$r}")->getFont()->setBold(true);
                        $sheet->getStyle("H{$r}")->getNumberFormat()
                                ->setFormatCode('#,##0');
                    }
                    $h++;
                }
                $documento->setActiveSheetIndex(0);
                $fileName = $data['filename'];
                $writer = new Xlsx($documento);
                $writer->save('/tmp/'.$fileName);
                
                header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");;
                header('Content-Disposition: attachment; filename="'.$fileName.'"');
                header("Cache-Control: max-age=0");
                header('Expires: 0');
                header('Pragma: public');
                header('Content-Length: ' . filesize('/tmp/'.$fileName));
                readfile('/tmp/'.$fileName);
                exit;
                
                //$csv->output();
            } catch (\Exception $ex) {
                $this->logs->error($ex->getMessage());
                echo 'ERROR';
                exit;
            }
        }


        public function generaMayoresDeudores($data)
        {
            $Gestiones = $this->__getMayoresDeudores($data);            

            $documento = new Spreadsheet();
            $documento->getProperties()
                        ->setCreator("Sinaptica")
                        ->setTitle('Mayores deudores')
                        ->setDescription('Reporte de mayores deudores');
            $h = 0;
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ];

            $styleArrayTit = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endColor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ];

            foreach ($Gestiones as $grupo) {
                if ($h == 0) {
                    $sheet = $documento->getActiveSheet();
                    $sheet->setTitle($grupo['proyecto']);
                } else {
                    $sheet = new Worksheet($documento, $grupo['proyecto']);
                    $documento->addSheet($sheet, $h);
                }

                $sheet->mergeCells('A1:D1');
                $sheet->getStyle('A1:D1')->applyFromArray($styleArray);
                $sheet->setCellValue("A1", "Mayores deudores");
                $sheet->getStyle("A1")->getFont()->setBold(true);
                $sheet->getStyle("A1")->applyFromArray($styleArrayTit);
                $sheet->setCellValue("A2", "Rut");
                $sheet->getStyle("A2")->getFont()->setBold(true);
                $sheet->setCellValue("B2", "Nombre");
                $sheet->getStyle("B2")->getFont()->setBold(true);
                $sheet->setCellValue("C2", "Saldo");
                $sheet->getStyle("C2")->getFont()->setBold(true);
                $sheet->setCellValue("D2", "Última gestión");
                $sheet->getStyle("D2")->getFont()->setBold(true);

                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getRowDimension('1')->setRowHeight(25);

                $r = 3;
                foreach ((array) $grupo['rows'] as $row) {
                    $sheet->setCellValue("A{$r}", $row['Rut']);
                    $sheet->setCellValue("B{$r}", $row['Nombre']);
                    $sheet->setCellValue("C{$r}", (float) $row['total']);
                    $sheet->getStyle("C{$r}")->getNumberFormat()
                         ->setFormatCode('#,##0');
                    $sheet->setCellValue("D{$r}", $row['ultima_gestion']);
                    $r++;
                }
                $sheet->getStyle('A1:D'.($r-1))->applyFromArray($styleArray);
                $h++;
            }

            // exit;
            $documento->setActiveSheetIndex(0);
            $fileName = $data['filename'];
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");;
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header("Cache-Control: max-age=0");
            $writer = new Xlsx($documento);
            $writer->save('php://output');
        }

        public function generaContactabilidadPorAgente($data)
        {
            try {

                $Gestiones = $this->__getContactabilidadPorAgente($data);         
                $documento = new Spreadsheet();
                $documento->getProperties()
                            ->setCreator("Sinaptica")
                            ->setTitle('Contactabilidad')
                            ->setDescription('Reporte de contactabilidad');
                $h = 0;
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ];
    
                $styleArrayTit = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => [
                            'argb' => 'FFA0A0A0',
                        ],
                        'endColor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ];
                
                foreach ($Gestiones as $grupo) {
                    $tit = 'Alta';
                    if ($h == 0) {
                        $sheet = $documento->getActiveSheet();
                        $sheet->setTitle($grupo['proyecto']);
                    } else {
                        $sheet = new Worksheet($documento, $grupo['proyecto']);
                        $documento->addSheet($sheet, $h);
                    }
    
                    //var_dump($grupo);
                    $resultados = $this->__organizeContactabilidad($grupo['hojas']['alta']);
                    if($grupo['proyecto'] == 'IC') {
                        $tit = 'IC';
                        if (count($grupo['hojas']['alta']) == 0) continue;
                        // $resultados = $this->__organizeContactabilidad($grupo['hojas']['baja']);
                        $grupo['hojas']['baja'] = [];
                    }
                    //var_dump($resultados); continue;
    
                    $sheet->mergeCells('A1:C1');
                    $sheet->getStyle('A1:C1')->applyFromArray($styleArray);
                    $sheet->setCellValue("A1", $tit);
                    $sheet->getStyle("A1")->getFont()->setBold(true);
                    $sheet->getStyle("A1")->applyFromArray($styleArrayTit);
                    $sheet->setCellValue("A2", "Etiqueta");
                    $sheet->setCellValue("B2", "Ruts");
                    $sheet->setCellValue("C2", "Saldos");
    
                    $sheet->getColumnDimension('A')->setAutoSize(true);
                    $sheet->getColumnDimension('B')->setAutoSize(true);
                    $sheet->getColumnDimension('C')->setAutoSize(true);
                    $sheet->getRowDimension('1')->setRowHeight(25);
                    
                    $r = 3;
                    foreach ($resultados['rows'] as $row) {
                        $sheet->setCellValue("A{$r}", $row['nombre']);
                        $sheet->getStyle("A{$r}")->getFont()->setBold(true);
                        $sheet->setCellValue("B{$r}", $row['ruts']);
                        $sheet->getStyle("B{$r}")->getNumberFormat()
                             ->setFormatCode('#,##0');
                        $sheet->getStyle("B{$r}")->getFont()->setBold(true);
                        $sheet->setCellValue("C{$r}", (float) $row['total_alta']);
                        $sheet->getStyle("C{$r}")->getNumberFormat()
                             ->setFormatCode('#,##0');
                        $sheet->getStyle("C{$r}")->getFont()->setBold(true);
                        $r++;
                        foreach($row['items'] as $subItem) {
                            $sheet->setCellValue("A{$r}", " - ".$subItem['gestion_n2']);
                            $sheet->setCellValue("B{$r}", $subItem['ruts']);
                            $sheet->getStyle("B{$r}")->getNumberFormat()
                                  ->setFormatCode('#,##0');
                            $sheet->setCellValue("C{$r}", (float) $subItem['total_alta']);
                            $sheet->getStyle("C{$r}")->getNumberFormat()
                                  ->setFormatCode('#,##0');
                            $r++;
                        }                  
                    }
                    $sheet->getStyle('A1:C'.$r)->applyFromArray($styleArray);
                    $sheet->getColumnDimension('D')->setWidth(5);
                    $sheet->getColumnDimension('E')->setWidth(5);
    
                    $sheet->setCellValue("A{$r}", "TOTAL:");
                    $sheet->getStyle("A{$r}")->getFont()->setBold(true);
                    $sheet->setCellValue("B{$r}", $resultados['general']['ruts']);
                    $sheet->getStyle("B{$r}")->getNumberFormat()
                             ->setFormatCode('#,##0');
                    $sheet->getStyle("B{$r}")->getFont()->setBold(true);
                    $sheet->setCellValue("C{$r}",  (float) $resultados['general']['total_alta']);
                    $sheet->getStyle("C{$r}")->getFont()->setBold(true);
                    $sheet->getStyle("C{$r}")->getNumberFormat()
                             ->setFormatCode('#,##0');
    
                    $resultados = [];
                    $resultados = $this->__organizeContactabilidad($grupo['hojas']['baja']);
    
                    if (count((array) $resultados['rows']) > 0) {
                        $sheet->mergeCells('F1:H1');
                        //$sheet->getStyle('F1:H1')->applyFromArray($styleArray);
                        $sheet->setCellValue("F1", "Baja");
                        $sheet->getStyle("F1")->getFont()->setBold(true);
                        $sheet->getStyle("F1")->applyFromArray($styleArrayTit);
                        $sheet->setCellValue("F2", "Etiqueta");
                        $sheet->setCellValue("G2", "Ruts");
                        $sheet->setCellValue("H2", "Saldos");
        
                        $sheet->getColumnDimension('F')->setAutoSize(true);
                        $sheet->getColumnDimension('G')->setAutoSize(true);
                        $sheet->getColumnDimension('H')->setAutoSize(true);
            
                        $r = 3;
                        foreach ($resultados['rows'] as $row) {
                            $sheet->setCellValue("F{$r}", $row['nombre']);
                            $sheet->getStyle("F{$r}")->getFont()->setBold(true);
                            $sheet->setCellValue("G{$r}", $row['ruts']);
                            $sheet->getStyle("G{$r}")->getNumberFormat()
                                 ->setFormatCode('#,##0');
                            $sheet->getStyle("G{$r}")->getFont()->setBold(true);
                            $sheet->setCellValue("H{$r}", (float) $row['total_baja']);
                            $sheet->getStyle("H{$r}")->getNumberFormat()
                                 ->setFormatCode('#,##0');
                            $sheet->getStyle("H{$r}")->getFont()->setBold(true);
                            $r++;
                            foreach($row['items'] as $subItem) {
                                $sheet->setCellValue("F{$r}", " - ".$subItem['gestion_n2']);
                                $sheet->setCellValue("G{$r}", $subItem['ruts']);
                                $sheet->getStyle("G{$r}")->getNumberFormat()
                                      ->setFormatCode('#,##0');
                                $sheet->setCellValue("H{$r}", (float) $subItem['total_baja']);
                                $sheet->getStyle("H{$r}")->getNumberFormat()
                                      ->setFormatCode('#,##0');
                                $r++;
                            }                  
                        }
                        $sheet->getStyle('F1:H'.$r)->applyFromArray($styleArray);
    
                        $sheet->setCellValue("F{$r}", "TOTAL:");
                        $sheet->getStyle("F{$r}")->getFont()->setBold(true);
                        $sheet->setCellValue("G{$r}", $resultados['general']['ruts']);
                        $sheet->getStyle("G{$r}")->getNumberFormat()
                                ->setFormatCode('#,##0');
                        $sheet->getStyle("G{$r}")->getFont()->setBold(true);
                        $sheet->setCellValue("H{$r}",  (float) $resultados['general']['total_baja']);
                        $sheet->getStyle("H{$r}")->getFont()->setBold(true);
                        $sheet->getStyle("H{$r}")->getNumberFormat()
                                ->setFormatCode('#,##0');
                    }
                    $h++;
                }
                $documento->setActiveSheetIndex(0);
                $fileName = 'Contactabilidad_'.$data['agente'].'.xlsx';
                $writer = new Xlsx($documento);
                $writer->save('/tmp/'.$fileName);
                
                header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");;
                header('Content-Disposition: attachment; filename="'.$fileName.'"');
                header("Cache-Control: max-age=0");
                header('Expires: 0');
                header('Pragma: public');
                header('Content-Length: ' . filesize('/tmp/'.$fileName));
                readfile('/tmp/'.$fileName);
                exit;
                
                //$csv->output();
            } catch (\Exception $ex) {
                $this->logs->error($ex->getMessage());
                echo 'ERROR';
                exit;
            }
        }

        public function bpoGestiones($data)
        {
            try {
                $hoy = (!isset($data['fecha']) || empty($data['fecha']))? date('Y-m-d'): $data['fecha'];
                $strSQL = "(SELECT
                    g.rut_cliente AS RUT,
                    IF(g.Id_TipoGestion = '1','DIRECTO', IF(g.Id_TipoGestion = '3','INDIRECTO','SIN CONTACTO')) AS CONTACTO,
                    'PESOS' AS MONEDA,
                    '9033' AS COD_EMPRESA,
                    DATE_FORMAT(fechahora, '%Y%m%d %H%i%s') AS FECHA_HORA_GESTION,
                    'TELEFONICA' AS TIPO,
                    (SELECT Codigo FROM Nivel2 WHERE id = g.n2 LIMIT 1) AS CONTACTABILIDAD,
                    IF(
                    (SELECT Codigo FROM Nivel3 WHERE id = g.n3 LIMIT 1) IS NULL || (SELECT Codigo FROM Nivel3 WHERE id = g.n3 LIMIT 1) = '',
                    (SELECT Codigo FROM Nivel2 WHERE id = g.n2 LIMIT 1),
                    (SELECT Codigo FROM Nivel3 WHERE id = g.n3 LIMIT 1)
                    ) AS RESULTADO,
                    REPLACE(g.observacion,';',',') AS COMENTARIO,
                    IF(g.fec_compromiso IS NULL || g.fec_compromiso = '', '', g.fec_compromiso) AS FECHA_COMPROMISO,
                    IF(g.monto_comp IS NULL || g.monto_comp = '', '', g.monto_comp) AS MONTO_COMPROMISO,
                    '' AS MTODEUMOMGES,
                    (SELECT Nombre_Completo FROM Persona WHERE Rut = g.rut_cliente LIMIT 1) AS NOMBRE_CLI,
                    DATE_FORMAT(fecha_gestion,'%Y%m%d') AS FECHA_GESTION,
                    fono_discado AS FONO,
                    -- g.nombre_ejecutivo,
                    (SELECT CONCAT(rut,dv) FROM Usuarios WHERE usuario = g.nombre_ejecutivo LIMIT 1) AS RUT_USUARIO_ECE,
                    '' AS `MOTIVO MORA`,
                    '' AS MAIL,
                    (SELECT direccion FROM direcciones WHERE Rut = g.rut_cliente LIMIT 1) AS DIRECCION,
                    (SELECT ciudad FROM direcciones WHERE Rut = g.rut_cliente LIMIT 1) AS CIUDAD,
                    (SELECT comuna FROM direcciones WHERE Rut = g.rut_cliente LIMIT 1) AS REGION
                    -- g.*
                    FROM gestion_ult_trimestre AS g
                    WHERE 
                        g.fecha_gestion = '{$hoy}')
                    UNION
                    (SELECT RUT, CONTACTO, MONEDA, COD_EMPRESA,FECHA_HORA_GESTION,TIPO,CONTACTABILIDAD,RESULTADO,
                    REPLACE(COMENTARIO,';',',') AS COMENTARIO,FECHA_COMPROMISO,MONTO_COMPROMISO,MTODEUMOMGES,
                    TRIM(NOMBRE_CLI),FECHA_GESTION,FONO,
                    (SELECT CONCAT(rut,dv) FROM Usuarios WHERE nombre = RUT_USUARIO_ECE LIMIT 1) AS  RUT_USUARIO_ECE,
                    `MOTIVO MORA`, COMENTARIO AS MAIL,DIRECCION,CIUDAD,REGION FROM EMAILS
                    WHERE FECHA_GESTION = '".str_replace('-','',$hoy)."')
                    UNION
                    (SELECT
                    RUT,
                    CONTACTO,
                    MONEDA,
                    COD_EMPRESA,
                    FECHA_HORA_GESTION,
                    TIPO,
                    CONTACTABILIDAD,
                    RESULTADO,
                    REPLACE(COMENTARIO,';',',') AS COMENTARIO,
                    FECHA_COMPROMISO,
                    MONTO_COMPROMISO,
                    MTODEUMOMGES,
                    NOMBRE_CLI,
                    FECHA_GESTION,
                    FONO,
                    (SELECT CONCAT(rut,dv) FROM Usuarios WHERE nombre = RUT_USUARIO_ECE LIMIT 1) AS  RUT_USUARIO_ECE,
                    `MOTIVO MORA`,
                    COMENTARIO AS MAIL,
                    DIRECCION,
                    CIUDAD,
                    REGION
                    FROM Sms
                    WHERE FECHA_GESTION = '".str_replace('-','',$hoy)."')
                    UNION
                    (SELECT
                    gb.RUT,
                    gb.CONTACTO,
                    gb.MONEDA,
                    gb.COD_EMPRESA,
                    gb.FECHA_HORA_GESTION,
                    gb.TIPO,
                    gb.CONTACTABILIDAD,
                    gb.RESULTADO,
                    REPLACE(gb.COMENTARIO,';',',') AS COMENTARIO,
                    gb.FECHA_COMPROMISO,
                    gb.MONTO_COMPROMISO,
                    gb.MTODEUMOMGES,
                    (SELECT Nombre_Completo FROM Persona WHERE Rut = SUBSTRING_INDEX(gb.RUT,'-',1) LIMIT 1) AS NOMBRE_CLI,
                    REPLACE(gb.FECHA_GESTION,'-','') AS FECHA_GESTION,
                    gb.FONO,
                    '111111' AS  RUT_USUARIO_ECE,
                    '' AS `MOTIVO MORA`,
                    gb.COMENTARIO AS MAIL,
                    gb.DIRECCION,
                    gb.CIUDAD,
                    gb.REGION
                    FROM gestiones_voicebot AS gb
                    WHERE gb.FECHA_GESTION = '{$hoy}')";

                //$this->logs->debug($strSQL);
                $Gestiones = $this->db->select($strSQL);
                if (count((array) $Gestiones) > 0 ) {
                    // $this->logs->debug($Gestiones);
                    //$this->logs->debug('Cantidad de filas: '. count((array) $Gestiones));
                    /*
                    $csv = Writer::createFromString();
                    $csv->setEnclosure(' ');
                    $csv->setDelimiter(';');
                    //$csv->insertOne(array_keys($Gestiones[0]));
                    $csv->insertAll((array) $Gestiones);
                    //$this->logs->debug('Insertadas: '.$insertadas);
                    return $csv->toString();
                    */
                    $output = fopen("php://output",'w');
                    foreach((array) $Gestiones as $Gestion) {
                        fwrite($output, implode(';', array_values($Gestion)) . "\r\n");
                    }
                    fclose($output);
                }
                echo '';
            } catch (\Exception $ex) {
                $this->logs->error($ex->getMessage());
                echo 'ERROR';
                exit;
            }
        }

        public function emxAcciones($data)
        {
            try {
                $hoy = (!isset($data['fecha']) || empty($data['fecha']))? date('Y-m-d'): $data['fecha'];
                $strSQL = "SELECT
                    CONCAT(g.rut_cliente, DATE_FORMAT(fechahora,'%Y%m%d%H%i')) AS clave_accion,
                    'BUSINESSPRO' AS origen,
                    '0' AS baja,
                    g.nombre_ejecutivo AS usuario,
                    (SELECT IF(Entidad = 'BIC','039','733') FROM Deuda WHERE Rut = g.rut_cliente AND Id_Cedente = g.cedente LIMIT 1) AS clave_empresa,
                    (SELECT CONCAT(Rut,Digito_Verificador) FROM Persona WHERE Rut = g.rut_cliente LIMIT 1) AS clave_persona,
                    '' AS clave_cuenta,
                    'Acción migrada' AS clave_objeto_gestion,
                    'TEMPRANA' AS clave_workflow,
                    (SELECT Codigo FROM Nivel1 WHERE id = g.n1 LIMIT 1) AS clave_tipo_accion,
                    '' AS met_codigo,
                    DATE_FORMAT(g.fechaHora, '%Y%m%d %H:%i') AS fecha_hora,
                    IF(g.Observacion IS NOT NULL,REPLACE(g.Observacion,';',','),'') AS comentario,
                    (SELECT Codigo FROM Nivel3 WHERE id = g.n3 LIMIT 1) AS clave_tipo_respuesta,
                    g.nombre_ejecutivo AS clave_usuario,
                    '0' AS costo,
                    g.duracion AS duracion,
                    '' AS clave_justificacion,
                    '' AS clave_tipo_vinc_per,
                    fono_discado AS dato_contacto,
                    'E' AS entrada_salida
                FROM gestion_ult_trimestre AS g
                WHERE fecha_gestion = '{$hoy}'
                ORDER BY fechahora DESC";

                //$this->logs->debug($strSQL);
                $Gestiones = $this->db->select($strSQL);
                //$this->logs->debug($Gestiones);

                $diaHabil = str_replace('-','', $this->__getHabilDay($hoy));
                $cant = count((array) $Gestiones);
                if ($cant > 0) {
                    /*
                    $Gestiones = array_map(function ($item) {
                        if (array_keys($item) == 'clave_accion') $item['clave_accion'] = (string) $item['clave_accion'];
                        return $item;
                    }, (array) $Gestiones);
                    */
                    //$this->logs->debug('Cantidad de filas: '. count((array) $Gestiones));
                    /*
                    $csv = Writer::createFromString();
                    $csv->setDelimiter(';');
                    $csv->insertOne(['HEADER',$diaHabil]);
                    $csv->insertAll((array) $Gestiones);
                    $csv->insertOne(['TRAILER', $cant]);
                    //$this->logs->debug('Insertadas: '.$insertadas);
                    return $csv->toString();
                    */

                    $output = fopen("php://output",'w');
                    fwrite($output, 'HEADER;'.$diaHabil."\r\n");
                    foreach((array) $Gestiones as $Gestion) {
                        fwrite($output, implode(';', array_values($Gestion)) . "\r\n");
                    }
                    fwrite($output, 'TRAILER;'.$cant."\r\n");
                    fclose($output);
                }
                return '';
            } catch (\Exception $ex) {
                $this->logs->error($ex->getMessage());
                echo 'ERROR';
                exit;
            }
        }

        public function emxPromesas($data)
        {
            try {
                $hoy = (!isset($data['fecha']) || empty($data['fecha']))? date('Y-m-d'): $data['fecha'];
                $strSQL = "SELECT 
                    IF (d.Entidad = 'BIC','039','733') AS clave_empresa,
                    'BUSINESSPRO' AS origen,
                    (SELECT Codigo FROM Nivel3 WHERE id = g.n3 LIMIT 1) AS clave_promesa,
                    g.nombre_ejecutivo AS usuario,
                    (SELECT CONCAT(Rut,Digito_Verificador) FROM Persona WHERE Rut = d.Rut LIMIT 1) AS clave_persona,
                    '' AS clave_cuenta,
                    '0' AS parcial,
                    DATE_FORMAT(g.fec_compromiso,'%Y%m%d') AS fecha_promesa,
                    'CLP' AS clave_moneda,
                    REPLACE(REPLACE(FORMAT(g.monto_comp,0,'es_CL'),'.',''),',','.') AS monto,
                    (SELECT REPLACE(REPLACE(FORMAT(SUM(Deuda),0,'es_CL'),'.',''),',','.') FROM Deuda WHERE Rut = g.rut_cliente LIMIT 1) AS deuda,
                    IF(g.observacion IS NOT NULL,REPLACE(g.observacion,';',','),'') AS comentario,
                    '0' AS baja,
                    DATE_FORMAT(g.fecha_gestion, '%Y%m%d') AS alta_fecha,
                    '0' AS sumatoria_deudas
                    FROM 
                        gestion_ult_trimestre AS g
                        JOIN Deuda AS d
                        ON g.rut_cliente = d.Rut
                    WHERE 
                        g.fecha_gestion = '{$hoy}'
                        AND (g.fec_compromiso IS NOT NULL AND g.fec_compromiso != '')
                    GROUP BY d.Rut, g.monto_comp;";

                //$this->logs->debug($strSQL);
                $Gestiones = $this->db->select($strSQL);
                //$this->logs->debug($Gestiones);

                $diaHabil = str_replace('-','', $this->__getHabilDay($hoy));
                $cant = count((array) $Gestiones);
                if ($cant > 0) {
                    /*
                    $Gestiones = array_map(function ($item) {
                        if (array_keys($item) == 'clave_accion') $item['clave_accion'] = (string) $item['clave_accion'];
                        return $item;
                    }, (array) $Gestiones);
                    */
                    //$this->logs->debug('Cantidad de filas: '. count((array) $Gestiones));
                    /*
                    $csv = Writer::createFromString();
                    $csv->setDelimiter(';');
                    $csv->insertOne(['HEADER',$diaHabil]);
                    $insertadas = (int) $csv->insertAll((array) $Gestiones);
                    $csv->insertOne(['TRAILER', $cant]);
                    //$this->logs->debug('Insertadas: '.$insertadas);
                    return $csv->toString();
                    */
                    $output = fopen("php://output",'w');
                    fwrite($output, 'HEADER;'.$diaHabil."\r\n");
                    foreach((array) $Gestiones as $Gestion) {
                        fwrite($output, implode(';', array_values($Gestion)) . "\r\n");
                    }
                    fwrite($output, 'TRAILER;'.$cant."\r\n");
                    fclose($output);
                }
                return '';
            } catch (\Exception $ex) {
                $this->logs->error($ex->getMessage());
                echo 'ERROR';
                exit;
            }
        }

        public function extraSinTelefonos()
        {
            try {
                $rs = $this->db->select("SELECT Id_Cedente FROM mandante_cedente WHERE Id_Mandante = {$this->Id_Mandante};");
                $cedentesId = array_map(function ($item) {
                    return (int) $item['Id_Cedente'];
                }, (array) $rs);
                //this->logs->debug($cedentesId);
                $registros = $this->db->select("SELECT t1.Rut, t1.Digito_Verificador AS DV, t1.Nombre_Completo AS Nombre FROM Persona t1 WHERE NOT EXISTS (SELECT NULL FROM fono_cob t2 WHERE t2.Rut = t1.Rut) AND t1.Id_Cedente IN (".implode(',',$cedentesId).") ORDER BY t1.Rut ASC;");
                $cant = count((array) $registros);
                if ($cant > 0) {
                    $csv = Writer::createFromString();
                    $csv->setDelimiter(';');
                    $csv->insertOne(array_keys($registros[0]));
                    $csv->insertAll((array)$registros);
                    return $csv->toString();
                }
                return '';
            } catch (\Exception $ex) {
                $this->logs->error($ex->getMessage());
                echo 'ERROR';
                exit;
            }
        }

        public function extraSinEmails()
        {
            try {
                $rs = $this->db->select("SELECT Id_Cedente FROM mandante_cedente WHERE Id_Mandante = {$this->Id_Mandante};");
                $cedentesId = array_map(function ($item) {
                    return (int) $item['Id_Cedente'];
                }, (array) $rs);
                //$this->logs->debug($cedentesId);
                $registros = $this->db->select("SELECT t1.Rut, t1.Digito_Verificador AS DV, t1.Nombre_Completo AS Nombre FROM Persona t1 WHERE NOT EXISTS (SELECT NULL FROM Email t2 WHERE t2.Rut = t1.Rut) AND t1.Id_Cedente IN (".implode(',',$cedentesId).") ORDER BY t1.Rut ASC;");
                $cant = count((array) $registros);
                if ($cant > 0) {
                    $csv = Writer::createFromString();
                    $csv->setDelimiter(';');
                    $csv->insertOne(array_keys($registros[0]));
                    $csv->insertAll((array)$registros);
                    return $csv->toString();
                }
                return '';
            } catch (\Exception $ex) {
                $this->logs->error($ex->getMessage());
                echo 'ERROR';
                exit;
            }
        }

        public function extraSinDirecciones()
        {
            try {
                $rs = $this->db->select("SELECT Id_Cedente FROM mandante_cedente WHERE Id_Mandante = {$this->Id_Mandante};");
                $cedentesId = array_map(function ($item) {
                    return (int) $item['Id_Cedente'];
                }, (array) $rs);
                //$this->logs->debug($cedentesId);
                $registros = $this->db->select("SELECT t1.Rut, t1.Digito_Verificador AS DV, t1.Nombre_Completo AS Nombre FROM Persona t1 WHERE NOT EXISTS (SELECT NULL FROM direcciones t2 WHERE t2.Rut = t1.Rut) AND t1.Id_Cedente IN (".implode(',',$cedentesId).") ORDER BY t1.Rut ASC;");
                $cant = count((array) $registros);
                if ($cant > 0) {
                    $csv = Writer::createFromString();
                    $csv->setDelimiter(';');
                    $csv->insertOne(array_keys($registros[0]));
                    $csv->insertAll((array)$registros);
                    return $csv->toString();
                }
                return '';
            } catch (\Exception $ex) {
                $this->logs->error($ex->getMessage());
                echo 'ERROR';
                exit;
            }
        }

        public function extraSinGestiones($datos)
        {
            try {
                $mes = date('Y-m');
                $registros = $this->db->select("SELECT t1.Cartera, (SELECT CONCAT(Rut,'-',Digito_Verificador) FROM Persona AS p WHERE p.Rut = t1.Rut LIMIT 1) AS Rut, (SELECT Nombre_Completo FROM Persona AS p WHERE p.Rut = t1.Rut LIMIT 1) AS Nombre, t1.Deuda FROM Deuda t1 WHERE NOT EXISTS (SELECT NULL FROM gestion_ult_trimestre t2 WHERE t2.rut_cliente = t1.Rut AND DATE_FORMAT(t2.fecha_gestion, '%Y-%m') = '{$mes}') AND t1.Id_Cedente = {$datos['cartera']} GROUP BY t1.Rut ORDER BY t1.Deuda DESC;");
                $cant = count((array) $registros);
                if ($cant > 0) {
                    $csv = Writer::createFromString();
                    $csv->setDelimiter(';');
                    $csv->insertOne(array_keys($registros[0]));
                    $csv->insertAll((array)$registros);
                    return $csv->toString();
                }
                return '';
            } catch (\Exception $ex) {
                $this->logs->error($ex->getMessage());
                echo 'ERROR';
                exit;
            }
        }

        private function __getHabilDay($date)
        {
            $dayofweek = date('w', strtotime($date));
            $fecha = new \DateTime($date);
            $dias = 1;
            if (in_array((int) $dayofweek, [1])) { 
                $dias = 1;
            } 
            $date = $fecha->sub(new \DateInterval("P{$dias}D"))->format('Y-m-d');
            return $date;
        }

        private function __getGestionesGeneral($data)
        {
            $Gestiones = [];
            try {
                //$this->logs->debug('__getGestionesGeneral');
                //$this->logs->debug($data);
                $idCedente = (int) $data['proyecto'];
                $hoy = date('Y-m-d');
                if(isset($data['mes']) && !empty($data['mes'])) $hoy = date('Y-m-d', strtotime(trim($data['mes'])));
                $query = "SELECT
                b.rut_cliente AS rut, 
                b.fecha_gestion AS Fecha_gestion,
                DATE_FORMAT(b.fechahora, '%H:%i') AS HoraGestion,
                DATE_FORMAT(b.fechahora, '%H:00') AS Tramo_Hora,
                a.ultimo_fono AS Fono_Discado,
                'ADT' AS OrigenFono, 
                a.nombre_ejecutivo AS Nombre_Ejecutivo,
                'CALL' AS OrigenGestion,
                a.hnivel1 AS Nivel1,
                a.hnivel2 AS Nivel2,
                a.hnivel3 AS Nivel3,
                b.observacion AS Observacion,
                (IF ((SELECT r.Respuesta_N1 FROM `Nivel1` AS r WHERE r.Id_Cedente = b.cedente AND r.id = b.n1 LIMIT 1) = 'TITULAR', b.fono_discado, '')) AS Fono_mejor_gestion,
                DATE(b.fechahora) AS FechaMejorGestionCedente,
                (SELECT r.Respuesta_N1 FROM `Nivel1` AS r WHERE r.Id_Cedente = b.cedente AND r.id = b.n1 LIMIT 1) AS Nivel1MGC,
                (SELECT r2.Respuesta_N2 FROM `Nivel2` AS r2 WHERE r2.Id_Nivel1 = n1 AND r2.id = b.n2 LIMIT 1) AS Nivel2MGC,
                (SELECT r3.Respuesta_N3 FROM `Nivel3` AS r3 WHERE r3.Id_Nivel2 = n2 AND r3.id = b.n3 LIMIT 1) AS Nivel3MGC,
                a.intensidad_call,
                a.Intensidad_TITU,
                a.Intensidad_ADM,
                a.cantidad AS TotIntensidad,
                a.ultima_gestion AS fechaUltGes,
                a.ultimo_agente AS UsuarioUltGes,
                b.fec_compromiso AS fechaCompromiso,
                IF(b.monto_comp > 0, b.monto_comp, '')  AS monto,
                (SELECT cc.Cartera FROM Deuda AS cc WHERE cc.Id_Cedente = b.cedente LIMIT 1) AS COBRADORES,
                TIMESTAMPDIFF(DAY, fecha_gestion, CURRENT_DATE()) AS DiasSinGestion
                FROM
                    (
                    SELECT 
                        MAX(u.id_gestion) AS id_ultima,
                        u.id_gestion,
                        u.rut_cliente, 
                        u.Peso AS ultimo_peso,
                        MIN(u.Peso) AS mayor_peso, 
                        COUNT(1) AS cantidad,
                        u.fono_discado AS ultimo_fono,
                        u.nombre_ejecutivo,
                        u.fechahora, u.nombre_ejecutivo AS ultimo_agente,
                        (SELECT r.Respuesta_N1 FROM `Nivel1` AS r WHERE r.Id_Cedente = u.cedente AND r.id = (SELECT n1 FROM gestion_ult_trimestre WHERE id_gestion = u.id_gestion LIMIT 1)) AS hnivel1, 
                        (SELECT r2.Respuesta_N2 FROM `Nivel2` AS r2 WHERE r2.Id_Nivel1 = (SELECT n1 FROM gestion_ult_trimestre WHERE id_gestion = MAX(u.id_gestion) LIMIT 1) AND r2.id = (SELECT n2 FROM gestion_ult_trimestre WHERE id_gestion = MAX(u.id_gestion) LIMIT 1)) AS hnivel2, 
                        (SELECT r3.Respuesta_N3 FROM `Nivel3` AS r3 WHERE r3.Id_Nivel2 = (SELECT n2 FROM gestion_ult_trimestre WHERE id_gestion = MAX(u.id_gestion) LIMIT 1) AND r3.id = (SELECT n3 FROM gestion_ult_trimestre WHERE id_gestion = MAX(u.id_gestion) LIMIT 1)) AS hnivel3,
                        SUM(IF ((SELECT r.Respuesta_N1 FROM `Nivel1` AS r WHERE r.Id_Cedente = u.cedente AND r.id = u.n1 LIMIT 1) != 'TITULAR', 1, 0)) AS intensidad_call,
                        SUM(IF ((SELECT r.Respuesta_N1 FROM `Nivel1` AS r WHERE r.Id_Cedente = u.cedente AND r.id = u.n1 LIMIT 1) = 'TITULAR', 1, 0)) AS Intensidad_TITU,
                        SUM(IF ((SELECT r.Respuesta_N1 FROM `Nivel1` AS r WHERE r.Id_Cedente = u.cedente AND r.id = u.n1 LIMIT 1) = 'ADMINISTRATIVO', 1, 0)) AS Intensidad_ADM,
                        DATE(MAX(u.fechahora)) AS ultima_gestion
                     FROM gestion_ult_trimestre AS u WHERE DATE_FORMAT(u.fecha_gestion, '%Y-%m') = DATE_FORMAT('{$hoy}', '%Y-%m')
                     GROUP BY u.rut_cliente
                     ) a
                    JOIN gestion_ult_trimestre b
                ON a.rut_cliente = b.rut_cliente AND a.mayor_peso = b.Peso 
                WHERE DATE_FORMAT(b.fecha_gestion, '%Y-%m') = DATE_FORMAT('{$hoy}', '%Y-%m');";
                //$this->logs->debug($query);
                $Gestiones = $this->db->select($query);
            } catch (\Exception $ex) {
                $this->logs->error('ERROR: __getGestionesGeneral()');
                $this->logs->error($ex->getMessage());
            }
            //$db = new Db();
            return $Gestiones;
        }

        private function __organizeContactabilidad($rows)
        {
            $datos = [];
            $newArr = [];

            if (count($rows) > 0) {
                foreach($rows as $values) {
                  $newArr[$values['gestion_n1']]['nombre'] = $values['gestion_n1'];
                  $newArr[$values['gestion_n1']]['total_alta'] = 0;
                  $newArr[$values['gestion_n1']]['total_baja'] = 0;
                  $newArr[$values['gestion_n1']]['ruts'] = 0;
                  $newArr[$values['gestion_n1']]['items'][] =  $values;
                }
          
                foreach($newArr as $key => $value) {
                  $newArr[$key]['ruts'] = array_reduce($value['items'], function($carry, $item) {
                    $carry += (int) $item['ruts'];
                    return $carry;
                  }, 0);
                  $newArr[$key]['total_alta'] = array_reduce($value['items'], function($carry, $item) {
                    $carry += (float) $item['total_alta'];
                    return $carry;
                  }, 0);
          
                  $newArr[$key]['total_baja'] = array_reduce($value['items'], function($carry, $item) {
                    $carry += (float) $item['total_baja'];
                    return $carry;
                  }, 0);
                }
          
                $general['ruts'] = array_reduce(array_values($newArr), function($carry, $item) {
                  $carry += (int) $item['ruts'];
                  return $carry;
                }, 0);
              
                $general['total_alta'] = array_reduce(array_values($newArr), function($carry, $item) {
                  $carry += (float) $item['total_alta'];
                  return $carry;
                }, 0);
          
                $general['total_baja'] = array_reduce(array_values($newArr), function($carry, $item) {
                  $carry += (float) $item['total_baja'];
                  return $carry;
                }, 0);

                $datos['general'] = $general;

            }
      
            $datos['rows'] = $newArr;            
            return $datos;
        }

        private function __getContactabilidad($data)
        {
            $proyectos = [];
            $fDesde = $data['cfDesde'];
            $fHasta = $data['cfHasta'];
            //$this->logs->debug($data);

            $selectVista_0 = " WHERE (DATE(fecha_gestion) BETWEEN '{$fDesde}' AND '{$fHasta}') ";
            $selectVista_a = " AND (DATE(u.fecha_gestion) BETWEEN '{$fDesde}' AND '{$fHasta}') ";
            $selectVista_b = " AND (DATE(b.fecha_gestion)  BETWEEN '{$fDesde}' AND '{$fHasta}') ";

            if ($fDesde == $fHasta) {
                $selectVista_0 = " WHERE DATE(fecha_gestion) = '{$fHasta}' ";
                $selectVista_a = " AND (DATE(u.fecha_gestion) = '{$fHasta}') ";
                $selectVista_b = " AND (DATE(b.fecha_gestion) = '{$fHasta}') ";
            }

            $strSQL = "SELECT cedente AS proyecto, (SELECT c.Nombre_Cedente FROM Cedente AS c WHERE c.Id_Cedente = cedente LIMIT 1) AS nombre_proyecto, COUNT(DISTINCT rut_cliente) AS ruts, MAX(fecha_gestion) AS ultima_gestion FROM gestion_ult_trimestre {$selectVista_0} GROUP BY cedente;";
            $grupos = $this->db->select($strSQL);
            if (count((array) $grupos) > 0) {
                foreach ((array) $grupos as $grupo) {
                    if (($grupo['proyecto']) == 1) $idNivel = 26;
                    if (($grupo['proyecto']) == 2) $idNivel = 72;
                    if (($grupo['proyecto']) == 3) $idNivel = 118;
                    $strSqlAlta = "SELECT
                    SUM(d.Saldo) AS total_alta,
                    SUM(d.Saldo) AS total_baja,
                    SUM(d.Saldo_Dia) AS saldos,
                    SUM(d.Saldo - d.Saldo_Dia) AS recupero,
                    d.estado,
                    COUNT(d.Factura) AS facturas,
                    COUNT(DISTINCT d.Rut) AS ruts,
                    (
                        SELECT g.n2 FROM gestion_ult_trimestre AS g WHERE g.rut_cliente = d.Rut AND g.cedente = d.Id_Cedente AND (DATE(g.fecha_gestion) BETWEEN '{$fDesde}' AND '{$fHasta}') ORDER BY g.Peso ASC, g.fecha_gestion DESC LIMIT 1
                    ) AS ultima_gestion,
                    (SELECT IF (ultima_gestion IS NULL, {$idNivel}, ultima_gestion)) AS n2,
                    (SELECT n1.Respuesta_N1 FROM Nivel1 AS n1 JOIN Nivel2 AS nn ON n1.id = nn.Id_Nivel1 WHERE nn.id = n2) AS gestion_n1,
                    (SELECT n2.Respuesta_N2 FROM Nivel2 AS n2 WHERE n2.id = n2) AS gestion_n2,
                        DATE(d.Fecha_Descargo) AS fecha_descargo
                    FROM
                        Deuda AS d
                    WHERE 
                        d.Id_Cedente = {$grupo['proyecto']}
                        AND d.estado = 'ALTA'
                    GROUP BY gestion_n2
                    ORDER BY gestion_n1 DESC, ruts DESC";

                    $strSqlBaja = "SELECT
                    SUM(d.Saldo) AS total_baja,
                    SUM(d.Saldo) AS total_alta,
                    SUM(d.Saldo_Dia) AS saldos,
                    SUM(d.Saldo - d.Saldo_Dia) AS recupero,
                    d.estado,
                    COUNT(d.Factura) AS facturas,
                    COUNT(DISTINCT d.Rut) AS ruts,
                    (
                        SELECT g.n2 FROM gestion_ult_trimestre AS g WHERE g.rut_cliente = d.Rut AND g.cedente = d.Id_Cedente AND (DATE(g.fecha_gestion) BETWEEN '{$fDesde}' AND '{$fHasta}') ORDER BY g.Peso ASC, g.fecha_gestion DESC LIMIT 1
                    ) AS ultima_gestion,
                    (SELECT IF (ultima_gestion IS NULL, {$idNivel}, ultima_gestion)) AS n2,
                    (SELECT n1.Respuesta_N1 FROM Nivel1 AS n1 JOIN Nivel2 AS nn ON n1.id = nn.Id_Nivel1 WHERE nn.id = n2) AS gestion_n1,
                    (SELECT n2.Respuesta_N2 FROM Nivel2 AS n2 WHERE n2.id = n2) AS gestion_n2,
                        DATE(d.Fecha_Descargo) AS fecha_descargo
                    FROM
                        Deuda AS d
                    WHERE 
                        d.Id_Cedente = {$grupo['proyecto']}
                        AND d.estado = 'BAJA'
                    GROUP BY gestion_n2
                    ORDER BY gestion_n1 DESC, ruts DESC";

                    //$this->logs->debug('****** '.$grupo['nombre_proyecto'].' ******');
                    //$this->logs->debug($strSqlAlta);                    
                    $rsAlta = $this->db->select($strSqlAlta);
                    //$this->logs->debug($rsAlta);

                    $rsBaja = $this->db->select($strSqlBaja);
                    //$this->logs->debug($rsBaja);

                    //$this->logs->debug($strSqlBaja);
                    //$this->logs->debug('****** '.$grupo['nombre_proyecto'].' ******');

                    /*
                    echo 'Proyecto: '.$grupo['nombre_proyecto'].'<br/>';
                    echo 'Alta: '.$strSqlAlta.'<br/>';
                    echo '<br/>Baja: '.$strSqlBaja.'<br/>';
                    */
                    $proyectos[] = [
                        'proyecto' => $grupo['nombre_proyecto'],
                        'hojas' => [
                            'alta' => (array) $rsAlta,
                            'baja' => (array) $rsBaja
                        ],
                    ];
                }
            }
            return $proyectos;
        }

        private function __getMayoresDeudores($data)
        {
            $proyectos = [];
            $strSQL = "SELECT cedente AS proyecto, (SELECT c.Nombre_Cedente FROM Cedente AS c WHERE c.Id_Cedente = cedente LIMIT 1) AS nombre_proyecto, COUNT(DISTINCT rut_cliente) AS ruts, MAX(fecha_gestion) AS ultima_gestion FROM gestion_ult_trimestre WHERE DATE_FORMAT(fecha_gestion, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m') GROUP BY cedente;";
            $grupos = $this->db->select($strSQL);
            if (count((array) $grupos) > 0) {
                foreach ((array) $grupos as $grupo) {
                    $strSQL = "SELECT 
                        p.Rut,
                        UPPER(p.Nombre_completo) AS Nombre,
                        s.total,
                        (SELECT CONCAT(fono_discado, ' | ', DATE_FORMAT(fecha_gestion,'%d-%m-%Y'),' | ', (SELECT n.Respuesta_N2 FROM Nivel2 AS n WHERE n.id = n2 LIMIT 1)) FROM gestion_ult_trimestre WHERE rut_cliente = p.Rut AND cedente = p.Id_Cedente ORDER BY fecha_gestion DESC LIMIT 1) AS ultima_gestion
                    FROM
                    (SELECT 
                        Rut,
                        Id_Cedente,
                        SUM(deuda) AS total
                    FROM Deuda 
                    WHERE Id_Cedente = {$grupo['proyecto']}
                    AND deuda > 0
                    GROUP BY Rut 
                    ORDER BY total DESC
                    LIMIT 30) AS s
                    JOIN Persona AS p ON p.Rut = s.Rut AND p.Id_Cedente = s.Id_Cedente
                    WHERE p.Id_Cedente = {$grupo['proyecto']}
                    ORDER BY s.total DESC;";

                    $deudores = $this->db->select($strSQL);

                    $proyectos[] = [
                        'proyecto' => $grupo['nombre_proyecto'],
                        'rows' => $deudores,
                    ];
                }
            }

            return $proyectos;
        }

        private function __getContactabilidadPorAgente($data)
        {
            $proyectos = [];
            $fDesde = $data['cfDesde'];
            $fHasta = $data['cfHasta'];
            
            if (!isset($data['agente']) || empty($data['agente'])) return $proyectos;
            $agente = trim($data['agente']);

            $selectVista_0 = " WHERE (DATE(fecha_gestion) BETWEEN '{$fDesde}' AND '{$fHasta}') ";
            $selectVista_a = " AND (DATE(u.fecha_gestion) BETWEEN '{$fDesde}' AND '{$fHasta}') ";
            $selectVista_b = " AND (DATE(b.fecha_gestion)  BETWEEN '{$fDesde}' AND '{$fHasta}') ";

            if ($fDesde == $fHasta) {
                $selectVista_0 = " WHERE DATE(fecha_gestion) = '{$fHasta}' ";
                $selectVista_a = " AND (DATE(u.fecha_gestion) = '{$fHasta}') ";
                $selectVista_b = " AND (DATE(b.fecha_gestion) = '{$fHasta}') ";
            }

            $strSQL = "SELECT cedente AS proyecto, (SELECT c.Nombre_Cedente FROM Cedente AS c WHERE c.Id_Cedente = cedente LIMIT 1) AS nombre_proyecto, COUNT(DISTINCT rut_cliente) AS ruts, MAX(fecha_gestion) AS ultima_gestion FROM gestion_ult_trimestre {$selectVista_0} GROUP BY cedente;";
            $grupos = $this->db->select($strSQL);
            if (count((array) $grupos) > 0) {
                foreach ((array) $grupos as $grupo) {
                    if (($grupo['proyecto']) == 1) $idNivel = 26;
                    if (($grupo['proyecto']) == 2) $idNivel = 72;
                    if (($grupo['proyecto']) == 3) $idNivel = 118;
                    $strSqlAlta = "SELECT
                    '{$agente}' AS agente,
                    SUM(d.Saldo) AS total_alta,
                    SUM(d.Saldo) AS total_baja,
                    SUM(d.Saldo_Dia) AS saldos,
                    SUM(d.Saldo - d.Saldo_Dia) AS recupero,
                    d.estado,
                    COUNT(d.Factura) AS facturas,
                    COUNT(DISTINCT d.Rut) AS ruts,
                    (
                        SELECT g.n2 FROM gestion_ult_trimestre AS g WHERE g.rut_cliente = d.Rut AND g.cedente = d.Id_Cedente AND DATE_FORMAT(fecha_gestion, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m') AND g.nombre_ejecutivo = '{$agente}' ORDER BY g.Peso ASC, g.fecha_gestion DESC LIMIT 1
                    ) AS ultima_gestion,
                    (SELECT IF (ultima_gestion IS NULL, {$idNivel}, ultima_gestion)) AS n2,
                    (SELECT n1.Respuesta_N1 FROM Nivel1 AS n1 JOIN Nivel2 AS nn ON n1.id = nn.Id_Nivel1 WHERE nn.id = n2) AS gestion_n1,
                    (SELECT n2.Respuesta_N2 FROM Nivel2 AS n2 WHERE n2.id = n2) AS gestion_n2,
                        DATE(d.Fecha_Descargo) AS fecha_descargo
                    FROM
                        Deuda AS d
                    WHERE 
                        d.Id_Cedente = {$grupo['proyecto']}
                        AND d.estado = 'ALTA'
                    GROUP BY gestion_n2
                    ORDER BY gestion_n1 DESC, ruts DESC";

                    $strSqlBaja = "SELECT
                    '{$agente}' AS agente,
                    SUM(d.Saldo) AS total_baja,
                    SUM(d.Saldo) AS total_alta,
                    SUM(d.Saldo_Dia) AS saldos,
                    SUM(d.Saldo - d.Saldo_Dia) AS recupero,
                    d.estado,
                    COUNT(d.Factura) AS facturas,
                    COUNT(DISTINCT d.Rut) AS ruts,
                    (
                        SELECT g.n2 FROM gestion_ult_trimestre AS g WHERE g.rut_cliente = d.Rut AND g.cedente = d.Id_Cedente AND DATE_FORMAT(fecha_gestion, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m') AND g.nombre_ejecutivo = '{$agente}' ORDER BY g.Peso ASC, g.fecha_gestion DESC LIMIT 1
                    ) AS ultima_gestion,
                    (SELECT IF (ultima_gestion IS NULL, {$idNivel}, ultima_gestion)) AS n2,
                    (SELECT n1.Respuesta_N1 FROM Nivel1 AS n1 JOIN Nivel2 AS nn ON n1.id = nn.Id_Nivel1 WHERE nn.id = n2) AS gestion_n1,
                    (SELECT n2.Respuesta_N2 FROM Nivel2 AS n2 WHERE n2.id = n2) AS gestion_n2,
                        DATE(d.Fecha_Descargo) AS fecha_descargo
                    FROM
                        Deuda AS d
                    WHERE 
                        d.Id_Cedente = {$grupo['proyecto']}
                        AND d.estado = 'BAJA'
                    GROUP BY gestion_n2
                    ORDER BY gestion_n1 DESC, ruts DESC";
                    
                    $rsAlta = $this->db->select($strSqlAlta);
                    $rsBaja = $this->db->select($strSqlBaja);
                    /*
                    echo 'Proyecto: '.$grupo['nombre_proyecto'].'<br/>';
                    echo 'Alta: '.$strSqlAlta.'<br/>';
                    echo '<br/>Baja: '.$strSqlBaja.'<br/>';
                    */
                    $proyectos[] = [
                        'proyecto' => $grupo['nombre_proyecto'],
                        'hojas' => [
                            'alta' => (array) $rsAlta,
                            'baja' => (array) $rsBaja
                        ],
                    ];
                }
            }
            return $proyectos;
        }
    }
?>