<?php

include_once __DIR__.'/../db/DB.php';
require_once __DIR__.'/../logs.php';

class Estrategia
{    
    private $Id;
    private $db;
    private $IdCedente;
    private $IdTipoEstrategia;
    private $IdEstrategia;
    private $IdColumna;
    private $IdTabla;
    private $IdSubQuery;
    private $Valor;
    private $Valor2;
    private $Logica;
    private $NombreCola;
    private $ValorPrioridad;
    private $ValorComentario;
    private $ValorCola;
    private $Id_Cedente;
    private $logs;

    public $array_central = [];

    public function __construct()
    {
        $this->db = new Db();
        $this->logs = new Logs();
    }

    public function MostrarTablas($IdCedente, $IdTipoEstrategia, $IdEstrategia)
    {
        $db = new DB();
        $this->IdCedente=$IdCedente;
        $this->IdTipoEstrategia = (int) $IdTipoEstrategia;
        $this->IdEstrategia=$IdEstrategia;        
        $rows = $db->select("SELECT id, nombre, titulo FROM SIS_Tablas WHERE FIND_IN_SET('{$this->IdCedente}',Id_Cedente) AND  tipo = {$this->IdTipoEstrategia}");

        if($IdTipoEstrategia == 1) {
            $EstrategiasCreadas = $db->select("SELECT id_estrategia FROM SIS_Querys_Estrategias WHERE id_estrategia = {$IdEstrategia}");
            if(count((array) $EstrategiasCreadas) == 0) {
                echo 0;
            } else {
                //echo '<select class="selectpicker" data-live-search="true" data-width="100%" id="SeleccioneTabla">';
                //echo '<option value="-1">Seleccione Tabla</option>'.PHP_EOL;
                echo '<option value=""> -- Seleccione -- </option>'.PHP_EOL;
                foreach((array) $rows as $row) {
                    $nombre = (!empty($row["titulo"]))? trim($row["titulo"]) : trim($row["nombre"]);
                   // echo '<option value="'.$row["id"].'">'.$row["nombre"].'</option>'.PHP_EOL;
                   echo '<option value="'.$row["id"].'">'.$nombre.'</option>'.PHP_EOL;
                   /*
                    echo '<div class="radio">
                        <label>
                            <input type="radio" class="tableItem" name="radioTable" id="radioTable_'.$row["id"].'" value="'.$row["id"].'">
                            '.$nombre.'
                        </label>
                    </div>';
                    */
                }
                //echo '</select>';
            }
        } else {            
            //echo '<select class="selectpicker" data-live-search="true" data-width="100%" id="SeleccioneTabla">';
            //echo '<option value="-1">Seleccione Tabla</option>'.PHP_EOL;
            echo '<option value=""> -- Seleccione -- </option>'.PHP_EOL;
            foreach((array) $rows as $row) {
                $nombre = (!empty($row["titulo"]))? trim($row["titulo"]) : trim($row["nombre"]);
                //echo '<option value="'.$row["id"].'">'.$row["nombre"].'</option>'.PHP_EOL;
                echo '<option value="'.$row["id"].'">'.$nombre.'</option>'.PHP_EOL;
                /*
                echo '<div class="radio">
                        <label>
                            <input type="radio" class="tableItem" name="radioTable" id="radioTable_'.$row["id"].'" value="'.$row["id"].'">
                            '.$nombre.'
                        </label>
                    </div>';
                    */
            }
            // echo '</select>';
        }      
    }

    public function MostrarColumnas($IdTabla)
    {
        $db = new DB();
        if(!isset($_SESSION)) session_start();
        $Id_Cedente = (int) $_SESSION['cedente'];
        $this->IdTabla = (int) $IdTabla;
        $Id = 0;
        $rows = $db->select("SELECT id, columna, alias FROM SIS_Columnas_Estrategias WHERE id_tabla = {$this->IdTabla} AND FIND_IN_SET('".$Id_Cedente."',Id_Cedente)");
       // echo '<select class="selectpicker" data-live-search="true" data-width="100%" id="SeleccioneColumna">';
        //echo '<option value="-1"> -- Seleccione --</option>';
        if ($rows) {
            echo '<option value=""> -- Seleccione -- </option>'.PHP_EOL;
            foreach((array) $rows as $row) {
                //echo '<option value="'.$row['id'].'">'.$row['columna'].'</option>'.PHP_EOL;
                $nombre = $row["columna"];
                if (!is_null($row["alias"]) && !empty($row["alias"])) $nombre = $row["alias"];
                echo '<option value="'.$row["id"].'">'.$nombre.'</option>'.PHP_EOL;
                /*
                echo '<div class="radio">
                        <label>
                            <input type="radio" class="columnItem" name="radioColumn" id="radioColumn_'.$row["id"].'" value="'.$row["id"].'">
                            '.$nombre.'
                        </label>
                    </div>';
                */
            }
        } else {
            echo '<option value="" disabled>Tabla sin columnas</option>'.PHP_EOL;
            /*
            echo '<div class="radio">
            <label>Tabla sin columnas</label>
            </div>';
            */
        }
        //echo '</select>';
        //echo "<input type='hidden' value='{$Id}' id='Id'>";
    }

    public function MostrarColor() 
    {
        $db = new DB();
        $rows= $db->select("SELECT id,nombre,comentario FROM SIS_Colores ");
        echo '<select   class="selectpicker" multiple data-live-search="true" data-width="100%" id="SeleccioneColor">';
        foreach((array) $rows as $row)
        {
            $Id = $row["id"];
            $Nombre = $row["nombre"];
            $Comentario = $row["comentario"];
            echo "<option value='$Id'>".$Nombre."-".$Comentario."</option>";
        }
        echo '</select>';
        echo "<input type='hidden' value='$Id' id='Color'>";
    }

    public function MostrarLogica($IdColumna)
    {
        $db = new DB();
        $this->IdColumna=$IdColumna;
        $rows = $db->select("SELECT logica, tipo_dato FROM SIS_Columnas_Estrategias WHERE id = {$this->IdColumna} LIMIT 1");
        echo '<option value="">-- Seleccione --</option>'.PHP_EOL;
        if ($rows) {
            $Logica   = (int) $rows[0]["logica"];
            $TipoDato = (int) $rows[0]["tipo_dato"];
            if ($Logica == 1) {
                echo '<option value="=">Igual a</option>'.PHP_EOL;
                echo '<option value="!=">Distinto de</option>'.PHP_EOL;
                /*
                echo '<div class="radio">
                        <label><input type="radio" class="logicItem" name="radioLogic" id="radioLogic_eq" value="=">igual a</label>
                    </div>';
                echo '<div class="radio">
                    <label><input type="radio" class="logicItem" name="radioLogic" id="radioLogic_neq" value="=">distinto a</label>
                    </div>';
                    */
            } else {
                echo '<option value="<">Menor a</option>'.PHP_EOL;
                echo '<option value=">">Mayor a</option>'.PHP_EOL;
                echo '<option value="<=">Menor o igual a</option>'.PHP_EOL;
                echo '<option value=">=">Mayor o igual a</option>'.PHP_EOL;
                if(!in_array($TipoDato, [0,1,6])) echo '<option value="!=">Distinto de</option>'.PHP_EOL;
            }
        } else {
            echo '<option value="" disabled> Sin datos </option>'.PHP_EOL;
        }
        /*
        foreach((array) $rows as $row) {
            $Logica   = (int) $row["logica"];
            $TipoDato = (int) $row["tipo_dato"];
        }
        //echo '<select class="selectpicker" data-live-search="true" data-width="100%" id="SeleccioneLogica">';
        //echo '<option value="-1"> -- Seleccione -- </option>';
        if($Logica == 1) {
            echo "<option value='='>Igual</option>";
            echo "<option value='!='>Distinto</option>";
        } else {
            echo "<option value='<'>Menor</option>";
            echo "<option value='>'>Mayor</option>";
            echo "<option value='='>Igual</option>";
            echo "<option value='<='>Menor o Igual</option>";
            echo "<option value='>='>Mayor o Igual</option>";
            echo "<option value='!='>Distinto</option>";
            if(!in_array($TipoDato, [0,1,6])) echo "<option value='!='>Distinto</option>";            
        }
        //echo '</select>';
        */
    }

    public function MostrarValor($IdLogica,$Id)
    {
        $db = new DB();
        $this->Id=$Id;
        $TipoDato=''; $relacion=''; $columnas_relacion = ''; $pivote_relacion = ''; $valores_pivote_relacion = ''; $columna_relacion_orden = '';
        $rows = $db->select("SELECT tipo_dato,columna,id_tabla,orden,relacion,Cedente,relacion,columnas_relacion,pivote_relacion,valores_pivote_relacion,columna_relacion_orden FROM SIS_Columnas_Estrategias WHERE id=$this->Id");
        foreach((array) $rows as $row)  {
            $TipoDato = (int) $row["tipo_dato"];
            $Columna  = $row["columna"];
            $Id_Tabla = (int) $row["id_tabla"];
            $Orden    = $row["orden"];
            $SiCendente = (int) $row["Cedente"];
            $relacion = $row["relacion"];
            $columnas_relacion = $row["columnas_relacion"];
            $pivote_relacion = $row["pivote_relacion"];
            $valores_pivote_relacion = $row["valores_pivote_relacion"];
            $columna_relacion_orden = $row["columna_relacion_orden"];
        }
        $Tabla = '';
        $rows = $db->select("SELECT nombre FROM SIS_Tablas WHERE id = {$Id_Tabla}");
        $this->logs->debug($rows);
        foreach((array) $rows as $row){
            $Tabla = $row["nombre"];
        }
        //Logica -2 Entre
        if($IdLogica == -2) {
            if($TipoDato == 0) {
                echo 11;
            } elseif($TipoDato == 1) {
                echo 10;   
            } elseif($TipoDato == 6) {
                echo 12;   
            }
        }
        //Logica Todas las anteriores
        else {
            if($TipoDato == 0) {
                echo 0;
            } else if($TipoDato == 1) {
                echo 1;   
            } else if($TipoDato == 3) {
                if ($Orden == 0) {
                    $OrdenQuery = "ORDER BY ".$Columna." ASC";
                } else {
                    $OrdenQuery = "ORDER BY ".$Columna." DESC";
                }
                echo '<select class="selectpicker" data-live-search="true" multiple data-width="100%" id="SeleccioneValor">';
                $rows = $db->select("SELECT $Columna FROM $Tabla GROUP BY $Columna $OrdenQuery");
                foreach((array) $rows as $row){
                    $Valor = $row[$Columna];
                    if($Valor != '') echo '<option value="'.$Valor.'">'.$Valor.'</option>'.PHP_EOL;
                }
                echo '</select>';
            } else if($TipoDato == 4) {    
                echo '<select class="selectpicker" data-live-search="true" multiple data-width="100%" id="SeleccioneValor">';
                echo "<option value='1'>Tiene ".$Columna."</option>"; 
                echo '</select>';
            } else if($TipoDato == 5) {
                echo '<select class="selectpicker" data-live-search="true" multiple data-width="100%" id="SeleccioneValor">';
                //$rows = $db->select("SELECT Id_TipoContacto,Nombre FROM Tipo_Contacto WHERE Id_TipoContacto IN (1,2,3,4,5,7,8,9)");
                $rows = $db->select("SELECT Id_TipoContacto,Nombre FROM Tipo_Contacto");
                foreach((array) $rows as $row){
                    $IdColumna = $row["Id_TipoContacto"];
                    $NombreColumna = $row["Nombre"];
                    echo "<option value='$IdColumna'>".$NombreColumna."</option>"; 
                }
                echo '</select>';
            } else if($TipoDato==6) {
                echo 2;
            } else if($TipoDato == 7) {
                if ($Orden == 0) {
                    $OrdenQuery = "ORDER BY ".$columna_relacion_orden." ASC";
                } else {
                    $OrdenQuery = "ORDER BY ".$columna_relacion_orden." DESC";
                }
                echo '<select class="selectpicker" data-live-search="true" multiple data-width="100%" id="SeleccioneValor">';
                $strQueryRelacion = "SELECT {$columnas_relacion} FROM $relacion WHERE {$pivote_relacion} IN ($valores_pivote_relacion) $OrdenQuery";
                $this->logs->debug($strQueryRelacion);
                $rows = $db->select($strQueryRelacion);
                //$this->logs->debug($rows);
                foreach((array) $rows as $row) {
                    if($row['id'] != '') echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>'.PHP_EOL;
                }
                echo '</select>';
            } else {                
                echo '<input type="text" class="form-control " id="SeleccioneValor">';
                echo "<br>";
            }
        }    
    }

