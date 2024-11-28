<?php 
class Telefonia
{
    function __construct(){
	}


    public function getReporteTelefonia($start, $end , $proveedor){
        $db = new DB('asterisk');

        $sqlBillMovil = "SELECT COALESCE(SUM(billsec),0) as suma  FROM cdr WHERE calldate between '" . $start . "' AND '" . $end . "' AND dst LIKE '9%' AND dcontext = '" . $proveedor. "'";
        $resultBillMovil = $db->select($sqlBillMovil);

        $sqlBillFijo= "SELECT COALESCE(SUM(billsec),0) as suma  FROM cdr WHERE calldate between '" . $start . "' AND '" . $end . "' AND dst NOT LIKE '9%' AND dcontext = '" . $proveedor. "'";
        $resultBillFijo = $db->select($sqlBillFijo);

        $sqlMovil = "SELECT count(*) as cantidad FROM cdr WHERE dst LIKE '9%' and calldate between '" . $start . "' AND '" . $end . "' AND dcontext = '" . $proveedor. "'";
        $resultMovil = $db->select($sqlMovil);

        $sqlFijo = "SELECT count(*) as cantidad FROM cdr WHERE dst NOT LIKE '9%' and calldate between '" . $start . "' AND '" . $end . "' AND dcontext = '" . $proveedor. "'";
        $resultFijo = $db->select($sqlFijo);

        $db2 = new DB();
        $sqlCostoFijo = "SELECT costoTelefonia FROM mantenedor_telefonia WHERE tipo = 'fijo'";
        $resultCostoFijo = $db2->select($sqlCostoFijo);

        $sqlCostoMovil= "SELECT costoTelefonia FROM mantenedor_telefonia WHERE tipo = 'movil'";
        $resultCostoMovil = $db2->select($sqlCostoMovil);

        $costoFijo = ($resultCostoFijo[0]["costoTelefonia"]*$resultFijo[0]["cantidad"]);
        $costoMovil= ($resultCostoMovil[0]["costoTelefonia"]*$resultMovil[0]["cantidad"]);
        $costoTotal = $costoFijo+$costoMovil;

        $arreglo = array();
        $arreglo["billMovil"] = $resultBillMovil[0]["suma"];
        $arreglo["billFijo"] = $resultBillFijo[0]["suma"];
        $arreglo["billTotal"] = $resultBillFijo[0]["suma"]+$resultBillMovil[0]["suma"];
        $arreglo["costoFijo"] = $costoFijo;
        $arreglo["costoMovil"] = $costoMovil;
        $arreglo["costoTotal"] = $costoTotal;
        $arreglo["proveedor"] = $proveedor;
        return $arreglo;
    }

    public function getProveedor(){
        $db = new DB('discador');
        $sql = "SELECT Codigo FROM dialPlan";
        $response = $db->select($sql);
        return $response;
    }
}
?>