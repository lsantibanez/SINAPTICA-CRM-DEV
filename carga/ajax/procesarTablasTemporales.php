<?php
    include_once("../../class/db/DB.php");
    include_once("../../includes/functions/Functions.php");

    if(!isset($_SESSION)){
        session_start();
    }
    
    $Id_Cedente     = $_SESSION['cedente'];
    $Id_Mandante    = $_SESSION['mandante'];
    $Fecha          = date('Y-m-d');
    $IdCFecha       = $Id_Cedente."_".$Fecha;
    $tipoCarga      = "nueva";

    if(isset($_POST['tipoCarga'])){
        $tipoCarga = $_POST['tipoCarga'];
    }

    $db = new DB();

    switch($tipoCarga){
        case 'nueva':
        case 'actualizacion':
            $ArrayColumnsPersona = array();
            $columnas = $db->select("select COLUMN_NAME as Field from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = 'Persona_tmp'");
            foreach($columnas as $columna){
                if($columna["Field"] !== "id_persona"){
                    array_push($ArrayColumnsPersona, $columna["Field"]);
                }
            }
            $ArrayImplodePersona = implode(',',$ArrayColumnsPersona);

            switch($tipoCarga){
                case 'nueva':
                    $db->query("INSERT INTO Persona_Historico ($ArrayImplodePersona) SELECT $ArrayImplodePersona FROM Persona WHERE FIND_IN_SET($Id_Cedente,Id_Cedente)");
                    $db->query("UPDATE Persona SET Id_Cedente = REPLACE(REPLACE(Id_Cedente,',$Id_Cedente',''),'$Id_Cedente,','') WHERE FIND_IN_SET($Id_Cedente,Id_Cedente)");
                    $db->query("DELETE FROM Persona WHERE Id_Cedente = $Id_Cedente");
                    $db->query("UPDATE Persona_Periodo SET Id_Cedente = REPLACE(Id_Cedente,',$Id_Cedente','') WHERE FIND_IN_SET($Id_Cedente,Id_Cedente)");
                    $db->query("DELETE FROM Persona_Periodo WHERE Id_Cedente = $Id_Cedente");


                    $QueryPersona = "INSERT INTO Persona_Periodo($ArrayImplodePersona) SELECT * FROM Persona_tmp ON DUPLICATE KEY UPDATE Persona_Periodo.Id_Cedente = CONCAT(REPLACE(Persona_Periodo.Id_Cedente,',$Id_Cedente',''),',','$Id_Cedente'), Persona_Periodo.Mandante = CONCAT(REPLACE(Persona_Periodo.Mandante,',$Id_Mandante',''),',','$Id_Mandante')";
                    $db->query($QueryPersona);
                break;
                case 'actualizacion':
                break;
            }
            $QueryPersona = "INSERT INTO Persona($ArrayImplodePersona) SELECT * FROM Persona_tmp ON DUPLICATE KEY UPDATE Persona.Id_Cedente = CONCAT(REPLACE(Persona.Id_Cedente,',$Id_Cedente',''),',','$Id_Cedente'), Persona.Mandante = CONCAT(REPLACE(Persona.Mandante,',$Id_Mandante',''),',','$Id_Mandante')";
            $db->query($QueryPersona);


            $ArrayColumnsDeuda = array();
            $columnas = $db->select("select COLUMN_NAME as Field from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = 'Deuda_tmp'");
            foreach($columnas as $columna){
                if($columna["Field"] !== "Id_deuda"){
                    array_push($ArrayColumnsDeuda, $columna["Field"]);
                }
            }

            $ArrayImplodeDeuda = implode(',',$ArrayColumnsDeuda);
            switch($tipoCarga){
                case 'nueva':
                    $db->query("INSERT INTO Deuda_Historico ($ArrayImplodeDeuda) SELECT $ArrayImplodeDeuda FROM Deuda WHERE Id_Cedente =  $Id_Cedente");
                    $db->query("DELETE FROM Deuda WHERE Id_Cedente =  $Id_Cedente");
                break;
                case 'actualizacion':
                break;
            }
            $QueryDeuda = "INSERT INTO Deuda($ArrayImplodeDeuda) SELECT * FROM Deuda_tmp";
            $db->query($QueryDeuda);

            $ArrayColumnsMail = array();
            $columnas = $db->select("select COLUMN_NAME as Field from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = 'Mail'");
            foreach($columnas as $columna){
                if(($columna["Field"] !== "id_mail") && ($columna["Field"] !== "Id_Cedente")){
                    array_push($ArrayColumnsMail, $columna["Field"]);
                }
            }

            $ArrayImplodeMail = implode(',',$ArrayColumnsMail);
            switch($tipoCarga){
                case 'nueva':
                    $db->query("DELETE FROM Mail_cedente WHERE Id_Cedente = $Id_Cedente");

                    $QueryFono= "INSERT INTO Mail_cedente ($ArrayImplodeMail,Id_Cedente) SELECT $ArrayImplodeMail,'".$Id_Cedente."' FROM Mail_tmp ";
                    $db->query($QueryFono);
                break;
                case 'actualizacion':
                break;
            }
            $QueryMail= "INSERT INTO Mail($ArrayImplodeMail) SELECT $ArrayImplodeMail FROM Mail_tmp ON DUPLICATE KEY UPDATE Mail.Origen = CONCAT(Mail.Origen , ',' ,'$IdCFecha')";
            $db->query($QueryMail);


            $ArrayColumnsDir = array();
            $columnas = $db->select("select COLUMN_NAME as Field from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = 'Direcciones'");
            foreach($columnas as $columna){
                if($columna["Field"] !== "Id_Direccion"){
                    array_push($ArrayColumnsDir, $columna["Field"]);
                }
            }

            $ArrayImplodeDir = implode(',',$ArrayColumnsDir);
            switch($tipoCarga){
                case 'nueva':
                    $db->query("DELETE FROM Direcciones_cedente WHERE Id_Cedente = $Id_Cedente");

                    $QueryFono= "INSERT INTO Direcciones_cedente ($ArrayImplodeDir,Id_Cedente) SELECT $ArrayImplodeDir,'".$Id_Cedente."' FROM Direcciones_tmp ";
                    $db->query($QueryFono);
                break;
                case 'actualizacion':
                break;
            }
            $QueryDir= "INSERT INTO Direcciones($ArrayImplodeDir) SELECT $ArrayImplodeDir FROM Direcciones_tmp ON DUPLICATE KEY UPDATE Direcciones.Origen = CONCAT(Direcciones.Origen , ',' ,'$IdCFecha')";
            $db->query($QueryDir);

            $ArrayColumnsFono = array();
            $columnas = $db->select("select COLUMN_NAME as Field from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = 'fono_cob_tmp'");
            foreach($columnas as $columna){
                if(($columna["Field"] !== "id_fono") && ($columna["Field"] !== "Id_Cedente")){
                    array_push($ArrayColumnsFono, $columna["Field"]);
                }
            }
           
            RepairFonos($Id_Cedente);
            $ArrayImplodeFono = implode(',',$ArrayColumnsFono);
            switch($tipoCarga){
                case 'nueva':
                     $db->query("DELETE FROM fono_cob_cedente WHERE Id_Cedente = $Id_Cedente");

                    $QueryFono= "INSERT INTO fono_cob_cedente ($ArrayImplodeFono,Id_Cedente) SELECT $ArrayImplodeFono,'".$Id_Cedente."' FROM fono_cob_tmp ";
                    $db->query($QueryFono);
                break;
                case 'actualizacion':
                break;
            }
            $QueryFono= "INSERT INTO fono_cob($ArrayImplodeFono) SELECT $ArrayImplodeFono FROM fono_cob_tmp ON DUPLICATE KEY UPDATE fono_cob.cedente = CONCAT(fono_cob.cedente , ',' ,'$IdCFecha')";
            $db->query($QueryFono);

            $MontoDeuda = 0;
            $SqlMontoDeuda =  $db->select("SELECT SUM(Deuda) AS deuda FROM Deuda_tmp WHERE Id_Cedente =  $Id_Cedente");
            if (count($SqlMontoDeuda) > 0){
                $MontoDeuda = $SqlMontoDeuda[0]["deuda"];
            }

            $Registros = 0;
            $SqlRegistros = $db->select("SELECT COUNT(Rut) AS ruts FROM Persona_tmp WHERE Id_Cedente = $Id_Cedente");
            $Registros = "";
            if (count($SqlRegistros) > 0){
                $Registros = $SqlRegistros[0]["ruts"];
            }
            
            $sql = "";
            switch($tipoCarga){
                case 'nueva':
                    $sql = "INSERT INTO Historico_Carga (Id_Cedente,fecha,Cant_Ruts,Deuda_Total) values ('".$_SESSION['cedente']."',NOW(),'".$Registros."','$MontoDeuda')";
                break;
                case 'actualizacion':
                    $sql = "UPDATE Historico_Carga SET Deuda_Total = (Deuda_Total + ".$MontoDeuda.") where id in (select id from (select id from Historico_Carga where Id_Cedente='".$_SESSION['cedente']."' order by id DESC LIMIT 1) tb1)";
                break;
            }
            $result = $db->query($sql);
            $ToReturn = array();
            if($result){
                $ToReturn["Result"] = "1";
            }else{
                $ToReturn["Result"] = "0";
            }
            $ToReturn["Resume"] = array();
                $ToReturn["Resume"]["Registros"] = $Registros;
                $ToReturn["Resume"]["TotalDeuda"] = $MontoDeuda;
            echo json_encode($ToReturn);

            $db->query("DELETE FROM Persona_tmp WHERE Id_Cedente = $Id_Cedente");
            $db->query("DELETE FROM Deuda_tmp WHERE Id_Cedente =  $Id_Cedente");
            $db->query("DELETE FROM Mail_tmp WHERE Id_Cedente =  $Id_Cedente");
            $db->query("DELETE FROM fono_cob_tmp WHERE Id_Cedente =  $Id_Cedente");
            $db->query("DELETE FROM Direcciones_tmp WHERE Id_Cedente =  $Id_Cedente");
        break;
        case 'pagos':
            $ArrayColumnsPagos = array();
            $QueryColPagos = $db->query("select COLUMN_NAME as Field from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = 'pagos_deudas_tmp'");
            foreach($QueryColPagos as $QueryColPago){
                if ($QueryColPago["Field"] !== "id"){
                    array_push($ArrayColumnsPagos,$QueryColPago["Field"]);
                }
            }

            $ArrayImplodePagos = implode(',',$ArrayColumnsPagos);
            $QueryPagos = "INSERT INTO pagos_deudas($ArrayImplodePagos) SELECT $ArrayImplodePagos FROM pagos_deudas_tmp";
            $db->query($QueryPagos);

            $db->query("DELETE FROM pagos_deudas_tmp WHERE Id_Cedente =  $Id_Cedente");
        break;
    }

function RepairFonos($Id_Cedente){
    $db = new DB();
    $QueryFonos = $db->select("SELECT * FROM fono_cob_tmp where Id_Cedente='".$Id_Cedente."'");
    foreach($QueryFonos as $fono){
        $Codigo = $fono["codigo_area"];
        $Fono = $fono["formato_subtel"];
        $Depurador = Depurador($Codigo,$Fono,"",false) == "1" ? true : false;
        if(!$Depurador){
            $db->query("DELETE FROM fono_cob_tmp WHERE Id_Cedente='$Id_Cedente' and formato_subtel='".$Fono."'");
        }
    }
}

?>