    public function CrearQuery($Valor,$Logica,$NombreCola,$IdColumna,$IdCedente,$IdEstrategia,$IdSubQuery,$IdTabla,$Color,$Valor2)
    {
        $db = new DB();
        $result = $db->query("SELECT * FROM SIS_Querys_Activas");
        $queryActiva = $result->num_rows;
        if ($queryActiva > 0) {            
            //bloquear Cola
            echo "0";
        } else {        
            $this->Valor = $Valor;
            $this->Valor2 = $Valor2;            
            $findme   = ',';
            $pos = strpos($this->Valor, $findme);
            $Coma = 0;
            if ($pos == true) {
                $Coma = 1;
                $this->Valor = explode(",", $this->Valor);
                $this->Valor = "'".implode("','", $this->Valor)."'";
            } else {
                $this->Valor = $this->Valor;
            }       

            $this->Logica = $Logica;
            $this->NombreCola = $NombreCola;
            $this->IdColumna = $IdColumna;
            $this->IdCedente = $IdCedente;
            $this->IdEstrategia = $IdEstrategia;
            $this->IdSubQuery = $IdSubQuery;
            $this->IdTabla = $IdTabla;
            $Query = '';
            $IfCedente = '';
            $QueryResult = '';
            $QueryFinal = []; 
            $db->query("INSERT INTO SIS_Querys_Activas(id_estrategia,nombre,fecha,hora,usuario,cedente,tabla) VALUES('{$IdEstrategia}','{$NombreCola}','','','','{$IdCedente}','')");                    
                       
            //$db->query("INSERT INTO SIS_Estrategias(nombre,comentario,fecha,hora,usuario,tipo,Id_Cedente,Id_Usuario,modo_operacion,periodicidad,grupo) VALUES('$this->nombre_estrategia','$this->comentario','$this->fecha','$this->hora','$this->usuario','$this->tipo_estrategia','$this->cedente','$this->idUsuario','','','')");
            $QueryDatos = $db->select("SELECT columna, id_tabla, tipo, id, Cedente, tipo_dato FROM SIS_Columnas_Estrategias WHERE id = {$this->IdColumna};");
            foreach ((array) $QueryDatos as $row) {
                $Columna = $row['columna'];
                $Tipo = $row['tipo'];
                $IfCedente = $row['Cedente'];
                $TipoDato = $row['tipo_dato'];
            }

            $cPivote = 'Rut'; $campoForaneo = 'Id_Cedente';
            $QueryDatosTabla = $db->select("SELECT nombre, pivote, campo_foraneo, tipo FROM SIS_Tablas WHERE id = {$this->IdTabla};");
            foreach((array) $QueryDatosTabla as $row) {
                $Tabla = $row['nombre'];
                $Dinamica = $row['tipo'];
                $cPivote = $row['pivote'];
                $campoForaneo = $row['campo_foraneo'];
            }           

            if($Tipo == 1) { 
                $this->Valor = "'".$this->Valor."'";
                if($this->Valor2 == '') {
                    
                } else {
                    $this->Valor2 = "'".$this->Valor2."'";                    
                }                
            } else {
                $this->Valor = $this->Valor;
                $this->Valor2 = $this->Valor2;                
            }

            $QueryPositivaKA = "SELECT Rut FROM Persona WHERE ";
            $QueryPositivaKB = " AND FIND_IN_SET('".$this->IdCedente."', Id_Cedente) AND con_deudas = 1";
            $CampoRut = '';
            switch($Tabla) {
                case "gestion_ult_trimestre":
                    $CampoRut = "rut_cliente";
                break;
                default:
                    $CampoRut = "Rut";
                break;
            }
            if($IfCedente == 0) {
                if($Coma == 1) {
                    $QueryResumen = "(SELECT ".$CampoRut." FROM {$Tabla} WHERE {$Columna} IN ({$this->Valor}))";
                } else {
                    if($this->Valor2 == ''){
                        $QueryResumen = "(SELECT ".$CampoRut." FROM {$Tabla} WHERE {$Columna} {$this->Logica} {$this->Valor})";
                    }else{
                        $QueryResumen = "(SELECT ".$CampoRut." FROM {$Tabla} WHERE {$Columna} BETWEEN {$this->Valor} AND {$this->Valor2} )";
                    }
                }
            }else{
                if($Coma == 1) {
                    $QueryResumen = "(SELECT ".$CampoRut." FROM $Tabla WHERE $Columna IN ($this->Valor) AND Id_Cedente = $this->IdCedente)";
                } else {
                    if ($this->Valor2 == '' ) {
                        $QueryResumen = "(SELECT ".$CampoRut." FROM $Tabla WHERE $Columna $this->Logica $this->Valor AND Id_Cedente = $this->IdCedente)";                        
                    } else {
                        $QueryResumen = "(SELECT ".$CampoRut." FROM $Tabla WHERE $Columna BETWEEN  $this->Valor AND $this->Valor2 AND Id_Cedente = $this->IdCedente)";                        
                    }
                }
            }
            if ($TipoDato == 4) {
                $QueryResumen = "(SELECT ".$CampoRut." FROM $Tabla)";
            }            
        
            if ($this->IdSubQuery==0) {
                $QueryPositiva = $QueryPositivaKA." Rut IN ".$QueryResumen.$QueryPositivaKB;
                $QueryNegativa = $QueryPositivaKA." NOT Rut IN(SELECT Rut FROM Persona WHERE Rut IN".$QueryResumen.")".$QueryPositivaKB;
                $NumeroEspaciosTotal = 0;
            } else {
                $NumeroEspaciosTotal = 5;
                $QueryEspacios = $db->select("SELECT espacios FROM SIS_Querys_Estrategias WHERE id = {$IdSubQuery} LIMIT 1");
                foreach((array) $QueryEspacios  as $row) {
                    $Espacios = $row['espacios'];
                }
                $NumeroEspaciosTotal = $NumeroEspaciosTotal+$Espacios;
                $Array = array();
                $i=$this->IdSubQuery;
                while($i != 0) {
                    $QueryIteracion = $db->select("SELECT id,id_subquery FROM SIS_Querys_Estrategias WHERE id=$i");
                    foreach((array) $QueryIteracion as $row )
                    {
                        $IdQuery = $row['id'];
                        $IdSubQueryIteracion = $row['id_subquery'];
                        if($IdSubQueryIteracion==0) {
                            array_push($Array,$IdQuery);
                        } else {
                            array_push($Array,$IdQuery);
                        } 
                    }  
                    $i = $IdSubQueryIteracion; 
                }

                $CantidadArray = count((array) $Array);
                $k = 0;
                while($k < $CantidadArray)
                {
                    //echo "SELECT query_resumen,condicion,id_subquery FROM SIS_Querys_Estrategias WHERE id = $Array[$k]";
                    $QueryFinal = $db->select("SELECT query_resumen,condicion,id_subquery FROM SIS_Querys_Estrategias WHERE id = {$Array[$k]}");
                    $CondicionFinal = '';
                    foreach((array) $QueryFinal as $row)
                    {
                        $Query = $row['query_resumen']; 
                        $Condicion = $row['condicion'];
                        $SubQuery = $row['id_subquery'];
                        if($SubQuery==0) {
                            $Comodin = '';
                        } else {
                            $Comodin = 'AND ';
                        }
                        $QueryResult  = $Comodin.$Condicion.$Query.$QueryResult;
                    }
                    $k++;
                }
                $QueryPositiva = $QueryPositivaKA.$QueryResult.$Comodin." AND Rut IN".$QueryResumen.$QueryPositivaKB;
                //$QueryNegativa = $QueryPositivaKA.$QueryResult.$Comodin." AND NOT Rut IN".$QueryResumen.$QueryPositivaKB;
                $QueryNegativa = $QueryPositivaKA.$QueryResult.$Comodin." AND NOT Rut IN(SELECT Rut FROM Persona WHERE Rut IN".$QueryResumen.")".$QueryPositivaKB;
            }    

            $CantidadRegistrosPositivos  = count((array) $db->select($QueryPositiva));
            $CantidadRegistrosNegativos  = count((array) $db->select($QueryNegativa));
            $strDocumentosPositivos = "SELECT {$cPivote} FROM {$Tabla} WHERE {$cPivote} IN ({$QueryPositiva}) AND {$campoForaneo} = {$this->IdCedente}";
            $this->logs->debug($strDocumentosPositivos);
            $CantidadDocumentosPositivos = count((array) $db->select($strDocumentosPositivos));

            $strDocumentosNegativos = "SELECT {$cPivote} FROM {$Tabla} WHERE {$cPivote} IN ({$QueryNegativa}) AND {$campoForaneo} = {$this->IdCedente}";
            $this->logs->debug($strDocumentosNegativos);
            $CantidadDocumentosNegativos = count((array) $db->select($strDocumentosNegativos));

            $QueryDeudaPositiva = "SELECT SUM(d.Deuda) as monto FROM Persona p, Deuda d WHERE p.Rut IN ({$QueryPositiva}) AND p.Rut = d.Rut AND d.Id_Cedente = {$this->IdCedente}";
            $this->logs->debug($QueryDeudaPositiva);
            $QueryDeudaExecPositiva = $db->select($QueryDeudaPositiva);
            $MontoMoraPositiva = '';
            foreach((array) $QueryDeudaExecPositiva as $row) {
                $MontoMoraPositiva = $row['monto'];
                if($MontoMoraPositiva == NULL || $MontoMoraPositiva == '' ){
                    $MontoMoraPositiva=0;
                }
            }
        
            $QueryDeudaNegativa = "SELECT SUM(d.Deuda) as monto FROM Persona p, Deuda d WHERE p.Rut IN ($QueryNegativa) AND p.Rut = d.Rut AND d.Id_Cedente =  $this->IdCedente";
            $this->logs->debug($QueryDeudaNegativa);
            $QueryDeudaExecNegativa = $db->select($QueryDeudaNegativa);
            $MontoMoraNegativa = '';
            foreach((array) $QueryDeudaExecNegativa as $row) {
                $MontoMoraNegativa = $row['monto'];
                if($MontoMoraNegativa == NULL || $MontoMoraNegativa == '' ){
                    $MontoMoraNegativa=0;
                }
            }
            $db->query("UPDATE SIS_Querys_Estrategias SET carpeta='1' WHERE id = {$this->IdSubQuery}"); 
            $queryResumenNegativa  = "(SELECT Rut FROM Persona WHERE Rut IN".$QueryResumen.")";
            $InsertarQueryPostiva  = $db->query("INSERT INTO SIS_Querys_Estrategias (tabla,query,monto,cola,cantidad,id_estrategia,query_resumen,condicion,id_subquery,Id_Cedente,columna,espacios,dinamica,color,documentos) VALUES ('{$Tabla}','".addslashes($QueryPositiva)."','$MontoMoraPositiva','$NombreCola','$CantidadRegistrosPositivos','$this->IdEstrategia','".addslashes($QueryResumen)."','Rut IN','$this->IdSubQuery','$this->IdCedente','$Columna','$NumeroEspaciosTotal','$Dinamica','$Color','$CantidadDocumentosPositivos')");
            $InsertarQueryNegativa = $db->query("INSERT INTO SIS_Querys_Estrategias (tabla,query,monto,cola,cantidad,id_estrategia,query_resumen,condicion,id_subquery,Id_Cedente,columna,espacios,dinamica,color,documentos) VALUES ('{$Tabla}','".addslashes($QueryNegativa)."','$MontoMoraNegativa','Disponibles','$CantidadRegistrosNegativos','$this->IdEstrategia','".addslashes($queryResumenNegativa)."','Rut NOT IN','$this->IdSubQuery','$this->IdCedente','$Columna','$NumeroEspaciosTotal','$Dinamica','$Color','$CantidadDocumentosNegativos')");
            
            $db->query("DELETE FROM  SIS_Querys_Activas WHERE id_estrategia = '$IdEstrategia'");
            $this->MostrarEstrategias($this->IdEstrategia);
        }
    }

