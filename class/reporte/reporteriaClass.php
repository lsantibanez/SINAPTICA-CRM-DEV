<?php
class Reporteria
{
    public $Cedentes = '';
    
    public function TipoBusqueda($Tipo)
	{
        $db = new DB();
        if($Tipo==1){
            $mandantes = $db->select("SELECT nombre, id FROM mandante");

            echo '<label for="sel1">Seleccione Mandante</label>';
            echo '<select class="selectpicker" id="Mandante"  data-live-search="true" data-width="100%">';
            echo '<option value="0">Seleccione</option>';
            
            foreach($mandantes as $mandante){
                echo "<option value='" . $mandante["id"] . "'>" . utf8_encode($mandante["nombre"]) . "</option>";
            }
            echo "</select>";
        }
        else if($Tipo==2){
        }
    }

    public function Cartera($Mandante)
	{
        $db = new DB();
        echo '<label for="sel1">Seleccione Cartera</label>';
        echo '<select class="selectpicker" id="Cartera"  data-live-search="true" data-width="100%">';
            echo '<option value="0">Seleccione</option>';
            echo '<option value="-1">Todas</option>';

        $sqlCedentes = "SELECT 
                                c.Id_Cedente AS id, c.Nombre_Cedente AS nombre 
                            FROM 
                                Cedente AS c
                                INNER JOIN mandante_cedente AS mc ON (c.Id_Cedente = mc.Id_Cedente)
                            WHERE
                                mc.Id_Mandante = '" . $Mandante . "' 
                                AND activo = 1";

        $cedentes = $db->select($sqlCedentes);

        foreach($cedentes as $cedente){
            echo "<option value='" . $cedente["id"] . "'>" . utf8_encode($cedente["nombre"]) . "</option>";
        }
        echo "</select>";
    }

