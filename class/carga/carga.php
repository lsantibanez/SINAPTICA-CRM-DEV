<?php
    class Carga{

        public $RequiredColumns;

        function __construct(){
            $this->getRequiredColumns();
        }
        function getTemplates(){
            $db = new DB();
            $SqlTemplate = "SELECT * FROM Template_Carga WHERE Id_Cedente = '".$_SESSION['cedente']."'";
            $Templates = $db->select($SqlTemplate);
            return $Templates;
        }
        function getTemplate($id){
            $db = new DB();
            $SqlTemplate = "SELECT * FROM Template_Carga WHERE id = '".$id."'";
            $Template = $db->select($SqlTemplate);
            return $Template[0];
        }
        function getSheets($id){
            $ToReturn = array();
            $db = new DB();
            $SqlSheets = "SELECT s.*, t.Tipo_Archivo FROM Sheet_Template_Carga s INNER JOIN Template_Carga t ON s.id_template = t.id WHERE t.id = '".$id."'";
            $Sheets = $db->select($SqlSheets);
            $Cont = 1;
            foreach($Sheets as $Sheet){
                $ArrayTmp = array();
                $ArrayTmp["N"] = $Cont;
                $ArrayTmp["Nombre"] = $Sheet["Nombre"];
                if($Sheet['Tipo_Archivo'] == 'txt' OR $Sheet['Tipo_Archivo'] == 'csv'){
                    $ArrayTmp["Sheet"] = $Cont;
                }else{
                    $ArrayTmp["Sheet"] = $Sheet["Sheet"];
                }
                $ArrayTmp["TipoCarga"] = $Sheet["TipoCarga"];
                $ArrayTmp["Accion"] = $Sheet["id"];
                array_push($ToReturn,$ArrayTmp);
                $Cont++;
            }
            return $ToReturn;
        }
        function getColumnsTemplate($Sheet){
            $ToReturn = array();
                $ToReturn["Persona"] = array();
                $ToReturn["Deuda"] = array();
                $ToReturn["Fono"] = array();
                $ToReturn["Direccion"] = array();
                $ToReturn["Mail"] = array();
                $ToReturn["Pagos"] = array();
                $ToReturn["Gestiones"] = array();
            $db = new DB();
            $SqlColumns = " SELECT
                                c.*,
                                t.Tipo_Archivo,
                                t.Separador_Cabecero
                            FROM
                                Sheet_Template_Carga s
                                INNER JOIN Template_Carga t ON t.id = s.id_template
                                INNER JOIN Columnas_Template_Carga c ON c.id_sheet = s.id 
                            WHERE
                                s.id = '".$Sheet."' 
                            ORDER BY
                                c.Tabla,
                                c.Columna";
            $Columns = $db->select($SqlColumns);
            $ContPersona = 1;
            $ContDeuda = 1;
            $Contfono_cob = 1;
            $ContDirecciones = 1;
            $ContMail = 1;
            $ContPagos = 1;
            foreach($Columns as $Column){
                $ArrayTmp = array();
                $ArrayTmp["Tabla"] = $Column["Tabla"];
                $ArrayTmp["ColumnDB"] = $Column["Campo"];
                if(($Column['Tipo_Archivo'] == 'txt' OR $Column['Tipo_Archivo'] == 'csv') && (!$Column['Separador_Cabecero'])){
                    $ArrayTmp["ColumnExcel"] = $Column["posicionInicio"];
                }else{
                    $ArrayTmp["ColumnExcel"] = $Column["Columna"];
                }
                if(($Column['Tipo_Archivo'] == 'txt' OR $Column['Tipo_Archivo'] == 'csv') && (!$Column['Separador_Cabecero'])){
                    $ArrayTmp["Funcion"] = $Column["cantCaracteres"];
                }else{
                    $ArrayTmp["Funcion"] = $Column["Funcion"];
                }
                $ArrayTmp["Parametro"] = $Column["Parametros"];
                $ArrayTmp["Configurado"] = $Column["Configurado"];
                $ArrayTmp["Mandatorio"] = $Column["Mandatorio"];
                $ArrayTmp["Accion"] = $Column["id"];
                
                switch($Column["Tabla"]){
                    case "Persona":
                        $ArrayTmp["N"] = $ContPersona;
                        array_push($ToReturn["Persona"],$ArrayTmp);
                        $ContPersona++;
                    break;
                    case "Deuda":
                        $ArrayTmp["N"] = $ContDeuda;
                        array_push($ToReturn["Deuda"],$ArrayTmp);
                        $ContDeuda++;
                    break;
                    case "fono_cob":
                        $ArrayTmp["N"] = $Contfono_cob;
                        array_push($ToReturn["Fono"],$ArrayTmp);
                        $Contfono_cob++;
                    break;
                    case "Direcciones":
                        $ArrayTmp["N"] = $ContDirecciones;
                        array_push($ToReturn["Direccion"],$ArrayTmp);
                        $ContDirecciones++;
                    break;
                    case "Mail":
                        $ArrayTmp["N"] = $ContMail;
                        array_push($ToReturn["Mail"],$ArrayTmp);
                        $ContMail++;
                    break;
                    case "pagos_deudas":
                        $ArrayTmp["N"] = $ContPagos;
                        array_push($ToReturn["Pagos"],$ArrayTmp);
                        $ContPagos++;
                    break;
                    case "gestion_ult_trimestre":
                        $ArrayTmp["N"] = $ContPagos;
                        array_push($ToReturn["Gestiones"],$ArrayTmp);
                        $ContPagos++;
                    break;
                }
            }
            return $ToReturn;
        }
        function getFuncionesColumnasExcel(){
            $ToReturn = array();
            $db = new DB();
            $SqlFunciones = "select * from Funciones_Template_Carga order by Codigo";
            $Funciones = $db->select($SqlFunciones);
            return $Funciones;
        }
        function addColumnCarga($id_template,$Sheet,$Tabla,$Campo,$PatronFecha,$ColumnaExcel,$PosicionInicio,$CantCaracteres,$Funcion,$Parametros,$PrioridadFono="1",$Configurado="1",$Mandatorio="0"){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $ToReturn["Status"] = "";
            $db = new DB();
            //if(!$this->ExisteColumnaCampo($Template["id"],$Sheet,$Tabla,$Campo,$ColumnaExcel)){
                $SqlInsert = "INSERT INTO Columnas_Template_Carga (id_template,Columna,posicionInicio,cantCaracteres,id_sheet,Funcion,Parametros,Tabla,Campo,PatronFecha,Prioridad_Fono,Configurado,Mandatorio) VALUES ('".$id_template."','".$ColumnaExcel."','".$PosicionInicio."','".$CantCaracteres."','".$Sheet."','".$Funcion."','".$Parametros."','".$Tabla."','".$Campo."','".$PatronFecha."','".$PrioridadFono."','".$Configurado."','".$Mandatorio."')";
                $Insert = $db->query($SqlInsert);
                if($Insert){
                    $ToReturn["result"] = true;
                }
            //}else{
            //    $ToReturn["Status"] = "Numero de Hoja ya se encuentra registrado.";
            //}
            return $ToReturn;
        }
        function ExisteColumnaCampo($idTemplate,$idSheet,$Tabla,$Campo,$ColumnaExcel){
            $ToReturn = false;
            $db = new DB();
            $SqlExist = "select * from Columnas_Template_Carga where id_template='".$idTemplate."' and id_sheet='".$idSheet."' and Campo='".$Campo."' and Columna='".$ColumnaExcel."' and Tabla='".$Tabla."'";
            $Exist = $db->select($SqlExist);
            if(count($Exist) > 0){
                $ToReturn = true;
            }
            return $ToReturn;
        }
        function deleteColumn($idColumn){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlDelete = "delete from Columnas_Template_Carga where id='".$idColumn."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getColumn($idColumn){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlColumn = "select * from Columnas_Template_Carga where id='".$idColumn."' LIMIT 1";
            $Column = $db->select($SqlColumn);
            if(count($Column) > 0){
                $ToReturn["result"] = true;
                $Column = $Column[0];
                $ToReturn["Column"] = array();
                $ToReturn["Column"]["Tabla"] = $Column["Tabla"];
                $ToReturn["Column"]["Campo"] = $Column["Campo"];
                $ToReturn["Column"]["PatronFecha"] = $Column["PatronFecha"];
                $ToReturn["Column"]["Columna"] = $Column["Columna"];
                $ToReturn["Column"]["posicionInicio"] = $Column["posicionInicio"];
                $ToReturn["Column"]["cantCaracteres"] = $Column["cantCaracteres"];
                $ToReturn["Column"]["Funcion"] = $Column["Funcion"];
                $ToReturn["Column"]["Parametros"] = $Column["Parametros"];
                $ToReturn["Column"]["PrioridadFono"] = $Column["Prioridad_Fono"];
            }
            return $ToReturn;
        }
        function updateColumnCarga($idColumn,$Tabla,$Campo,$PatronFecha,$ColumnaExcel,$PosicionInicio,$CantCaracteres,$Funcion,$Parametros,$PrioridadFono){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlUpdate = "update Columnas_Template_Carga set Columna='".$ColumnaExcel."', posicionInicio='".$PosicionInicio."', cantCaracteres='".$CantCaracteres."', Funcion='".$Funcion."', Parametros='".$Parametros."', Tabla='".$Tabla."', Campo='".$Campo."', PatronFecha='".$PatronFecha."', Prioridad_Fono='".$PrioridadFono."', Configurado='1' where id='".$idColumn."'";
            $Update = $db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function addSheetCarga($NombreHoja,$NumeroHoja,$TipoCarga,$id_template){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $ToReturn["Status"] = "";
            $db = new DB();
            $Template = $this->getTemplate($id_template);
            if(!$this->ExisteHoja($id_template,$NumeroHoja,$TipoCarga) OR ($Template['Tipo_Archivo'] == 'txt' OR $Template['Tipo_Archivo'] == 'csv')){
                $SqlInsert = "INSERT INTO Sheet_Template_Carga (id_template,Nombre,Sheet,TipoCarga) VALUES ('".$id_template."','".$NombreHoja."','".$NumeroHoja."','".$TipoCarga."')";
                $Insert = $db->query($SqlInsert);
                if($Insert){
                    $ToReturn["result"] = true;
                }
            }else{
                $ToReturn["Status"] = "Numero de Hoja ya se encuentra registrada.";
            }
            return $ToReturn;
        }
        function ExisteHoja($idTemplate,$Sheet,$TipoCarga){
            $ToReturn = false;
            $db = new DB();
            $SqlExist = "select * from Sheet_Template_Carga where id_template='".$idTemplate."' and Sheet='".$Sheet."' and TipoCarga='".$TipoCarga."'";
            $Exist = $db->select($SqlExist);
            if(count($Exist) > 0){
                $ToReturn = true;
            }
            return $ToReturn;
        }
        function deleteSheet($idSheet){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $DeleteColums = $this->deleteColumnsBySheet($idSheet);
            if($DeleteColums["result"]){
                $SqlDelete = "delete from Sheet_Template_Carga where id='".$idSheet."'";
                $Delete = $db->query($SqlDelete);
                if($Delete){
                    $ToReturn["result"] = true;
                }
            }
            return $ToReturn;
        }
        function deleteColumnsBySheet($idSheet){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlDelete = "delete from Columnas_Template_Carga where id_sheet='".$idSheet."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getSheet($idSheet){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlSheet = "   SELECT
                                s.*,
                                t.Separador_Cabecero AS Separador 
                            FROM
                                Sheet_Template_Carga s
                                INNER JOIN Template_Carga t ON s.id_template = t.id 
                            WHERE
                                s.id = '".$idSheet."'";
            $Sheet = $db->select($SqlSheet);
            if(count($Sheet) > 0){
                $ToReturn["result"] = true;
                $Sheet = $Sheet[0];
                $ToReturn["Sheet"] = array();
                $ToReturn["Sheet"]["Nombre"] = $Sheet["Nombre"];
                $ToReturn["Sheet"]["Sheet"] = $Sheet["Sheet"];
                $ToReturn["Sheet"]["TipoCarga"] = $Sheet["TipoCarga"];
                $ToReturn["Sheet"]["Separador"] = $Sheet["Separador"];
            }
            return $ToReturn;
        }
        function updateSheetCarga($idSheet,$NombreHoja,$NumeroHoja,$TipoCarga){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlUpdate = "UPDATE Sheet_Template_Carga SET Nombre = '".$NombreHoja."', Sheet = '".$NumeroHoja."', TipoCarga = '".$TipoCarga."' WHERE id = '".$idSheet."'";
            $Update = $db->query($SqlUpdate);
            if($Update){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function addTemplateCarga($NombreTemplate,$TipoArchivo,$Separador,$Cabecero,$id){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            if($id){
                $OldTemplate = $this->getTemplate($id);
                $SqlTemplate = "UPDATE Template_Carga SET NombreTemplate = '".$NombreTemplate."', Tipo_Archivo = '".$TipoArchivo."', Separador_Cabecero = '".$Separador."', haveHeader = '".$Cabecero."' WHERE id = '".$id."'";    
            }else{
                $SqlTemplate = "INSERT INTO Template_Carga (NombreTemplate,Tipo_Archivo,Separador_Cabecero,haveHeader,Id_Cedente) VALUES ('".$NombreTemplate."','".$TipoArchivo."','".$Separador."','".$Cabecero."','".$_SESSION['cedente']."')";
            }
            $Template = $db->query($SqlTemplate);
            if($Template){
                $ToReturn["result"] = true;
                if($OldTemplate){
                    if($OldTemplate["Tipo_Archivo"] != $TipoArchivo){
                        $this->deleteSheetsByTemplate($id);

                        /*switch($TipoArchivo){
                            case "xlsx":
                            case "xls":
                            break;
                            case "csv":
                            case "txt":
                                $this->addSheetCarga("UNICA",0);
                            break;
                        }*/
                    }
                    if($OldTemplate["Separador"] != $Separador){
                        $this->deleteColumnsByTemplate($id);
                    }
                }else{
                    /*switch($TipoArchivo){
                        case "xlsx":
                        case "xls":
                        break;
                        case "csv":
                        case "txt":
                            $this->addSheetCarga("UNICA",0);
                        break;
                    }*/
                }
            }
            return $ToReturn;
        }
        function deleteTemplate($id){
            $db = new DB();
            $SqlDeleteTemplate = "DELETE FROM Template_Carga WHERE id = '".$id."'";
            $DeleteTemplate = $db->query($SqlDeleteTemplate);
            if($DeleteTemplate){
                $this->deleteSheetsByTemplate($id);
            }
        }
        function deleteSheetsByTemplate($id){
            $db = new DB();
            $SqlDeleteSheets = "DELETE FROM Sheet_Template_Carga WHERE id_template = '".$id."'";
            $DeleteSheets = $db->query($SqlDeleteSheets);
            if($DeleteSheets){
                $this->deleteColumnsByTemplate($id);
            }
        }
        function deleteColumnsByTemplate($id){
            $db = new DB();
            $SqlDeleteColumns = "DELETE FROM Columnas_Template_Carga WHERE id_template = '".$id."'";
            $DeleteColumns = $db->query($SqlDeleteColumns);
        }
        function HaveCargaAutomaticaEnCurso(){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlCarga = "select * from java_process_live where Id_Cedente='".$_SESSION['cedente']."' and Id_Mandante='".$_SESSION['mandante']."' and id_process='1'";
            $Carga = $db->select($SqlCarga);
            if(count($Carga) > 0){
                $Carga = $Carga[0];
                $Status = $Carga["status"];
                $Comentario = $Carga["comment"];
                $Filename = $Carga["fileName"];
                $Usuario = $Carga["Id_Usuario"];
                if($Status == "Procesando"){
                    $ToReturn["result"] = true;
                    $ToReturn["comment"] = $Comentario;
                    $ToReturn["filename"] = $Filename;
                    $SqlNombreUsuario = "select Nombre from Personal where id_usuario='".$Usuario."'";
                    $NombreUsuario = $db->select($SqlNombreUsuario);
                    if(count($NombreUsuario) > 0){
                        $NombreUsuario = $NombreUsuario[0];
                        $Usuario = $NombreUsuario["Nombre"];
                        $ToReturn["usuario"] = $Usuario;
                    }else{
                        $ToReturn["usuario"] = '';
                    }
                }else{
                    $SqlDeleteCargaAutomaticaEnCurso = "delete from java_process_live where Id_Cedente='".$_SESSION['cedente']."' and Id_Mandante='".$_SESSION['mandante']."' and id_process='1'";
                    $DeleteCargaAutomaticaEnCurso = $db->query($SqlDeleteCargaAutomaticaEnCurso);
                }
            }
            return $ToReturn;
        }
        function FindFinishedJavaProcess(){
            $ToReturn = array();
            $db = new DB();
            if(isset($_SESSION["cedente"]) && isset($_SESSION["mandante"])){
                $SqlFinishedJavaProcess = "select * from java_process_live where status='Finalizado' and Id_Cedente='".$_SESSION["cedente"]."' and Id_Mandante='".$_SESSION["mandante"]."'";
                $FinishedJavaProcess = $db->select($SqlFinishedJavaProcess);
                if($FinishedJavaProcess){
                    foreach($FinishedJavaProcess as $JavaProcess){
                        if($_SESSION['id_usuario'] == $JavaProcess["Id_Usuario"]){
                            $ArrayTmp = array();
                            $ArrayTmp['filename'] = $JavaProcess["fileName"];
                            $ArrayTmp['process'] = $JavaProcess["id_process"];
                            $ArrayTmp['comment'] = $JavaProcess["comment"];
                            $SqlNombreUsuario = "select Nombre from Personal where id_usuario='".$JavaProcess["Id_Usuario"]."'";
                            $NombreUsuario = $db->select($SqlNombreUsuario);
                            if(count($NombreUsuario) > 0){
                                $NombreUsuario = $NombreUsuario[0];
                                $Usuario = $NombreUsuario["Nombre"];
                                $ArrayTmp['usuario'] = $Usuario;
                            }
                            $SqlDeleteCargaAutomaticaEnCurso = "delete from java_process_live where id='".$JavaProcess['id']."'";
                            $DeleteCargaAutomaticaEnCurso = $db->query($SqlDeleteCargaAutomaticaEnCurso);
                            array_push($ToReturn,$ArrayTmp);
                        }
                    }
                }
            }
            return $ToReturn;
        }
        function isDateField($Table,$Field){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlisDateField = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$Table."' AND COLUMN_NAME = '".$Field."'  and TABLE_SCHEMA='foco'";
            $isDateField = $db->select($SqlisDateField);
            if(count($isDateField) > 0){
                $isDateField = $isDateField[0];
                $Type = $isDateField["DATA_TYPE"];
                switch($Type){
                    case "date":
                    case "datetime":
                    case "datetime2(0)":
                    case "time":
                        $ToReturn["result"] = true;
                    break;
                }
            }
            return $ToReturn;
        }
        function ExisteFactura($Factura){
            $db = new DB();
            $ToReturn["result"] = false;
            switch($_SESSION['tipoSistema']){
                case "1":
                    $FieldNOperacion = "Numero_Factura";
                break;
                default:
                    $FieldNOperacion = "Numero_Operacion";
                break;
            }
            $SqlFactura = "select Rut from Deuda where ".$FieldNOperacion."='".$Factura."'";
            $Factura = $db->select($SqlFactura);
            if(count($Factura) > 0){
                $Factura = $Factura[0];
                $ToReturn["result"] = true;
                $ToReturn["Rut"] = $Factura["Rut"];
            }
            return $ToReturn;
        }
        function InsertFacturaInubicable($Factura){
            $ToReturn = array();
            $ToReturn["result"] = false;
            $db = new DB();
            $SqlInsert = "INSERT INTO facturas_inubicables (Numero_Factura,Fecha,Id_Mandante,Id_Cedente,Id_Usuario) VALUES ('".$Factura."',NOW(),'".$_SESSION['mandante']."','".$_SESSION['cedente']."','".$_SESSION['id_usuario']."')";
            $Insert = $db->query($SqlInsert);
            if($Insert){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getFacturasInubicables(){
            $db = new DB();
            $ToReturn = array();
            $SqlFacturas = "select facturas_inubicables.id as ID, facturas_inubicables.Numero_Factura as Numero_Factura, facturas_inubicables.Fecha as Fecha, Personal.Nombre as Nombre_Usuario from facturas_inubicables inner join Personal on Personal.id_usuario = facturas_inubicables.Id_Usuario where Id_Mandante='".$_SESSION['mandante']."' and Id_Cedente='".$_SESSION['cedente']."'";
            $Facturas = $db->select($SqlFacturas);
            foreach($Facturas as $Factura){
                $ArrayTmp = array();
                $ArrayTmp["Factura"] = utf8_encode($Factura["Numero_Factura"]);
                $ArrayTmp["Fecha"] = date("d-m-Y H:i:s", strtotime($Factura["Fecha"]));
                $ArrayTmp["Usuario"] = utf8_encode($Factura["Nombre_Usuario"]);
                $ArrayTmp["Seleccion"] = "0_".$Factura["ID"];
                $ArrayTmp["Accion"] = $Factura["ID"];
                array_push($ToReturn,$ArrayTmp);
            }
            return $ToReturn;
        }
        function deleteFacturasInubicables($idFacturas){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $Facturas = $this->getNumeroFacturasInubicables($idFacturas);
            $SqlDelete = "delete from facturas_inubicables where id in (".$idFacturas.")";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
                $ToReturn["Facturas"] = $Facturas;
            }
            return $ToReturn;
        }
        function getNumeroFacturasInubicables($idFacturas){
            $db = new DB();
            $ToReturn = array();
            $SqlFacturas = "select Numero_Factura from facturas_inubicables where id in (".$idFacturas.")";
            $Facturas = $db->select($SqlFacturas);
            foreach($Facturas as $Factura){
                array_push($ToReturn,$Factura["Numero_Factura"]);
            }
            return $ToReturn;
        }
        function getCargas($TipoCarga){
            $db = new DB();
            $ToReturn = array();
            $ArrayTables = array();
            switch($TipoCarga){
                case "carga":
                    array_push($ArrayTables,"Persona");
                    array_push($ArrayTables,"Deuda");
                break;
                case "pagos":
                    array_push($ArrayTables,"pagos_deudas");
                break;
            }
            foreach($ArrayTables as $Table){
                $WhereCedente = "";
                switch($Table){
                    case "Persona":
                        $WhereCedente = "FIND_IN_SET('".$_SESSION["cedente"]."',Id_Cedente)";
                    break;
                    case "Deuda":
                        $WhereCedente = "Id_Cedente='".$_SESSION["cedente"]."'";
                    break;
                    case "pagos_deudas":
                        $WhereCedente = "Id_Cedente='".$_SESSION["cedente"]."'";
                    break;
                }
                $Campos = $this->getCamposCarga($Table);
                $CamposArrayTmp = explode(",",$Campos.",Accion");
                $CamposArray = array();
                foreach($CamposArrayTmp as $CampoTmp){
                    $ArrayTmp = array();
                    $ArrayTmp["data"] = $CampoTmp;
                    array_push($CamposArray,$ArrayTmp);
                }
                $ToReturn[$Table] = array();
                if($Campos != ""){
                    $ToReturn[$Table] = $this->getCargaData($Table,$Campos,$WhereCedente);
                }else{
                    $ToReturn[$Table]["Data"] = array();
                    $ToReturn[$Table]["Cuadratura"] = "0";
                }
                $ToReturn[$Table]["Campos"] = $CamposArray;
            }
            return $ToReturn;
        }
        function getCamposCarga($Tabla){
            $ToReturn = "";
            $db = new DB();
            $SqlCampos = "select * from campos_cargas_asignaciones where tabla='".$Tabla."' and Id_Cedente='".$_SESSION["cedente"]."'";
            $Campos = $db->select($SqlCampos);
            if(count($Campos) > 0){
                $ToReturn = $Campos[0]["campos"];
            }
            return $ToReturn;
        }
        function getCargaData($Tabla,$Campos,$WhereCedente){
            $ToReturn = array();
            $db = new DB();
            $PrimaryKey = $this->getPrimaryKey($Tabla);
            /*$PrimaryKey = "";
            switch($Tabla){
                case "Deuda":
                    $PrimaryKey = "Id_deuda";
                break;
                case "Persona":
                    $PrimaryKey = "id_persona";
                break;
                case "pagos_deudas":
                    $PrimaryKey = "id";
                break;
            }*/
            $SqlCargaData = "select ".$Campos.",".$PrimaryKey." as Accion from ".$Tabla." where ".$WhereCedente." LIMIT 1000";
            $ToReturn["Data"] = $db->select($SqlCargaData);
            $SqlCargaData = "select count(*) as Cuadratura from ".$Tabla." where ".$WhereCedente;
            $ToReturn["Count"] = $db->select($SqlCargaData);
            $ToReturn["Count"] = number_format($ToReturn["Count"][0]["Cuadratura"],0);
            return $ToReturn;
        }
        function getPrimaryKey($Tabla){
            $db = new DB();
            $ToReturn = "";
            $SqlPrimaryKey = "SELECT COLUMN_NAME as Columna FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$Tabla."' AND COLUMN_KEY='PRI' AND TABLE_SCHEMA='foco'";
            $PrimaryKey = $db->select($SqlPrimaryKey);
            if(count($PrimaryKey) > 0){
                $ToReturn = $PrimaryKey[0]["Columna"];
            }else{
                $ToReturn = "";
            }
            return $ToReturn;
        }
        function deleteRowCarga($Tabla,$ID){
            $ToReturn = array();
            $PrimaryKey = $this->getPrimaryKey($Tabla);
            $ToReturn["result"] = false;
            $db = new DB();
            switch($Tabla){
                case "Persona":
                    $SqlUpdate = "update ".$Tabla." set Id_Cedente=REPLACE(REPLACE(Id_Cedente,',".$ID."',''),'".$ID.",','') where ".$PrimaryKey." = '".$ID."'";
                    $Update = $db->query($SqlUpdate);
                    if($Update){
                        $ToReturn["result"] = true;
                    }
                    if($ToReturn["result"]){
                        $SqlDelete = "delete from ".$Tabla." where ".$PrimaryKey." = '".$ID."' and Id_Cedente='".$_SESSION['cedente']."'";
                        $Delete = $db->query($SqlDelete);
                        if($Delete){
                            $ToReturn["result"] = true;
                        }
                    }
                break;
                default:
                    $SqlDelete = "delete from ".$Tabla." where ".$PrimaryKey." = '".$ID."'";
                    $Delete = $db->query($SqlDelete);
                    if($Delete){
                        $ToReturn["result"] = true;
                    }
                break;
            }
            
            return $ToReturn;
        }
        function addColumnsRequired($Tabla,$idSheet,$id_template){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $Fields = $this->RequiredColumns[$Tabla];
            foreach($Fields as $Field){
                $this->addColumnCarga($id_template,$idSheet,$Tabla,$Field,"","","","","","","1","0","1");
            }
            $ToReturn["result"] = true;
            return $ToReturn;
        }
        function deleteColumnsCargaFromTable($Tabla,$idSheet){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["result"] = false;
            $SqlDelete = "delete from Columnas_Template_Carga where id_sheet='".$idSheet."' and Tabla='".$Tabla."'";
            $Delete = $db->query($SqlDelete);
            if($Delete){
                $ToReturn["result"] = true;
            }
            return $ToReturn;
        }
        function getRequiredColumns(){
            $this->RequiredColumns = array();
            $this->RequiredColumns["Persona"] = array();
                array_push($this->RequiredColumns["Persona"],"Rut");
                array_push($this->RequiredColumns["Persona"],"Nombre_Completo");
            $this->RequiredColumns["Deuda"] = array();
                array_push($this->RequiredColumns["Deuda"],"Rut");
                array_push($this->RequiredColumns["Deuda"],"Deuda");
                array_push($this->RequiredColumns["Deuda"],"Fecha_Vencimiento");
                $TipoSistema = isset($_SESSION['tipoSistema']) ? $_SESSION['tipoSistema'] : 0;
                switch($TipoSistema){
                    case "1":
                        array_push($this->RequiredColumns["Deuda"],"Numero_Factura");
                    break;
                    default:
                        array_push($this->RequiredColumns["Deuda"],"Numero_Operacion");
                    break;
                }
            $this->RequiredColumns["Mail"] = array();
                array_push($this->RequiredColumns["Mail"],"Rut");
                array_push($this->RequiredColumns["Mail"],"correo_electronico");
            $this->RequiredColumns["Direcciones"] = array();
                array_push($this->RequiredColumns["Direcciones"],"Rut");
            $this->RequiredColumns["fono_cob"] = array();
                array_push($this->RequiredColumns["fono_cob"],"Rut");
                array_push($this->RequiredColumns["fono_cob"],"formato_subtel");
            $this->RequiredColumns["pagos_deudas"] = array();
                array_push($this->RequiredColumns["pagos_deudas"],"Rut");
                array_push($this->RequiredColumns["pagos_deudas"],"Monto");
                array_push($this->RequiredColumns["pagos_deudas"],"Fecha_Pago");
                switch($TipoSistema){
                    case "1":
                        array_push($this->RequiredColumns["pagos_deudas"],"Numero_Factura");
                    break;
                    case "2":
                        array_push($this->RequiredColumns["pagos_deudas"],"Numero_Operacion");
                    break;
                }
            $this->RequiredColumns["gestion_ult_trimestre"] = array();
                array_push($this->RequiredColumns["gestion_ult_trimestre"],"rut_cliente");
                array_push($this->RequiredColumns["gestion_ult_trimestre"],"nombre_grabacion");
                array_push($this->RequiredColumns["gestion_ult_trimestre"],"cedente");
                array_push($this->RequiredColumns["gestion_ult_trimestre"],"Id_TipoGestion");
                array_push($this->RequiredColumns["gestion_ult_trimestre"],"fono_discado");
                array_push($this->RequiredColumns["gestion_ult_trimestre"],"hora_gestion");
                array_push($this->RequiredColumns["gestion_ult_trimestre"],"fecha_gestion");
                array_push($this->RequiredColumns["gestion_ult_trimestre"],"fechahora");
                array_push($this->RequiredColumns["gestion_ult_trimestre"],"nombre_ejecutivo");
        }
        function HaveMandatoryFieldsNoConfigured($id_template){
            $ToReturn = array();
            $ToReturn = true;
            $db = new DB();
            $SqlColumnasMandatorias = " SELECT
                                            * 
                                        FROM
                                            Columnas_Template_Carga 
                                        WHERE
                                            Mandatorio = '1' 
                                            AND Configurado = '0' 
                                            AND id_template = '".$id_template."'";
            $ColumnasMandatorias = $db->select($SqlColumnasMandatorias);
            if(count($ColumnasMandatorias) > 0){
                $ToReturn = false;
            }
            return $ToReturn;
        }
        function getOrigenes(){
            $db = new DB();
            $query = "SELECT * FROM origenes_gestiones";
            $Origenes = $db->select($query);
            return $Origenes;
        }
    }
?>