    public function MostrarEstrategias($IdEstrategia)
    {
        $db = new DB();        
        $this->IdEstrategia = (int) $IdEstrategia;
        $QueryEstrategia = "SELECT id,id_subquery FROM SIS_Querys_Estrategias WHERE id_estrategia = {$this->IdEstrategia} AND id_subquery = 0;";
        $rows = $db->select($QueryEstrategia);
        if ($rows) {
            foreach((array) $rows as $row) {
                $id = (int) $row["id"];
                $id_subquery = $row["id_subquery"];
                array_push($this->array_central, $id);
                $query = "SELECT * FROM SIS_Querys_Estrategias WHERE id_subquery = {$id};";
                // var_dump($query);
                $this->N($query);
            }
        }

        $QueryEstrategia = "SELECT cola,id,espacios,cantidad,monto,dinamica,carpeta,prioridad,comentario,terminal,sistema,documentos, id_subquery FROM SIS_Querys_Estrategias WHERE id_estrategia = {$this->IdEstrategia} AND id_subquery = 0 ORDER BY id_subquery ASC";
        $estrategias = $db->select($QueryEstrategia);

        if ($estrategias) {
            echo '<div class="row" style="padding: 12px;">'.PHP_EOL;
            echo '<div class="col-md-12">'.PHP_EOL;
            echo '<table class="table">'.PHP_EOL;
            echo '<thead><tr>'.PHP_EOL;
            echo '<th colspan="2">Segmento</th>'.PHP_EOL;
            echo '<th style="width: 10%; text-align: right;">Cant. Ruts</th>'.PHP_EOL;
            echo '<th style="width: 10%; text-align: right;">Cant. Documentos</th>'.PHP_EOL;
            echo '<th style="width: 15%; text-align: right;">Saldo/Deuda</th>'.PHP_EOL;
            echo '<th style="width: 15%; text-align: right;">&nbsp;</th>'.PHP_EOL;
            echo '</tr></thead>'.PHP_EOL;

            echo '<tbody>'.PHP_EOL;
            foreach ((array) $estrategias as $key => $estrategia) {
                $Disabled = ''; $Dividir = '';
                $Dividir = '<input type="text" name="K'.$estrategia['id'].'" class="form-control col-md-4 Cola" id="K'.$estrategia['id'].'" value="'.$estrategia['cola'].'" readonly />';
                if ($estrategia['carpeta'] != '1' && (int) $estrategia['cantidad'] > 0) {
                    $Dividir = '<div class="input-group">';
                    $Dividir .= '<input type="text" name="K'.$estrategia['id'].'" class="form-control col-md-4 Cola" id="K'.$estrategia['id'].'" value="'.$estrategia['cola'].'" style="border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important;" />';
                    $Dividir .= '<span class="input-group-btn"><button type="button" id="'.$estrategia['id'].'" class="btn btn-success SubEstrategia" title="Segmentar" style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;"><i class="fa fa-arrows-split-up-and-left"></i>&nbsp;&nbsp;Segmentar</button></span>';
                    $Dividir .= '</div>';
                }

                echo '<tr id="'.$estrategia['id'].'">'.PHP_EOL;
                //echo '<td style="width: 3%;">.</td>'.PHP_EOL;
                echo '<td colspan="2">'.$Dividir.'</td>'.PHP_EOL;
                echo '<td style="vertical-align: middle; text-align: right; font-size: 14px; font-weight: 600;">'.number_format((int) $estrategia['cantidad'], 0, '', '.').'</td>'.PHP_EOL;
                echo '<td style="vertical-align: middle; text-align: right; font-size: 14px; font-weight: 600;">'.number_format((int) $estrategia['documentos'], 0, '', '.').'</td>'.PHP_EOL;
                echo '<td style="vertical-align: middle; text-align: right; font-size: 14px; font-weight: 600;">$ '.number_format((float) $estrategia['monto'], 0, '', '.').'</td>'.PHP_EOL;
                echo '<td style="vertical-align: middle; text-align: right;">'.PHP_EOL;
                echo '<button type="button" class="btn btn-primary" id="Actualizar" style="margin-left: 5px;" title="Actualizar"><i class="fa-solid fa-repeat"></i></button>';
                if ((int) $estrategia['cantidad'] > 0) {
                    //echo '<button type="button" class="btn btn-info Ver" style="margin-left: 5px;" '.$Disabled.' title="Descargar" id="D-'.$estrategia['id'].'"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar</button>';
                    echo '<button type="button" class="btn btn-success Asignar" id="btnAsignar-'.$estrategia['id'].'" style="margin-left: 5px;" '.$Disabled.' title="Asignar"><i class="fa-solid fa-envelopes-bulk"></i>&nbsp;&nbsp;Asignar</button>';
                }
                echo '</td>'.PHP_EOL;
                echo '</tr>'.PHP_EOL;

                echo $this->__subQueries2($estrategia['id']);
            }
            echo '</tbody>'.PHP_EOL;

            /*
            echo '<tfoot>'.PHP_EOL;
            echo '<tr><td colspan="7">'.($key + 1).' Segmento(s)</td></tr>'.PHP_EOL;
            echo '<tfoot>'.PHP_EOL;
            */

            echo '</table>'.PHP_EOL;
            echo '</div>'.PHP_EOL;
            echo '</div>'.PHP_EOL;
            /*
            echo '<div class="row">'.PHP_EOL;
            foreach ((array) $estrategias as $estrategia) {
                $clase = 'panel-primary';
                $Disabled = ''; $Dividir = '';
                if ($estrategia['carpeta'] != '1' && (int) $estrategia['cantidad'] > 0) {
                    $Dividir = '<div class="input-group">';
                    $Dividir .= '<input type="text" name="K'.$estrategia['id'].'" class="form-control col-md-4 Cola" id="K'.$estrategia['id'].'" value="'.$estrategia['cola'].'" style="border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important;" />';
                    $Dividir .= '<span class="input-group-btn"><button type="button" id="'.$estrategia['id'].'" class="btn btn-success SubEstrategia" title="Segmentar" style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;"><i class="fa fa-arrows-split-up-and-left"></i>&nbsp;&nbsp;Segmentar</button></span>';
                    $Dividir .= '</div>';
                }
                if (mb_strtolower($estrategia['cola']) == 'Disponibles') $clase = 'panel-warning';
                echo '<div class="col-md-12">'.PHP_EOL;
                echo '<div class="panel '.$clase.'" style="border: 1px solid #eeeeee !important;" id="E'.$estrategia['id'].'">';
                echo '<div class="panel-heading" style="height: 35px; border-top-right-radius: 4px !important; border-top-left-radius: 4px !important; padding: 10px 15px;">';
                echo '<h4 style="margin: 0;"><i class="fa fa-list"></i>&nbsp;&nbsp;'.$estrategia['cola'].'</h4>';
                // echo '<input type="hidden" value="'.$estrategia['cola'].'" id="K'.$estrategia['id'].'" />';
                echo '</div>';
                //echo '<div class="panel-body">';
                echo '<table class="table">';
                echo '<tbody>';
                echo '<tr id="'.$estrategia['id'].'">';
                echo '<td style="background-color: #eee; text-align: right; width: 15%;">Cant. Rut:</td>';
                echo '<td style="width: 15%; text-align:center; font-weight: 600;">'.number_format((int) $estrategia['cantidad'], 0, '', '.').'</td>'; 
                echo '<td style="background-color: #eee; text-align: right; width: 15%;">Cant. Documentos:</td>';
                echo '<td style="width: 15%; text-align:center; font-weight: 600;">'.number_format((int) $estrategia['documentos'], 0, '', '.').'</td>'; 
                echo '<td style="background-color: #eee; text-align: right; width: 15%;"><i class="fa fa-money"></i>&nbsp;&nbsp;Deuda:</td>';
                echo '<td style="text-align: right; font-weight: 600;">$ '.number_format((float) $estrategia['monto'], 0, '', '.').'</td>'; 
                echo '</tr>';
                echo '<tr><td colspan="3" style="text-align: right;">'.$Dividir.'</td><td colspan="3" style="text-align: right;">';
                //echo $Dividir;
                echo '<button type="button" class="btn btn-primary" id="Actualizar" style="margin-left: 5px;" title="Actualizar"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Actualizar</button>';
                if ((int) $estrategia['cantidad'] > 0) {
                    echo '<button type="button" class="btn btn-info Ver" style="margin-left: 5px;" '.$Disabled.' title="Descargar" id="D-'.$estrategia['id'].'"><i class="fa fa-download"></i>&nbsp;&nbsp;Descargar</button>';
                    echo '<button type="button" class="btn btn-purple Asignar" id="btnAsignar-'.$estrategia['id'].'" style="margin-left: 5px;" '.$Disabled.' title="Asignar"><i class="fa fa-sign-in"></i>&nbsp;&nbsp;Asignar</button>';
                }
                echo '</td></tr>';
                echo $this->__subQueries($estrategia['id']);
                echo '</tbody>';
                echo '</table>';
                //echo '</div>';
                echo '</div>'.PHP_EOL;
                echo '</div>'.PHP_EOL;
            }
            echo '</div>'.PHP_EOL;
            */
        } else {
            echo '';
        }
    }