    public function Periodo($Cartera, $Mandante){
        $db = new DB();
        if($Cartera==-1){
            $periodos = $db->select("SELECT 
                                            descripcion, Mandante 
                                        FROM 
                                            Periodo_Mandante 
                                        WHERE 
                                            Mandante = $Mandante ORDER BY id DESC");
            
            echo '<label for="sel1">Seleccione Periodo</label>';
            echo '<select class="selectpicker" id="Periodo"  data-live-search="true" data-width="100%">';
                echo '<option value="0">Seleccione</option>';
    
            foreach($periodos as $periodo){
                echo "<option value='" . $periodo["Mandante"] . "'>" . utf8_encode($periodo["descripcion"]) . "</option>";
            }
            echo "</select>";
        }else{
            $carteras = $db->select("SELECT descripcion, Cedente FROM Periodo_Cedente WHERE Cedente = $Cartera ORDER BY id DESC");

            echo '<label for="sel1">Seleccione Periodo</label>';
            echo '<select class="selectpicker" id="Periodo"  data-live-search="true" data-width="100%">';
                echo '<option value="0">Seleccione</option>';

            foreach($carteras as $cartera){
                echo "<option value='" . $cartera["Cedente"] . "'>" . utf8_encode($cartera["descripcion"]) . "</option>";
            }
            echo "</select>";
        }
    }

    public function VerEjecutivo($Cedente){
        $db = new DB();
        if($Cedente == 1){
            $Cedentes='10,11,12,13,15,14,31,32';
        }
        elseif($Cedente == 2){
            $Cedentes='4,5,6,7,8,9';
        }

        $ejecutivos = $db->select("SELECT 
                                        nombre_ejecutivo 
                                    FROM 
                                        gestion_ult_trimestre 
                                    WHERE cedente IN ($Cedentes) GROUP BY nombre_ejecutivo");
        echo '<div class="col-sm-2">';
            echo '<div class="form-group">';
                echo '<label for="sel1">Seleccione Cartera</label>';
                echo '<select class="selectpicker" id="Ejecutivo"  data-live-search="true" data-width="100%">';
                    echo '<option value="0">Seleccione</option>';
                foreach($ejecutivos as $ejecutivo){
                    echo "<option value='" . $ejecutivo["nombre_ejecutivo"] . "'>" . $ejecutivo["nombre_ejecutivo"] . "</option>";
                }
                echo "</select>";
            echo "</div>";
        echo "</div>";
    }

    public function MostrarGestiones($Tipo,$Periodo,$Mandante,$Cartera){
        $db = new DB();
        $ListaTotal = '';
        if($Cartera == -1){
            $listas = $db->select("SELECT Lista_Vicidial FROM mandante_cedente WHERE Id_Mandante=$Mandante and activo = 1");
            
            foreach($listas as $lista){
                $ListaTotal = $lista["Lista_Vicidial"] . "," . $ListaTotal;
            }

            $ListaTotal = substr($ListaTotal, 0, -1);

            $periodos = $db->select("SELECT Fecha_Inicio,Fecha_Termino FROM Periodo_Mandante WHERE Mandante = $Mandante");

            foreach($periodos as $periodo){
                $FechaInicio = $periodo["Fecha_Inicio"];
                $FechaTermino2 = $periodo["Fecha_Termino"];

                $FechaTermino = '';
                if($FechaTermino2=='0000-00-00'){
                    $FechaTermino = date('Y-m-d');
                }
                else{
                    $FechaTermino = $FechaTermino2;
                }
            }
        }else{
            $listas = $db->select("SELECT Lista_Vicidial FROM mandante_cedente WHERE Id_Cedente=$Cartera and activo = 1");

            foreach($listas as $lista){
                $ListaTotal = $lista["Lista_Vicidial"] . "," . $ListaTotal;
            }

            $ListaTotal = substr($ListaTotal, 0, -1);

            $periodos = $db->select("SELECT Fecha_Inicio,Fecha_Termino FROM Periodo_Cedente WHERE Cedente = $Cartera");

            foreach($periodos as $periodo){
                $FechaInicio = $periodo["Fecha_Inicio"];
                $FechaTermino2 = $periodo["Fecha_Termino"];

                $FechaTermino = '';
                if($FechaTermino2=='0000-00-00'){
                    $FechaTermino = date('Y-m-d');
                }
                else{
                    $FechaTermino = $FechaTermino2;
                }
            }
        }
        
        $Cedentes = $ListaTotal;
    
        echo '<table id="TablaScroll" class="table table-striped table-bordered" cellspacing="0" width="100%">';
            echo '<thead>';
                echo '<tr>';
                    echo "<th class='bg-primary'>Total Gestiones Call Día</th>";
                    $Encabezado = 1;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr></thead><tbody>';

                echo "<tr id=''>";
                    echo "<td >Contactados</td>";
                    $Encabezado = 2;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';

                echo "<tr id=''>";
                    echo "<td >No Contactados</td>";
                    $Encabezado = 3;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';

                echo "<tr id=''>";
                    echo "<td >Total General</td>";
                    $Encabezado = 4;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';

                echo "<tr id=''>";
                    echo "<td >N° Ejecutivos</td>";
                    $Encabezado = 5;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';
        echo '</tbody></table>';
        echo "<br>";

        echo '<table id="TablaScroll2" class="table table-striped table-bordered" cellspacing="0" width="100%">';
            echo '<thead>';
                echo '<tr>';
                    echo "<th class='bg-primary'>Total Gestiones Call Acumulado</th>";
                    $Encabezado = 6;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr></thead><tbody>';

                echo "<tr id=''>";
                    echo "<td >Contactados</td>";
                    $Encabezado = 7;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';

                echo "<tr id=''>";
                    echo "<td >No Contactados</td>";
                    $Encabezado = 8;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';

                echo "<tr id=''>";
                    echo "<td >Total General</td>";
                    $Encabezado = 9;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';
        echo '</tbody></table>';
        echo "<br>";


        echo '<table id="TablaScroll3" class="table table-striped table-bordered" cellspacing="0" width="100%">';
            echo '<thead>';
                echo '<tr>';
                    echo "<th class='bg-warning'>Total Gestiones Rut Unicos Cartera/th>";
                    $Encabezado = 10;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr></thead><tbody>';

                echo "<tr id=''>";
                    echo "<td >Contactados</td>";
                    $Encabezado = 11;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';

                echo "<tr id=''>";
                    echo "<td >No Contactados</td>";
                    $Encabezado = 12;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';

                echo "<tr id=''>";
                    echo "<td >Total General</td>";
                    $Encabezado = 13;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';

                echo "<tr id=''>";
                    echo "<td >Contactabilidad</td>";
                    $Encabezado = 14;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';
        echo '</tbody></table>';
        echo "<br>";


        echo '<table id="TablaScroll4" class="table table-striped table-bordered" cellspacing="0" width="100%">';
            echo '<thead>';
                echo '<tr>';
                    echo "<th class='bg-warning'>Total Gestiones Acumuladas Rut Unicos</th>";
                    $Encabezado = 15;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr></thead><tbody>';

                echo "<tr id=''>";
                    echo "<td >Contactados</td>";
                    $Encabezado = 16;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';

                echo "<tr id=''>";
                    echo "<td >No Contactados</td>";
                    $Encabezado = 17;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';

                echo "<tr id=''>";
                    echo "<td >Total General</td>";
                    $Encabezado = 18;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';
        echo '</tbody></table>';
        echo "<br>";

        //Contactabilida Real
        echo '<table id="TablaScroll5" class="table table-striped table-bordered" cellspacing="0" width="100%">';
            echo '<thead>';
                echo '<tr>';
                    echo "<th class='bg-danger'>Contactabilidad Real Periodo</th>";
                    $Encabezado = 19;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr></thead><tbody>';

                echo "<tr id=''>";
                    echo "<td >Asignacion</td>";
                    $Encabezado = 20;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';

                echo "<tr id=''>";
                    echo "<td >Contactabilidad Real</td>";
                    $Encabezado = 21;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';

                echo "<tr id=''>";
        if($Cartera==-1){
            $metas = $db->select("SELECT Meta FROM mandante_cedente WHERE Id_Mandante=$Periodo and activo = 1");

            $x=0;
            $MetaTotal = 0;
            foreach($metas as $meta){
                $MetaTotal = $meta["Meta"] + $MetaTotal;
                $x++;
            }
            $Promedio = round($MetaTotal/$x);
        }else{
            $metas = $db->select("SELECT Meta FROM mandante_cedente WHERE Id_Cedente=$Periodo and activo = 1");
            $x=0;
            $MetaTotal = 0;
            foreach($metas as $meta){
                $MetaTotal = $meta["Meta"] + $MetaTotal;
                $x++;
            }
            $Promedio = round($MetaTotal/$x);
        }
        
                    echo "<td>Cumplimiento sobre Meta :<b> $Promedio % </b></td>";
                    $Encabezado = 22;
                    self::Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante);
                echo '</tr>';
        echo '</tbody></table>';
        echo "<br>";
    }

    public function Funcion($FechaInicio,$FechaTermino,$Encabezado,$Cedentes,$Periodo,$Cartera,$Mandante){
        $db = new DB();
        $this->FechaInicio  = $FechaInicio;
        $this->FechaTermino = $FechaTermino;
        $this->Encabezado   = $Encabezado;
        $this->Cedentes     = $Cedentes;
       if($this->Encabezado == 22){
            $data = array();
        }
        else if($this->Encabezado == 11){
            $data2 = array();
        }
        else if($this->Encabezado == 14){
            $data3 = array();
        }
        else if($this->Encabezado == 16){
            $data4 = array();
        }
        else if($this->Encabezado == 18){
            $data5 = array();
        }

        $Query = '';
        $Fecha = '';
        $Cantidad = 0;
        $Fecha_Ultima = '';

        $datetime1 = date_create($this->FechaInicio);
        $datetime2 = date_create($this->FechaTermino);
        $interval = date_diff($datetime1, $datetime2);
        $days = $interval->format('%a');
        $i=0;    

        $DiaArray = explode('-',$this->FechaInicio);
        $Dia = $DiaArray[2];
        $Mes = $DiaArray[1];
        $Ano = $DiaArray[0];

        while($i<=$days){
            switch($Mes){
                case "1" : $MesNombre = "Ene"; break;
                case "2" : $MesNombre = "Feb"; break;
                case "3" : $MesNombre = "Mar"; break;
                case "4" : $MesNombre = "Abr"; break;
                case "5" : $MesNombre = "May"; break;
                case "6" : $MesNombre = "Jun"; break;
                case "7" : $MesNombre = "Jul"; break;
                case "8" : $MesNombre = "Ago"; break;
                case "9" : $MesNombre = "Sep"; break;
                case "10" : $MesNombre = "Oct"; break;
                case "11" : $MesNombre = "Nov"; break;
                case "12" : $MesNombre = "Dic"; break;
            }

            $Fecha = $Ano."-".$Mes."-".$Dia;
            $fecha = new DateTime($Fecha);
            $fecha->modify('last day of this month');
            $Ultimo = $fecha->format('d');
            if($Dia==$Ultimo && $Mes==12){
                $Fecha = $Ano."-".$Mes."-".$Dia;
                $Mes=1;
                $Ano = $Ano+1;
                $Dia=0;
            }

            else if($Dia==$Ultimo){
                $Fecha = $Ano."-".$Mes."-".$Dia;
                $Fecha_Ultima = $Ultimo;
                $Mes=$Mes+1;
                $Dia=0;
            }
            else{
                $Fecha = $Ano."-".$Mes."-".$Dia;
            }
            switch($this->Encabezado){
                
                case 1 : 
                    if($Dia==""){
                        $Dia == $Fecha_Ultima;
                    }
                    else if(strlen($Dia)<2){
                        $Dia = "0".$Dia;
                    }else{}
                    if($Dia == 0){
                        echo "<th class='bg-primary'><center>".$MesNombre."-".$Ultimo."</center></th>";

                    }else{
                        echo "<th class='bg-primary'><center>".$MesNombre."-".$Dia."</center></th>";

                    }
                break;

                case 2 :
                    $Query = $db->select("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)");
                    $Cantidad = count($Query);
                    echo "<td ><center>".$Cantidad."</center></td>";
                break;

                case 3 :
                    $Query = $db->select("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion  NOT IN (1,2,5)");
                    $Cantidad = count($Query);
                    echo "<td ><center>".$Cantidad."</center></td>";
                break;

                case 4 :
                    $Query = $db->select("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' ");
                    $Cantidad = count($Query);
                    echo "<td ><center>".$Cantidad."</center></td>";
                break;

                case 5 :
                    $Query = $db->select("SELECT rut_cliente FROM gestion_ult_trimestre WHERE  fecha_gestion = '$Fecha' AND cedente IN($this->Cedentes)  AND NOT nombre_ejecutivo='VDAD' GROUP BY nombre_ejecutivo  ");
                    $Cantidad = count($Query);
                    echo "<td ><center>".$Cantidad."</center></td>";
                break;
                
                case 6 : 
                    if($Dia==""){
                        $Dia == $Fecha_Ultima;
                    }
                    else if(strlen($Dia)<2){
                        $Dia = "0".$Dia;
                    }else{}
                    if($Dia == 0){
                        echo "<th class='bg-primary'><center>".$MesNombre."-".$Ultimo."</center></th>";

                    }else{
                        echo "<th class='bg-primary'><center>".$MesNombre."-".$Dia."</center></th>";

                    }
                break;

                case 7 :
                    $Query = $db->select("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)");
                    $Cantidad = count($Query);
                    $CantidadTotal = $Cantidad+$CantidadTotal;
                    echo "<td ><center>".$CantidadTotal."</center></td>";
                break;

                case 8 :
                    $Query = $db->select("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion NOT IN (1,2,5)");
                    $Cantidad = count($Query);
                    $CantidadTotal = $Cantidad+$CantidadTotal;
                    echo "<td ><center>".$CantidadTotal."</center></td>";
                break;

                case 9 :
                    $Query = $db->select("SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' ");
                    $Cantidad = count($Query);
                    $CantidadTotal = $Cantidad+$CantidadTotal;
                    echo "<td ><center>".$CantidadTotal."</center></td>";
                break;

                case 10 : 
                    if($Dia==""){
                        $Dia == $Fecha_Ultima;
                    }
                    else if(strlen($Dia)<2){
                        $Dia = "0".$Dia;
                    }else{}
                    if($Dia == 0){
                        echo "<th class='bg-warning'><center>".$MesNombre."-".$Ultimo."</center></th>";

                    }else{
                        echo "<th class='bg-warning'><center>".$MesNombre."-".$Dia."</center></th>";

                    }
                break;

                case 11 :
                    
                    $Query = $db->select("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante ");
                    $Cantidad = count($Query);
                    array_push($data2, array('y' => $Dia,'a' => $Cantidad));
                    echo "<td ><center>".$Cantidad."</center></td>";
                break;

                case 12 :
                    $Query = $db->select("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion NOT IN (1,2,5)) AND NOT Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante");
                    $Cantidad = count($Query);
                    echo "<td ><center>".$Cantidad."</center></td>";
                break;

                case 13 :
                    $Query = $db->select("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha') AND Mandante = $Mandante ");
                    $Cantidad1 = count($Query);
                    echo "<td ><center>".$Cantidad1."</center></td>";
                break;
                case 14 :
                    $Query1 = $db->select("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante");
                    $Cantidad1 = count($Query1);

                    $Query2 = $db->select("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha') AND Mandante = $Mandante ");
                    $Cantidad2 = count($Query2);

                    $Cantidad = round(($Cantidad1/$Cantidad2)*100);
                    array_push($data3, array('y' => $Dia,'a' => $Cantidad));
                    echo "<td ><center>".$Cantidad." % </center></td>";
                break;

                case 15 : 
                    if($Dia==""){
                        $Dia == $Fecha_Ultima;
                    }
                    else if(strlen($Dia)<2){
                        $Dia = "0".$Dia;
                    }else{}
                    if($Dia == 0){
                        echo "<th class='bg-warning'><center>".$MesNombre."-".$Ultimo."</center></th>";

                    }else{
                        echo "<th class='bg-warning'><center>".$MesNombre."-".$Dia."</center></th>";

                    }
                break;

                case 16 :
                    $Query = $db->select("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante ");
                    $Cantidad = count($Query);
                    $CantidadTotal = $Cantidad+$CantidadTotal;
                    array_push($data4, array('y' => $Dia,'a' => $CantidadTotal));
                    echo "<td ><center>".$CantidadTotal."</center></td>";
                break;

                case 17 :
                    $Query = $db->select("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion NOT IN (1,2,5)) AND NOT Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante");
                    $Cantidad = count($Query);
                    $CantidadTotal = $Cantidad+$CantidadTotal;
                    echo "<td ><center>".$CantidadTotal."</center></td>";
                break;

                case 18 :
                    $Query = $db->select("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha') AND Mandante = $Mandante ");
                    $Cantidad = count($Query);
                    $CantidadTotal = $Cantidad+$CantidadTotal;
                    array_push($data5, array('y' => $Dia,'a' => $CantidadTotal));
                    echo "<td ><center>".$CantidadTotal."</center></td>";
                break;

                case 19 : 
                    if($Dia==""){
                        $Dia == $Fecha_Ultima;
                    }
                    else if(strlen($Dia)<2){
                        $Dia = "0".$Dia;
                    }else{}
                    if($Dia == 0){
                        echo "<th class='bg-danger'><center>".$MesNombre."-".$Ultimo."</center></th>";

                    }else{
                        echo "<th class='bg-danger'><center>".$MesNombre."-".$Dia."</center></th>";

                    }
                break;

                case 20 : 
                    $Query = $db->select("SELECT Rut FROM Persona_Periodo WHERE  Id_Cedente = $Cartera ");
                    $Cantidad = count($Query);
                    if($Dia==""){
                        $Dia == $Fecha_Ultima;
                    }
                    else if(strlen($Dia)<2){
                        $Dia = "0".$Dia;
                    }else{}
                    if($Dia == 0){
                        echo "<th class='bg-danger'><center>".$Cantidad."</center></th>";

                    }else{
                        echo "<th class='bg-danger'><center>".$Cantidad."</center></th>";

                    }
                break;

                case 21:
                    $Query = $db->select("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante");
                    $Contactabilidad = count($Query);
                    $ContactabilidadTotal = $ContactabilidadTotal+$Contactabilidad;

                    $QueryAsignacion = $db->select("SELECT Rut FROM Persona_Periodo WHERE  Mandante = $Mandante ");
                    $CantidadAsignacion = count($QueryAsignacion);

                    $ContactabilidadFinal = round(($ContactabilidadTotal/$CantidadAsignacion)*100);
                    echo "<td ><center>".$ContactabilidadFinal." %</center></td>";
                break;

                case 22 :
                    if($Cartera==-1){
                        $Promedio = 0;
                        $QueryMeta = $db->select("SELECT Meta FROM mandante_cedente WHERE Id_Mandante=$Periodo and activo = 1");
                        $w=0;
                        $MetaTotal = 0;
                        foreach($QueryMeta as $meta){
                            $Meta = $meta["Meta"];
                            $MetaTotal = $Meta+$MetaTotal;
                            $w++;
                        }
                        $Promedio = round($MetaTotal/$w);

                        $Query = $db->select("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante ");
                        $Contactabilidad = count($Query);
                        $ContactabilidadTotal = $ContactabilidadTotal+$Contactabilidad;

                        $QueryAsignacion = $db->select("SELECT Rut FROM Persona_Periodo WHERE  Mandante = $Mandante ");
                        $CantidadAsignacion = count($QueryAsignacion);

                        $ContactabilidadFinal = round(($ContactabilidadTotal/$CantidadAsignacion)*100);

                        $ContactabilidadMeta = round(($ContactabilidadFinal/$Promedio)*100);
                        array_push($data, array('y' => $Dia,'a' => $ContactabilidadMeta));
                        if($ContactabilidadMeta<=5){
                        $BG = "bg-danger";
                        }
                        elseif($ContactabilidadMeta>5 && $ContactabilidadMeta<=10){
                            $BG = "bg-warning";
                        }
                        elseif($ContactabilidadMeta>10 && $ContactabilidadMeta<=20){
                            $BG = "bg-mint";
                        }
                        elseif($ContactabilidadMeta>20 && $ContactabilidadMeta<=100){
                            $BG = "bg-success";
                        }
                        echo "<td  class='$BG'><center>$ContactabilidadMeta %</center></td>";
                    }
                    else{
                        $Promedio = 0;
                        $QueryMeta = $db->select("SELECT Meta FROM mandante_cedente WHERE Id_Mandante=$Mandante and activo = 1");
                        $w=0;
                        $MetaTotal = 0;
                        foreach($QueryMeta as $meta){
                            $Meta = $meta["Meta"];
                            $MetaTotal = $Meta+$MetaTotal;
                            $w++;
                        }

                        $Promedio = round($MetaTotal/$w);

                        $Query = $db->select("SELECT Rut FROM Persona_Periodo WHERE Rut IN (SELECT rut_cliente FROM gestion_ult_trimestre WHERE cedente IN($this->Cedentes) and fecha_gestion = '$Fecha' AND Id_TipoGestion IN (1,2,5)) AND Mandante = $Mandante");
                        $Contactabilidad = count($Query);
                        $ContactabilidadTotal = $ContactabilidadTotal+$Contactabilidad;

                        $QueryAsignacion = $db->select("SELECT Rut FROM Persona_Periodo WHERE  Mandante = $Mandante ");
                        $CantidadAsignacion = count($QueryAsignacion);

                        $ContactabilidadFinal = round(($ContactabilidadTotal/$CantidadAsignacion)*100);

                        $ContactabilidadMeta = round(($ContactabilidadFinal/$Promedio)*100);
                        array_push($data, array('y' => $Dia,'a' => $ContactabilidadMeta));
                        if($ContactabilidadMeta<=5){
                        $BG = "bg-danger";
                        }
                        elseif($ContactabilidadMeta>5 && $ContactabilidadMeta<=10){
                            $BG = "bg-warning";
                        }
                        elseif($ContactabilidadMeta>10 && $ContactabilidadMeta<=20){
                            $BG = "bg-mint";
                        }
                        elseif($ContactabilidadMeta>20 && $ContactabilidadMeta<=100){
                            $BG = "bg-success";
                        }
                        echo "<td  class='$BG'><center>$ContactabilidadMeta %</center></td>";
                    }
                    
                    
                break;
            }
            $Dia++;
            $i++;
        }
        if($this->Encabezado==22){
            $json1 = json_encode($data);
            echo "<input type='hidden' id='json1' value='$json1'>";
        }
        else if($this->Encabezado==11){
            $json2 = json_encode($data2);
            echo "<input type='hidden' id='json2' value='$json2'>";
        }
        else if($this->Encabezado==14){
            $json3 = json_encode($data3);
            echo "<input type='hidden' id='json3' value='$json3'>";
        }
        else if($this->Encabezado==16){
            $json4 = json_encode($data4);
            echo "<input type='hidden' id='json4' value='$json4'>";
        }
        else if($this->Encabezado==18){
            $json5 = json_encode($data5);
            echo "<input type='hidden' id='json5' value='$json5'>";
        }
    }

    public function getEjecutivosLevelFour(){
        $db = new DB();
        $output = '';
        
        $ejecutivos = $db->select("SELECT id, nombre from Usuarios");
        
        foreach($ejecutivos as $ejecutivo){
            $output .= '<option value="'.$ejecutivo["id"].'">'.$ejecutivo["nombre"].'</option>';
        }

        return $output;
    }

    public function mostrarTabla($varMandante){
        $db = new DB();
        $dbDiscador = new DB('discador');
        $arrayCedente = array();
        $queryCedente = $db->select("SELECT Id_Cedente FROM mandante_cedente WHERE Id_Mandante = $varMandante");
        $contarMandante = count($queryCedente);
        foreach($queryCedente as $rowCedente){
            $varCedente = $rowCedente['Id_Cedente'];
            array_push($arrayCedente,$varCedente);

        }

        $varCedenteImplode = implode(',',$arrayCedente);
        $querySuma = $dbDiscador->select("SELECT * FROM reporteOnLine WHERE cartera IN($varCedenteImplode) AND activo = 1");
        $Cantidad = count($querySuma);

        if($contarMandante==0){
            echo 0;
        }else{
            echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="min-desktop"><center>Estado</center></th>';
            echo '<th class="min-desktop"><center>Cantidad</center></th>';        
            echo '</tr>';
            echo '</thead>';
            echo '<tfoot>';
            echo '<tr>';
            echo '<th><center><b>TOTAL</b></center></th>';
            echo '<th><center>'.$Cantidad.'</center></th>';
            echo '</tr>';
            echo '</tfoot>';
            echo '<tbody>';
            $queryAgentes = $dbDiscador->select("SELECT count(*) as Cantidad,estatus FROM reporteOnLine WHERE cartera IN($varCedenteImplode) AND activo = 1 GROUP BY estatus");
            foreach($queryAgentes as $rowAgente)
            {
                $varClass = '';
                switch($rowAgente['estatus']){
                    case "INCALL":
                        $varClass = "btn btn-success  linkAgente ti-headphone-alt";
                    break;
                    case "DISPONIBLE":
                        $varClass = "btn btn-danger  linkAgente ti-headphone";
                    break;
                    case "DEAD":
                        $varClass = "btn btn-dark  linkAgente ti-time";
                    break;
                    case "PAUSED":
                        $varClass = "btn btn-warning  linkAgente ti-control-pause";
                    break;

                }
                $status = $rowAgente['estatus'];
                echo "<tr id='$status'>";
                echo "<td><button class='$varClass'></button>  ".$status."</td>";
                echo "<td><center><a href='#' class='linkAgente'>".$rowAgente['Cantidad']."</a></center></td>";
                echo '</tr>';   
            }
            echo '</tbody>';
            echo '</table>';
        }            
    }

    public function mostrarTablaGrafico($varMandante){
        $db = new DB();
        $dbDiscador = new DB('discador');
        $arrayCedente = array();
        $arrayCantidad= array();
        
        $queryCedente = $db->select("SELECT Id_Cedente FROM mandante_cedente WHERE Id_Mandante = $varMandante");
        $contarMandante = count($queryCedente);
        foreach($queryCedente as $rowCedente){
            $varCedente = $rowCedente['Id_Cedente'];
            array_push($arrayCedente,$varCedente);

        }

        $varCedenteImplode = implode(',',$arrayCedente);
        $querySuma = $dbDiscador->select("SELECT * FROM reporteOnLine WHERE cartera IN($varCedenteImplode) AND activo = 1");
        $Cantidad = count($querySuma);

        if($Cantidad==0){
            echo 0;
        }else{
            
            $queryAgentes = $dbDiscador->select("SELECT count(*) as Cantidad,estatus FROM reporteOnLine WHERE cartera IN($varCedenteImplode) AND activo = 1 GROUP BY estatus");
            foreach($queryAgentes as $rowAgente){
                
                $cantidad = $rowAgente['Cantidad'];
                $status = $rowAgente['estatus'];
                $arrayCantidad[$status] = $cantidad;                 
            }
        }  
        
        echo $json1 = json_encode($arrayCantidad);
    }
    public function puestosTrabajo($varIdAgente,$varMandante){
        if($varIdAgente==''){
            echo 0;

        }else{
            $db = new DB();
            $dbDiscador = new DB('discador');
            $arrayCedente = array();
            $queryCedente = $db->select("SELECT Id_Cedente FROM mandante_cedente WHERE Id_Mandante = $varMandante");
            $contarMandante = count($queryCedente);
            foreach($queryCedente as $rowCedente){
                $varCedente = $rowCedente['Id_Cedente'];
                array_push($arrayCedente,$varCedente);
    
            }
    
            $varCedenteImplode = implode(',',$arrayCedente);

            echo '<table id="demo-dt-basic" class="table table-striped table-bordered" cellspacing="0" width="100%">';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="min-desktop"><center>Puesto</center></th>';
            echo '<th class="min-desktop"><center>Estado</center></th>';
            echo '<th class="min-desktop"><center>Agentes</center></th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $queryAgente = $dbDiscador->select("SELECT anexo,ejecutivo FROM reporteOnLine WHERE cartera IN ($varCedenteImplode) AND estatus = '$varIdAgente' AND activo = 1 ");
            foreach($queryAgente as $rowAgente){
                $varAnexo = $rowAgente['anexo'];
                $varEjecutivo = $rowAgente['ejecutivo'];
                echo '<tr>';
                echo "<td><center>".$varAnexo."</center></td>";
                echo "<td><center>".$varEjecutivo."</center></td>";
                echo "<td><center>lponce</center></td>";
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';

        }
        

                    
    }
    public function campanasTabla($varMandante){
        $DB = new DB();        
        $DB2 = new DB('discador');
        
        //Selecciona Mandante
        $arrayMandante = array();
        $arrayCedente = array();
        $arrayEstrategias = array();
        $arrayColas = array();
        $arrayGrupos = array();
        $arrayTipoMarcacion = array();
        $arrayCasos = array();
        $arrayCasosBarridos = array();

        $nombreMandante = '';
        $nombreCedente = '';
        $nombreEstrategias = '';

        $queryMandante = $DB->select("SELECT nombre FROM mandante WHERE id = $varMandante");
        $contarMandante = count($queryMandante);
        foreach($queryMandante as $rowMandante){
            $nombreMandante= $rowMandante['nombre'];
            array_push($arrayMandante,$nombreMandante);
        }

        //Selecciona Cedentes
        $queryCedente = $DB->select("SELECT Id_Cedente FROM mandante_cedente WHERE Id_Mandante = $varMandante");
        $contarMandante = count($queryCedente);
        foreach($queryCedente as $rowCedente){
            $varCedente = $rowCedente['Id_Cedente'];
            
    
            $queryNombreCedente = $DB->select("SELECT Nombre_Cedente FROM Cedente WHERE Id_Cedente = $varCedente");
            foreach($queryNombreCedente as $rowNombreCedente){
                $nombreCedente = $rowNombreCedente['Nombre_Cedente'];
                array_push($arrayCedente,$nombreCedente);                  
            }
            $queryEstrategias = $DB->select("SELECT id,nombre FROM SIS_Estrategias WHERE Id_Cedente = $varCedente");
            $existeEstrategia = count($queryEstrategias);
            if($existeEstrategia>0){
                foreach($queryEstrategias as $rowNombreEstrategias){
                    $idEstrategia = $rowNombreEstrategias['id'];
                    $nombreEstrategias= $rowNombreEstrategias['nombre'];
                    $queryColas = $DB->select("SELECT id_estrategia,cola,id FROM SIS_Querys_Estrategias WHERE id_estrategia = $idEstrategia AND terminal = 1");
                    foreach($queryColas as $rowColas){
            
                        $nombreColaCompleto = $rowColas['cola']."-".$nombreEstrategias."<br>";
                        $idCola = $rowColas['id'];
                        $preficoCola = "QR_".$varCedente."_".$idCola."_G";
                        $queryColaGrupo = $DB->select("SELECT * FROM Asterisk_Discador_Cola WHERE cola LIKE '%$preficoCola%'");
                        $existePredictivo = count($queryColaGrupo);
                        if($existePredictivo>0){
                            foreach($queryColaGrupo as $rowColaGrupo){
                                $nombreCola = $rowColaGrupo['Cola'];
                                $idDR = $rowColaGrupo['id'];

                                $nombreColaExplode = explode("_",$nombreCola);
                                $idGrupo = $nombreColaExplode[4];
                                $queryNombreGrupo = $DB->select("SELECT Nombre FROM grupos WHERE IdGrupo = $idGrupo");
                                foreach($queryNombreGrupo as $rowNombreGrupo){
                                    $nombreGrupo = $rowNombreGrupo['Nombre'];

                                    $queryQueue = $DB->select("SELECT Queue FROM Asterisk_All_Queues WHERE id_discador = $idDR");
                                    foreach($queryQueue as $rowQueue){
                                        $queue = $rowQueue['Queue'];
                                    }
                                    $prefijoDR = "DR_".$queue."_".$nombreCola; 

                                    $queryQueue = $DB2->select("SELECT * FROM $prefijoDR");
                                    $contarCasos = count($queryQueue);
    
                                    $queryQueueBarridos = $DB2->select("SELECT * FROM $prefijoDR WHERE llamado = 1");
                                    $contarCasosBarridos = count($queryQueueBarridos);

                                    array_push($arrayMandante,$nombreMandante); 
                                    array_push($arrayCedente,$nombreCedente);                  
                                    array_push($arrayColas,$nombreColaCompleto);  
                                    array_push($arrayEstrategias,$nombreEstrategias);
                                    array_push($arrayGrupos,$nombreGrupo);
                                    array_push($arrayTipoMarcacion,'Predictivo');
                                    array_push($arrayCasos,$contarCasos);
                                    array_push($arrayCasosBarridos,$contarCasosBarridos);
                                }
                            }

                        }
                        $queryColaGrupoTabla = $DB->select("SHOW  TABLES  LIKE '%$preficoCola%'"); 
                        foreach($queryColaGrupoTabla as $rowNombreGrupoTabla){
                            $nombreCola = $rowNombreGrupoTabla["Tables_in_foco (%$preficoCola%)"];
                            array_push($arrayMandante,$nombreMandante); 
                            array_push($arrayCedente,$nombreCedente);                  
                            array_push($arrayColas,$nombreColaCompleto);  
                            array_push($arrayEstrategias,$nombreEstrategias);
                            array_push($arrayGrupos,$nombreGrupo);
                            array_push($arrayTipoMarcacion,'Manual');
                            array_push($arrayCasos,'0');
                            array_push($arrayCasosBarridos,'0');
                        }   
                    }
                }
            }else{
                
            }    
        }
        $contarGrupos = count($arrayGrupos);
        echo '<table id="tablaCampana" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="min-desktop">Mandante</th>';
        echo '<th class="min-desktop"><center>Cartera</center></th>';
        echo '<th class="min-desktop"><center>Estrategia</center></th>';
        echo '<th class="min-desktop"><center>Colas</center></th>';
        echo '<th class="min-desktop"><center>Grupos</center></th>';
        echo '<th class="min-desktop"><center>Marcación</center></th>';
        echo '<th class="min-desktop"><center>Casos/Barridos</center></th>';        
        echo '<th class="min-desktop"><center>% Barridos</center></th>';
        
        echo '</tr>';
        $i = 0;
        $j=1;
        while($i<$contarGrupos){
            echo "<tr>";
            echo "<td><center>".$arrayMandante[$i]."</center></td>";                                    
            echo "<td><center>".$arrayCedente[$i]."</center></td>";                                     
            echo "<td><center>".$arrayEstrategias[$i]."</center></td>";                
            echo "<td><center>".$arrayColas[$i]."</center></td>"; 
            echo "<td><center>".$arrayGrupos[$i]."</center></td>";  
            echo "<td><center>".$arrayTipoMarcacion[$i]."</center></td>"; 
            echo "<td><center>".$arrayCasos[$i]."/".$arrayCasosBarridos[$i]."</center></td>";  
            
            $porcentajeBarrido = $arrayCasosBarridos[$i]/$arrayCasos[$i]*100;
            echo "<td><center>".$porcentajeBarrido." %</center></td>";      
            
            
            
            
            echo "</tr>";
            $i++;
            $j++;
        } 
        echo '</thead>';
        echo '<tbody>';
        echo '</tbody>';
        echo '</table>';   
    }

    public function selectMandante(){
        $DB = new DB();
        $queryMandante = $DB->select("SELECT nombre, id FROM mandante");
        echo '<label for="sel1">Seleccione Mandante</label>';
        
        echo '<select class="selectpicker" id="selectMandante"  data-live-search="true" data-width="100%">';
        echo '<option value="0">Seleccione</option>';
        foreach($queryMandante as $rowMandante){
            $nombreMandante = $rowMandante['nombre'];
            $idMandante = $rowMandante['id'];
            echo "<option value='$idMandante'>".utf8_encode($nombreMandante)."</option>";    
        }
        echo "</select>";
    }
}
?>