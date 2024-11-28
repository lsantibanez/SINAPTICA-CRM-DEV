<?php

class Bienvenida{

    public function getMeta($nivel,$idUsuario){
        $mysqli = new DB();
        $fechaIngreso  = "";
        $sqlFecha = $mysqli->select("SELECT fecha_ingreso FROM disal.`carterizacionFinal` WHERE 1 order by fecha_ingreso ASC LIMIT 1");
        if ($sqlFecha) {
            $fechaIngreso = mysqli_fetch_assoc($sqlFecha)['fecha_ingreso'];
        }
        /*
        foreach($sqlFecha as $row){
            $fechaIngreso = $row['fecha_ingreso'];
        }
        */
        if($nivel == 1){
            $queryMeta = $mysqli->select("SELECT sum(saldo) as saldoTotal , 
            cobrador ,tramo FROM disal.carterizacionFinal WHERE fecha_ingreso = '$fechaIngreso' GROUP BY cobrador");
            $totalDeuda = 0;
            $color = "";
            $sumCobrador = 0;
            $sumaRebajado = 0;
            $sumaPago = 0;
            echo "<table id='tabla_meta'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Cobrador</th>";
            echo "<th>Total Cartera</th>";
            echo "<th>Tramo</th>";
            echo "<th>Meta</th>";
            echo "<th>Rebajado (Cruce Aging)</th>";
            echo "<th>Target Rebajado</th>";
            echo "<th>% Gestionado</th>";           
            echo "<th>% Sin Gestión</th>";
            echo "<th>Pagos Carterización</th>";
            echo "<th>Pagos Totales</th>";
            echo "</tr>";
            echo "</thead>";
            
            foreach((array) $queryMeta as $row){
                $cobrador = $row['cobrador'];
                $meta = 0;
                $saldoRebaja = 0;
                $target = 0;
                $saldoTotal = $row['saldoTotal'];
                $totalDeuda = $saldoTotal + $totalDeuda;
                $colorT = "";
                
                $tramo = $row['tramo'];
                if($tramo == 'Tramo 2'){
                    $color = "E6EEC1";
                }elseif($tramo == 'Tramo 3'){
                    $color = "F9BC8F";
                }else{
                    $color = "B0FBA9";
                }
                
                $metaCobrador = 0;
                $metaCobradorFormat = 0;
                $queryCobra = $mysqli->select("SELECT meta FROM disal.meta WHERE cobrador = '$cobrador'");
                foreach((array) $queryCobra as $rowC){
                    $meta = $rowC['meta'];
                    $metaCobrador = round($saldoTotal*$meta/100);
                    $metaCobradorFormat = number_format($metaCobrador, 0, '', '.');
                }
              
                $queryRebaja = $mysqli->select("SELECT sum(saldo) as saldoRebaja FROM disal.carterizacionFinal 
                WHERE rebajado = 1 and cobrador = '$cobrador' AND fecha_ingreso = '$fechaIngreso'");
                foreach((array) $queryRebaja as $rowR){
                    $saldoRebaja = $rowR['saldoRebaja'];
                    $sumaRebajado = $saldoRebaja + $sumaRebajado;

                }
                
                $queryPago = $mysqli->select("SELECT sum(saldo) as saldoPago FROM disal.carterizacionFinal 
                WHERE pagado = 1 and cobrador = '$cobrador' AND fecha_ingreso = '$fechaIngreso'");
                foreach((array) $queryPago as $rowP){
                    $saldoPago = $rowP['saldoPago'];
                    $sumaPago = $saldoPago + $sumaPago;
                    $target =round($saldoPago/$metaCobrador*100);

                }    
                $saldoPagoTOTAL = 0;
                $queryPagoTotal2 = $mysqli->select("SELECT sum(saldo) as saldoPago FROM disal.carterizacionFinalRut
                WHERE pagado = 1 and cobrador = '$cobrador' AND fecha_ingreso = '$fechaIngreso'");
                foreach((array) $queryPagoTotal2 as $rowPT){
                    $saldoPagoTOTAL = $rowPT['saldoPago'];
                }    
    
    
                $sumCobrador = $metaCobrador+$sumCobrador;


                echo "<tr>";
                echo "<th id='$cobrador'><a href='detalleCobrador.php?cobrador=$cobrador'>".$row['cobrador']."</a></th>";
                echo "<th>$ ".number_format($row['saldoTotal'], 0, '', '.')."</th>";
                echo "<th>".$row['tramo']."</th>";
                echo "<th bgcolor='$color'>$ ".$metaCobradorFormat."</th>";
                echo "<th>$ ".number_format($saldoRebaja, 0, '', '.')."</th>";
                if($target>=90){
                    $colorT = "#13A3F3";
                }else{
                    $colorT = "#F35013";
                }

                
                $cGestionado = count($mysqli->select("SELECT * FROM disal.carterizacionFinal WHERE cobrador = '$cobrador' 
                and gestionado = 1 AND fecha_ingreso = '$fechaIngreso' GROUP by rut"));
                $caGestionar =  count($mysqli->select("SELECT * FROM disal.carterizacionFinal WHERE cobrador = '$cobrador' AND fecha_ingreso = '$fechaIngreso'
                GROUP by rut"));

                $pGestionado = round($cGestionado*100/$caGestionar);
                $noGestionado = 100-$pGestionado;
                
                echo "<th bgcolor='$colorT'><font color='#FFFFFF'><center>".$target."%</center></font></th>";
                echo "<th><center>".$pGestionado."%</center></font></th>";
                echo "<th><center><a href='#' class='accedeColas'>".$noGestionado."%</a></center></font></th>";
                echo "<th>$ ".number_format($saldoPago, 0, '', '.')."</th>";
                echo "<th>$ ".number_format($saldoPagoTOTAL, 0, '', '.')."</th>";




                echo "</tr>";
                
            }

            $queryPagoTotal = $mysqli->select("SELECT sum(saldo) as saldoPago FROM disal.carterizacionFinalRut
            WHERE pagado = 1 ");
            foreach($queryPagoTotal as $rowPT){
                $saldoPagoT = $rowPT['saldoPago'];
            }    

            echo "<tbody>";
            echo "</tbody>";
            echo "<tfoot>";
            echo "<tr>";
            echo "<th bgcolor='#9ED9F9'></th>";
            echo "<th bgcolor='#9ED9F9'>$ ".number_format($totalDeuda, 0, '', '.')."</th>";
            echo "<th bgcolor='#9ED9F9'></th>";
            echo "<th bgcolor='#9ED9F9'>$ ".number_format($sumCobrador, 0, '', '.')."</th>";
            echo "<th bgcolor='#9ED9F9'>$ ".number_format($sumaRebajado, 0, '', '.')."</th>";
            echo "<th bgcolor='#9ED9F9'></th>";
            echo "<th bgcolor='#9ED9F9'></th>";
            echo "<th bgcolor='#9ED9F9'></th>";
            echo "<th bgcolor='#9ED9F9'>$ ".number_format($sumaPago, 0, '', '.')."</th>";
            echo "<th bgcolor='#9ED9F9'>$ ".number_format($saldoPagoT, 0, '', '.')."</th>";


            echo "</tr>";
            echo "</tfoot>";
            echo "</table>";
        }elseif($nivel == 2){
            $cobrador = 'IBR';
            $queryMeta = $mysqli->select("SELECT sum(saldo) as saldoTotal , 
            cobrador ,tramo FROM disal.carterizacionFinal WHERE cobrador = '$cobrador'");
            $totalDeuda = 0;
            $color = "";
            echo "<table id='tabla_meta'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Cobrador</th>";
            echo "<th>Total Cartera</th>";
            echo "<th>Tramo</th>";
            echo "<th>Meta</th>";
            echo "<th>Recupero</th>";
            echo "<th>Target</th>";
            echo "<th>% Gestionado</th>";           
            echo "<th>% Sin Gestión</th>";
            echo "</tr>";
            echo "</thead>";
            foreach($queryMeta as $row){
                $cobrador = $row['cobrador'];
                $meta = 0;
                $saldoRebaja = 0;
                $target = 0;
                $colaSG = "";
                $saldoTotal = $row['saldoTotal'];
                $totalDeuda = $saldoTotal + $totalDeuda;
                $colorT = "";
                $tramo = $row['tramo'];
                if($tramo == 'Tramo 2'){
                    $color = "E6EEC1";
                }elseif($tramo == 'Tramo 3'){
                    $color = "F9BC8F";
                }else{
                    $color = "B0FBA9";
                }
                
                $metaCobrador = 0;
                $metaCobradorFormat = 0;
                $queryCobra = $mysqli->select("SELECT meta ,colaSG FROM disal.meta WHERE cobrador = '$cobrador'");
                foreach($queryCobra as $rowC){
                    $meta = $rowC['meta'];
                    $colaSG = $rowC['colaSG'];
                    $metaCobrador = round($saldoTotal*$meta/100);
                    $metaCobradorFormat = number_format($metaCobrador, 0, '', '.');
                }
                $queryRebaja = $mysqli->select("SELECT sum(saldo) as saldoRebaja FROM disal.carterizacionFinal 
                WHERE pagado = 1 and cobrador = '$cobrador'");
                foreach($queryRebaja as $rowR){
                    $saldoRebaja = $rowR['saldoRebaja'];
                    $target =round($saldoRebaja/$metaCobrador*100);
                }


                echo "<tr>";
                echo "<th>".$row['cobrador']."</th>";
                echo "<th>$ ".number_format($row['saldoTotal'], 0, '', '.')."</th>";
                echo "<th>".$row['tramo']."</th>";
                echo "<th bgcolor='$color'>$ ".$metaCobradorFormat."</th>";
                echo "<th>$ ".number_format($saldoRebaja, 0, '', '.')."</th>";
                if($target>=90){
                    $colorT = "#13A3F3";
                }else{
                    $colorT = "#F35013";
                }

                $cGestionado = count($mysqli->select("SELECT * FROM disal.carterizacionFinal WHERE cobrador = '$cobrador' 
                and gestionado = 1 AND fecha_ingreso = '$fechaIngreso' GROUP by rut"));
                $caGestionar =  count($mysqli->select("SELECT * FROM disal.carterizacionFinal WHERE cobrador = '$cobrador' AND fecha_ingreso = '$fechaIngreso'
                GROUP by rut"));

                $pGestionado = round($cGestionado*100/$caGestionar);
                $noGestionado = 100-$pGestionado;

                echo "<th bgcolor='$colorT'><font color='#FFFFFF'><center>".$target."%</center></font></th>";
                echo "<th><center>".$pGestionado."%</center></font></th>";
                echo "<th id='$colaSG'><center><i class='accedeColaMeta' id='$colaSG' style='cursor: pointer;'>".$noGestionado."%</i></center></th>";
                echo "</tr>";
                
            }
            echo "<tbody>";
            echo "</tbody>";
            echo "</table>";
        }else{
            $cobrador = "";
            $queryUser = $mysqli->select("SELECT nombre FROM Usuarios WHERE id = $idUsuario");
            foreach($queryUser as $rowU){
                $cobrador = strtolower($rowU['nombre']);

            }
            $queryMeta = $mysqli->select("SELECT sum(saldo) as saldoTotal , 
            cobrador ,tramo FROM disal.carterizacionFinal WHERE cobrador = '$cobrador'");
            $totalDeuda = 0;
            $color = "";
            echo "<table id='tabla_meta'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Cobrador</th>";
            echo "<th>Total Cartera</th>";
            echo "<th>Tramo</th>";
            echo "<th>Meta</th>";
            echo "<th>Recupero</th>";
            echo "<th>Target</th>";
            echo "<th>% Gestionado</th>";           
            echo "<th>% Sin Gestión</th>";
            echo "</tr>";
            echo "</thead>";
            foreach($queryMeta as $row){
                $cobrador = $row['cobrador'];
                $meta = 0;
                $saldoRebaja = 0;
                $target = 0;
                $colaSG = "";
                $saldoTotal = $row['saldoTotal'];
                $totalDeuda = $saldoTotal + $totalDeuda;
                $colorT = "";
                $tramo = $row['tramo'];
                if($tramo == 'Tramo 2'){
                    $color = "E6EEC1";
                }elseif($tramo == 'Tramo 3'){
                    $color = "F9BC8F";
                }else{
                    $color = "B0FBA9";
                }
                
                $metaCobrador = 0;
                $metaCobradorFormat = 0;
                $queryCobra = $mysqli->select("SELECT meta ,colaSG FROM disal.meta WHERE cobrador = '$cobrador'");
                foreach($queryCobra as $rowC){
                    $meta = $rowC['meta'];
                    $colaSG = $rowC['colaSG'];
                    $metaCobrador = round($saldoTotal*$meta/100);
                    $metaCobradorFormat = number_format($metaCobrador, 0, '', '.');
                }
                $queryRebaja = $mysqli->select("SELECT sum(saldo) as saldoRebaja FROM disal.carterizacionFinal 
                WHERE pagado = 1 and cobrador = '$cobrador' AND fecha_ingreso = '$fechaIngreso'");
                foreach($queryRebaja as $rowR){
                    $saldoRebaja = $rowR['saldoRebaja'];
                    $target =round($saldoRebaja/$metaCobrador*100);
                }


                echo "<tr>";
                echo "<th>".$row['cobrador']."</th>";
                echo "<th>$ ".number_format($row['saldoTotal'], 0, '', '.')."</th>";
                echo "<th>".$row['tramo']."</th>";
                echo "<th bgcolor='$color'>$ ".$metaCobradorFormat."</th>";
                echo "<th>$ ".number_format($saldoRebaja, 0, '', '.')."</th>";
                if($target>=90){
                    $colorT = "#13A3F3";
                }else{
                    $colorT = "#F35013";
                }

                $cGestionado = count($mysqli->select("SELECT * FROM disal.carterizacionFinal WHERE cobrador = '$cobrador' 
                and gestionado = 1 AND fecha_ingreso = '$fechaIngreso' GROUP by rut"));
                $caGestionar =  count($mysqli->select("SELECT * FROM disal.carterizacionFinal WHERE cobrador = '$cobrador' AND fecha_ingreso = '$fechaIngreso'
                GROUP by rut"));

                $pGestionado = round($cGestionado*100/$caGestionar);
                $noGestionado = 100-$pGestionado;

                echo "<th bgcolor='$colorT'><font color='#FFFFFF'><center>".$target."%</center></font></th>";
                echo "<th><center>".$pGestionado."%</center></font></th>";
                echo "<th id='$colaSG'><center><i class='accedeColaMeta' id='$colaSG' style='cursor: pointer;'>".$noGestionado."%</i></center></th>";
                echo "</tr>";
                
            }
            echo "<tbody>";
            echo "</tbody>";
            echo "</table>";

        }    
    }

    public function getCobrador($nivel,$cobrador){
        $mysqli = new DB();
        $cantidadLlamadas = 0;
        $cantidadLlamadasGestion = 0;
        
        if($nivel == 1){
            $queryClientes = $mysqli->select("SELECT  `rut`, `cliente`, `deudaCarterizada`, `cobrador`, `saldoActual`, `documentos`, 
            `pv`, `tr`, `se`, `nv`, `cv`, `co`,`com` FROM disal.`reporteAging` WHERE cobrador = '$cobrador'");
            echo "<table id='tabla_cobrador'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Rut</th>";
            echo "<th>Cliente</th>";
            echo "<th>Deuda Carterizada</th>";
            echo "<th>Deuda Recuperada</th>";
            echo "<th>% Recupero</th>";
            echo "<th>Documentos</th>";
            echo "<th>Deuda Actual</th>";
            echo "<th>Llamadas Efectivas</th>";
            echo "<th>Total Llamadas</th>";
            echo "<th>Compromisos Vigentes</th>";
            echo "<th>Compromisos Rotos</th>";
            echo "<th>Por Vencer</th>";
            echo "<th>0-30</th>";
            echo "<th>30-60</th>";
            echo "<th>60-90</th>";
            echo "<th>90-120</th>";
            echo "<th>120-180</th>";
            echo "<th>+180</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($queryClientes as $row){
                $rut = $row['rut'];
                $cliente = $row['cliente'];
                $saldoCarterizado = $row['deudaCarterizada'];
                $saldoActual = $row['saldoActual'];
                $doc = $row['documentos'];
                $pv = $row['pv'];
                $tr = $row['tr'];
                $se = $row['se'];
                $nv = $row['nv'];
                $cv = $row['cv'];
                $co = $row['co'];
                $com = $row['com'];
                echo "<tr>";
                echo "<th><a href='../../clientes/clientesDetalle.php?rut=$rut'>".$rut."</a></th>";
                echo "<th>".$cliente."</th>";
                echo "<th>".$saldoCarterizado."</th>";
                $sqlPago = $mysqli->select("SELECT SUM(saldo) as sumaPagado FROM disal.carterizacionFinal WHERE Rut = '$rut' AND  pagado = 1");
                foreach($sqlPago as $rowP){
                    $sumaPagado = $rowP['sumaPagado'];
                    echo "<th>".$sumaPagado."</th>";
                    $porcentaje = round(($sumaPagado/$saldoCarterizado)*100);
                    switch($porcentaje){
                        case ($porcentaje >= 90 && $porcentaje <= 100) : 
                            echo "<th><span class='label label-table label-success'>".$porcentaje."%</span></th>"; 
                            break;
                        case ($porcentaje >= 50 && $porcentaje < 90) :
                            echo "<th><span class='label label-table label-primary'>".$porcentaje."%</span></th>"; 
                            break;
                        case ($porcentaje >= 30 && $porcentaje < 50) :
                            echo "<th><span class='label label-table label-warning'>".$porcentaje."%</span></th>"; 
                            break;
                        case ($porcentaje >= 0 && $porcentaje < 30) :
                            echo "<th><span class='label label-table label-danger'>".$porcentaje."%</span></th>"; 
                            break; 
                        case ($porcentaje == 0 || $porcentaje == '') :
                            echo "<th><span class='label label-table label-danger'>".$porcentaje."%</span></th>"; 
                            break;
 
                    }
                }
                echo "<th>".$doc."</th>";

                $sqlGestion = $mysqli->select("SELECT Cantidad FROM Cantidad_Llamadas_Gestion_Mes WHERE Rut = '$rut' ");
                foreach($sqlGestion as $row4){
                    $cantidadLlamadasGestion = $row4['Cantidad'];
                }

                $sqlLlamadas = $mysqli->select("SELECT Cantidad FROM Cantidad_Llamadas_Mes WHERE Rut = '$rut' ");
                foreach($sqlLlamadas as $row3){
                    $cantidadLlamadas = $row3['Cantidad'];

                }

                echo "<th>".$saldoActual."</th>";
                echo "<th>$cantidadLlamadasGestion</th>";
                echo "<th>$cantidadLlamadas</th>";
                $sqlComp = $mysqli->select("SELECT count(*) as cantidad FROM Compromisos WHERE Rut = '$rut' AND Compromiso IN ('FUTUROS','HOY')");
                foreach($sqlComp as $rowC){
                    $cantidadComp = $rowC['cantidad'];
                    echo "<th>".$cantidadComp."</th>";
                }
                $sqlCompRoto = $mysqli->select("SELECT count(*) as cantidad FROM Compromisos WHERE Rut = '$rut' AND Compromiso NOT IN ('FUTUROS','HOY')");
                foreach($sqlCompRoto as $rowR){
                    $cantidadCompRoto = $rowR['cantidad'];
                    echo "<th>".$cantidadCompRoto."</th>";
                }
                echo "<th>".$pv."</th>";
                echo "<th>".$tr."</th>";
                echo "<th>".$se."</th>";
                echo "<th>".$nv."</th>";
                echo "<th>".$cv."</th>";
                echo "<th>".$co."</th>";
                echo "<th>".$com."</th>";
              //  echo "<th>".$com."</th>";

                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
            
        }
    }
    
    public function getAging($cobrador){
        $mysqli = new DB();
        $queryAging = $mysqli->select("SELECT sum(saldo) AS suma, cobrador ,aging FROM disal.carterizacionFinal WHERE 
        cobrador = '$cobrador' group by aging ORDER BY orderAging ASC");
        $sumaTotal = 0;
        echo "<br>";
        echo "<table class='tabla_aging'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Cobrador</th>";

        foreach($queryAging as $row){
            echo "<th>".$row['aging']."</th>";
        }
        echo "<th>TOTAL GENERAL</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tr>";
        echo "<th>$cobrador</th>";

        foreach($queryAging as $row){
            $sumaTotal = $row['suma'] + $sumaTotal;
            echo "<th>".number_format($row['suma'], 0, '', '.')."</th>";
            
        }
        echo "<th>".number_format($sumaTotal, 0, '', '.')."</th>";

        echo "</tr>";
        echo "<tbody>";
        echo "</tbody>";
        echo "</table>";

    }
    public function getAsignacion($idUsuario){
        $mysqli = new DB();
        $nivel = 0;
        $cobrador = "";
        $query = $mysqli->select("SELECT usuario,nombre,nivelFactura FROM Usuarios WHERE id = $idUsuario");
        foreach($query as $row){
            $nivel = $row['nivelFactura'];
            $cobrador = strtolower($row['nombre']);
        }
        if($nivel == 1){
            echo "<table id='tabla_asignacion'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Rut</th>";
            echo "<th>Monto</th>";
            echo "<th>Cobrador</th>";
            echo "<th>%</th>";
            echo "<th>Ir</th>";
            echo "</tr>";
            echo "</thead>";
            $queryCobrador = $mysqli->select("SELECT rut,monto,cobrador FROM A_DISAL_Carterizacion_Final WHERE 1");
            foreach($queryCobrador as $rowC){
                echo "<tr>";
                echo "<th>".$rowC['rut']."</th>";
                echo "<th>".$rowC['monto']."</th>";
                echo "<th>".$rowC['cobrador']."</th>";
                echo "<th>%</th>";
                echo "<th>Ir</th>";
                echo "</tr>";

            }

           
            echo "<tbody>";
            echo "</tbody>";
            echo "</table>";
        }else{
            
        }

    }
    public function CalendarioAgenda($idCalendar){
        $Cedente = $_SESSION['cedente'];
        $IdUsuario = $_SESSION['id_usuario'];

        $db = new DB();
        $CalendarioArray = array();
        $Query = '';
        if($idCalendar == 1){
            $QueryCautiva = "SELECT query FROM SIS_Querys_Estrategias WHERE  cautiva = 1 AND terminal = 1 AND Id_Cedente = $Cedente AND IdUserCautiva = $IdUsuario AND ver_agenda = 1 ORDER BY prioridad";
        }
        else{
            $QueryCautiva = "SELECT query FROM SIS_Querys_Estrategias WHERE  cautiva = 1 AND terminal = 1 AND Id_Cedente = $Cedente AND IdUserCautiva = $IdUsuario AND id = $idCalendar AND ver_agenda = 1";
        }
        $Cautivos = $db->select($QueryCautiva);
        
        if($Cautivos){
            foreach($Cautivos as $Cautivo){
                $Query = $Cautivo['query'];
            }

            $Cont = 0;
            $SqlAgenda = "SELECT FechaAgenda, Agenda, Rut FROM Agendamiento WHERE Rut IN ($Query) and Id_Cedente='".$_SESSION["cedente"]."'";
            $Agendas = $db->select($SqlAgenda);
            if($Agendas){
                foreach($Agendas as $Agenda){
                    $AgendaArray = array();
                    $AgendaArray['start']= $Agenda['FechaAgenda'];
                    if($Agenda['Agenda']){
                        $Titulo = $Agenda['Agenda'];
                    }else{
                        $Titulo = $Agenda['Rut'];
                    }
                    $AgendaArray['title']= "A - ".$Titulo;
                    $AgendaArray['Rut']= $Agenda['Rut'];
                    switch ($Agenda['Agenda']){
                        case 'Vencido' :
                            $AgendaArray['className'] = "danger";
                            break;
                        case 'Hoy' :
                            $AgendaArray['className'] = "purple";
                            break;
                        case 'Mañana' :
                            $AgendaArray['className'] = "success";
                            break;
                        case 'Futuro' : 
                            $AgendaArray['className'] = "primary";
                            break;
                    }
                    $CalendarioArray[$Cont] = $AgendaArray;
                    $Cont++;
                }
            }
            
            $SqlComp = "SELECT FechaCompromiso, Compromiso, Rut FROM Agendamiento_Compromiso WHERE Rut IN ($Query) and Id_Cedente='".$_SESSION["cedente"]."'";
            $Compromisos = $db->select($SqlComp);
            if($Compromisos){
                foreach($Compromisos as $Compromiso){
                    $CompromisoArray = array();
                    $CompromisoArray['start']= $Compromiso['FechaCompromiso'];
                    if($Compromiso['Compromiso']){
                        $Titulo = $Compromiso['Compromiso'];
                    }else{
                        $Titulo = $Compromiso['Rut'];
                    }
                    $CompromisoArray['title']= "C - ".$Titulo;
                    $CompromisoArray['Rut']= $Compromiso['Rut'];
                    switch ($Compromiso['Compromiso']){
                        case 'Roto' :
                            $CompromisoArray['className'] = "danger";
                            break;
                        case 'Hoy' :
                            $CompromisoArray['className'] = "purple";
                            break;
                        case 'Mañana' :
                            $CompromisoArray['className'] = "success";
                            break;
                        case 'Futuro' : 
                            $CompromisoArray['className'] = "primary";
                            break;
                    }
                    $CalendarioArray[$Cont] = $CompromisoArray;
                    $Cont++;
                }
            }
        }
        return $CalendarioArray;
    }

    public function accesoColas($idUsuario){
        $asignaciones = array();
        if(isset($_SESSION['cedente'])){
            $Cedente = $_SESSION['cedente'];
            $db = new DB();
            $sql = "SELECT 
                        ac.id_cola, ac.asignacion, qe.cola, qe.id_estrategia, qe.prioridad, qe.ver_agenda
                    FROM 
                        asignacion_cola AS ac
                    INNER JOIN 
                        SIS_Querys_Estrategias AS qe 
                    ON 
                        (ac.id_cola = qe.id), Usuarios AS u
                    INNER JOIN 
                        Personal AS p 
                    ON 
                        (u.Id_Personal = p.Id_Personal)
                    WHERE 
                        qe.terminal = 1 
                    AND  
                        (SUBSTRING_INDEX(SUBSTRING_INDEX(ac.asignacion,'_',5), '_', -1) = p.Id_Personal OR qe.IdUserCautiva = '".$idUsuario."')
                    AND 
                        ((SUBSTRING_INDEX(SUBSTRING_INDEX(ac.asignacion,'_', 4), '_', -1) = 'S') 
                    OR 
                        (SUBSTRING_INDEX(SUBSTRING_INDEX(ac.asignacion,'_',4), '_', -1) = 'E'))
                    AND 
                        u.id = '".$idUsuario."' 
                    AND 
                        ac.asignacion NOT IN (SELECT Cola FROM Asterisk_Discador_Cola)
                    AND 
                        qe.ver_agenda = 1 
                    AND 
                        qe.Id_Cedente = '".$Cedente."'
                    GROUP BY
	                    ac.asignacion
                    ORDER BY 
                        qe.prioridad";

            $rows = $db->select($sql);
            
            if($rows) {
                foreach($rows as $row){
                    $asignacion = $row["asignacion"];
                    $explode = explode("_", $asignacion);
                    if(isset($explode[7])){
                        $activa = $explode[7];
                        if($activa == 1 && $row['ver_agenda'] == 1){
                            $queryAsignacion =  "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'fire_crm' and TABLE_NAME  = '$asignacion'";
                            $colas = $db->select($queryAsignacion);
                            if($colas){
                                foreach($colas as $cola){
                                    $sqlCasos = "SELECT COUNT(id) AS casos FROM " . $asignacion;
                                    $casos = $db->select($sqlCasos);
                                    $arreglo = array();
                                    $arreglo["id"]          = $row["id_cola"];
                                    $arreglo["cola"]        = $row["cola"];
                                    $arreglo["casos"]       = $casos[0]["casos"];
                                    $arreglo["asignacion"]  = $asignacion;
                                    $arreglo["estrategia"]  = $row["id_estrategia"];
                                    $arreglo["prioridad"]   = $row["prioridad"];
                                    $arreglo["porcentaje"]  = (int) $this->getProgressAsignacion($asignacion);
                                    array_push($asignaciones, $arreglo);  
                                }
                            }
                        }
                    }
                }
            } else {
                // Total Cartera
                $tableNameTC = "QR_".$Cedente."_0_XF_".$_SESSION['MM_Username']."_1_1_1_TC";
                $db->query("DROP TABLE IF EXISTS $tableNameTC");
                $crear = "CREATE TABLE $tableNameTC (
                    id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
                    Rut int, 
                    estado INT NOT NULL DEFAULT 0, 
                    orden INT, 
                    fechaGestion datetime,
                    llamado INT DEFAULT 0,
                    id_usuario INT DEFAULT 0,
                    estado_cola INT DEFAULT 2,
                    fechaRellamar datetime,
                    INDEX(Rut))";
                $db->query($crear);
                
                $db->query("INSERT INTO  $tableNameTC (Rut) SELECT
                                                                Rut 
                                                            FROM
                                                                Deuda 
                                                            WHERE
                                                                Id_Cedente = '$Cedente'
                                                            AND 
                                                                COBRADOR = '". $_SESSION['nombreUsuario'] ."' 
                                                            GROUP BY rut
                            ");

                $contar5 = $db->select("SELECT
                                            tb.*,
                                            gut.fecha_gestion,
                                            u.id as id_usuario
                                        FROM
                                            $tableNameTC AS tb
                                            LEFT JOIN gestion_ult_trimestre AS gut ON gut.rut_cliente = tb.Rut 
                                            AND MONTH ( gut.fecha_gestion ) = MONTH(CURRENT_DATE ())
                                            AND YEAR ( gut.fecha_gestion ) = YEAR(CURRENT_DATE ())
                                            AND gut.Cedente = 1
                                            JOIN Usuarios as u ON u.usuario = gut.nombre_ejecutivo
                                        GROUP BY
                                            tb.Rut");
                //$contar5 = $db->select("SELECT * FROM $tableNameTC");//$_SESSION['id_usuario']
                $contarTC = count($contar5);
                foreach($contar5 as $row){
                    $id = $row['id'];
                    if($row['fecha_gestion'] != null && $row['fecha_gestion'] != ''){
                        $llamado = 1;
                        $estado_cola = 3;
                        $fechaGestion = $row['fecha_gestion'];
                        $estado = 2;
                    }else{
                        $llamado = 0;
                        $estado_cola = 2;
                        $fechaGestion = null;
                        $estado = 0;
                    }
                    $db->query("UPDATE $tableNameTC SET orden = $id, llamado = '$llamado', estado_cola = '$estado_cola', fechaGestion = '$fechaGestion',estado = '$estado' WHERE id = $id");
                }
                
                $percentil = $db->select("SELECT ROUND((SUM(llamado) / (SELECT COUNT(*) FROM $tableNameTC) * 100), 2) AS porcentaje FROM $tableNameTC");
                $datos = $db->select("SELECT * FROM $tableNameTC");
                $casos = count($datos);
                $arreglo = array();
                $arreglo["id"]          = 0;
                $arreglo["cola"]        = "TOTAL CARTERA";
                $arreglo["casos"]       = $casos;
                $arreglo["asignacion"]  = "SISTEMA";
                $arreglo["estrategia"]  = "0";
                $arreglo["prioridad"]   = "1";
                $arreglo["porcentaje"]  = (int) $percentil[0]['porcentaje'];
                $arreglo["usuario"]     = $_SESSION['MM_Username'];
                array_push($asignaciones, $arreglo);  
            }
        }

        return $asignaciones;
        /*
        $colores = array("default","primary","info","success","mint","warning","danger","pink","purple","dark");
        $color = $colores[0];
        echo "<button class='btn btn-$color'>".$idUsuario."</button>";*/
    }

    public function accesoDirectoColas($cola, $estrategia, $asignacion){
        $db = new DB();

        $arraySinGestion = array();
        $arrayConGestion = array();
        $arraySinGestionNew = array();
        $arrayConGestionNew = array();

        $fecha = date('Y-m');
        $fechaInicio = $fecha."-01";
        $fechaTermino = $fecha."-31";

        $this->cola=$cola;
		$this->estrategia=$estrategia;
        $this->asignacion=$asignacion;
		$_SESSION['cola'] = $this->cola;
		$_SESSION['estrategia'] = $this->estrategia;
        $_SESSION['asignacion'] = $this->asignacion;
        $asignacion = $this->asignacion;
        $cola = $this->cola;
        $db->query("TRUNCATE TABLE $asignacion");
        

        $query = $db->select("SELECT query FROM `SIS_Querys_Estrategias` WHERE id = $cola");
        $sql = "";
        foreach($query as $q){
            $sql = $q['query'];
        }
        $queryEstrategia = $db->select($sql);
        foreach($queryEstrategia as $row){
            $rut = $row['Rut'];
            $existe = $db->select("SELECT * FROM `gestion_ult_trimestre` WHERE rut_cliente = '$rut' and fecha_gestion BETWEEN '$fechaInicio' AND '$fechaTermino'");
            if($existe){
                array_push($arrayConGestion,$rut);
            }else{
                array_push($arraySinGestion,$rut);
            }
        }

        $arrayCG = implode(",", $arrayConGestion);
        $orderConGestion = "SELECT Rut,sum(Deuda) as saldo FROM foco.`Deuda` WHERE Rut in ($arrayCG) GROUP BY Rut order by saldo DESC";
        $queryNewCG = $db->query($orderConGestion);
        foreach($queryNewCG as $row){
            $rutCG = $row['Rut'];
            array_push($arrayConGestionNew,$rutCG);
        
        }

        $arraySG = implode(",", $arraySinGestion);
        $orderSinGestion = "SELECT Rut,sum(Deuda) as saldo FROM foco.`Deuda` WHERE Rut in ($arraySG) GROUP BY Rut order by saldo DESC";
        $queryNewSG = $db->query($orderSinGestion);
        foreach($queryNewSG as $row){
            $rutSG = $row['Rut'];
            array_push($arraySinGestionNew,$rutSG);
        
        }



        $i = 0;
        $contador = count($arraySinGestionNew);
        while($i < $contador){
            $rut = $arraySinGestionNew[$i];
            $orden = $i+1;
            $query = "INSERT INTO $asignacion(`Rut`,`orden`) VALUES ('$rut',$orden)";
            $db->query($query);
            $i++;

        }
        $lastId = 0;
        $q2 = $db->select("SELECT id FROM $asignacion ORDER by id DESC limit 1");
        foreach($q2 as $row){
            $lastId = $row['id'];

        }


        $j = 0;
        $contadorJ = count($arrayConGestionNew);
        while($j < $contadorJ){
            $rut = $arrayConGestionNew[$j];
            $lastId = $lastId+1;
            $query = "INSERT INTO $asignacion(`Rut`,`orden`,`estado`) VALUES ('$rut',$lastId,1)";
            $db->query($query);
            $j++;
            $lastId++;

        }
        
    }

    public function accesoDirectoColas2($cola, $estrategia, $asignacion){
        $this->cola=$cola;
		$this->estrategia=$estrategia;
        $this->asignacion=$asignacion;
		$_SESSION['cola'] = $this->cola;
		$_SESSION['estrategia'] = $this->estrategia;
        $_SESSION['asignacion'] = $this->asignacion;
    }

    public function accesoDirectoRut($Rut)
    {
        $this->Rut = $Rut;
		$_SESSION['AccesoDirectoRut'] = $this->Rut;
    }

    function getProgressAsignacion($Asignacion)
    {
		$db = new DB();
		$ToReturn = 0;
		$SqlTotal = "SELECT count(*) AS Total FROM {$Asignacion}";
		$Total = $db->select($SqlTotal);
		$Total = (int) $Total[0]["Total"];
		$SqlGestionado = "SELECT count(*) AS Gestionado FROM ".$Asignacion." WHERE estado != '0'";
		$Gestionado = $db->select($SqlGestionado);
		$Gestionado = (int) $Gestionado[0]["Gestionado"];
		$ToReturn = $Gestionado == 0 ? 0 : ($Gestionado * 100) / $Total;
		$ToReturn = round($ToReturn);
		return $ToReturn;
    }
    
    function getDash($idUser,$cedente){
        $db = new DB();
        $cedenteN = (int)$cedente * -1;
        $qe = $db->query("SELECT * FROM SIS_Estrategias WHERE id = $cedenteN AND Id_Cedente = $cedente");
        if($qe){
            $db->query("INSERT INTO SIS_Estrategias (id,nombre,Id_Cedente) VALUES ($cedenteN,'SISTEMA',$cedente) ");
        }

        $query = $db->select("SELECT usuario,nombre FROM Usuarios WHERE id = $idUser");
        $user = "";
        $nombre = "";
        foreach($query as $row){
            $user = $row["usuario"];
            $nombre = $row["nombre"];
        }

        $hoy = date("Y-m-d");   

        //+1 Millon
        $tableName = "QR_".$cedente."_0_XA_".$user."_1_1_1_M1M";
        $db->query("DROP TABLE IF EXISTS $tableName");
        $crear = "CREATE TABLE $tableName (
            id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
            Rut int, 
            estado INT NOT NULL DEFAULT 0, 
            orden INT, 
            fechaGestion datetime,
            llamado INT DEFAULT 0,
            id_usuario INT DEFAULT 0,
            estado_cola INT DEFAULT 2,
            fechaRellamar datetime,
            INDEX(Rut))";
        $db->query($crear);

        //Buscamos todos los rut y sus montos //Probar con acastro
        $sRuts = $db->select("SELECT
                                rut,SUM(Monto_Factura) as monto
                            FROM
                                Deuda
                            WHERE
                                COBRADOR = ( SELECT nombre_carga FROM ejecutivo_carga_sistema WHERE nombre_sistema = '$user')
                                AND Id_Cedente = '$cedente'
                            GROUP BY
                                rut");
        //Reccorremos la consulta
        foreach($sRuts as $row){
            //Condicionamos la insercion
            if($row['monto'] >= 1000000){

                $db->query("INSERT INTO  $tableName (Rut) VALUES ('".$row['rut']."')");

            }
        }

        $contar = $db->select("SELECT * FROM $tableName");
        $contarM = count($contar);
        foreach($contar as $row){
            $id = $row['id'];
            $db->query("UPDATE  $tableName SET orden = $id WHERE id = $id");
        }
        $botonM = "";
        if($contarM == 0){
            $botonM = "<button disabled class='btn btn-danger btn-md getCola'><i class='fa fa-stop'></i> Sin Registros</button>";
        }else{
            $botonM = "<button class='btn btn-success btn-md getCola'><i class='fa fa-play'></i> Acceder</button>";
        }

        // Sin Contacto 3 y 4
        $month = (int)date('m');
        $tableNameSC = "QR_".$cedente."_0_XB_".$user."_1_1_1_SC";
        $db->query("DROP TABLE IF EXISTS $tableNameSC");
        $crear = "CREATE TABLE $tableNameSC (
            id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
            Rut int, 
            estado INT NOT NULL DEFAULT 0, 
            orden INT, 
            fechaGestion datetime,
            llamado INT DEFAULT 0,
            id_usuario INT DEFAULT 0,
            estado_cola INT DEFAULT 2,
            fechaRellamar datetime,
            INDEX(Rut))";
        $db->query($crear);
         
        $db->query("INSERT INTO  $tableNameSC (Rut) SELECT
                                                        rut_cliente 
                                                    FROM
                                                        gestion_ult_trimestre 
                                                    WHERE
                                                        nombre_ejecutivo = '$user'
                                                        AND MONTH ( fecha_gestion ) = '$month' 
                                                        AND cedente = '$cedente' 
                                                        AND Id_TipoGestion IN (3,4) ");

        $contar2 = $db->select("SELECT * FROM $tableNameSC");
        $contarSC = count($contar2);
        foreach($contar2 as $row){
            $id = $row['id'];
            $db->query("UPDATE  $tableNameSC SET orden = $id WHERE id = $id");
        }
        $botonSC = "";
        if($contarSC == 0){
            $botonSC = "<button disabled class='btn btn-danger btn-md getCola'><i class='fa fa-stop'></i> Sin Registros</button>";
        }else{
            $botonSC = "<button class='btn btn-success btn-md getCola'><i class='fa fa-play'></i> Acceder</button>";
        }
        
        // Sin Gestion

        $tableNameSG = "QR_".$cedente."_0_XC_".$user."_1_1_1_SG";
        $db->query("DROP TABLE IF EXISTS $tableNameSG");
        $crear = "CREATE TABLE $tableNameSG (
            id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
            Rut int, 
            estado INT NOT NULL DEFAULT 0, 
            orden INT, 
            fechaGestion datetime,
            llamado INT DEFAULT 0,
            id_usuario INT DEFAULT 0,
            estado_cola INT DEFAULT 2,
            fechaRellamar datetime,
            INDEX(Rut))";
        $db->query($crear);
         
        $db->query("INSERT INTO  $tableNameSG (Rut) SELECT
                                                        Rut 
                                                    FROM
                                                        Deuda 
                                                    WHERE
                                                        Rut NOT IN ( SELECT rut_cliente FROM gestion_ult_trimestre )
                                                    AND 
                                                        Id_Cedente = '$cedente'
                                                    AND 
                                                        COBRADOR = ( SELECT nombre_carga FROM ejecutivo_carga_sistema WHERE nombre_sistema = '$user')");

        $contar3 = $db->select("SELECT * FROM $tableNameSG");
        $contarSG = count($contar3);
        foreach($contar3 as $row){
            $id = $row['id'];
            $db->query("UPDATE  $tableNameSG SET orden = $id WHERE id = $id");
        }
        $botonSG = "";
        if($contarSG == 0){
            $botonSG = "<button disabled class='btn btn-danger btn-md getCola'><i class='fa fa-stop'></i> Sin Registros</button>";
        }else{
            $botonSG = "<button class='btn btn-success btn-md getCola'><i class='fa fa-play'></i> Acceder</button>";
        }
        
        // En Deuda
        
        $tableNameED = "QR_".$cedente."_0_XD_".$user."_1_1_1_ED";
        $db->query("DROP TABLE IF EXISTS $tableNameED");
        $crear = "CREATE TABLE $tableNameED (
            id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
            Rut int, 
            estado INT NOT NULL DEFAULT 0, 
            orden INT, 
            fechaGestion datetime,
            llamado INT DEFAULT 0,
            id_usuario INT DEFAULT 0,
            estado_cola INT DEFAULT 2,
            fechaRellamar datetime,
            INDEX(Rut))";
        $db->query($crear);
         
        $db->query("INSERT INTO  $tableNameED (Rut) SELECT
                                                        Rut 
                                                    FROM
                                                        Deuda 
                                                    WHERE
                                                        Rut NOT IN ( SELECT rut_cliente FROM gestion_ult_trimestre )
                                                    AND 
                                                        Id_Cedente = '$cedente'
                                                    AND 
                                                        COBRADOR = ( SELECT nombre_carga FROM ejecutivo_carga_sistema WHERE nombre_sistema = '$user')");

        $contar4 = $db->select("SELECT * FROM $tableNameED");
        $contarED = count($contar4);
        foreach($contar4 as $row){
            $id = $row['id'];
            $db->query("UPDATE  $tableNameED SET orden = $id WHERE id = $id");
        }
        $botonED = "";
        if($contarED == 0){
            $botonED = "<button disabled class='btn btn-danger btn-md getCola'><i class='fa fa-stop'></i> Sin Registros</button>";
        }else{
            $botonED = "<button class='btn btn-success btn-md getCola'><i class='fa fa-play'></i> Acceder</button>";
        }

        // Total Cartera
        
        $tableNameTC = "QR_".$cedente."_0_XF_".$user."_1_1_1_TC";
        $db->query("DROP TABLE IF EXISTS $tableNameTC");
        $crear = "CREATE TABLE $tableNameTC (
            id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
            Rut int, 
            estado INT NOT NULL DEFAULT 0, 
            orden INT, 
            fechaGestion datetime,
            llamado INT DEFAULT 0,
            id_usuario INT DEFAULT 0,
            estado_cola INT DEFAULT 2,
            fechaRellamar datetime,
            INDEX(Rut))";
        $db->query($crear);
         
        $db->query("INSERT INTO  $tableNameTC (Rut) SELECT
                                                        Rut 
                                                    FROM
                                                        Deuda 
                                                    WHERE
                                                        Id_Cedente = '$cedente'
                                                    AND 
                                                        COBRADOR = ( SELECT nombre_carga FROM ejecutivo_carga_sistema WHERE nombre_sistema = '$user')");

        $contar5 = $db->select("SELECT * FROM $tableNameTC");
        $contarTC = count($contar5);
        foreach($contar5 as $row){
            $id = $row['id'];
            $db->query("UPDATE  $tableNameTC SET orden = $id WHERE id = $id");
        }
        $botonTC = "";
        if($contarTC == 0){
            $botonTC = "<button disabled class='btn btn-danger btn-md getCola'><i class='fa fa-stop'></i> Sin Registros</button>";
        }else{
            $botonTC = "<button class='btn btn-success btn-md getCola'><i class='fa fa-play'></i> Acceder</button>";
        }


        echo $return = 
        "<div class='table-responsive'>
            <table id='acceso_directo'>
                <thead>
                    <tr>
                        <th>Prioridad</th>
                        <th>Nombre</th>
                        <th>Asignación</th>
                        <th>Casos</th>
                        <th>Acceder</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id=$tableName>
                        <td>1</td>
                        <td>$nombre</td>
                        <td>+1 MILLÓN</td>
                        <td>$contarM</td>
                        <td>
                        $botonM</td>
                    </tr>
                    <tr id=$tableNameSC>
                        <td>2</td>
                        <td>$nombre</td>
                        <td>SIN CONTACTO</td>
                        <td>$contarSC</td>
                        <td>
                        $botonSC</td>
                    </tr>
                    <tr id=$tableNameSG>
                        <td>3</td>
                        <td>$nombre</td>
                        <td>SIN GESTIÓN</td>
                        <td>$contarSG</td>
                        <td>
                        $botonSG</td>
                    </tr>
                    <tr id=$tableNameED>
                        <td>4</td>
                        <td>$nombre</td>
                        <td>EN DEUDA</td>
                        <td>$contarED</td>
                        <td>
                        $botonED</td>
                    </tr>
                    <tr id=$tableNameTC>
                        <td>4</td>
                        <td>$nombre</td>
                        <td>TOTAL CARTERA</td>
                        <td>$contarTC</td>
                        <td>
                        $botonTC</td>
                    </tr>
                </tbody>
            </table>
        </div>";
    }
}

//Nueva cartera para todos los casos que esten en deuda.
?>