    private function __subQueries2($queryId, $nivel = 0, $padding = 2)
    {
        $html = '';
        $strSql2 = "SELECT cola,id,espacios,cantidad,monto,dinamica,carpeta,prioridad,comentario,terminal,sistema,documentos, id_subquery FROM SIS_Querys_Estrategias WHERE id_estrategia = {$this->IdEstrategia} AND id_subquery = {$queryId} ORDER BY id_subquery ASC";
        $rsSubQueries = $this->db->select($strSql2);
         
        if ($rsSubQueries) {
            $nivel++;
            foreach ((array) $rsSubQueries as $subQuery) {
                $Disabled = '';$Dividir = '';
                $Dividir = '<input type="text" name="K'.$subQuery['id'].'" class="form-control col-md-4 Cola" id="K'.$subQuery['id'].'" value="'.$subQuery['cola'].'" readonly />';
                if ($subQuery['carpeta'] != '1' && (int) $subQuery['cantidad'] > 0) {
                    $Dividir = '<div class="input-group">';
                    $Dividir .= '<input type="text" name="K'.$subQuery['id'].'" class="form-control col-md-4 Cola" id="K'.$subQuery['id'].'" value="'.$subQuery['cola'].'" style="border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important;" />';
                    $Dividir .= '<span class="input-group-btn"><button type="button" id="'.$subQuery['id'].'" class="btn btn-success SubEstrategia" title="Segmentar" style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;"><i class="fa fa-arrows-split-up-and-left"></i>&nbsp;&nbsp;Segmentar</button></span>';
                    $Dividir .= '</div>';
                }
                $html .= '<tr id="'.$subQuery['id'].'" class="nivel-'.$nivel.'-table">';
                $html .= '<td style="vertical-align: middle; text-align: center;"><i class="fa-solid fa-arrow-right-to-bracket"></i></td>';
                $html .= '<td style="padding-left: '.$padding.'px;">'.$Dividir.'</td>';
                //$html .= '<td>'.$Dividir.'</td>';
                $html .= '<td style="text-align: right;"><span style="display: block; width: 100%; font-weight: 600; padding-top: 8px;">'.number_format($subQuery['cantidad'], 0, '', '.').'</span></td>';
                $html .= '<td style="text-align: right;"><span style="display: block; width: 100%; font-weight: 600; padding-top: 8px;">'.number_format($subQuery['documentos'], 0, '', '.').'</span></td>';
                $html .= '<td style="text-align: right;"><span style="display: block; width: 100%; font-weight: 600; padding-top: 8px;">$ '.number_format((float) $subQuery['monto'], 0, '', '.').'</span></td>';
                $html .= '<td style="text-align: right;">';
                $html .= '<button type="button" class="btn btn-primary" id="Actualizar" style="margin-left: 5px;" title="Actualizar"><i class="fa-solid fa-repeat"></i></button>';
                if ((int) $subQuery['cantidad'] > 0) {
                   // $html .= '<button type="button" class="btn btn-info Ver" style="margin-left: 5px;" '.$Disabled.' title="Descargar" id="D-'.$subQuery['id'].'"><i class="fa fa-download"></i></button>';
                    $html .= '<button type="button" class="btn btn-success Asignar" id="btnAsignar-'.$subQuery['id'].'" style="margin-left: 5px;" '.$Disabled.' title="Asignar"><i class="fa-solid fa-envelopes-bulk"></i>&nbsp;&nbsp;Asignar</button>';
                }
                $html .= '</td>';
                $html .= '</tr>';
                $html .= $this->__subQueries2($subQuery['id'], $nivel, ((int) $subQuery['espacios'] * 4));
            }
        }
        return $html;
    }

    private function __subQueries($queryId, $nivel = false, $padding = 2)
    {
        $html = '';
        $strSql2 = "SELECT cola,id,espacios,cantidad,monto,dinamica,carpeta,prioridad,comentario,terminal,sistema,documentos, id_subquery FROM SIS_Querys_Estrategias WHERE id_estrategia = {$this->IdEstrategia} AND id_subquery = {$queryId} ORDER BY id_subquery ASC";
        $rsSubQueries = $this->db->select($strSql2);
        if ($rsSubQueries) {
            if (!$nivel) {
                $html = '<tr><td colspan="6">';
                $html .= '<table class="table table-sm">';
                $html .= '<thead>';
                $html .= '<tr>'; 
                $html .= '<th><i class="fa fa-folder"></i>&nbsp;&nbsp;Sub Grupo</th>';
                $html .= '<th style="width: 10%; text-align: center;">Cant. Rut</th>';
                $html .= '<th style="width: 10%; text-align: center;">Cant. Documentos</th>';
                $html .= '<th style="width: 15%; text-align: right;"><i class="fa fa-money"></i>&nbsp;&nbsp;Deuda</th>';
                $html .= '<th style="width: 25%; text-align: center;">&nbsp;</th>';
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
            }
            foreach ((array) $rsSubQueries as $subQuery) {
                $Disabled = '';$Dividir = '';
                $Dividir = '<input type="text" name="K'.$subQuery['id'].'" class="form-control col-md-4 Cola" id="K'.$subQuery['id'].'" value="'.$subQuery['cola'].'" readonly />';
                if ($subQuery['carpeta'] != '1' && (int) $subQuery['cantidad'] > 0) {
                    $Dividir = '<div class="input-group">';
                    $Dividir .= '<input type="text" name="K'.$subQuery['id'].'" class="form-control col-md-4 Cola" id="K'.$subQuery['id'].'" value="'.$subQuery['cola'].'" style="border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important;" />';
                    $Dividir .= '<span class="input-group-btn"><button type="button" id="'.$subQuery['id'].'" class="btn btn-success SubEstrategia" title="Segmentar" style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;"><i class="fa fa-arrows-split-up-and-left"></i>&nbsp;&nbsp;Segmentar</button></span>';
                    $Dividir .= '</div>';
                }
                $html .= '<tr id="'.$subQuery['id'].'">';
                $html .= '<td style="padding-left: '.$padding.'px;">';
                $html .= '<div class="input-group"><span class="input-group-addon" style="background-color: #eee; border-top-left-radius: 4px !important; border-bottom-left-radius: 4px !important;"><i class="fa fa-indent" aria-hidden="true"></i></span><input type="text" class="form-control" name="K'.$subQuery['id'].'" value="'.$subQuery['cola'].'" id="K'.$subQuery['id'].'" style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; border-left: none !important;" /></div></td>';
                $html .= '<td style="text-align: center;"><span style="display: block; width: 100%; font-weight: 600; padding-top: 8px;">'.number_format($subQuery['cantidad'], 0, '', '.').'</span></td>';
                $html .= '<td style="text-align: center;"><span style="display: block; width: 100%; font-weight: 600; padding-top: 8px;">'.number_format($subQuery['documentos'], 0, '', '.').'</span></td>';
                $html .= '<td style="text-align: right;"><span style="display: block; width: 100%; font-weight: 600; padding-top: 8px;">$ '.number_format((float) $subQuery['monto'], 0, '', '.').'</span></td>';
                $html .= '<td style="text-align: right;">';
                $html .= $Dividir;
                $html .= '<button type="button" class="btn btn-primary" id="Actualizar" style="margin-left: 5px;" title="Actualizar"><i class="fa fa-refresh"></i></button>';
                if ((int) $subQuery['cantidad'] > 0) {
                    $html .= '<button type="button" class="btn btn-info Ver" style="margin-left: 5px;" '.$Disabled.' title="Descargar" id="D-'.$subQuery['id'].'"><i class="fa fa-download"></i></button>';
                    $html .= '<button type="button" class="btn btn-purple Asignar" id="btnAsignar-'.$subQuery['id'].'" style="margin-left: 5px;" '.$Disabled.' title="Asignar"><i class="fa-solid fa-envelopes-bulk"></i>&nbsp;&nbsp;Asignar</button>';
                }
                $html .= '</td>';
                $html .= '</tr>';
                $html .= $this->__subQueries($subQuery['id'], true, ((int) $subQuery['espacios'] * 4));
            }
            if (!$nivel) {
                $html .= '</tbody>';
                $html .= '</table></td></tr>';
            }
        }
        return $html;
    }

    public function N($query)
    {
        $db = new DB();
        $rows = $db->query($query);
        if ($rows) {
            $filas = $rows->fetch_all(MYSQLI_ASSOC);
            foreach((array)  $filas as $row) {
                if (!is_null($row["id"])) {
                    $id = (int) $row["id"];
                    array_push($this->array_central, $id);
                    $query = "SELECT id FROM SIS_Querys_Estrategias WHERE id_subquery = {$id};";
                    $this->N($query);
                }
            }
        }
    }

    public function ActualizarPrioridad($Id,$ValorPrioridad)
    {
        $db = new DB();
        $this->Id=$Id;
        $this->ValorPrioridad=$ValorPrioridad;
        $db->query("UPDATE SIS_Querys_Estrategias SET prioridad = '{$this->ValorPrioridad}' WHERE id = {$Id}");
    }

    public function ActualizarComentario($Id,$ValorComentario)
    {
        $db = new DB();
        $this->Id=$Id;
        $this->ValorComentario=$ValorComentario;
        $db->query("UPDATE SIS_Querys_Estrategias SET comentario = '{$this->ValorComentario}' WHERE id = {$Id}");
    }

    public function ActualizarCola($Id,$ValorCola)
    {
        $db = new DB();
        $this->Id=$Id;
        $this->ValorCola=$ValorCola;
        $db->query("UPDATE SIS_Querys_Estrategias SET cola='$this->ValorCola' WHERE id=$Id");
    }

    public function Deshacer($IdEstrategia)
    {
        $db = new DB();
        $this->IdEstrategia=$IdEstrategia;
        $rows = $db->select("SELECT id_subquery FROM SIS_Querys_Estrategias WHERE id_estrategia = $this->IdEstrategia ORDER BY id_subquery DESC LIMIT 1");
        foreach((array) $rows as $row){
            $IdSubqueryDesahacer = $row["id_subquery"];
            $SqlColas = "SELECT id FROM SIS_Querys_Estrategias WHERE id_subquery = $IdSubqueryDesahacer AND id_estrategia = $this->IdEstrategia and sistema =0";
            $Colas = $db->select($SqlColas);
            foreach((array) $Colas as $Cola){
                $idCola = $Cola["id"];
                $Prefix = "QR_".$_SESSION["cedente"]."_".$idCola;
                $SqlTables = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'foco' and TABLE_NAME like '".$Prefix."%'";
                $Tables = $db->select($SqlTables);
                foreach((array) $Tables as $Table){
                    $Tabla = $Table["TABLE_NAME"];
                    $SqlDrop = "DROP TABLE ".$Tabla;
                    $Drop = $db->query($SqlDrop);
                }
            }
            $db->query("DELETE FROM SIS_Querys_Estrategias WHERE id_subquery = $IdSubqueryDesahacer AND id_estrategia = $this->IdEstrategia and sistema =0");
            $db->query("UPDATE SIS_Querys_Estrategias SET carpeta='0' WHERE id = $IdSubqueryDesahacer");
        }
    }

    public function Terminal($IdTerminal,$Check,$Categorias,$TipoCategoria,$ver_agenda,$idUserCautiva,$comentario)
    {
        $db = new DB();
        $FieldCategoria = "";
        switch($TipoCategoria){
            case "Colores":
                $FieldCategoria = "color";
            break;
            case "Prioridad_Fonos":
                $FieldCategoria = "Prioridad_Fono";
            break;
        }

        if ($idUserCautiva) {
            $cautiva = 1;
        } else {
            $cautiva = 0;
            $idUserCautiva = 0;
        }

        if ($Check == 0) {
            $query = "UPDATE SIS_Querys_Estrategias SET terminal = '".$Check."', color = '', Prioridad_Fono = '' WHERE id = '".$IdTerminal."'";
        } else {
            $query = "UPDATE SIS_Querys_Estrategias SET terminal = '".$Check."', ".$FieldCategoria." = '".$Categorias."', ver_agenda = '".$ver_agenda."', cautiva = '".$cautiva."', idUserCautiva = '".$idUserCautiva."', comentario = '".$comentario."' WHERE id = '".$IdTerminal."'";          
        }

        $ToReturn = $db->query($query);  
        return $ToReturn;
    }

    public function MoverGrupo($IdSubQuery)
    {
        $db = new DB();
        $this->IdSubQuery=$IdSubQuery;
        $rows = $db->select("SELECT cola, monto, cantidad FROM SIS_Querys_Estrategias WHERE id = $this->IdSubQuery LIMIT 1");
        $html = '';
        if ($rows) {
            $datos = (array) $rows[0];
            $html = '<table class="table table-sm" style="margin: 0;">'.PHP_EOL;
            $html .= '<thead>'.PHP_EOL;
            $html .= '<tr>'.PHP_EOL;
            $html .= '<th colspan="2">'.$datos['cola'].'</th>'.PHP_EOL;
            $html .= '</tr>'.PHP_EOL;
            $html .= '</thead>'.PHP_EOL;
            $html .= '<tbody>'.PHP_EOL;
            $html .= '<tr>'.PHP_EOL;
            $html .= '  <td style="width: 40%; text-align: right;">Registros:</td>'.PHP_EOL;
            $html .= '  <td style="text-align: left; font-weight: 600;">'.number_format($datos["cantidad"],0, "", ".").'</td>'.PHP_EOL;
            $html .= '</tr>'.PHP_EOL;
            $html .= '<tr>'.PHP_EOL;
            $html .= '  <td style="width: 40%; text-align: right;">Monto:</td>'.PHP_EOL;
            $html .= '  <td style="text-align: left; font-weight: 600;">$ '.number_format($datos["monto"], 0, "", ".").'</td>'.PHP_EOL;
            $html .= '</tr>'.PHP_EOL;
            $html .= '</tbody>'.PHP_EOL;
            /*
            foreach((array) $rows as $row) {
                echo $Cola= "Grupo : ".$row["cola"]." | ";
                echo $Monto= "Registros : ".number_format($row["cantidad"],0, "", ".")." | ";
                echo $Monto= "Monto : $ ".number_format($row["monto"], 0, "", ".")."";
            }
            */
            $html .= '</table>';
        }

        echo $html;
    }

    public function Total($IdCedente)
    {
        $db = new DB();
        $salida = '';
        $this->IdCedente = (int) $IdCedente;
        $rows = $db->select("SELECT fecha, Cant_Ruts, Deuda_Total FROM Historico_Carga WHERE Id_Cedente = {$this->IdCedente} ORDER BY fecha DESC LIMIT 1");
        $html = '';
        if ($rows) {
            $datos = $rows[0];
            $html = '<table class="table table-sm" style="margin: 0;">'.PHP_EOL;
            $salida = 'Fecha Asignación: '.$datos['fecha'].' | ';
            $salida = 'Registros: '.number_format($datos['Cant_Ruts'],0, '', '.').' | ';
            $salida = 'Monto: $ '.number_format($datos['Deuda_Total'], 0, '', '.');
            $html .= '</table>'.PHP_EOL;
        }
        /*
        if(count((array) $rows)<1) {
            echo "Sin Información";
        } else {
            foreach((array) $rows as $row){
                echo $Cola = "Fecha Asignación : ".$row["fecha"]." | ";
                echo $Monto = "Registros : ".number_format($row["Cant_Ruts"],0, "", ".")." | ";
                echo $Monto = "Monto : $ ".number_format($row["Deuda_Total"], 0, "", ".")." ";
            }
        } 
        */
        echo $html;
    }

    public function crearEstrategia($nombre_estrategia,$tipo_estrategia,$comentario,$fecha,$hora,$usuario,$cedente,$idUsuario,$separar)
	{
        $db = new DB();
        $result = $db->query("SELECT * FROM SIS_Querys_Activas");
        $queryActiva = $result->num_rows;
        if($queryActiva > 0) {            
            echo "0";
        } else {
            $this->nombre_estrategia=$nombre_estrategia;
            $this->tipo_estrategia=$tipo_estrategia;
            $this->comentario=$comentario;
            $this->fecha=$fecha;
            $this->hora=$hora;
            $this->usuario=$usuario;
            $this->cedente=$cedente;
            $this->idUsuario=$idUsuario;
            $IdEstrategia = '';

            $db->query("INSERT INTO SIS_Estrategias(nombre,comentario,fecha,hora,usuario,tipo,Id_Cedente,Id_Usuario,modo_operacion,periodicidad,grupo) VALUES('$this->nombre_estrategia','$this->comentario','$this->fecha','$this->hora','$this->usuario','$this->tipo_estrategia','$this->cedente','$this->idUsuario','0','0','0')");
            $query1 = $db->select("SELECT id FROM SIS_Estrategias WHERE nombre = '$this->nombre_estrategia' AND fecha = '$this->fecha' AND hora = '$this->hora' LIMIT 1");        
            foreach((array) $query1 as $row) {
                $IdEstrategia = $row['id'];
            }
            if(!$_SESSION){
                session_start();
            }
            $_SESSION['IdEstrategia'] = $IdEstrategia;
            $db->query("INSERT INTO SIS_Querys_Activas(id_estrategia,nombre,fecha,hora,usuario,cedente) VALUES('$IdEstrategia','$this->nombre_estrategia','$this->fecha','$this->hora','$this->usuario','$this->cedente')");
            if($separar != 1) {    
                /*$QueryResumen = "(SELECT Rut FROM fono_cob)";
                $QueryPositiva = "SELECT Rut FROM Persona WHERE Rut IN (SELECT Rut FROM fono_cob)  AND $this->cedente in (select * from STRING_SPLIT(Id_Cedente,','))";
                $QueryNegativa = "SELECT Rut FROM Persona WHERE NOT Rut IN (SELECT Rut FROM fono_cob)  AND $this->cedente in (select * from STRING_SPLIT(Id_Cedente,','))";*/
                // $QueryResumen = "(SELECT Rut FROM fono_cob)";
                $QueryResumen = "(SELECT Rut FROM Persona WHERE Rut IN (SELECT fono_cob.Rut FROM fono_cob INNER JOIN Persona ON Persona.Rut = fono_cob.Rut WHERE fono_cob.vigente = '1') AND FIND_IN_SET('".$this->cedente."',Id_Cedente) AND Persona.con_deudas = 1)";
                //$QueryPositiva = "SELECT Rut FROM Persona WHERE Rut IN (SELECT Rut FROM fono_cob  WHERE LEN(formato_subtel)>8 AND LEN(formato_subtel)<10)  AND $this->cedente in (select * from STRING_SPLIT(Id_Cedente,','))";
                //$QueryNegativa = "SELECT Rut FROM Persona WHERE NOT Rut IN (SELECT Rut FROM fono_cob  WHERE LEN(formato_subtel)>8 AND LEN(formato_subtel)<10)  AND $this->cedente in (select * from STRING_SPLIT(Id_Cedente,','))";
                $QueryPositiva = "SELECT Rut FROM Persona WHERE Rut IN ".$QueryResumen." AND FIND_IN_SET('".$this->cedente."',Id_Cedente)";
                $QueryNegativa = "SELECT Rut FROM Persona WHERE NOT Rut IN ".$QueryResumen." AND FIND_IN_SET('".$this->cedente."',Id_Cedente)";
                //$QueryNegativa = "SELECT Rut FROM Persona WHERE NOT Rut IN (SELECT Rut FROM Persona WHERE Rut IN (SELECT Rut FROM fono_cob WHERE DATALENGTH(formato_subtel) = 9)) AND $this->cedente in (select * from STRING_SPLIT(Id_Cedente,','))";
                $CantidadRegistrosPositivos = count((array) $db->select($QueryPositiva));
                $CantidadRegistrosNegativos = count((array) $db->select($QueryNegativa));
                $QueryDeudaPositiva = "SELECT SUM(d.Deuda) AS suma FROM Persona p, Deuda d WHERE p.Rut IN ($QueryPositiva) AND p.Rut = d.Rut AND d.Id_Cedente = '$this->cedente';";
                $QueryDeudaExecPositiva = $db->select($QueryDeudaPositiva);
                $MontoMoraPositiva = '';
                foreach((array) $QueryDeudaExecPositiva as $row){
                    $MontoMoraPositiva = $row['suma'];
                    if($MontoMoraPositiva == NULL || $MontoMoraPositiva == ''){
                        $MontoMoraPositiva = 0;
                    }
                }

                $QueryDeudaNegativa = "SELECT SUM(d.Deuda) as monto FROM Persona p, Deuda d WHERE p.Rut IN ($QueryNegativa) AND p.Rut = d.Rut AND d.Id_Cedente = '$this->cedente';";
                $QueryDeudaExecNegativa = $db->select($QueryDeudaNegativa);
                $MontoMoraNegativa = '';
                foreach((array) $QueryDeudaExecNegativa as $row){
                    $MontoMoraNegativa = $row['monto'];
                    if($MontoMoraNegativa == NULL || $MontoMoraNegativa == ''){
                        $MontoMoraNegativa = 0;
                    }
                }

                $SqlDocumentosPositivos = "SELECT Rut FROM Deuda WHERE Rut IN ($QueryPositiva) AND Id_Cedente = '$this->cedente';";
                $SqlDocumentosNegativos = "SELECT Rut FROM Deuda WHERE Rut IN ($QueryNegativa) AND Id_Cedente = '$this->cedente';";
                $CantidadDocumentosPositivos = count((array) $db->select($SqlDocumentosPositivos));
                $CantidadDocumentosNegativos = count((array) $db->select($SqlDocumentosNegativos));

                $db->query("INSERT INTO SIS_Querys_Estrategias (query,monto,cola,cantidad,id_estrategia,query_resumen,condicion,id_subquery,Id_Cedente,columna,espacios,dinamica,sistema,documentos) VALUES ('".addslashes($QueryPositiva)."','$MontoMoraPositiva','Con Telefonos','$CantidadRegistrosPositivos','$IdEstrategia','".addslashes($QueryResumen)."','Rut IN','0','$this->cedente','','0','0','1','$CantidadDocumentosPositivos')");
                $db->query("INSERT INTO SIS_Querys_Estrategias (query,monto,cola,cantidad,id_estrategia,query_resumen,condicion,id_subquery,Id_Cedente,columna,espacios,dinamica,sistema,documentos) VALUES ('".addslashes($QueryNegativa)."','$MontoMoraNegativa','Sin Telefonos','$CantidadRegistrosNegativos','$IdEstrategia','".addslashes($QueryResumen)."','NOT Rut IN','0','$this->cedente','','0','0','1','$CantidadDocumentosNegativos')");
                echo "1";
                $db->query("DELETE FROM SIS_Querys_Activas WHERE id_estrategia = '$IdEstrategia'");
            }else{
                echo "1";
                $db->query("DELETE FROM SIS_Querys_Activas WHERE id_estrategia = '$IdEstrategia'");
            }
        }
	}

    public function RecalculaQuery()
    {
        $db = new DB();
        $SelectQuery = $db->select("SELECT id,query,Id_Cedente FROM SIS_Querys_Estrategias");
        $Fecha = date('Y-m-d');
        foreach((array) $SelectQuery as $row){
            $Id = $row["id"];
            $Query = $row["query"];
            $Id_Cedente = $row["Id_Cedente"];
            $ColaFinal = "QR_".$Id_Cedente."_".$Id;
            $ExecQuery = $db->select("SELECT SUM( d.Deuda) as suma FROM Persona p, Deuda d WHERE p.Rut IN ($Query) AND p.Rut = d.Rut");
            foreach((array) $ExecQuery as $row){
                $MontoMora= $row["suma"];
                if($MontoMora == NULL || $MontoMora == '' ){
                    $MontoMora=0;
                }
            }

            $CantidadRegistros = count((array) $db->select($Query));
            $CantidadDocumentos = count((array) $db->select("SELECT Rut FROM Deuda WHERE Rut IN ($Query)"));
            $db->query("UPDATE SIS_Querys_Estrategias SET cantidad=$CantidadRegistros,monto=$MontoMora,documentos = $CantidadDocumentos WHERE id=$Id");
            /*$QueryConcat = $db->select("SELECT  GROUP_CONCAT(Rut) as group FROM Persona WHERE Rut IN($Query) AND '".$Id_Cedente."' in (select * from (Id_Cedente,','))");
            foreach((array) $QueryConcat as $row){
                $Concat = $row["group"];
            }

            $db->query("INSERT INTO Trazabilidad_Rut_Grupo(Rut, Cola_Trabajo, Fecha_Traza, Id_Cedente,Monto,Registros) VALUES ('$Concat','$ColaFinal','$Fecha','$Id_Cedente','$MontoMora','$CantidadRegistros')");
            $QueryRecupero = $db->select("SELECT 
                                        t.id,
                                        MAX(ROUND( (r.Monto / t.Monto) *100 )) as recupero
                                        FROM Recupero_Foco r, Trazabilidad_Rut_Grupo t
                                        WHERE FIND_IN_SET( r.Rut, t.Rut ) GROUP BY t.id ");
            foreach((array) $QueryRecupero as $row){
                $Id=$row["id"];
                $Recupero = $row["recupero"];
                //$db->query("UPDATE Trazabilidad_Rut_Grupo SET Recupero=$Recupero WHERE id=$Id AND Fecha_Traza = '$Fecha'");
            }*/          
        } 
    }

    public function RecalculaQueryCedente($Cedente,$IdEstrategia)
    {
        $db = new DB();
        $this->Id_Cedente = $Cedente;
        $MontoMora = 0;
        $CantidadRegistros  = 0;
        $CantidadDocumentos = 0;
        //echo "SELECT id,query,Id_Cedente FROM SIS_Querys_Estrategias WHERE Id_Cedente = $this->Id_Cedente AND id_estrategia=$IdEstrategia";
        $SelectQuery = $db->select("SELECT id,query,Id_Cedente FROM SIS_Querys_Estrategias WHERE Id_Cedente = $this->Id_Cedente AND id=$IdEstrategia");
        $Fecha = date('Y-m-d');
        foreach((array) $SelectQuery as $row)
        {
            $Id = $row["id"];
            $Query = $row["query"];
            $Id_Cedente = $row["Id_Cedente"];
            $ColaFinal = "QR_".$Id_Cedente."_".$Id;
            $CantidadRegistros = count((array) $db->select($Query));
            $ExecQuery = $db->select("SELECT SUM( d.Deuda) as suma FROM Persona p, Deuda d  WHERE p.Rut IN ($Query) AND p.Rut = d.Rut AND d.Id_Cedente = $this->Id_Cedente");
            foreach((array) $ExecQuery as $row){
                $MontoMora= $row["suma"];
                if($MontoMora == NULL || $MontoMora == '' ){
                    $MontoMora=0;
                }
            }

            $CantidadDocumentos = count((array) $db->select("SELECT Rut FROM Deuda WHERE Rut IN ($Query) and Id_Cedente = $this->Id_Cedente"));
            //echo "UPDATE SIS_Querys_Estrategias SET cantidad=$CantidadRegistros,documentos=$CantidadDocumentos,monto=$MontoMora WHERE id=$Id";
            $db->query("UPDATE SIS_Querys_Estrategias SET cantidad=$CantidadRegistros,documentos=$CantidadDocumentos,monto=$MontoMora WHERE id=$Id");               
        } 
    }

    function getTipoTelefono() 
    {
        $db = new DB();
        $SqlTipoTelefono = "select * from SIS_Categoria_Fonos where mundo = '1' order by prioridad";
        $TipoTelefono = $db->select($SqlTipoTelefono);
        return $TipoTelefono;
    }

    public function VerFonos()
	{
        $db = new DB();
        echo '<form id="Form_Terminal">';
        echo '<div class="row">';
        echo '<div class="col-md-6 form-group">';
        echo '<label class="control-label" for="name">Tipo Categoría Telefónica</label>';
        echo '<select class="selectpicker form-control" title="Seleccione Tipo de Categoría"  name="TipoCategoria" data-live-search="true" data-width="100%">
                            <option value="Colores">Tipos de Contacto</option>
                            <option value="Prioridad_Fonos">Prioridades</option>
                            </select>';
        echo '</div>';
        echo '<div class="col-md-6 form-group">';
        echo '<label class="control-label" for="name">Categoría Telefónica</label>';
        echo '<select class="selectpicker form-control" multiple title="Seleccione Categoría"  name="Categorias" id="Categorias" data-live-search="true" data-width="100%" data-actions-box="true"></select>';
        echo '</div>';
        echo '</div>';
        echo '<div class="row">';
        echo '<div class="col-md-6 form-group">';
        echo '<label class="control-label" for="name">Prioridad de Trabajo</label>';
        echo '<input type="number" class="form-control" name="prioridad" id="prioridad">';
        echo '</div>';
        echo '<div class="col-md-6 form-group">';
        echo '<label class="control-label" for="name">Agenda</label><br>';
        echo '<input name="ver_agenda" id="ver_agenda" class="toggle-switch" type="checkbox" checked="">';
        echo '<label class="toggle-switch-label"></label>';
        echo '</div>';
        echo '</div>';
        echo '<div class="row">';
        echo '<div class="col-md-6 form-group">';
        echo '<label class="control-label" for="name">Cautiva</label>';
        echo '<select class="selectpicker form-control" title="Seleccione Ejecutivo"  name="idUserCautiva" id="idUserCautiva" data-live-search="true" data-width="100%">';
        $ejecutivos = $db->select("SELECT id, nombre FROM Usuarios");
        foreach((array) $ejecutivos as $ejecutivo) {
            echo '<option value="'.$ejecutivo["id"].'">'.$ejecutivo["nombre"].'</option>';
        }
        echo '</select>';
        echo '</div>';
        echo '<div class="col-md-6 form-group">';
        echo '<label class="control-label" for="name">Comentario</label>';
        echo '<textarea id="comentario" name="comentario" class="form-control"></textarea>';
        echo '</div>';
        echo '</div>';
        echo '</form>';
    }

    function getCategoriasFromTipoCategoria($Tipo)
    {
        $db = new DB();
        $ToReturn = "";
        switch($Tipo){
            case "Colores":
                $rows = $db->select("SELECT sc.color as id,  CONCAT(tc.Nombre,' - ',sc.color_nombre) as comentario
                                    FROM
                                        SIS_Categoria_Fonos sc
                                            INNER JOIN Tipo_Contacto tc on tc.Id_TipoContacto = sc.tipo_contacto
                                    WHERE
                                        sc.mundo='1'
                                    ORDER BY
	                                    tc.Nombre");
                foreach((array) $rows as $row) {
                    $ToReturn .= "<option value='".$row["id"]."'>".utf8_encode($row["comentario"])."</option>";
                }
            break;
            case "Prioridad_Fonos":
                $SqlPrioridades = "SELECT
                                    Columnas_Template_Carga.Prioridad_Fono as Prioridad
                                FROM
                                    Columnas_Template_Carga
                                        INNER JOIN Template_Carga on Template_Carga.id = Columnas_Template_Carga.id_template
                                WHERE
                                    Template_Carga.Id_Cedente = '".$_SESSION["cedente"]."' AND
                                    Columnas_Template_Carga.Tabla = 'fono_cob' AND
                                    Columnas_Template_Carga.Campo = 'formato_subtel'
                                GROUP BY
                                    Columnas_Template_Carga.Prioridad_Fono
                                ORDER BY
                                    Columnas_Template_Carga.Prioridad_Fono ASC";
                $Prioridades = $db->select($SqlPrioridades);
                $ToReturn .= "<option value='0'>Prioridad 0</option>";
                foreach((array) $Prioridades as $Prioridad){
                    $ToReturn .= "<option value='".$Prioridad["Prioridad"]."'>Prioridad ".$Prioridad["Prioridad"]."</option>";
                }
                /*if(count((array) $Prioridades) == 0){
                    $ToReturn .= "<option value='0'>Prioridad 0</option>";
                }*/
            break;
        }
        return $ToReturn;
    }

    function addslashes_mssql($QueryPositiva)
    {
        $singQuotePattern = "'";
        $singQuoteReplace = "''";

        if (is_array($QueryPositiva)) {
            foreach((array) $QueryPositiva AS $id => $value) {
                $QueryPositiva[$id] = stripslashes(preg_replace($singQuotePattern, $singQuoteReplace, $value));
            }
        } else {
            $QueryPositiva = str_replace("'", "''", $QueryPositiva);   
        }
       
        return $QueryPositiva;
    }

    function DownloadResumenAsignacion($IdCola,$Porcentaje)
    {
		$db = new DB();
        $Rows = "";
        $query = "SELECT query FROM SIS_Querys_Estrategias WHERE id = '".$IdCola."'";
        $SIS_Querys_Estrategias = $db->select($query);

        if($SIS_Querys_Estrategias){

            $objPHPExcel = new PHPExcel();
            $Cedente = $_SESSION['cedente'];
            $fileName = "Resumen asignacion ".date("d_m_Y H_i_s");

            $NextSheet = 0;
            
            $Sql = "SELECT * FROM Columnas_Asignacion_Dial WHERE Id_Mandante IN (SELECT Id_Mandante FROM mandante_cedente WHERE Id_Cedente = '".$Cedente."') ORDER BY Prioridad";
            $ColumnasAsignacion = $db->select($Sql);
            $Col = 0;
            $ArrayStackedColumns = array();
            foreach((array) $ColumnasAsignacion as $ColumnaAsignacion){
                $Rows .= $ColumnaAsignacion["Nombre"].";";
                if($ColumnaAsignacion["Tipo_Campo"] == "1"){
                    array_push($ArrayStackedColumns,$ColumnaAsignacion["Campo"]);
                }
                $Col++;
            }

            $SqlColumnas = "SELECT SIS_Columnas_Estrategias.columna FROM SIS_Tablas INNER JOIN SIS_Columnas_Estrategias ON SIS_Columnas_Estrategias.id_tabla = SIS_Tablas.id WHERE SIS_Tablas.nombre = 'Deuda' AND FIND_IN_SET('".$Cedente."',SIS_Columnas_Estrategias.Id_Cedente)  ORDER BY SIS_Columnas_Estrategias.columna";
            $Columnas = $db->select($SqlColumnas);

            $ArrayDeudaSearch = array();
            foreach((array) $Columnas as $Columna){
                $key = array_search($Columna["columna"],$ArrayStackedColumns);
                if($key === FALSE){
                    array_push($ArrayDeudaSearch,$Columna['columna']);
                }
            }
            $ArrayDeudaSearchImplode = implode(",",$ArrayDeudaSearch);

            $Cont = 2;

            $SqlDeudas = $SIS_Querys_Estrategias[0]['query'];
            $find = "SELECT Rut";
            $replace = "SELECT COUNT(Rut) as Rut";
            $SqlCantidad = preg_replace("/$find/",$replace,$SqlDeudas,1);
            $Cantidad = $db->select($SqlCantidad);
            $Cantidad = $Cantidad[0]["Rut"];
            $Limit = round(($Cantidad * $Porcentaje) / 100);
            if($Limit < 1){
                $Limit = 1;
            }
            // $find = "SELECT";
            // $replace = "SELECT TOP(".$Top.")";
            // $SqlDeudas = preg_replace("/$find/",$replace,$SqlDeudas,1);
            $SqlDeudas .= " LIMIT ".$Limit;
            
            $Deudas = $db->select($SqlDeudas);
            foreach((array) $Deudas as $Deuda){
                $Rows .= "\r\n";
                $Rut = $Deuda["Rut"];
                $SqlFonos = "SELECT fono_cob.*, SIS_Categoria_Fonos.tipo_var AS Gestion FROM SIS_Categoria_Fonos INNER JOIN fono_cob ON fono_cob.color = SIS_Categoria_Fonos.color WHERE fono_cob.rut = '".$Rut."' AND SIS_Categoria_Fonos.sel = '0' ORDER BY SIS_Categoria_Fonos.prioridad LIMIT 3";
                $Fonos = $db->select($SqlFonos);
                $FonosTmp = array();
                foreach((array) $Fonos as $Fono){
                    array_push($FonosTmp,$Fono["formato_subtel"]."_".$Fono["Gestion"]);
                }
                $FonoEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],0,strpos($FonosTmp[0],"_")) : "";
                $GestionEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],strpos($FonosTmp[0],"_") + 1,strlen($FonosTmp[0])) : "";
                $ColorEspecial = isset($FonosTmp[0]) ? substr($FonosTmp[0],strripos($FonosTmp[0],"_") + 1,strlen($FonosTmp[0])) : "";

                $Fono2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],0,strpos($FonosTmp[1],"_")) : "";
                $Gestion2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],strpos($FonosTmp[1],"_") + 1,strlen($FonosTmp[1])) : "";
                $ColorFono2 = isset($FonosTmp[1]) ? substr($FonosTmp[1],strripos($FonosTmp[1],"_") + 1,strlen($FonosTmp[1])) : "";

                $Fono3 = isset($FonosTmp[2]) ? substr($FonosTmp[2],0,strpos($FonosTmp[2],"_")) : "";
                $Gestion3 = isset($FonosTmp[2]) ? substr($FonosTmp[2],strpos($FonosTmp[2],"_") + 1,strlen($FonosTmp[2])) : "";
                $ColorFono3 = isset($FonosTmp[3]) ? substr($FonosTmp[3],strripos($FonosTmp[3],"_") + 1,strlen($FonosTmp[3])) : "";
                
                $SqlMejorGestion = "SELECT * FROM Mejor_Gestion_Historica WHERE Rut = '".$Rut."' ORDER BY fechahora DESC LIMIT 1";
                $MejorGestion = $db->select($SqlMejorGestion);
                $MejorGestionTexto = "";
                $MejorGestionFecha = "";
                $MejorGestionN1 = "";
                $MejorGestionN2 = "";
                $MejorGestionN3 = "";
                $MejorGestionFechaAgendamiento = "";
                $MejorGestionFechaCompromiso = "";
                if(count((array) $MejorGestion) > 0){
                    $SqlTipoContacto = "SELECT * FROM Tipo_Contacto WHERE Id_TipoContacto = '".$MejorGestion[0]["Id_TipoGestion"]."'";
                    $TipoContacto = $db->select($SqlTipoContacto);
                    if(count((array) $TipoContacto) > 0){
                        $MejorGestionTexto = $TipoContacto[0]["Nombre"];
                        $MejorGestionFecha = $MejorGestion[0]["fechahora"];
                        $MejorGestionN1 = isset($MejorGestion[0]["n1"]) ? $MejorGestion[0]["n1"] : "";
                        $MejorGestionN2 = isset($MejorGestion[0]["n2"]) ? $MejorGestion[0]["n2"] : "";
                        $MejorGestionN3 = isset($MejorGestion[0]["n3"]) ? $MejorGestion[0]["n3"] : "";
                        $MejorGestionFechaAgendamiento = isset($MejorGestion[0]["fecha_agendamiento"]) ? $MejorGestion[0]["fecha_agendamiento"] : "";
                        $MejorGestionFechaCompromiso = isset($MejorGestion[0]["fecha_compromiso"]) ? $MejorGestion[0]["fecha_compromiso"] : "";
                    }
                }

                $SqlUltimaGestion = "SELECT * FROM Ultima_Gestion_Historica WHERE Rut = '".$Rut."' ORDER BY fechahora DESC LIMIT 1";
                $UltimaGestion = $db->select($SqlUltimaGestion);
                $UltimaGestionTexto = "";
                $UltimaGestionFecha = "";
                $UltimaGestionObservacion = "";
                $UltimaGestionUsuario = "";
                $UltimaGestionN1 = "";
                $UltimaGestionN2 = "";
                $UltimaGestionN3 = "";
                $UltimaGestionFechaAgendamiento = "";
                $UltimaGestionFechaCompromiso = "";
                if(count((array) $UltimaGestion) > 0){
                    $SqlTipoContacto = "SELECT * FROM Tipo_Contacto WHERE Id_TipoContacto = '".$UltimaGestion[0]["Id_TipoGestion"]."'";
                    $TipoContacto = $db->select($SqlTipoContacto);
                    $UltimaGestionObservacion = "";
                    if(count((array) $TipoContacto) > 0){
                        $UltimaGestionTexto = $TipoContacto[0]["Nombre"];
                        $UltimaGestionFecha = $UltimaGestion[0]["fecha_gestion"];
                        $UltimaGestionObservacion = $UltimaGestion[0]["observacion"];
                        $UltimaGestionUsuario = $UltimaGestion[0]["nombre_ejecutivo"];
                        $UltimaGestionN1 = isset($UltimaGestion[0]["n1"]) ? $UltimaGestion[0]["n1"] : "";
                        $UltimaGestionN2 = isset($UltimaGestion[0]["n2"]) ? $UltimaGestion[0]["n2"] : "";
                        $UltimaGestionN3 = isset($UltimaGestion[0]["n3"]) ? $UltimaGestion[0]["n3"] : "";
                        $UltimaGestionFechaAgendamiento = isset($UltimaGestion[0]["fecha_agendamiento"]) ? $UltimaGestion[0]["fecha_agendamiento"] : "";
                        $UltimaGestionFechaCompromiso = isset($UltimaGestion[0]["fecha_compromiso"]) ? $UltimaGestion[0]["fecha_compromiso"] : "";
                    }
                }

                $SqlUltimoCompromiso = "SELECT * FROM Ultimo_Compromiso WHERE Rut = '".$Rut."'";
                $UltimoCompromiso = $db->select($SqlUltimoCompromiso);
                $UltimoCompromisoTexto = "";
                $UltimoCompromisoFecha = "";
                $UltimoCompromisoObservacion = "";
                if(count((array) $UltimoCompromiso) > 0){
                    $SqlTipoContacto = "SELECT * FROM Tipo_Contacto WHERE Id_TipoContacto = '".$UltimoCompromiso[0]["Id_TipoGestion"]."'";
                    $TipoContacto = $db->select($SqlTipoContacto);
                    if(count((array) $TipoContacto) > 0){
                        $UltimoCompromisoTexto = $TipoContacto[0]["Nombre"];
                        $UltimoCompromisoFecha = $UltimoCompromiso[0]["fec_compromiso"];
                        $UltimoCompromisoObservacion = $UltimoCompromiso[0]["observacion"];	
                    }
                }

                $Tareas = new Tareas();
                $FechaPeriodo = $Tareas->getFechasPeriodosCargas();

                $SqlCantidadGestiones = "SELECT COUNT(*) AS Cantidad FROM gestion_ult_trimestre WHERE rut_cliente = '".$Rut."' AND fecha_gestion BETWEEN '".$FechaPeriodo["Desde"]."' AND '".$FechaPeriodo["Hasta"]."' AND FIND_IN_SET(cedente,(SELECT Lista_Vicidial FROM mandante_cedente WHERE Id_Cedente = '".$Cedente."'))";
                $CantidadGestiones = $db->select($SqlCantidadGestiones);
                if(count((array) $CantidadGestiones) > 0){
                    $CantidadGestiones = $CantidadGestiones[0]["Cantidad"];
                }

                $Col = 0;
                foreach((array) $ColumnasAsignacion as $ColumnaAsignacion){
                    $Operacion = $ColumnaAsignacion["Operacion"];
                    $Tabla = $ColumnaAsignacion["Tabla"];
                    $Campo = $ColumnaAsignacion["Campo"];
                    $Campo = $Operacion != "" ? $Operacion."(".$Campo.")" : $Campo;
                    $TipoCampo = $ColumnaAsignacion["Tipo_Campo"];
                    $Value = "";
                    switch($TipoCampo){
                        case '1':
                            switch($Tabla){
                                case "Deuda":
                                    $Sql = "SELECT ".$Campo." AS Val FROM ".$Tabla." WHERE Rut = '".$Rut."' AND Id_Cedente = '".$Cedente."' LIMIT 1";
                                break;
                                default:
                                    $Sql = "SELECT ".$Campo." AS Val FROM ".$Tabla." WHERE Rut = '".$Rut."' LIMIT 1";
                                break;
                            }
                            $Vals = $db->select($Sql);
                            foreach((array) $Vals as $Val){
                                $Value = $Val["Val"];
                            }
                        break;
                        case '2':
                            switch($Campo){
                                /*
                                    INICIO VARIABLES FONOS
                                */
                                case 'fono_especial':
                                    $Value = $FonoEspecial;
                                break;
                                case 'gestion_fono_especial':
                                    $Value = $GestionEspecial;
                                break;
                                case 'color_fono_especial':
                                    $Value = $ColorEspecial;
                                break;
                                case 'fono_2':
                                    $Value = $Fono2;
                                break;
                                case 'gestion_fono_2':
                                    $Value = $Gestion2;
                                break;
                                case 'color_fono_2':
                                    $Value = $ColorFono2;
                                break;
                                case 'fono_3':
                                    $Value = $Fono3;
                                break;
                                case 'gestion_fono_3':
                                    $Value = $Gestion3;
                                break;
                                case 'color_fono_3':
                                    $Value = $ColorFono3;
                                break;
                                /*
                                    FIN VARIABLES FONOS
                                */

                                /*
                                    INICIO VARIABLES GESTION
                                */
                                case 'mejor_gestion_texto':
                                    $Value = $MejorGestionTexto;
                                break;
                                case 'mejor_gestion_fecha':
                                    $Value = $MejorGestionFecha;
                                break;
                                case 'mejor_gestion_n1':
                                    $Value = $MejorGestionN1;
                                break;
                                case 'mejor_gestion_n2':
                                    $Value = $MejorGestionN2;
                                break;
                                case 'mejor_gestion_n3':
                                    $Value = $MejorGestionN3;
                                break;
                                case 'mejor_gestion_fecha_agendamiento':
                                    $Value = $MejorGestionFechaAgendamiento;
                                break;
                                case 'mejor_gestion_fecha_compromiso':
                                    $Value = $MejorGestionFechaCompromiso;
                                break;
                                case 'ultima_gestion_texto':
                                    $Value = $UltimaGestionTexto;
                                break;
                                case 'ultima_gestion_fecha':
                                    $Value = $UltimaGestionFecha;
                                break;
                                case 'ultima_gestion_observacion':
                                    $Value = $UltimaGestionObservacion;
                                break;
                                case 'ultima_gestion_usuario':
                                    $Value = $UltimaGestionUsuario;
                                break;
                                case 'ultima_gestion_n1':
                                    $Value = $UltimaGestionN1;
                                break;
                                case 'ultima_gestion_n2':
                                    $Value = $UltimaGestionN2;
                                break;
                                case 'ultima_gestion_n3':
                                    $Value = $UltimaGestionN3;
                                break;
                                case 'ultima_gestion_fecha_agendamiento':
                                    $Value = $UltimaGestionFechaAgendamiento;
                                break;
                                case 'ultima_gestion_fecha_compromiso':
                                    $Value = $UltimaGestionFechaCompromiso;
                                break;
                                case 'ultimo_compromiso_texto':
                                    $Value = $UltimoCompromisoTexto;
                                break;
                                case 'ultimo_compromiso_fecha':
                                    $Value = $UltimoCompromisoFecha;
                                break;
                                case 'ultimo_compromiso_observacion':
                                    $Value = $UltimoCompromisoObservacion;
                                break;
                                case 'cantidad_gestiones':
                                    $Value = $CantidadGestiones;
                                break;
                                /*
                                    FIN VARIABLES GESTION
                                */
                            }
                        break;
                    }
                    $Value = utf8_encode($Value);
                    $Value = str_replace(";","",$Value);
                    $Value = str_replace("\n","",$Value);
                    $Value = str_replace("\r","",$Value);
                    $Rows .= $Value.";";
                    $Col++;
                }
                $Cont++;
            }
            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="'.$fileName.'.csv"');
            header('Cache-Control: max-age=0');
        }
		return $Rows;
    }

    public function DownloadDetalleEstrategia($IdCola)
    {
        $db = new DB();
		$query = "SELECT e.nombre, q.cola, e.Id_Cedente, q.query, q.Prioridad_fono, q.color FROM SIS_Querys_Estrategias q INNER JOIN SIS_Estrategias e ON q.id_estrategia = e.id WHERE q.id = '".$IdCola."'";
        $Estrategia = $db->select($query);

        if($Estrategia){
            $Estrategia = $Estrategia[0];
            $Nombre = $Estrategia['nombre'];
            $Cola = $Estrategia['cola'];
            $Cedente = $Estrategia['Id_Cedente'];
            $Prioridad_fono = $Estrategia['Prioridad_fono'];
            $color = $Estrategia['color'];
            $fileName = "Detalle estrategia ".$Nombre;
            $Campana = date('Ymd').'_'.$Cedente.'_'.$Nombre;
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()
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
            $objPHPExcel->getActiveSheet()->setTitle('Detalle de la estrategia');

            $style = ['alignment' =>
                [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ]
            ];

            $columnLetter = PHPExcel_Cell::stringFromColumnIndex(0);

            $objPHPExcel->setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow(0,1,"Campaña");
            $objPHPExcel->setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow(1,1,"Rut");
            $objPHPExcel->setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow(2,1,"Nombre_Completo");
            $objPHPExcel->setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow(3,1,"Teléfono");
            $objPHPExcel->setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow(4,1,"Prioridad");
            $objPHPExcel->setActiveSheetIndex($NextSheet)
                        ->setCellValueByColumnAndRow(5,1,"Tipo_Contacto_Fono");

            $row = 2;
            $CedenteClass = new Cedente();
            $CedenteArray = $CedenteClass->mostrarCedente($Cedente);
            $idPais = $CedenteArray[0]["id_pais"];

            $FonosMaxLengthQuery = "";
            switch($idPais){
                case "1":
                $FonosMaxLengthQuery = " AND LENGTH(f.formato_subtel) = 9 ";
                break;
                default:
                $FonosMaxLengthQuery = "";
                break;
            }

            $QueryRut = $Estrategia['query'];
            if($color && $color != 'undefined'){
                $QueryColor = " AND f.color IN(".$color.")";
            }else{
                $QueryColor = "";
            }
            if($Prioridad_fono && $Prioridad_fono != 'undefined'){
                $QueryPrioridad = " AND f.Prioridad_Fono IN (".$Prioridad_fono.")";
            }else{
                $QueryPrioridad = "";
            }
            /*$query = "SELECT
                            p.Rut,
                            p.Nombre_Completo,
                            f.formato_subtel AS Telefono,
                            f.Prioridad_Fono AS Prioridad,
                            tc.Nombre AS Tipo_Contacto_Fono 
                        FROM
                            Persona p
                            INNER JOIN fono_cob f ON p.Rut = f.Rut
                            INNER JOIN SIS_Categoria_Fonos scf ON f.color = scf.color
                            INNER JOIN Tipo_Contacto tc ON scf.tipo_contacto = tc.Id_TipoContacto 
                            INNER JOIN (".$QueryRut.") r on r.Rut = p.Rut
                        WHERE
                            f.vigente = '1'
                            AND LENGTH(f.formato_subtel) = 9";*/
            $query = "SELECT
                            p.Rut,
                            p.Nombre_Completo,
                            f.formato_subtel AS Telefono,
                            f.Prioridad_Fono AS Prioridad,
                            tc.Nombre AS Tipo_Contacto_Fono 
                        FROM
                            Persona p
                            LEFT JOIN fono_cob f ON p.Rut = f.Rut AND f.vigente = '1' ".$FonosMaxLengthQuery." ".$QueryPrioridad."
                            LEFT JOIN SIS_Categoria_Fonos scf ON f.color = scf.color ".$QueryColor."
                            LEFT JOIN Tipo_Contacto tc ON scf.tipo_contacto = tc.Id_TipoContacto 
                            INNER JOIN (".$QueryRut.") r on r.Rut = p.Rut
                        WHERE
                            p.Rut != 0 AND f.formato_subtel IS NOT NULL";
            $Datos = $db->select($query);
            foreach((array) $Datos as $Dato){
                $col = 0;
                $objPHPExcel->setActiveSheetIndex($NextSheet)->setCellValueByColumnAndRow($col,$row,$Campana);
                $objPHPExcel->setActiveSheetIndex($NextSheet)->setCellValueByColumnAndRow($col+1,$row,$Dato['Rut']);
                $objPHPExcel->setActiveSheetIndex($NextSheet)->setCellValueByColumnAndRow($col+2,$row,$Dato["Nombre_Completo"]);
                $objPHPExcel->setActiveSheetIndex($NextSheet)->setCellValueByColumnAndRow($col+3,$row,$Dato["Telefono"]);
                $objPHPExcel->setActiveSheetIndex($NextSheet)->setCellValueByColumnAndRow($col+4,$row,$Dato["Prioridad"]);
                $objPHPExcel->setActiveSheetIndex($NextSheet)->setCellValueByColumnAndRow($col+5,$row,$Dato["Tipo_Contacto_Fono"]);
                $row++;
            }
        }

        $objPHPExcel->setActiveSheetIndex(0);
        foreach(range('A','F') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$fileName.'".xlsx');
        header('Cache-Control: max-age=0');
        $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
        $objWriter->save('php://output');
	}
}
?>