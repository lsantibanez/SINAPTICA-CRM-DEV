<?php
    class Derivaciones{

        function getOficinas($idMandante){
            $db = new DB();
            $SqlOficinas = "select *, '' as Accion from Oficinas_Derivaciones where Id_Mandante='".$idMandante."'";
            $Oficinas = $db->select($SqlOficinas);
            return $Oficinas;
        }
        function getReprogramaciones($TipoReprogramaciones,$idMandante,$fecha){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["Fields"] = array();
            $ToReturn["Headers"] = array();
            $ToReturn["Tabla"] = "";
            switch($TipoReprogramaciones){
                case "diario":
                    $SqlReprogramaciones = "
                    SELECT
                        *
                    FROM
                        (
                            select
                                Deuda.Numero_Operacion as Folio,
                                Deuda.Rutdv as Rut,
                                REPLACE(CONCAT('$',' ',FORMAT(Deuda.SALDOA,0)),',','.') as SaldoAdeudado,
                                IF(Ges.resultado_n3 = 1964,IF(Deuda.MARCA_PAGO_ULT_3M = '1_1_1','NO','SI'),'NO') as Abono,
                                REPLACE(CONCAT('$',' ',FORMAT(Deuda.VCUOTA,0)),',','.') as MontoAbono,
                                DATE_FORMAT(Ges.fec_compromiso, '%d-%m-%Y') as DiaVisita,
                                Ges.fechahora as FechaHoraGestion,
                                Ges.fono_discado as TelefonoDeudor,
                                (select correo_electronico from Mail where Rut = Ges.rut_cliente LIMIT 1) as MailContacto,
                                (select OD.Nombre from respuestas_campos_gestion RCG LEFT JOIN Oficinas_Derivaciones OD on OD.cod = SUBSTRING(RCG.Valor,locate('-',RCG.Valor) + 1,9999) where RCG.id_gestion=Ges.id_gestion and RCG.id_campo = '8') as Oficina,
                                (select OD.cod from respuestas_campos_gestion RCG LEFT JOIN Oficinas_Derivaciones OD on OD.cod = SUBSTRING(RCG.Valor,locate('-',RCG.Valor) + 1,9999) where RCG.id_gestion=Ges.id_gestion and RCG.id_campo = '8') as CodOficina,
                                Ges.n3 as TipoReprogramacion,
                                Ges.nombre_ejecutivo as Ejecutivo
                            from
                                gestion_ult_trimestre as Ges
                                    INNER JOIN (select * from respuestas_campos_gestion Res where Res.id_campo='13') Res on Res.id_gestion = Ges.id_gestion
                                    INNER join Deuda on Deuda.Rut = Ges.rut_cliente and Deuda.Numero_Operacion = REPLACE(Res.Valor,'\t','')
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = Deuda.Id_Cedente
                            where
                                Ges.fecha_gestion='".$fecha."' AND
                                Deuda.JUDICIALIZADO = 'NO' AND
                                mandante_cedente.Id_Mandante='".$idMandante."' AND
                                Ges.resultado_n3 in (1964,1965,1966,1967,1968,1969,1971)
                            ORDER BY Ges.fechahora DESC
                        ) tb1
                    GROUP BY tb1.Folio";

                    
                    $Headers = array();
                    array_push($Headers,"Ejecutivo");
                    array_push($Headers,"Oficina");
                    array_push($Headers,"Folio");
                    array_push($Headers,"Rut");
                    array_push($Headers,"Saldo Adeudado");
                    array_push($Headers,"Abono");
                    array_push($Headers,"Monto Abono");
                    array_push($Headers,"Dia De Visita");
                    array_push($Headers,"Telefono Deudor");
                    array_push($Headers,"Mail Contacto");
                    array_push($Headers,"Tipo de Reprogramacion");
                    
                    
                    $Fields = array();
                    array_push($Fields,array("data"=>"Ejecutivo"));
                    array_push($Fields,array("data"=>"Oficina"));
                    array_push($Fields,array("data"=>"Folio"));
                    array_push($Fields,array("data"=>"Rut"));
                    array_push($Fields,array("data"=>"SaldoAdeudado"));
                    array_push($Fields,array("data"=>"Abono"));
                    array_push($Fields,array("data"=>"MontoAbono"));
                    array_push($Fields,array("data"=>"DiaVisita"));
                    array_push($Fields,array("data"=>"TelefonoDeudor"));
                    array_push($Fields,array("data"=>"MailContacto"));
                    array_push($Fields,array("data"=>"TipoReprogramacion"));
                    
                    $ToReturn["Fields"] = $Fields;
                    $ToReturn["Headers"] = $Headers;
                    $ToReturn["Table"] = "tablaReprogramacionesDiaria";
                break;
                case "mensual":
                    $SqlReprogramaciones = "";
                    
                    $Headers = array();
                    $Fields = array();
                    
                    $ToReturn["Fields"] = $Fields;
                    $ToReturn["Headers"] = $Headers;
                    $ToReturn["Table"] = "tablaReprogramacionesMensual";
                break;
            }
            $Reprogramaciones = $db->select($SqlReprogramaciones);
            $ToReturn["Reprogramaciones"] = $Reprogramaciones;
            return $ToReturn;
        }
        function getAcuerdosCastigo($TipoAcuerdos,$idMandante,$fecha){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["Acuerdos"] = array();
            $ToReturn["Headers"] = array();
            $ToReturn["Fields"] = array();
            $ToReturn["Tabla"] = "";
            switch($TipoAcuerdos){
                case "diario":
                    $SqlAcuerdos = "
                    SELECT
                        *
                    FROM
                        (
                        select
                            Deuda.Numero_Operacion as Folio,
                            Deuda.Rutdv as Rut,
                            Deuda.SALDOA as DeudaTotal,
                            Deuda.Capital as DeudaCapital,
                            Ges.monto_comp as Abono,
                            IFNULL((select Valor from respuestas_campos_gestion where id_gestion = Ges.id_gestion AND id_campo='6'),'1') as Cuotas_convenio,
                            Ges.fono_discado as TelefonoCliente,
                            Ges.fec_compromiso as DiaDeVisita,
                            Ges.fechahora as FechaHoraGestion,
                            IFNULL((select correo_electronico from Mail where Rut = Ges.rut_cliente LIMIT 1),'') as MailContacto,
                            Ges.n3 as TipoDePago,
                            Ges.resultado_n3 as IdTipoDePago,
                            Deuda.Descuento_convenio,
                            Deuda.Descuento_pago_total,
                            Ges.id_gestion,
                            Ges.nombre_ejecutivo as Ejecutivo,
                            (select OD.Nombre from respuestas_campos_gestion RCG LEFT JOIN Oficinas_Derivaciones OD on OD.cod = SUBSTRING(RCG.Valor,locate('-',RCG.Valor) + 1,9999) where RCG.id_gestion=Ges.id_gestion and RCG.id_campo = '8') as Oficina,
                            (select OD.cod from respuestas_campos_gestion RCG LEFT JOIN Oficinas_Derivaciones OD on OD.cod = SUBSTRING(RCG.Valor,locate('-',RCG.Valor) + 1,9999) where RCG.id_gestion=Ges.id_gestion and RCG.id_campo = '8') as CodOficina
                        FROM
                            gestion_ult_trimestre as Ges
                                    INNER JOIN (select * from respuestas_campos_gestion Res where Res.id_campo='13') Res on Res.id_gestion = Ges.id_gestion
                                    INNER join Deuda on Deuda.Rut = Ges.rut_cliente and Deuda.Numero_Operacion = REPLACE(Res.Valor,'	','')
                                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = Ges.cedente
                        where
                            Ges.fecha_gestion='".$fecha."' AND
                            Deuda.JUDICIALIZADO = 'NO' AND
                            mandante_cedente.Id_Mandante='".$idMandante."' AND
                            Ges.resultado_n3 in (1059,1055)
                        ORDER BY Ges.fechahora DESC
                        ) tb1
                    GROUP BY tb1.Folio";
                    $Acuerdos = $db->select($SqlAcuerdos);
                    foreach($Acuerdos as $Acuerdo){
                        $ArrayTmp = array();
                        $Ejecutivo = $Acuerdo["Ejecutivo"];
                        $CodOficina = $Acuerdo["CodOficina"];
                        $Oficina = $Acuerdo["Oficina"];
                        $Folio = $Acuerdo["Folio"];
                        $Rut = $Acuerdo["Rut"];
                        $DeudaTotal = $Acuerdo["DeudaTotal"];
                        $DeudaCapital = $Acuerdo["DeudaCapital"];
                        $Abono = $Acuerdo["Abono"];
                        $Cuotas_convenio = $Acuerdo["Cuotas_convenio"];
                        $TelefonoCliente = $Acuerdo["TelefonoCliente"];
                        $DiaDeVisita = $Acuerdo["DiaDeVisita"];
                        $MailContacto = $Acuerdo["MailContacto"];
                        $TipoDePago = $Acuerdo["TipoDePago"];
                        $IdTipoDePago = $Acuerdo["IdTipoDePago"];
                        $Descuento_convenio = $Acuerdo["Descuento_convenio"];
                        $Descuento_pago_total = $Acuerdo["Descuento_pago_total"];
        
                        $Abono = $IdTipoDePago == "1055" ? 0 : $Abono;
        
                        $ResultanteAbono = $DeudaCapital - $Abono;
                        $PorcentajeResultante = 0;
                        if(($IdTipoDePago == "1059") && (strrpos($Descuento_convenio,"Desc") != -1)){
                            $PorcentajeResultante = substr($Descuento_convenio, - 2);
                        }
                        if(($IdTipoDePago == "1055") && (strrpos($Descuento_pago_total,"Desc") != -1)){
                            $PorcentajeResultante = substr($Descuento_pago_total, - 2);
                        }
                        $PorcentajeResultante = is_numeric($PorcentajeResultante) ? $PorcentajeResultante : 0;
                        $APagar = $ResultanteAbono - ($ResultanteAbono * ($PorcentajeResultante / 100));
                        $ValorCuotas = $APagar / $Cuotas_convenio;
        
                        $ArrayTmp["Ejecutivo"] = $Ejecutivo;
                        $ArrayTmp["CodOficina"] = $CodOficina;
                        $ArrayTmp["Oficina"] = $Oficina;
                        $ArrayTmp["Folio"] = $Folio;
                        $ArrayTmp["Rut"] = $Rut;
                        $ArrayTmp["DeudaTotal"] = "$ ".number_format($DeudaTotal,0,',','.');
                        $ArrayTmp["DeudaCapital"] = "$ ".number_format($DeudaCapital,0,',','.');
                        $ArrayTmp["Abono"] = $Abono;
                        $ArrayTmp["Resultante"] = "$ ".number_format($ResultanteAbono,0,',','.');
                        $ArrayTmp["PorcentajeResultante"] = $PorcentajeResultante."%";
                        $ArrayTmp["APagar"] = "$ ".number_format($APagar,0,',','.');
                        $ArrayTmp["Cuotas"] = $Cuotas_convenio;
                        $ArrayTmp["ValorCuotas"] = "$ ".number_format($ValorCuotas,0,',','.');
                        $ArrayTmp["Telefono"] = $TelefonoCliente;
                        $ArrayTmp["DiaVisita"] = date("d-m-Y",strtotime($DiaDeVisita));
                        $ArrayTmp["Mail"] = $MailContacto;
                        $ArrayTmp["TipoDePago"] = $TipoDePago;
        
                        array_push($ToReturn["Acuerdos"],$ArrayTmp);
                    }
                    $Headers = array();
                    array_push($Headers,"Ejecutivo");
                    array_push($Headers,"Oficina");
                    array_push($Headers,"Folio");
                    array_push($Headers,"Rut");
                    array_push($Headers,"Deuda Total");
                    array_push($Headers,"Deuda Capital");
                    array_push($Headers,"Abono");
                    array_push($Headers,"Resultante");
                    array_push($Headers,"Porcentaje Resultante");
                    array_push($Headers,"A Pagar");
                    array_push($Headers,"Cuotas");
                    array_push($Headers,"Valor Cuotas");
                    array_push($Headers,"Telefono");
                    array_push($Headers,"Dia De Visita");
                    array_push($Headers,"Mail");
                    array_push($Headers,"Tipo De Pago");
                    $Fields = array();
                    array_push($Fields,array("data"=>"Ejecutivo"));
                    array_push($Fields,array("data"=>"Oficina"));
                    array_push($Fields,array("data"=>"Folio"));
                    array_push($Fields,array("data"=>"Rut"));
                    array_push($Fields,array("data"=>"DeudaTotal"));
                    array_push($Fields,array("data"=>"DeudaCapital"));
                    array_push($Fields,array("data"=>"Abono"));
                    array_push($Fields,array("data"=>"Resultante"));
                    array_push($Fields,array("data"=>"PorcentajeResultante"));
                    array_push($Fields,array("data"=>"APagar"));
                    array_push($Fields,array("data"=>"Cuotas"));
                    array_push($Fields,array("data"=>"ValorCuotas"));
                    array_push($Fields,array("data"=>"Telefono"));
                    array_push($Fields,array("data"=>"DiaVisita"));
                    array_push($Fields,array("data"=>"Mail"));
                    array_push($Fields,array("data"=>"TipoDePago"));
                break;
                case "mensual":
                    $SqlAcuerdos = "";
                break;
            }
            $ToReturn["Fields"] = $Fields;
            $ToReturn["Headers"] = $Headers;
            $ToReturn["Table"] = "tablaAcuerdos";
            return $ToReturn;
        }
        function downloadRepros($idMandante,$tipoRepros,$fecha){
            ob_start();
            $db = new DB();
            $Reprogramaciones = $this->getReprogramaciones($tipoRepros,$idMandante,$fecha);
            $Headers = $Reprogramaciones["Headers"];
            $Fields = $Reprogramaciones["Fields"];
            $Reprogramaciones = $Reprogramaciones["Reprogramaciones"];
            $Rows = "";
            foreach($Headers as $Header){
                $Header = utf8_encode($Header);
                $Header = str_replace(";","",$Header);
                $Header = str_replace("\n","",$Header);
                $Header = str_replace("\r","",$Header);
                $Rows .= $Header.";";
            }
            $Rows .= "\r\n";
            //$Rows .= "<br>";
            foreach($Reprogramaciones as $Reprogramacion){
                
                foreach($Fields as $Field){
                    $Value = $Reprogramacion[$Field["data"]];
                    //$Value = utf8_encode($Value);
                    if(is_numeric($Value)){
                        $Value = round($Value);
                    }else{
                        $Value = utf8_decode($Value);
                    }
					$Value = str_replace(";","",$Value);
					$Value = str_replace("\n","",$Value);
					$Value = str_replace("\r","",$Value);
					$Rows .= $Value.";";
                }
                $Rows .= "\r\n";
                //$Rows .= "<br>";
            }
            echo $Rows;
            header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="REPROGRAMACIONES '.date("dmY",strtotime($fecha)).'.csv"');
			header('Cache-Control: max-age=0');
        }
        function downloadAcuerdos($idMandante,$tipoAcuerdos,$fecha){
            ob_start();
            $db = new DB();
            $Acuerdos = $this->getAcuerdosCastigo($tipoAcuerdos,$idMandante,$fecha);
            $Headers = $Acuerdos["Headers"];
            $Fields = $Acuerdos["Fields"];
            $Acuerdos = $Acuerdos["Acuerdos"];
            $Rows = "";
            foreach($Headers as $Header){
                $Header = utf8_encode($Header);
                $Header = str_replace(";","",$Header);
                $Header = str_replace("\n","",$Header);
                $Header = str_replace("\r","",$Header);
                $Rows .= $Header.";";
            }
            $Rows .= "\r\n";
            //$Rows .= "<br>";
            foreach($Acuerdos as $Acuerdo){
                
                foreach($Fields as $Field){
                    $Value = $Acuerdo[$Field["data"]];
                    //$Value = utf8_encode($Value);
                    if(is_numeric($Value)){
                        $Value = round($Value);
                    }else{
                        $Value = utf8_decode($Value);
                    }
					$Value = str_replace(";","",$Value);
					$Value = str_replace("\n","",$Value);
					$Value = str_replace("\r","",$Value);
					$Rows .= $Value.";";
                }
                $Rows .= "\r\n";
                //$Rows .= "<br>";
            }
            echo $Rows;
            header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="ACUERDOS DE CASTIGO '.date("dmY",strtotime($fecha)).'.csv"');
			header('Cache-Control: max-age=0');
        }
        function sendRepros($idMandante,$tipoRepros,$fecha){
            $db = new DB();
            $Reprogramaciones = $this->getReprogramaciones($tipoRepros,$idMandante,$fecha);
            $Headers = $Reprogramaciones["Headers"];
            $Fields = $Reprogramaciones["Fields"];
            unset($Fields[0]);
            unset($Headers[0]);
            unset($Fields[1]);
            unset($Headers[1]);
            $Reprogramaciones = $Reprogramaciones["Reprogramaciones"];

            $SqlConfig = "select * from config_derivaciones";
            $Config = $db->select($SqlConfig);
            $Config = $Config[0];
            
            $ReprogramacionesOficinas = array();
            foreach($Reprogramaciones as $Reprogramacion){
                $CodOficina = $Reprogramacion["CodOficina"];
                if(!isset($ReprogramacionesOficinas[$CodOficina])){
                    $ReprogramacionesOficinas[$CodOficina] = array();
                }
                array_push($ReprogramacionesOficinas[$CodOficina],$Reprogramacion);
            }
            foreach($ReprogramacionesOficinas as $CodOficina => $Oficina){
                $TablaHTML = "";
                $TablaHTML .= "<table style='border-collapse:collapse;border-spacing:0;border-color:#aabcfe'>";
                    $TablaHTML .= "<thead>";
                        $TablaHTML .= "<tr>";
                            foreach($Headers as $Header){
                                $TablaHTML .= "<th style='font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#aabcfe;color:#000000;background-color:#BDD7EE;text-align:left;vertical-align:top'>".$Header."</th>";
                            }
                        $TablaHTML .= "</tr>";
                    $TablaHTML .= "</thead>";
                    $TablaHTML .= "<tbody>";
                        foreach($Oficina as $Data){
                            $TablaHTML .= "<tr>";
                            foreach($Fields as $Field){
                                $TablaHTML .= "<td style='font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#aabcfe;color:#000000;background-color:#ffffff;text-align:left;vertical-align:top'>".$Data[$Field["data"]]."</td>";
                            }
                            $TablaHTML .= "</tr>";
                        }
                    $TablaHTML .= "</tbody>";
                $TablaHTML .= "</table>";
                $Content = $Config["htmlReprosDiaria"];

                $Content = str_replace("[TABLA]",$TablaHTML,$Content);
                $Content = html_entity_decode($Content);
                $Mail = new Email();

                $SqlSucursal = "select * from Oficinas_Derivaciones where cod='".$CodOficina."'";
                $Sucursal = $db->select($SqlSucursal);
                $Sucursal = $Sucursal[0];
                $NombreSucursal = strtoupper($Sucursal["Nombre"]);
                $CorreoNormalizacion = $Sucursal["Correo"];
                $CorreoAgente = $Sucursal["CorreoAgente"];
                $AgentesCC = $CorreoAgente.";".$Config["correosCC"];
                $Asunto = "COMPROMISO DE REPROGRAMACION - ".$NombreSucursal;
                /* echo "<br><br>";
                echo "Asunto: " . $Asunto."<br>";
                echo "Correos Normalizacion: " . $CorreoNormalizacion."<br>";
                echo "Correos Agentes: " . $AgentesCC."<br>";
                //echo $Content;
                echo "<br><br>"; */
                //echo $Content;

                $Mail->SendMailGeneral($Content,$Asunto,$CorreoNormalizacion,$_SESSION['cedente'],"0",$AgentesCC);
            }
        }
        function sendAcuerdos($idMandante,$tipoAcuerdos,$fecha){
            $db = new DB();
            $Acuerdos = $this->getAcuerdosCastigo($tipoAcuerdos,$idMandante,$fecha);
            $Headers = $Acuerdos["Headers"];
            $Fields = $Acuerdos["Fields"];
            unset($Fields[0]);
            unset($Headers[0]);
            unset($Fields[1]);
            unset($Headers[1]);
            $Acuerdos = $Acuerdos["Acuerdos"];

            $SqlConfig = "select * from config_derivaciones";
            $Config = $db->select($SqlConfig);
            $Config = $Config[0];
            
            $AcuerdosOficinas = array();
            foreach($Acuerdos as $Acuerdo){
                $CodOficina = $Acuerdo["CodOficina"];
                if(!isset($AcuerdosOficinas[$CodOficina])){
                    $AcuerdosOficinas[$CodOficina] = array();
                }
                array_push($AcuerdosOficinas[$CodOficina],$Acuerdo);
            }
            foreach($AcuerdosOficinas as $CodOficina => $Oficina){
                $TablaHTML = "";
                $TablaHTML .= "<table style='border-collapse:collapse;border-spacing:0;border-color:#aabcfe'>";
                    $TablaHTML .= "<thead>";
                        $TablaHTML .= "<tr>";
                            foreach($Headers as $Header){
                                $TablaHTML .= "<th style='font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#aabcfe;color:#000000;background-color:#BDD7EE;text-align:left;vertical-align:top'>".$Header."</th>";
                            }
                        $TablaHTML .= "</tr>";
                    $TablaHTML .= "</thead>";
                    $TablaHTML .= "<tbody>";
                        foreach($Oficina as $Data){
                            $TablaHTML .= "<tr>";
                            foreach($Fields as $Field){
                                $TablaHTML .= "<td style='font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#aabcfe;color:#000000;background-color:#ffffff;text-align:left;vertical-align:top'>".$Data[$Field["data"]]."</td>";
                            }
                            $TablaHTML .= "</tr>";
                        }
                    $TablaHTML .= "</tbody>";
                $TablaHTML .= "</table>";
                $Content = $Config["htmlAcuerdosDiaria"];

                $Content = str_replace("[TABLA]",$TablaHTML,$Content);
                $Content = html_entity_decode($Content);
                $Mail = new Email();

                $SqlSucursal = "select * from Oficinas_Derivaciones where cod='".$CodOficina."'";
                $Sucursal = $db->select($SqlSucursal);
                $Sucursal = $Sucursal[0];
                $NombreSucursal = strtoupper($Sucursal["Nombre"]);
                $CorreoNormalizacion = $Sucursal["Correo"];
                $CorreoAgente = $Sucursal["CorreoAgente"];
                $AgentesCC = $CorreoAgente.";".$Config["correosCC"];
                $Asunto = "ACUERDOS DE CASTIGO - ".$NombreSucursal;
                        //echo "<br><br>";
                        //echo "Asunto: " . $Asunto."<br>";
                        //echo "Correos Normalizacion: " . $CorreoNormalizacion."<br>";
                        //echo "Correos Agentes: " . $AgentesCC."<br>";
                        //echo $Content;
                        //echo "<br><br>";
                //echo $Content;

                $Mail->SendMailGeneral($Content,$Asunto,$CorreoNormalizacion,$_SESSION['cedente'],"0",$AgentesCC);
            }
        }
        function getCesantias($TipoCesantia,$idMandante,$fechaStart,$fechaEnd){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["Fields"] = array();
            $ToReturn["Headers"] = array();
            $ToReturn["Tabla"] = "";
            switch($TipoCesantia){
                case "activo":
                    $SqlCesantias = "
                    SELECT
                        *
                    FROM
                    (
                        select
                            SUBSTRING(D.CARTERA,locate('_',D.CARTERA) + 1,9999) as Cartera,
                            D.Rutdv as Rut,
                            (select OD.Nombre from respuestas_campos_gestion RCG LEFT JOIN Oficinas_Derivaciones OD on OD.cod = SUBSTRING(RCG.Valor,locate('-',RCG.Valor) + 1,9999) where RCG.id_gestion=Ges.id_gestion and RCG.id_campo = '8') as Sucursal,
                            Ges.observacion as Observacion,
                            '' as FechaEnvio,
                            Ges.nombre_ejecutivo as Ejecutivo,
                            '' as Respuesta
                        from
                            gestion_ult_trimestre Ges
                                INNER JOIN respuestas_campos_gestion RGes on RGes.id_gestion = Ges.id_gestion and RGes.id_campo='15' and SUBSTRING(RGes.Valor,locate('-',RGes.Valor) + 1,9999) = '1'
                                INNER JOIN Deuda D on D.Rut = Ges.rut_cliente
                                INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = Ges.cedente
                        WHERE
                            Ges.fecha_gestion between '".$fechaStart."' and '".$fechaEnd."' AND
                            mandante_cedente.Id_Mandante='".$idMandante."'
                        ORDER BY
                            Ges.fechahora DESC
                    ) tb1
                    GROUP BY
                        Rut";
                    
                    $Headers = array();
                    array_push($Headers,"Cartera");
                    array_push($Headers,"Rut");
                    array_push($Headers,"Sucursal");
                    array_push($Headers,"Observacion");
                    array_push($Headers,"Fecha de Envio");
                    array_push($Headers,"Ejecutivo");
                    array_push($Headers,"Respuesta");
                    
                    
                    $Fields = array();
                    array_push($Fields,array("data"=>"Cartera"));
                    array_push($Fields,array("data"=>"Rut"));
                    array_push($Fields,array("data"=>"Sucursal"));
                    array_push($Fields,array("data"=>"Observacion"));
                    array_push($Fields,array("data"=>"FechaEnvio"));
                    array_push($Fields,array("data"=>"Ejecutivo"));
                    array_push($Fields,array("data"=>"Respuesta"));
                    
                    $ToReturn["Fields"] = $Fields;
                    $ToReturn["Headers"] = $Headers;
                    $ToReturn["Table"] = "tablaActivoCesantia";
                break;
                case "activara":
                    $SqlCesantias = "
                    SELECT
                        *
                    FROM
                    (
                        select
                            SUBSTRING(D.CARTERA,locate('_',D.CARTERA) + 1,9999) as Cartera,
                            D.Rutdv as Rut,
                            (select OD.Nombre from respuestas_campos_gestion RCG LEFT JOIN Oficinas_Derivaciones OD on OD.cod = SUBSTRING(RCG.Valor,locate('-',RCG.Valor) + 1,9999) where RCG.id_gestion=Ges.id_gestion and RCG.id_campo = '8') as Sucursal,
                            Ges.observacion as Observacion,
                            DATE_FORMAT(Ges.fechaAgendamiento,'%d/%m%/%Y') as FechaVisita,
                            Ges.nombre_ejecutivo as Ejecutivo
                        from
                            gestion_ult_trimestre Ges
                                INNER JOIN respuestas_campos_gestion RGes on RGes.id_gestion = Ges.id_gestion and RGes.id_campo='15' and SUBSTRING(RGes.Valor,locate('-',RGes.Valor) + 1,9999) = '2'
                                INNER JOIN Deuda D on D.Rut = Ges.rut_cliente
                                INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = Ges.cedente
                        WHERE
                            Ges.fecha_gestion between '".$fechaStart."' and '".$fechaEnd."' AND
                            mandante_cedente.Id_Mandante='".$idMandante."'
                        ORDER BY
                            Ges.fechahora DESC
                    ) tb1
                    GROUP BY
                        Rut";
                        
                    $Headers = array();
                    array_push($Headers,"Cartera");
                    array_push($Headers,"Rut");
                    array_push($Headers,"Sucursal");
                    array_push($Headers,"Observacion");
                    array_push($Headers,"Fecha de Visita");
                    array_push($Headers,"Ejecutivo");
                    
                    
                    $Fields = array();
                    array_push($Fields,array("data"=>"Cartera"));
                    array_push($Fields,array("data"=>"Rut"));
                    array_push($Fields,array("data"=>"Sucursal"));
                    array_push($Fields,array("data"=>"Observacion"));
                    array_push($Fields,array("data"=>"FechaVisita"));
                    array_push($Fields,array("data"=>"Ejecutivo"));
                    
                    $ToReturn["Fields"] = $Fields;
                    $ToReturn["Headers"] = $Headers;
                    $ToReturn["Table"] = "tablaActivaraCesantia";
                break;
            }
            $Cesantias = $db->select($SqlCesantias);
            $ToReturn["Cesantias"] = $Cesantias;
            return $ToReturn;
        }
        function downloadCesantias($idMandante,$tipoCesantia,$fechaStart,$fechaEnd){
            ob_start();
            $db = new DB();
            $Cesantias = $this->getCesantias($tipoCesantia,$idMandante,$fechaStart,$fechaEnd);
            $Headers = $Cesantias["Headers"];
            $Fields = $Cesantias["Fields"];
            $Cesantias = $Cesantias["Cesantias"];
            $Rows = "";
            switch($tipoCesantia){
                case "activo":
                    $nombreArchivo = "HISTORIAL DE RUTS QUE TIENEN ACTIVO SEGURO DE CESANTIA - ";
                break;
                case "activara":
                    $nombreArchivo = "HISTORIAL DE RUTS QUE ACTIVARAN SEGURO DE CESANTIA - ";
                break;
            }
            foreach($Headers as $Header){
                $Header = utf8_encode($Header);
                $Header = str_replace(";","",$Header);
                $Header = str_replace("\n","",$Header);
                $Header = str_replace("\r","",$Header);
                $Rows .= $Header.";";
            }
            $Rows .= "\r\n";
            //$Rows .= "<br>";
            foreach($Cesantias as $Cesantia){
                
                foreach($Fields as $Field){
                    $Value = $Cesantia[$Field["data"]];
                    //$Value = utf8_encode($Value);
                    if(is_numeric($Value)){
                        $Value = round($Value);
                    }else{
                        $Value = utf8_decode($Value);
                    }
					$Value = str_replace(";","",$Value);
					$Value = str_replace("\n","",$Value);
					$Value = str_replace("\r","",$Value);
					$Rows .= $Value.";";
                }
                $Rows .= "\r\n";
                //$Rows .= "<br>";
            }
            echo $Rows;
            header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="'.$nombreArchivo.''.date("dmY",strtotime($fechaStart)).' al '.date("dmY",strtotime($fechaEnd)).'.csv"');
			header('Cache-Control: max-age=0');
        }
        function getReclamos($idMandante,$fechaStart,$fechaEnd){
            $db = new DB();
            $ToReturn = array();
            $ToReturn["Fields"] = array();
            $ToReturn["Headers"] = array();
            $ToReturn["Tabla"] = "";
            
            $SqlReclamos = "
            SELECT
                *
            FROM
            (
                SELECT
                    Ges.rut_cliente as Rut,
                    D.CARTERA as Cartera,
                    Ges.fono_discado as Fono,
                    DATE_FORMAT(Res.Valor,'%d-%m-%Y') as Fecha,
                    (select OD.Nombre from respuestas_campos_gestion RCG LEFT JOIN Oficinas_Derivaciones OD on OD.cod = SUBSTRING(RCG.Valor,locate('-',RCG.Valor) + 1,9999) where RCG.id_gestion=Ges.id_gestion and RCG.id_campo = '8') as Sucursal,
                    Ges.observacion as Observacion,
                    DATE_FORMAT(Ges.fecha_gestion,'%d-%m-%Y') as Gestion,
                    '' as Respuesta,
                    '' as RespuestaSucursal
                FROM
                gestion_ult_trimestre Ges
                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = Ges.cedente
                    INNER JOIN Deuda D on D.Rut = Ges.rut_cliente AND D.Id_Cedente = mandante_cedente.Id_Cedente
                    INNER JOIN respuestas_campos_gestion Res on Res.id_gestion = Ges.id_gestion AND Res.id_campo='16'
                WHERE
                    Ges.fecha_gestion BETWEEN '".$fechaStart."' and '".$fechaEnd."' AND
                    mandante_cedente.Id_Mandante='".$idMandante."'
                ORDER BY
                    Ges.fechahora DESC
            ) tb1
            GROUP BY
                Rut";
            
            $Headers = array();
            array_push($Headers,"Rut");
            array_push($Headers,"Cartera");
            array_push($Headers,"Fono");
            array_push($Headers,"Fecha");
            array_push($Headers,"Sucursal");
            array_push($Headers,"Observacion");
            array_push($Headers,"Gestion");
            array_push($Headers,"Respuesta");
            array_push($Headers,"Respuesta Sucursal");
            
            
            $Fields = array();
            array_push($Fields,array("data"=>"Rut"));
            array_push($Fields,array("data"=>"Cartera"));
            array_push($Fields,array("data"=>"Fono"));
            array_push($Fields,array("data"=>"Fecha"));
            array_push($Fields,array("data"=>"Sucursal"));
            array_push($Fields,array("data"=>"Observacion"));
            array_push($Fields,array("data"=>"Gestion"));
            array_push($Fields,array("data"=>"Respuesta"));
            array_push($Fields,array("data"=>"RespuestaSucursal"));
            
            $ToReturn["Fields"] = $Fields;
            $ToReturn["Headers"] = $Headers;
            $ToReturn["Table"] = "tablaReclamos";
            $Reclamos = $db->select($SqlReclamos);
            $ToReturn["Reclamos"] = $Reclamos;
            return $ToReturn;
        }
        function downloadReclamos($idMandante,$fechaStart,$fechaEnd){
            ob_start();
            $db = new DB();
            $Reclamos = $this->getReclamos($idMandante,$fechaStart,$fechaEnd);
            $Headers = $Reclamos["Headers"];
            $Fields = $Reclamos["Fields"];
            $Reclamos = $Reclamos["Reclamos"];
            $Rows = "";
            
            $nombreArchivo = "RECLAMOS - ";

            foreach($Headers as $Header){
                $Header = utf8_encode($Header);
                $Header = str_replace(";","",$Header);
                $Header = str_replace("\n","",$Header);
                $Header = str_replace("\r","",$Header);
                $Rows .= $Header.";";
            }
            $Rows .= "\r\n";
            //$Rows .= "<br>";
            foreach($Reclamos as $Reclamo){
                
                foreach($Fields as $Field){
                    $Value = $Reclamo[$Field["data"]];
                    //$Value = utf8_encode($Value);
                    if(is_numeric($Value)){
                        $Value = round($Value);
                    }else{
                        $Value = utf8_decode($Value);
                    }
					$Value = str_replace(";","",$Value);
					$Value = str_replace("\n","",$Value);
					$Value = str_replace("\r","",$Value);
					$Rows .= $Value.";";
                }
                $Rows .= "\r\n";
                //$Rows .= "<br>";
            }
            echo $Rows;
            header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="'.$nombreArchivo.''.date("dmY",strtotime($fechaStart)).' al '.date("dmY",strtotime($fechaEnd)).'.csv"');
			header('Cache-Control: max-age=0');
        }
        function getCompromisosHites($idMandante,$fechaStart,$fechaEnd){
            
            $fechaHasta = strtotime('-1 day',strtotime($fechaStart));
            $fechaHasta = date ('Ymd',$fechaHasta);

            $CedenteClass = new Cedente();

            $Cedente = $CedenteClass->mostrarCedente($_SESSION["cedente"]);
            $Cedente = $Cedente[0];

            $fechaInicioPeriodo = $Cedente["inicio_periodo"];
            $fechaInicioPeriodo = date("Ymd",strtotime($fechaInicioPeriodo));
            
            $db = new DB();
            $ToReturn = array();
            $ToReturn["Fields"] = array();
            $ToReturn["Headers"] = array();
            $ToReturn["Tabla"] = "";
            
            $SqlCompromisos = "
            SELECT
                D.Rut as Rut,
                D.Tramo_Morosidad as Tramo,
                CASE 
                    WHEN D.Deuda_AS400 = 0 THEN 0 ELSE
                        CASE WHEN D.Deuda_AS400 > 1 and D.Deuda_AS400 < 1000 THEN 1000 ELSE
                            CASE WHEN D.Deuda_AS400 > 1000 and D.Deuda_AS400 < 10000 THEN IF(SUBSTR(D.Deuda_AS400,2,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,1)+1,'000'),D.Deuda_AS400) ELSE
                                CASE WHEN D.Deuda_AS400 > 10000 and D.Deuda_AS400 < 100000 THEN IF(SUBSTR(D.Deuda_AS400,3,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,2)+1,'000'),D.Deuda_AS400) ELSE
                                    CASE WHEN D.Deuda_AS400 > 10000 and D.Deuda_AS400 < 100000 THEN IF(SUBSTR(D.Deuda_AS400,4,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,3)+1,'000'),D.Deuda_AS400) ELSE
                                        CASE WHEN D.Deuda_AS400 > 100000 and D.Deuda_AS400 < 1000000 THEN IF(SUBSTR(D.Deuda_AS400,5,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,3)+1,'000'),D.Deuda_AS400) ELSE
                                            CASE WHEN D.Deuda_AS400 > 1000000 and D.Deuda_AS400 < 10000000 THEN IF(SUBSTR(D.Deuda_AS400,4,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,4)+1,'000'),D.Deuda_AS400) ELSE
                                                CASE WHEN D.Deuda_AS400 > 10000000 and D.Deuda_AS400 < 100000000 THEN IF(SUBSTR(D.Deuda_AS400,5,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,5)+1,'000'),D.Deuda_AS400)
                                                END
                                            END
                                        END
                                    END
                                END
                            END
                    END
                END as Monto,
                CASE 
                    WHEN D.Oferta_2x1 = 0 THEN 0 ELSE
                        CASE WHEN D.Oferta_2x1 > 1 and D.Oferta_2x1 < 1000 THEN 1000 ELSE
                            CASE WHEN D.Deuda_AS400 > 1000 and D.Deuda_AS400 < 10000 THEN IF(SUBSTR(D.Oferta_2x1,2,3) > 0,CONCAT(SUBSTR(D.Oferta_2x1,1,1)+1,'000'),D.Oferta_2x1) ELSE
                                CASE WHEN D.Oferta_2x1 > 10000 and D.Oferta_2x1 < 100000 THEN IF(SUBSTR(D.Oferta_2x1,3,3) > 0,CONCAT(SUBSTR(D.Oferta_2x1,1,2)+1,'000'),D.Oferta_2x1) ELSE
                                    CASE WHEN D.Oferta_2x1 > 10000 and D.Oferta_2x1 < 100000 THEN IF(SUBSTR(D.Oferta_2x1,4,3) > 0,CONCAT(SUBSTR(D.Oferta_2x1,1,3)+1,'000'),D.Oferta_2x1) ELSE
                                        CASE WHEN D.Oferta_2x1 > 100000 and D.Oferta_2x1 < 1000000 THEN IF(SUBSTR(D.Oferta_2x1,5,3) > 0,CONCAT(SUBSTR(D.Oferta_2x1,1,3)+1,'000'),D.Oferta_2x1) ELSE
                                            CASE WHEN D.Oferta_2x1 > 1000000 and D.Oferta_2x1 < 10000000 THEN IF(SUBSTR(D.Oferta_2x1,4,3) > 0,CONCAT(SUBSTR(D.Oferta_2x1,1,4)+1,'000'),D.Oferta_2x1) ELSE
                                                CASE WHEN D.Oferta_2x1 > 10000000 and D.Oferta_2x1 < 100000000 THEN IF(SUBSTR(D.Oferta_2x1,5,3) > 0,CONCAT(SUBSTR(D.Oferta_2x1,1,5)+1,'000'),D.Oferta_2x1)
                                                END
                                            END
                                        END
                                    END
                                END
                            END
                    END
                END as Oferta,
                Ges.fec_compromiso as FechaCompromiso,
                DATE_FORMAT(NOW(),'%d/%m/%Y') as FechaEnvio
            FROM
                gestion_ult_trimestre Ges
                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = Ges.cedente
                    INNER JOIN Deuda D on D.Rut = Ges.rut_cliente AND D.Id_Cedente = mandante_cedente.Id_Cedente
            WHERE
                mandante_cedente.Id_Mandante='".$idMandante."' AND
                mandante_cedente.Id_Cedente='".$_SESSION["cedente"]."' AND
                Ges.fecha_gestion BETWEEN '".$fechaStart."' and '".$fechaEnd."' AND
                Ges.rut_cliente not in (select rut_cliente from gestion_ult_trimestre where fecha_gestion BETWEEN '".$fechaInicioPeriodo."' and '".$fechaHasta."' and resultado_n3='1786') AND
                Ges.resultado_n3='1786'";
            
            $Headers = array();
            array_push($Headers,"Rut");
            array_push($Headers,"Tramo");
            array_push($Headers,"Monto");
            array_push($Headers,"Oferta");
            array_push($Headers,"Fecha de Compromiso");
            array_push($Headers,"Fecha de Envio");
            
            
            $Fields = array();
            array_push($Fields,array("data"=>"Rut"));
            array_push($Fields,array("data"=>"Tramo"));
            array_push($Fields,array("data"=>"Monto"));
            array_push($Fields,array("data"=>"Oferta"));
            array_push($Fields,array("data"=>"FechaCompromiso"));
            array_push($Fields,array("data"=>"FechaEnvio"));
            
            $ToReturn["Fields"] = $Fields;
            $ToReturn["Headers"] = $Headers;
            $ToReturn["Table"] = "tablaCompromisosHites";
            $Compromisos = $db->select($SqlCompromisos);
            $ToReturn["Compromisos"] = $Compromisos;
            return $ToReturn;
        }
        function downloadCompromisosHites($idMandante,$fechaStart,$fechaEnd){
            ob_start();
            $db = new DB();
            $Compromisos = $this->getCompromisosHites($idMandante,$fechaStart,$fechaEnd);
            $Headers = $Compromisos["Headers"];
            $Fields = $Compromisos["Fields"];
            $Compromisos = $Compromisos["Compromisos"];
            $Rows = "";
            
            $nombreArchivo = "COMPROMISOS - ";

            foreach($Headers as $Header){
                $Header = utf8_encode($Header);
                $Header = str_replace(";","",$Header);
                $Header = str_replace("\n","",$Header);
                $Header = str_replace("\r","",$Header);
                $Rows .= $Header.";";
            }
            $Rows .= "\r\n";
            //$Rows .= "<br>";
            foreach($Compromisos as $Compromiso){
                
                foreach($Fields as $Field){
                    $Value = $Compromiso[$Field["data"]];
                    //$Value = utf8_encode($Value);
                    if(is_numeric($Value)){
                        $Value = round($Value);
                    }else{
                        $Value = utf8_decode($Value);
                    }
					$Value = str_replace(";","",$Value);
					$Value = str_replace("\n","",$Value);
					$Value = str_replace("\r","",$Value);
					$Rows .= $Value.";";
                }
                $Rows .= "\r\n";
                //$Rows .= "<br>";
            }
            echo $Rows;
            header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="'.$nombreArchivo.''.date("dmY",strtotime($fechaStart)).' al '.date("dmY",strtotime($fechaEnd)).'.csv"');
			header('Cache-Control: max-age=0');
        }
        function getCompromisosHitesTributario($tipoCompromiso,$idMandante,$fechaStart,$fechaEnd){
            
            $fechaHasta = strtotime('-1 day',strtotime($fechaStart));
            $fechaHasta = date ('Ymd',$fechaHasta);

            $CedenteClass = new Cedente();

            $Cedente = $CedenteClass->mostrarCedente($_SESSION["cedente"]);
            $Cedente = $Cedente[0];

            $fechaInicioPeriodo = $Cedente["inicio_periodo"];
            $fechaInicioPeriodo = date("Ymd",strtotime($fechaInicioPeriodo));
            
            $db = new DB();
            $ToReturn = array();
            $ToReturn["Fields"] = array();
            $ToReturn["Headers"] = array();
            $ToReturn["Tabla"] = "";
            
            switch($tipoCompromiso){
                case "normales":
                    $SqlCompromisos = "
                    SELECT
                        D.Rut as Rut,
                        D.Ano_Castigo as AnoCastigo,
                        CASE 
                            WHEN D.Deuda_AS400 = 0 THEN 0 ELSE
                                CASE WHEN D.Deuda_AS400 > 1 and D.Deuda_AS400 < 1000 THEN 1000 ELSE
                                    CASE WHEN D.Deuda_AS400 > 1000 and D.Deuda_AS400 < 10000 THEN IF(SUBSTR(D.Deuda_AS400,2,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,1)+1,'000'),D.Deuda_AS400) ELSE
                                        CASE WHEN D.Deuda_AS400 > 10000 and D.Deuda_AS400 < 100000 THEN IF(SUBSTR(D.Deuda_AS400,3,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,2)+1,'000'),D.Deuda_AS400) ELSE
                                            CASE WHEN D.Deuda_AS400 > 10000 and D.Deuda_AS400 < 100000 THEN IF(SUBSTR(D.Deuda_AS400,4,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,3)+1,'000'),D.Deuda_AS400) ELSE
                                                CASE WHEN D.Deuda_AS400 > 100000 and D.Deuda_AS400 < 1000000 THEN IF(SUBSTR(D.Deuda_AS400,5,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,3)+1,'000'),D.Deuda_AS400) ELSE
                                                    CASE WHEN D.Deuda_AS400 > 1000000 and D.Deuda_AS400 < 10000000 THEN IF(SUBSTR(D.Deuda_AS400,4,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,4)+1,'000'),D.Deuda_AS400) ELSE
                                                        CASE WHEN D.Deuda_AS400 > 10000000 and D.Deuda_AS400 < 100000000 THEN IF(SUBSTR(D.Deuda_AS400,5,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,5)+1,'000'),D.Deuda_AS400)
                                                        END
                                                    END
                                                END
                                            END
                                        END
                                    END
                            END
                        END as Monto,
                        CASE 
                            WHEN D.A_pagar_pago_total = 0 THEN 0 ELSE
                                CASE WHEN D.A_pagar_pago_total > 1 and D.A_pagar_pago_total < 1000 THEN 1000 ELSE
                                    CASE WHEN D.A_pagar_pago_total > 1000 and D.A_pagar_pago_total < 10000 THEN IF(SUBSTR(D.A_pagar_pago_total,2,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total,1,1)+1,'000'),D.A_pagar_pago_total) ELSE
                                        CASE WHEN D.A_pagar_pago_total > 10000 and D.A_pagar_pago_total < 100000 THEN IF(SUBSTR(D.A_pagar_pago_total,3,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total,1,2)+1,'000'),D.A_pagar_pago_total) ELSE
                                            CASE WHEN D.A_pagar_pago_total > 10000 and D.A_pagar_pago_total < 100000 THEN IF(SUBSTR(D.A_pagar_pago_total,4,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total,1,3)+1,'000'),D.A_pagar_pago_total) ELSE
                                                CASE WHEN D.A_pagar_pago_total > 100000 and D.A_pagar_pago_total < 1000000 THEN IF(SUBSTR(D.A_pagar_pago_total,5,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total,1,3)+1,'000'),D.A_pagar_pago_total) ELSE
                                                    CASE WHEN D.A_pagar_pago_total > 1000000 and D.A_pagar_pago_total < 10000000 THEN IF(SUBSTR(D.A_pagar_pago_total,4,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total,1,4)+1,'000'),D.A_pagar_pago_total) ELSE
                                                        CASE WHEN D.A_pagar_pago_total > 10000000 and D.A_pagar_pago_total < 100000000 THEN IF(SUBSTR(D.A_pagar_pago_total,5,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total,1,5)+1,'000'),D.A_pagar_pago_total)
                                                        END
                                                    END
                                                END
                                            END
                                        END
                                    END
                            END
                        END as PagoTotal,
                        Ges.fec_compromiso as FechaCompromiso,
                        DATE_FORMAT(NOW(),'%d/%m/%Y') as FechaEnvio
                        FROM
                        gestion_ult_trimestre Ges
                            INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = Ges.cedente
                            INNER JOIN Deuda D on D.Rut = Ges.rut_cliente AND D.Id_Cedente = mandante_cedente.Id_Cedente
                            INNER JOIN respuestas_campos_gestion R on R.id_gestion = Ges.id_gestion AND R.Valor='Normal-1'
                    WHERE
                        mandante_cedente.Id_Mandante='".$idMandante."' AND
                        mandante_cedente.Id_Cedente='".$_SESSION["cedente"]."' AND
                        Ges.fecha_gestion BETWEEN '".$fechaStart."' and '".$fechaEnd."' AND
                        Ges.rut_cliente not in (select rut_cliente from gestion_ult_trimestre where fecha_gestion BETWEEN '".$fechaInicioPeriodo."' and '".$fechaHasta."' and resultado_n3='1829') AND
                        Ges.resultado_n3='1829'";
                    
                    $Headers = array();
                    array_push($Headers,"Rut");
                    array_push($Headers,"Ano Castigo");
                    array_push($Headers,"Monto");
                    array_push($Headers,"Pago Total");
                    array_push($Headers,"Fecha de Compromiso");
                    array_push($Headers,"Fecha de Envio");
                    
                    
                    $Fields = array();
                    array_push($Fields,array("data"=>"Rut"));
                    array_push($Fields,array("data"=>"AnoCastigo"));
                    array_push($Fields,array("data"=>"Monto"));
                    array_push($Fields,array("data"=>"PagoTotal"));
                    array_push($Fields,array("data"=>"FechaCompromiso"));
                    array_push($Fields,array("data"=>"FechaEnvio"));
                    
                    $ToReturn["Fields"] = $Fields;
                    $ToReturn["Headers"] = $Headers;
                    $ToReturn["Table"] = "tablaCompromisosHitesTributarioNormal";
                    $Compromisos = $db->select($SqlCompromisos);
                    $ToReturn["Compromisos"] = $Compromisos;
                break;
                case "especiales":

                    $SqlCompromisos = "
                    SELECT
                        D.Rut as Rut,
                        D.Ano_Castigo as AnoCastigo,
                        CASE 
                            WHEN D.Deuda_AS400 = 0 THEN 0 ELSE
                                CASE WHEN D.Deuda_AS400 > 1 and D.Deuda_AS400 < 1000 THEN 1000 ELSE
                                    CASE WHEN D.Deuda_AS400 > 1000 and D.Deuda_AS400 < 10000 THEN IF(SUBSTR(D.Deuda_AS400,2,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,1)+1,'000'),D.Deuda_AS400) ELSE
                                        CASE WHEN D.Deuda_AS400 > 10000 and D.Deuda_AS400 < 100000 THEN IF(SUBSTR(D.Deuda_AS400,3,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,2)+1,'000'),D.Deuda_AS400) ELSE
                                            CASE WHEN D.Deuda_AS400 > 10000 and D.Deuda_AS400 < 100000 THEN IF(SUBSTR(D.Deuda_AS400,4,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,3)+1,'000'),D.Deuda_AS400) ELSE
                                                CASE WHEN D.Deuda_AS400 > 100000 and D.Deuda_AS400 < 1000000 THEN IF(SUBSTR(D.Deuda_AS400,5,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,3)+1,'000'),D.Deuda_AS400) ELSE
                                                    CASE WHEN D.Deuda_AS400 > 1000000 and D.Deuda_AS400 < 10000000 THEN IF(SUBSTR(D.Deuda_AS400,4,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,4)+1,'000'),D.Deuda_AS400) ELSE
                                                        CASE WHEN D.Deuda_AS400 > 10000000 and D.Deuda_AS400 < 100000000 THEN IF(SUBSTR(D.Deuda_AS400,5,3) > 0,CONCAT(SUBSTR(D.Deuda_AS400,1,5)+1,'000'),D.Deuda_AS400)
                                                        END
                                                    END
                                                END
                                            END
                                        END
                                    END
                            END
                        END as Monto,
                        CASE 
                            WHEN D.A_pagar_pago_total = 0 THEN 0 ELSE
                                CASE WHEN D.A_pagar_pago_total > 1 and D.A_pagar_pago_total < 1000 THEN 1000 ELSE
                                    CASE WHEN D.A_pagar_pago_total > 1000 and D.A_pagar_pago_total < 10000 THEN IF(SUBSTR(D.A_pagar_pago_total,2,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total,1,1)+1,'000'),D.A_pagar_pago_total) ELSE
                                        CASE WHEN D.A_pagar_pago_total > 10000 and D.A_pagar_pago_total < 100000 THEN IF(SUBSTR(D.A_pagar_pago_total,3,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total,1,2)+1,'000'),D.A_pagar_pago_total) ELSE
                                            CASE WHEN D.A_pagar_pago_total > 10000 and D.A_pagar_pago_total < 100000 THEN IF(SUBSTR(D.A_pagar_pago_total,4,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total,1,3)+1,'000'),D.A_pagar_pago_total) ELSE
                                                CASE WHEN D.A_pagar_pago_total > 100000 and D.A_pagar_pago_total < 1000000 THEN IF(SUBSTR(D.A_pagar_pago_total,5,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total,1,3)+1,'000'),D.A_pagar_pago_total) ELSE
                                                    CASE WHEN D.A_pagar_pago_total > 1000000 and D.A_pagar_pago_total < 10000000 THEN IF(SUBSTR(D.A_pagar_pago_total,4,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total,1,4)+1,'000'),D.A_pagar_pago_total) ELSE
                                                        CASE WHEN D.A_pagar_pago_total > 10000000 and D.A_pagar_pago_total < 100000000 THEN IF(SUBSTR(D.A_pagar_pago_total,5,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total,1,5)+1,'000'),D.A_pagar_pago_total)
                                                        END
                                                    END
                                                END
                                            END
                                        END
                                    END
                            END
                        END as PagoTotal,
                        CASE 
                            WHEN D.A_pagar_pago_total_especial = 0 THEN 0 ELSE
                                CASE WHEN D.A_pagar_pago_total_especial > 1 and D.A_pagar_pago_total_especial < 1000 THEN 1000 ELSE
                                    CASE WHEN D.A_pagar_pago_total_especial > 1000 and D.A_pagar_pago_total_especial < 10000 THEN IF(SUBSTR(D.A_pagar_pago_total_especial,2,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total_especial,1,1)+1,'000'),D.A_pagar_pago_total_especial) ELSE
                                        CASE WHEN D.A_pagar_pago_total_especial > 10000 and D.A_pagar_pago_total_especial < 100000 THEN IF(SUBSTR(D.A_pagar_pago_total_especial,3,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total_especial,1,2)+1,'000'),D.A_pagar_pago_total_especial) ELSE
                                            CASE WHEN D.A_pagar_pago_total_especial > 10000 and D.A_pagar_pago_total_especial < 100000 THEN IF(SUBSTR(D.A_pagar_pago_total_especial,4,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total_especial,1,3)+1,'000'),D.A_pagar_pago_total_especial) ELSE
                                                CASE WHEN D.A_pagar_pago_total_especial > 100000 and D.A_pagar_pago_total_especial < 1000000 THEN IF(SUBSTR(D.A_pagar_pago_total_especial,5,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total_especial,1,3)+1,'000'),D.A_pagar_pago_total_especial) ELSE
                                                    CASE WHEN D.A_pagar_pago_total_especial > 1000000 and D.A_pagar_pago_total_especial < 10000000 THEN IF(SUBSTR(D.A_pagar_pago_total_especial,4,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total_especial,1,4)+1,'000'),D.A_pagar_pago_total_especial) ELSE
                                                        CASE WHEN D.A_pagar_pago_total_especial > 10000000 and D.A_pagar_pago_total_especial < 100000000 THEN IF(SUBSTR(D.A_pagar_pago_total_especial,5,3) > 0,CONCAT(SUBSTR(D.A_pagar_pago_total_especial,1,5)+1,'000'),D.A_pagar_pago_total_especial)
                                                        END
                                                    END
                                                END
                                            END
                                        END
                                    END
                            END
                        END as PagoTotalEspecial,
                        Ges.fec_compromiso as FechaCompromiso,
                        DATE_FORMAT(NOW(),'%d/%m/%Y') as FechaEnvio
                        FROM
                        gestion_ult_trimestre Ges
                            INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = Ges.cedente
                            INNER JOIN Deuda D on D.Rut = Ges.rut_cliente AND D.Id_Cedente = mandante_cedente.Id_Cedente
                            INNER JOIN respuestas_campos_gestion R on R.id_gestion = Ges.id_gestion AND R.Valor='Especial-2'
                    WHERE
                        mandante_cedente.Id_Mandante='".$idMandante."' AND
                        mandante_cedente.Id_Cedente='".$_SESSION["cedente"]."' AND
                        Ges.fecha_gestion BETWEEN '".$fechaStart."' and '".$fechaEnd."' AND
                        Ges.rut_cliente not in (select rut_cliente from gestion_ult_trimestre where fecha_gestion BETWEEN '".$fechaInicioPeriodo."' and '".$fechaHasta."' and resultado_n3='1829') AND
                        Ges.resultado_n3='1829'";
                    
                    $Headers = array();
                    array_push($Headers,"Rut");
                    array_push($Headers,"Ano Castigo");
                    array_push($Headers,"Monto");
                    array_push($Headers,"Pago Total");
                    array_push($Headers,"Pago Total Especial");
                    array_push($Headers,"Fecha de Compromiso");
                    array_push($Headers,"Fecha de Envio");
                    
                    
                    $Fields = array();
                    array_push($Fields,array("data"=>"Rut"));
                    array_push($Fields,array("data"=>"AnoCastigo"));
                    array_push($Fields,array("data"=>"Monto"));
                    array_push($Fields,array("data"=>"PagoTotal"));
                    array_push($Fields,array("data"=>"PagoTotalEspecial"));
                    array_push($Fields,array("data"=>"FechaCompromiso"));
                    array_push($Fields,array("data"=>"FechaEnvio"));
                    
                    $ToReturn["Fields"] = $Fields;
                    $ToReturn["Headers"] = $Headers;
                    $ToReturn["Table"] = "tablaCompromisosHitesTributarioEspecial";
                    $Compromisos = $db->select($SqlCompromisos);
                    $ToReturn["Compromisos"] = $Compromisos;
                break;
            }
            return $ToReturn;
        }
        function downloadCompromisosHitesTributario($tipoCompromiso,$idMandante,$fechaStart,$fechaEnd){
            ob_start();
            $db = new DB();
            $Compromisos = $this->getCompromisosHitesTributario($tipoCompromiso,$idMandante,$fechaStart,$fechaEnd);
            $Headers = $Compromisos["Headers"];
            $Fields = $Compromisos["Fields"];
            $Compromisos = $Compromisos["Compromisos"];
            $Rows = "";
            
            $nombreArchivo = "COMPROMISOS - ";

            foreach($Headers as $Header){
                $Header = utf8_encode($Header);
                $Header = str_replace(";","",$Header);
                $Header = str_replace("\n","",$Header);
                $Header = str_replace("\r","",$Header);
                $Rows .= $Header.";";
            }
            $Rows .= "\r\n";
            //$Rows .= "<br>";
            foreach($Compromisos as $Compromiso){
                
                foreach($Fields as $Field){
                    $Value = $Compromiso[$Field["data"]];
                    //$Value = utf8_encode($Value);
                    if(is_numeric($Value)){
                        $Value = round($Value);
                    }else{
                        $Value = utf8_decode($Value);
                    }
					$Value = str_replace(";","",$Value);
					$Value = str_replace("\n","",$Value);
					$Value = str_replace("\r","",$Value);
					$Rows .= $Value.";";
                }
                $Rows .= "\r\n";
                //$Rows .= "<br>";
            }
            echo $Rows;
            header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="'.$nombreArchivo.''.date("dmY",strtotime($fechaStart)).' al '.date("dmY",strtotime($fechaEnd)).'.csv"');
			header('Cache-Control: max-age=0');
        }
        function getCompromisosLaPolarCastigo($idMandante,$fechaStart,$fechaEnd){
            
            $fechaHasta = strtotime('-1 day',strtotime($fechaStart));
            $fechaHasta = date ('Ymd',$fechaHasta);

            $CedenteClass = new Cedente();

            $Cedente = $CedenteClass->mostrarCedente($_SESSION["cedente"]);
            $Cedente = $Cedente[0];

            $fechaInicioPeriodo = date("Ym")."01";
            
            $db = new DB();
            $ToReturn = array();
            $ToReturn["Fields"] = array();
            $ToReturn["Headers"] = array();
            $ToReturn["Tabla"] = "";

            /* $SqlCompromisos = "
            select
                Ges.nombre_ejecutivo as Ejecutivo,
                'Soporte' as Empresa,
                D.Tipo_tarjeta as Castigo,
                D.Rutdv as Rut,
                (select SUBSTRING(Res.Valor,1,locate(' - ',Res.Valor) - 1) from respuestas_campos_gestion Res where Res.id_gestion=Ges.id_gestion and Res.id_campo = '18') as Sucursal,
                (select SUBSTRING(Res.Valor,1,locate(' - ',Res.Valor) - 1) from respuestas_campos_gestion Res where Res.id_gestion=Ges.id_gestion and Res.id_campo = '19') as TipoCompromiso,
                Ges.fec_compromiso as FechaDePago,
                CASE
                    WHEN D.Segmento = '1. Platinium' THEN '0%'
                        ELSE CASE WHEN YEAR(D.Fecha_Castigo) <= 2013 THEN '80%'
                            ELSE CASE WHEN YEAR(D.Fecha_Castigo) = 2014 OR YEAR(D.Fecha_Castigo) = 2015 THEN '70%'
                                ELSE CASE WHEN YEAR(D.Fecha_Castigo) >= 2016 THEN '50%'
                            END
                        END
                    END
                END PorcDescSol,
                CASE
                    WHEN D.Segmento = '1. Platinium' THEN '0%'
                    ELSE CASE WHEN YEAR(D.Fecha_Castigo) = 2011 THEN '80%'
                        ELSE CASE WHEN YEAR(D.Fecha_Castigo) = 2012 THEN '80%' 
                            ELSE CASE WHEN YEAR(D.Fecha_Castigo) = 2013 THEN '70%' 
                                ELSE CASE WHEN YEAR(D.Fecha_Castigo) = 2014 THEN '60%'
                                    ELSE CASE WHEN YEAR(D.Fecha_Castigo) = 2015 THEN '50%'
                                        ELSE CASE WHEN YEAR(D.Fecha_Castigo) = 2016 THEN '30%'
                                            ELSE CASE WHEN YEAR(D.Fecha_Castigo) = 2017 THEN '25%'
                                                ELSE CASE WHEN YEAR(D.Fecha_Castigo) = 2018 THEN '20%' END
                                            END
                                        END
                                    END
                                END
                            END
                        END
                    END	
                END AS PorcDescCampana,
                YEAR(D.Fecha_Castigo) as AnoCastigo,
                D.Deuda as DeudaActual,
                CASE
                    WHEN (select SUBSTRING(Res.Valor,locate(' - ',Res.Valor) + 3,9999) from respuestas_campos_gestion Res where Res.id_gestion=Ges.id_gestion and Res.id_campo = '20') = 1 THEN D.A_pagar_pago_total
                    ELSE CASE WHEN (select SUBSTRING(Res.Valor,locate(' - ',Res.Valor) + 3,9999) from respuestas_campos_gestion Res where Res.id_gestion=Ges.id_gestion and Res.id_campo = '20') = 2 THEN D.A_pagar_pago_total_especial
                        ELSE NULL END
                END as ValorPago,
                Ges.observacion as Observacion
            From
                gestion_ult_trimestre Ges
                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = Ges.cedente
                    INNER JOIN Deuda D on D.Rut = Ges.rut_cliente AND D.Id_Cedente='255'
            WHERE
                mandante_cedente.Id_Mandante='".$idMandante."' AND
                mandante_cedente.Id_Cedente='".$_SESSION["cedente"]."' AND
                Ges.fecha_gestion BETWEEN '".$fechaStart."' and '".$fechaEnd."' AND
                Ges.resultado_n3 = '2547' AND
                Ges.rut_cliente not in (select rut_cliente from gestion_ult_trimestre where fecha_gestion BETWEEN '".$fechaInicioPeriodo."' and '".$fechaHasta."' and resultado_n3='2547')
            GROUP BY
                Ges.rut_cliente
            ORDER BY
                Ges.rut_cliente,
                Ges.fechahora DESC"; */


            $SqlCompromisos = "
                select
                    Ges.nombre_ejecutivo as Ejecutivo,
                    'Soporte' as Empresa,
                    D.Tipo_tarjeta as Castigo,
                    D.Rutdv as Rut,
                    (select SUBSTRING(Res.Valor,1,locate(' - ',Res.Valor) - 1) from respuestas_campos_gestion Res where Res.id_gestion=Ges.id_gestion and Res.id_campo = '18') as Sucursal,
                    (select SUBSTRING(Res.Valor,1,locate(' - ',Res.Valor) - 1) from respuestas_campos_gestion Res where Res.id_gestion=Ges.id_gestion and Res.id_campo = '19') as TipoCompromiso,
                    Ges.fec_compromiso as FechaDePago,
                    '' AS PorcDescSol,
                    '' AS PorcDescCampana,
                    YEAR(D.Fecha_Castigo) as AnoCastigo,
                    D.Deuda as DeudaActual,
                    CASE
                        WHEN (select SUBSTRING(Res.Valor,locate(' - ',Res.Valor) + 3,9999) from respuestas_campos_gestion Res where Res.id_gestion=Ges.id_gestion and Res.id_campo = '20') = 1 THEN D.A_pagar_pago_total
                        ELSE CASE WHEN (select SUBSTRING(Res.Valor,locate(' - ',Res.Valor) + 3,9999) from respuestas_campos_gestion Res where Res.id_gestion=Ges.id_gestion and Res.id_campo = '20') = 2 THEN D.A_pagar_pago_total_especial
                            ELSE NULL END
                    END as ValorPago,
                    Ges.observacion as Observacion
                From
                    gestion_ult_trimestre Ges
                        INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = Ges.cedente
                        INNER JOIN Deuda D on D.Rut = Ges.rut_cliente AND D.Id_Cedente='255'
                WHERE
                    mandante_cedente.Id_Mandante='".$idMandante."' AND
                    mandante_cedente.Id_Cedente='".$_SESSION["cedente"]."' AND
                    Ges.fecha_gestion BETWEEN '".$fechaStart."' and '".$fechaEnd."' AND
                    Ges.resultado_n3 = '2547' AND
                    Ges.rut_cliente not in (select rut_cliente from gestion_ult_trimestre where fecha_gestion BETWEEN '".$fechaInicioPeriodo."' and '".$fechaHasta."' and resultado_n3='2547')
                GROUP BY
                    Ges.rut_cliente
                ORDER BY
                    Ges.rut_cliente,
                    Ges.fechahora DESC";
            
            $Headers = array();
            array_push($Headers,"Ejecutivo");
            array_push($Headers,"Empresa");
            array_push($Headers,"Castigo");
            array_push($Headers,"Rut");
            array_push($Headers,"Sucursal");
            array_push($Headers,"Tipo de Compromiso");
            array_push($Headers,"Fecha De Pago");
            array_push($Headers,"% Desc Sol");
            array_push($Headers,"% Desc Campana");
            array_push($Headers,"Ano Castigo");
            array_push($Headers,"Deuda Actual");
            array_push($Headers,"Valor Pago");
            array_push($Headers,"Observacion");
            
            
            $Fields = array();
            array_push($Fields,array("data"=>"Ejecutivo"));
            array_push($Fields,array("data"=>"Empresa"));
            array_push($Fields,array("data"=>"Castigo"));
            array_push($Fields,array("data"=>"Rut"));
            array_push($Fields,array("data"=>"Sucursal"));
            array_push($Fields,array("data"=>"TipoCompromiso"));
            array_push($Fields,array("data"=>"FechaDePago"));
            array_push($Fields,array("data"=>"PorcDescSol"));
            array_push($Fields,array("data"=>"PorcDescCampana"));
            array_push($Fields,array("data"=>"AnoCastigo"));
            array_push($Fields,array("data"=>"DeudaActual"));
            array_push($Fields,array("data"=>"ValorPago"));
            array_push($Fields,array("data"=>"Observacion"));
            
            $ToReturn["Fields"] = $Fields;
            $ToReturn["Headers"] = $Headers;
            $ToReturn["Table"] = "tablaCompromisosLaPolarCastigo";
            $CompromisosTmp = $db->select($SqlCompromisos);

            $Compromisos = array();
            foreach($CompromisosTmp as $Compromiso){
                $ArrayTmp = array();
                $TipoCompromiso = $Compromiso["TipoCompromiso"];
                $AnoCastigo = (Int) $Compromiso["AnoCastigo"];
                foreach($Compromiso as $Key => $Value){
                    switch($Key){
                        case "PorcDescSol":
                            switch($TipoCompromiso){
                                case "PUT":
                                    if($AnoCastigo <= 2013){
                                        $ArrayTmp[$Key] = "90%";
                                    }
                                    if($AnoCastigo == 2014){
                                        $ArrayTmp[$Key] = "80%";
                                    }
                                    if($AnoCastigo == 2015){
                                        $ArrayTmp[$Key] = "70%";
                                    }
                                    if($AnoCastigo == 2016){
                                        $ArrayTmp[$Key] = "60%";
                                    }
                                    if($AnoCastigo == 2017){
                                        $ArrayTmp[$Key] = "40%";
                                    }
                                    if($AnoCastigo == 2018){
                                        $ArrayTmp[$Key] = "30%";
                                    }
                                    if($AnoCastigo >= 2019){
                                        $ArrayTmp[$Key] = "0%";
                                    }
                                break;
                                case "CONVENIO":
                                    if($AnoCastigo <= 2013){
                                        $ArrayTmp[$Key] = "70%";
                                    }
                                    if($AnoCastigo == 2014){
                                        $ArrayTmp[$Key] = "60%";
                                    }
                                    if($AnoCastigo == 2015){
                                        $ArrayTmp[$Key] = "50%";
                                    }
                                    if($AnoCastigo == 2016){
                                        $ArrayTmp[$Key] = "40%";
                                    }
                                    if($AnoCastigo == 2017){
                                        $ArrayTmp[$Key] = "25%";
                                    }
                                    if($AnoCastigo == 2018){
                                        $ArrayTmp[$Key] = "20%";
                                    }
                                    if($AnoCastigo >= 2019){
                                        $ArrayTmp[$Key] = "15%";
                                    }
                                break;
                            }
                        break;
                        case "PorcDescCampana":
                        switch($TipoCompromiso){
                            case "PUT":
                                if($AnoCastigo <= 2013){
                                    $ArrayTmp[$Key] = "80%";
                                }
                                if($AnoCastigo == 2014){
                                    $ArrayTmp[$Key] = "70%";
                                }
                                if($AnoCastigo == 2015){
                                    $ArrayTmp[$Key] = "60%";
                                }
                                if($AnoCastigo == 2016){
                                    $ArrayTmp[$Key] = "50%";
                                }
                                if($AnoCastigo == 2017){
                                    $ArrayTmp[$Key] = "30%";
                                }
                                if($AnoCastigo == 2018){
                                    $ArrayTmp[$Key] = "25%";
                                }
                                if($AnoCastigo >= 2019){
                                    $ArrayTmp[$Key] = "20%";
                                }
                            break;
                            case "CONVENIO":
                                if($AnoCastigo <= 2013){
                                    $ArrayTmp[$Key] = "70%";
                                }
                                if($AnoCastigo == 2014){
                                    $ArrayTmp[$Key] = "60%";
                                }
                                if($AnoCastigo == 2015){
                                    $ArrayTmp[$Key] = "50%";
                                }
                                if($AnoCastigo == 2016){
                                    $ArrayTmp[$Key] = "40%";
                                }
                                if($AnoCastigo == 2017){
                                    $ArrayTmp[$Key] = "25%";
                                }
                                if($AnoCastigo == 2018){
                                    $ArrayTmp[$Key] = "20%";
                                }
                                if($AnoCastigo >= 2019){
                                    $ArrayTmp[$Key] = "15%";
                                }
                            break;
                        }
                        break;
                        default:
                            $ArrayTmp[$Key] = $Value;
                        break;
                    }
                }
                array_push($Compromisos,$ArrayTmp);
            }

            $ToReturn["Compromisos"] = $Compromisos;
            return $ToReturn;
        }
        function downloadCompromisosLaPolarCastigo($idMandante,$fechaStart,$fechaEnd){
            ob_start();
            $db = new DB();
            $Compromisos = $this->getCompromisosLaPolarCastigo($idMandante,$fechaStart,$fechaEnd);
            $Headers = $Compromisos["Headers"];
            $Fields = $Compromisos["Fields"];
            $Compromisos = $Compromisos["Compromisos"];
            $Rows = "";
            
            $nombreArchivo = "COMPROMISOS - ";

            foreach($Headers as $Header){
                $Header = utf8_encode($Header);
                $Header = str_replace(";","",$Header);
                $Header = str_replace("\n","",$Header);
                $Header = str_replace("\r","",$Header);
                $Rows .= $Header.";";
            }
            $Rows .= "\r\n";
            //$Rows .= "<br>";
            foreach($Compromisos as $Compromiso){
                
                foreach($Fields as $Field){
                    $Value = $Compromiso[$Field["data"]];
                    //$Value = utf8_encode($Value);
                    if(is_numeric($Value)){
                        $Value = round($Value);
                    }else{
                        $Value = utf8_decode($Value);
                    }
					$Value = str_replace(";","",$Value);
					$Value = str_replace("\n","",$Value);
					$Value = str_replace("\r","",$Value);
					$Rows .= $Value.";";
                }
                $Rows .= "\r\n";
                //$Rows .= "<br>";
            }
            echo $Rows;
            header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="'.$nombreArchivo.''.date("dmY",strtotime($fechaStart)).' al '.date("dmY",strtotime($fechaEnd)).'.csv"');
			header('Cache-Control: max-age=0');
        }
        function getRenegociacionesLaPolar($idMandante,$fechaStart,$fechaEnd){
            
            $fechaHasta = strtotime('-1 day',strtotime($fechaStart));
            $fechaHasta = date ('Ymd',$fechaHasta);

            $CedenteClass = new Cedente();

            $Cedente = $CedenteClass->mostrarCedente($_SESSION["cedente"]);
            $Cedente = $Cedente[0];

            $fechaInicioPeriodo = $Cedente["inicio_periodo"];
            $fechaInicioPeriodo = date("Ymd",strtotime($fechaInicioPeriodo));
            
            $db = new DB();
            $ToReturn = array();
            $ToReturn["Fields"] = array();
            $ToReturn["Headers"] = array();
            $ToReturn["Tabla"] = "";
            
            $SqlRenegociaciones = "
            select
                'Soporte' as Empresa,
                D.MARCA as Asignacion,
                REPLACE(REPLACE(D.Tramo_Morosidad,'T0',''),'T','') as Tramo,
                D.Rutdv as Rut,
                CASE WHEN D.Id_Cedente = '253' THEN D.DEUDA_TOTAL ELSE D.Saldo_Insoluto END as MontoInsoluto,
                D.Deuda as MontoAtrasado,
                D.Fecha_Vencimiento as FechaMora,
                D.tipo_cartera as Cartera,
                '5000' as Abono,
                (select SUBSTRING(Res.Valor,1,locate(' - ',Res.Valor) - 1) from respuestas_campos_gestion Res where Res.id_gestion=Ges.id_gestion and Res.id_campo = '18') as Sucursal,
                'RENEGOCIACION' as TipoCompromiso,
                Ges.fec_compromiso as FechaDePago,
                Ges.nombre_ejecutivo as Ejecutivo,
                Ges.observacion as Observacion
            From
                gestion_ult_trimestre Ges
                    INNER JOIN Deuda D on D.Rut = Ges.rut_cliente and D.Id_Cedente='".$_SESSION["cedente"]."'
                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = D.Id_Cedente
            WHERE
                mandante_cedente.Id_Mandante='".$idMandante."' AND
                Ges.cedente='".$_SESSION["cedente"]."' AND
                Ges.fecha_gestion BETWEEN '".$fechaStart."' and '".$fechaEnd."' AND
                (Ges.resultado_n3 = '2468' OR Ges.resultado_n3='2389') AND
                Ges.rut_cliente not in (select rut_cliente from gestion_ult_trimestre where fecha_gestion BETWEEN '".$fechaInicioPeriodo."' and '".$fechaHasta."' and (resultado_n3 = '2468' OR resultado_n3='2389'))
            ORDER BY
                Ges.fechahora";
            
            $Headers = array();
            array_push($Headers,"Empresa");
            array_push($Headers,"Asignación");
            array_push($Headers,"Tramo");
            array_push($Headers,"Rut");
            array_push($Headers,"Monto Insoluto");
            array_push($Headers,"Monto Atrasado");
            array_push($Headers,"Fecha Mora");
            array_push($Headers,"Cartera");
            array_push($Headers,"Abono");
            array_push($Headers,"Sucursal");
            array_push($Headers,"Tipo Compromiso");
            array_push($Headers,"Fecha de Pago");
            array_push($Headers,"Ejecutivo");
            array_push($Headers,"Observación");
            
            
            $Fields = array();
            array_push($Fields,array("data"=>"Empresa"));
            array_push($Fields,array("data"=>"Asignacion"));
            array_push($Fields,array("data"=>"Tramo"));
            array_push($Fields,array("data"=>"Rut"));
            array_push($Fields,array("data"=>"MontoInsoluto"));
            array_push($Fields,array("data"=>"MontoAtrasado"));
            array_push($Fields,array("data"=>"FechaMora"));
            array_push($Fields,array("data"=>"Cartera"));
            array_push($Fields,array("data"=>"Abono"));
            array_push($Fields,array("data"=>"Sucursal"));
            array_push($Fields,array("data"=>"TipoCompromiso"));
            array_push($Fields,array("data"=>"FechaDePago"));
            array_push($Fields,array("data"=>"Ejecutivo"));
            array_push($Fields,array("data"=>"Observacion"));
            
            $ToReturn["Fields"] = $Fields;
            $ToReturn["Headers"] = $Headers;
            $ToReturn["Table"] = "tablaRenegociacionesLaPolar";
            $Renegociaciones = $db->select($SqlRenegociaciones);
            $ToReturn["Renegociaciones"] = $Renegociaciones;
            return $ToReturn;
        }
        function downloadRenegociacionesLaPolar($idMandante,$fechaStart,$fechaEnd){
            ob_start();
            $db = new DB();
            $Renegociaciones = $this->getRenegociacionesLaPolar($idMandante,$fechaStart,$fechaEnd);
            $Headers = $Renegociaciones["Headers"];
            $Fields = $Renegociaciones["Fields"];
            $Renegociaciones = $Renegociaciones["Renegociaciones"];
            $Rows = "";
            
            $nombreArchivo = "RENEGOCIACIONES - ";

            foreach($Headers as $Header){
                $Header = utf8_encode($Header);
                $Header = str_replace(";","",$Header);
                $Header = str_replace("\n","",$Header);
                $Header = str_replace("\r","",$Header);
                $Rows .= $Header.";";
            }
            $Rows .= "\r\n";
            //$Rows .= "<br>";
            foreach($Renegociaciones as $Renegociacion){
                
                foreach($Fields as $Field){
                    $Value = $Renegociacion[$Field["data"]];
                    //$Value = utf8_encode($Value);
                    if(is_numeric($Value)){
                        $Value = round($Value);
                    }else{
                        $Value = utf8_decode($Value);
                    }
					$Value = str_replace(";","",$Value);
					$Value = str_replace("\n","",$Value);
					$Value = str_replace("\r","",$Value);
					$Rows .= $Value.";";
                }
                $Rows .= "\r\n";
                //$Rows .= "<br>";
            }
            echo $Rows;
            header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="'.$nombreArchivo.''.date("dmY",strtotime($fechaStart)).' al '.date("dmY",strtotime($fechaEnd)).'.csv"');
			header('Cache-Control: max-age=0');
        }
        function getAjusteDePagoLaPolarCastigo($idMandante,$fechaStart,$fechaEnd){
            
            $fechaHasta = strtotime('-1 day',strtotime($fechaStart));
            $fechaHasta = date ('Ymd',$fechaHasta);

            $CedenteClass = new Cedente();

            $Cedente = $CedenteClass->mostrarCedente($_SESSION["cedente"]);
            $Cedente = $Cedente[0];

            $fechaInicioPeriodo = $Cedente["inicio_periodo"];
            $fechaInicioPeriodo = date("Ymd",strtotime($fechaInicioPeriodo));
            
            $db = new DB();
            $ToReturn = array();
            $ToReturn["Fields"] = array();
            $ToReturn["Headers"] = array();
            $ToReturn["Tabla"] = "";
            
            $SqlAjusteDePago = "
            select
                'Soporte' as Empresa,
                D.MARCA as Asignacion,
                REPLACE(REPLACE(D.Tramo_Morosidad,'T0',''),'T','') as Tramo,
                D.Rutdv as Rut,
                CASE WHEN D.Id_Cedente = '253' THEN D.DEUDA_TOTAL ELSE D.Saldo_Insoluto END as MontoInsoluto,
                D.Deuda as MontoAtrasado,
                D.Fecha_Vencimiento as FechaMora,
                D.tipo_cartera as Cartera,
                '5000' as Abono,
                (select SUBSTRING(Res.Valor,1,locate(' - ',Res.Valor) - 1) from respuestas_campos_gestion Res where Res.id_gestion=Ges.id_gestion and Res.id_campo = '18') as Sucursal,
                'RENEGOCIACION' as TipoCompromiso,
                Ges.fec_compromiso as FechaDePago,
                Ges.nombre_ejecutivo as Ejecutivo,
                Ges.observacion as Observacion
            From
                gestion_ult_trimestre Ges
                    INNER JOIN Deuda D on D.Rut = Ges.rut_cliente and D.Id_Cedente='".$_SESSION["cedente"]."'
                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = D.Id_Cedente
            WHERE
                mandante_cedente.Id_Mandante='".$idMandante."' AND
                Ges.cedente='".$_SESSION["cedente"]."' AND
                Ges.fecha_gestion BETWEEN '".$fechaStart."' and '".$fechaEnd."' AND
                (Ges.resultado_n3 = '2468' OR Ges.resultado_n3='2389') AND
                Ges.rut_cliente not in (select rut_cliente from gestion_ult_trimestre where fecha_gestion BETWEEN '".$fechaInicioPeriodo."' and '".$fechaHasta."' and (resultado_n3 = '2468' OR resultado_n3='2389'))
            ORDER BY
                Ges.fechahora";
            $SqlAjusteDePago = "SELECT
                'N°' as N,
                'rut' as Rut,
                'dv' as Dv,
                'ano' as Ano,
                'fecha de pago' as FechaDePago,
                'insoluto' as Insoluto,
                'monto pagado' as montoPagado,
                'insoluto antes de pago' as InsolutoAntesPago,
                'a pagar' as APagar,
                'diferencia' as Diferencia,
                'desc real' as DescripcionReal,
                'califica' as Califica
            ";
            $SqlAjusteDePago = "SELECT
                @rownum:=@rownum+1 as N,
                Ges.rut_cliente as Rut,
                SUBSTRING(D.Rutdv, POSITION('-' IN D.Rutdv) + 1, 1) as Dv,
                'ano' as Ano,
                'fecha de pago' as FechaDePago,
                'insoluto' as Insoluto,
                'monto pagado' as MontoPagado,
                'insoluto antes de pago' as InsolutoAntesPago,
                'a pagar' as APagar,
                'diferencia' as Diferencia,
                'desc real' as DescripcionReal,
                'califica' as Califica
            From
                (SELECT @rownum:=0) r,
                gestion_ult_trimestre Ges
                    INNER JOIN Deuda D on D.Rut = Ges.rut_cliente and D.Id_Cedente='".$_SESSION["cedente"]."'
                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = D.Id_Cedente
            WHERE
                mandante_cedente.Id_Mandante='".$idMandante."' AND
                Ges.cedente='".$_SESSION["cedente"]."' AND
                Ges.fecha_gestion BETWEEN '".$fechaStart."' and '".$fechaEnd."' AND
                Ges.resultado_n3 = '2523' AND
                Ges.rut_cliente not in (select rut_cliente from gestion_ult_trimestre where fecha_gestion BETWEEN '".$fechaInicioPeriodo."' and '".$fechaHasta."' and resultado_n3='2523')
            ORDER BY
                Ges.fechahora";

            
            $Headers = array();
            array_push($Headers,"N");
            array_push($Headers,"Rut");
            array_push($Headers,"Dv");
            array_push($Headers,"Ano");
            array_push($Headers,"Fecha De Pago");
            array_push($Headers,"Insoluto");
            array_push($Headers,"Monto Pagado");
            array_push($Headers,"Insoluto Antes Pago");
            array_push($Headers,"A Pagar");
            array_push($Headers,"Diferencia");
            array_push($Headers,"Descripcion Real");
            array_push($Headers,"Califica");
            
            
            $Fields = array();
            array_push($Fields,array("data"=>"N"));
            array_push($Fields,array("data"=>"Rut"));
            array_push($Fields,array("data"=>"Dv"));
            array_push($Fields,array("data"=>"Ano"));
            array_push($Fields,array("data"=>"FechaDePago"));
            array_push($Fields,array("data"=>"Insoluto"));
            array_push($Fields,array("data"=>"MontoPagado"));
            array_push($Fields,array("data"=>"InsolutoAntesPago"));
            array_push($Fields,array("data"=>"APagar"));
            array_push($Fields,array("data"=>"Diferencia"));
            array_push($Fields,array("data"=>"DescripcionReal"));
            array_push($Fields,array("data"=>"Califica"));
            
            $ToReturn["Fields"] = $Fields;
            $ToReturn["Headers"] = $Headers;
            $ToReturn["Table"] = "tablaAjusteDePagosLaPolarCastigo";
            $AjusteDePago = $db->select($SqlAjusteDePago);
            $ToReturn["AjustesDePago"] = $AjusteDePago;
            return $ToReturn;
        }
        function downloadAjusteDePagoLaPolarCastigo($idMandante,$fechaStart,$fechaEnd){
            ob_start();
            $db = new DB();
            $AjusteDePago = $this->getAjusteDePagoLaPolarCastigo($idMandante,$fechaStart,$fechaEnd);
            $Headers = $AjusteDePago["Headers"];
            $Fields = $AjusteDePago["Fields"];
            $AjusteDePago = $AjusteDePago["AjustesDePago"];
            $Rows = "";
            
            $nombreArchivo = "AJUSTE DE PAGO - ";

            foreach($Headers as $Header){
                $Header = utf8_encode($Header);
                $Header = str_replace(";","",$Header);
                $Header = str_replace("\n","",$Header);
                $Header = str_replace("\r","",$Header);
                $Rows .= $Header.";";
            }
            $Rows .= "\r\n";
            //$Rows .= "<br>";
            foreach($AjusteDePago as $Ajuste){
                
                foreach($Fields as $Field){
                    $Value = $Ajuste[$Field["data"]];
                    //$Value = utf8_encode($Value);
                    if(is_numeric($Value)){
                        $Value = round($Value);
                    }else{
                        $Value = utf8_decode($Value);
                    }
					$Value = str_replace(";","",$Value);
					$Value = str_replace("\n","",$Value);
					$Value = str_replace("\r","",$Value);
					$Rows .= $Value.";";
                }
                $Rows .= "\r\n";
                //$Rows .= "<br>";
            }
            echo $Rows;
            header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="'.$nombreArchivo.''.date("dmY",strtotime($fechaStart)).' al '.date("dmY",strtotime($fechaEnd)).'.csv"');
			header('Cache-Control: max-age=0');
        }
        function getOferta75DescuentoCruzVerdeConsumo($idMandante,$fechaStart,$fechaEnd){
            
            $fechaHasta = strtotime('-1 day',strtotime($fechaStart));
            $fechaHasta = date ('Ymd',$fechaHasta);

            $CedenteClass = new Cedente();

            $Cedente = $CedenteClass->mostrarCedente($_SESSION["cedente"]);
            $Cedente = $Cedente[0];

            $fechaInicioPeriodo = $Cedente["inicio_periodo"];
            $fechaInicioPeriodo = date("Ymd",strtotime($fechaInicioPeriodo));
            
            $db = new DB();
            $ToReturn = array();
            $ToReturn["Fields"] = array();
            $ToReturn["Headers"] = array();
            $ToReturn["Tabla"] = "";
            
            $SqlOfertas = "SELECT
                D.Rut as Rut,
                D.Rutdv as Dv,
                Ges.fono_discado as FonoContactado,
                Ges.monto_comp as Abono,
                (SELECT Valor FROM respuestas_campos_gestion R WHERE R.id_gestion = Ges.id_gestion AND id_campo='24') as Sucursal
            From
                gestion_ult_trimestre Ges
                    INNER JOIN Deuda D on D.Rut = Ges.rut_cliente and D.Id_Cedente='".$_SESSION["cedente"]."'
                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = D.Id_Cedente
            WHERE
                mandante_cedente.Id_Mandante='".$idMandante."' AND
                Ges.cedente='".$_SESSION["cedente"]."' AND
                Ges.fecha_gestion BETWEEN '".$fechaStart."' and '".$fechaEnd."' AND
                Ges.resultado_n3='3344'
            GROUP BY
	            Ges.id_gestion
            ORDER BY
                Ges.fechahora";
            
            $Headers = array();
            array_push($Headers,"Rut");
            array_push($Headers,"Dv");
            array_push($Headers,"Fono Contactado");
            array_push($Headers,"Abono");
            array_push($Headers,"Sucursal");
            
            
            $Fields = array();
            array_push($Fields,array("data"=>"Rut"));
            array_push($Fields,array("data"=>"Dv"));
            array_push($Fields,array("data"=>"FonoContactado"));
            array_push($Fields,array("data"=>"Abono"));
            array_push($Fields,array("data"=>"Sucursal"));
            
            $ToReturn["Fields"] = $Fields;
            $ToReturn["Headers"] = $Headers;
            $ToReturn["Table"] = "tablaOferta75DescuentoCruzVerdeConsumo";
            $Ofertas = $db->select($SqlOfertas);
            $ToReturn["Ofertas"] = $Ofertas;
            return $ToReturn;
        }
        function downloadOferta75DescuentoCruzVerdeConsumo($idMandante,$fechaStart,$fechaEnd){
            ob_start();
            $db = new DB();
            $Ofertas = $this->getOferta75DescuentoCruzVerdeConsumo($idMandante,$fechaStart,$fechaEnd);
            $Headers = $Ofertas["Headers"];
            $Fields = $Ofertas["Fields"];
            $Ofertas = $Ofertas["Ofertas"];
            $Rows = "";
            
            $nombreArchivo = "Ofertas - ";

            foreach($Headers as $Header){
                $Header = utf8_encode($Header);
                $Header = str_replace(";","",$Header);
                $Header = str_replace("\n","",$Header);
                $Header = str_replace("\r","",$Header);
                $Rows .= $Header.";";
            }
            $Rows .= "\r\n";
            //$Rows .= "<br>";
            foreach($Ofertas as $Oferta){
                
                foreach($Fields as $Field){
                    $Value = $Oferta[$Field["data"]];
                    //$Value = utf8_encode($Value);
                    if(is_numeric($Value)){
                        $Value = round($Value);
                    }else{
                        $Value = utf8_decode($Value);
                    }
					$Value = str_replace(";","",$Value);
					$Value = str_replace("\n","",$Value);
					$Value = str_replace("\r","",$Value);
					$Rows .= $Value.";";
                }
                $Rows .= "\r\n";
                //$Rows .= "<br>";
            }
            echo $Rows;
            header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="'.$nombreArchivo.''.date("dmY",strtotime($fechaStart)).' al '.date("dmY",strtotime($fechaEnd)).'.csv"');
			header('Cache-Control: max-age=0');
        }
        function getRenegociacionCruzVerdeConsumo($idMandante,$fechaStart,$fechaEnd){
            
            $fechaHasta = strtotime('-1 day',strtotime($fechaStart));
            $fechaHasta = date ('Ymd',$fechaHasta);

            $CedenteClass = new Cedente();

            $Cedente = $CedenteClass->mostrarCedente($_SESSION["cedente"]);
            $Cedente = $Cedente[0];

            $fechaInicioPeriodo = $Cedente["inicio_periodo"];
            $fechaInicioPeriodo = date("Ymd",strtotime($fechaInicioPeriodo));
            
            $db = new DB();
            $ToReturn = array();
            $ToReturn["Fields"] = array();
            $ToReturn["Headers"] = array();
            $ToReturn["Tabla"] = "";
            
            $SqlRenegociaciones = "SELECT
                D.Rut as Rut,
                D.Rutdv as Dv,
                Ges.fono_discado as FonoContactado,
                Ges.monto_comp as Abono,
                (SELECT Valor FROM respuestas_campos_gestion R WHERE R.id_gestion = Ges.id_gestion AND id_campo='24') as Sucursal
            From
                gestion_ult_trimestre Ges
                    INNER JOIN Deuda D on D.Rut = Ges.rut_cliente and D.Id_Cedente='".$_SESSION["cedente"]."'
                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = D.Id_Cedente
            WHERE
                mandante_cedente.Id_Mandante='".$idMandante."' AND
                Ges.cedente='".$_SESSION["cedente"]."' AND
                Ges.fecha_gestion BETWEEN '".$fechaStart."' and '".$fechaEnd."' AND
                Ges.resultado_n3='3345'
            GROUP BY
	            Ges.id_gestion
            ORDER BY
                Ges.fechahora";
            
            $Headers = array();
            array_push($Headers,"Rut");
            array_push($Headers,"Dv");
            array_push($Headers,"Fono Contactado");
            array_push($Headers,"Abono");
            array_push($Headers,"Sucursal");
            
            
            $Fields = array();
            array_push($Fields,array("data"=>"Rut"));
            array_push($Fields,array("data"=>"Dv"));
            array_push($Fields,array("data"=>"FonoContactado"));
            array_push($Fields,array("data"=>"Abono"));
            array_push($Fields,array("data"=>"Sucursal"));
            
            $ToReturn["Fields"] = $Fields;
            $ToReturn["Headers"] = $Headers;
            $ToReturn["Table"] = "tablaRenegociacionCruzVerdeConsumo";
            $Renegociaciones = $db->select($SqlRenegociaciones);
            $ToReturn["Renegociaciones"] = $Renegociaciones;
            return $ToReturn;
        }
        function downloadRenegociacionCruzVerdeConsumo($idMandante,$fechaStart,$fechaEnd){
            ob_start();
            $db = new DB();
            $Renegociaciones = $this->getRenegociacionCruzVerdeConsumo($idMandante,$fechaStart,$fechaEnd);
            $Headers = $Renegociaciones["Headers"];
            $Fields = $Renegociaciones["Fields"];
            $Renegociaciones = $Renegociaciones["Renegociaciones"];
            $Rows = "";
            
            $nombreArchivo = "Renegociaciones - ";

            foreach($Headers as $Header){
                $Header = utf8_encode($Header);
                $Header = str_replace(";","",$Header);
                $Header = str_replace("\n","",$Header);
                $Header = str_replace("\r","",$Header);
                $Rows .= $Header.";";
            }
            $Rows .= "\r\n";
            //$Rows .= "<br>";
            foreach($Renegociaciones as $Renegociacion){
                
                foreach($Fields as $Field){
                    $Value = $Renegociacion[$Field["data"]];
                    //$Value = utf8_encode($Value);
                    if(is_numeric($Value)){
                        $Value = round($Value);
                    }else{
                        $Value = utf8_decode($Value);
                    }
					$Value = str_replace(";","",$Value);
					$Value = str_replace("\n","",$Value);
					$Value = str_replace("\r","",$Value);
					$Rows .= $Value.";";
                }
                $Rows .= "\r\n";
                //$Rows .= "<br>";
            }
            echo $Rows;
            header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="'.$nombreArchivo.''.date("dmY",strtotime($fechaStart)).' al '.date("dmY",strtotime($fechaEnd)).'.csv"');
			header('Cache-Control: max-age=0');
        }
        function getRenegociacionCruzVerdeTarjeta($idMandante,$fechaStart,$fechaEnd){
            
            $fechaHasta = strtotime('-1 day',strtotime($fechaStart));
            $fechaHasta = date ('Ymd',$fechaHasta);

            $CedenteClass = new Cedente();

            $Cedente = $CedenteClass->mostrarCedente($_SESSION["cedente"]);
            $Cedente = $Cedente[0];

            $fechaInicioPeriodo = $Cedente["inicio_periodo"];
            $fechaInicioPeriodo = date("Ymd",strtotime($fechaInicioPeriodo));
            
            $db = new DB();
            $ToReturn = array();
            $ToReturn["Fields"] = array();
            $ToReturn["Headers"] = array();
            $ToReturn["Tabla"] = "";
            
            $SqlRenegociaciones = "SELECT
                D.Rut as Rut,
                D.Rutdv as Dv,
                Ges.fono_discado as FonoContactado,
                Ges.monto_comp as AbonoMinimo,
                '' as AbonoRealizado,
                Ges.observacion as Observaciones,
                (SELECT Valor FROM respuestas_campos_gestion R WHERE R.id_gestion = Ges.id_gestion AND id_campo='25') as Sucursal
            From
                gestion_ult_trimestre Ges
                    INNER JOIN Deuda D on D.Rut = Ges.rut_cliente and D.Id_Cedente='".$_SESSION["cedente"]."'
                    INNER JOIN mandante_cedente on mandante_cedente.Id_Cedente = D.Id_Cedente
            WHERE
                mandante_cedente.Id_Mandante='".$idMandante."' AND
                Ges.cedente='".$_SESSION["cedente"]."' AND
                Ges.fecha_gestion BETWEEN '".$fechaStart."' and '".$fechaEnd."' AND
                Ges.resultado_n3='3343'
            GROUP BY
	            Ges.id_gestion
            ORDER BY
                Ges.fechahora";
            
            $Headers = array();
            array_push($Headers,"Rut");
            array_push($Headers,"Dv");
            array_push($Headers,"Fono Contactado");
            array_push($Headers,"Abono Minimo");
            array_push($Headers,"Abono Realizado");
            array_push($Headers,"Observaciones");
            
            
            $Fields = array();
            array_push($Fields,array("data"=>"Rut"));
            array_push($Fields,array("data"=>"Dv"));
            array_push($Fields,array("data"=>"FonoContactado"));
            array_push($Fields,array("data"=>"AbonoMinimo"));
            array_push($Fields,array("data"=>"AbonoRealizado"));
            array_push($Fields,array("data"=>"Observaciones"));
            
            $ToReturn["Fields"] = $Fields;
            $ToReturn["Headers"] = $Headers;
            $ToReturn["Table"] = "tablaRenegociacionCruzVerdeTarjeta";
            $Renegociaciones = $db->select($SqlRenegociaciones);
            $ToReturn["Renegociaciones"] = $Renegociaciones;
            return $ToReturn;
        }
        function downloadRenegociacionCruzVerdeTarjeta($idMandante,$fechaStart,$fechaEnd){
            ob_start();
            $db = new DB();
            $Renegociaciones = $this->getRenegociacionCruzVerdeTarjeta($idMandante,$fechaStart,$fechaEnd);
            $Headers = $Renegociaciones["Headers"];
            $Fields = $Renegociaciones["Fields"];
            $Renegociaciones = $Renegociaciones["Renegociaciones"];
            $Rows = "";
            
            $nombreArchivo = "Renegociaciones - ";

            foreach($Headers as $Header){
                $Header = utf8_encode($Header);
                $Header = str_replace(";","",$Header);
                $Header = str_replace("\n","",$Header);
                $Header = str_replace("\r","",$Header);
                $Rows .= $Header.";";
            }
            $Rows .= "\r\n";
            //$Rows .= "<br>";
            foreach($Renegociaciones as $Renegociacion){
                
                foreach($Fields as $Field){
                    $Value = $Renegociacion[$Field["data"]];
                    //$Value = utf8_encode($Value);
                    if(is_numeric($Value)){
                        $Value = round($Value);
                    }else{
                        $Value = utf8_decode($Value);
                    }
					$Value = str_replace(";","",$Value);
					$Value = str_replace("\n","",$Value);
					$Value = str_replace("\r","",$Value);
					$Rows .= $Value.";";
                }
                $Rows .= "\r\n";
                //$Rows .= "<br>";
            }
            echo $Rows;
            header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="'.$nombreArchivo.''.date("dmY",strtotime($fechaStart)).' al '.date("dmY",strtotime($fechaEnd)).'.csv"');
			header('Cache-Control: max-age=0');
        }


        function getTemplate($tipoTemplate){
            $db = new DB();
            $Template = '';
            if($tipoTemplate){
                $query = "SELECT ".$tipoTemplate." as Template FROM config_derivaciones";
                $row = $db->select($query);
                if($row){
                    $Template = html_entity_decode($row[0]['Template']);
                }
            }
            
            return $Template;
        }

        public function updateTemplate($tipoTemplate, $Template){
            $db = new DB();
            $Template = htmlentities($Template);
            $query = "UPDATE config_derivaciones SET ".$tipoTemplate." = '".$Template."'";
            $ToReturn = $db->query($query);
            return $ToReturn;
        }
        function getCorreosCC(){
            $db = new DB();
            $CorreosCC = '';
            $query = "SELECT correosCC FROM config_derivaciones";
            $row = $db->select($query);
            if($row){
                $correosCC = $row[0]['correosCC'];
            }
            
            return $correosCC;
        }
        public function updateCorreosCC($correosCC){
            $db = new DB();
            $query = "UPDATE config_derivaciones SET correosCC = '".$correosCC."'";
            $ToReturn = $db->query($query);
            return $ToReturn;
        }

        function getNiveles($tipoNivel){
            $db = new DB();
            if($tipoNivel){
                $query = "SELECT ".$tipoNivel." as Nivel3 FROM config_derivaciones";
                $row = $db->select($query);
                if($row){
                    $Nivel3 = $row[0]['Nivel3'];
                    $Nivel3 = explode(",",$Nivel3);
                    $Nivel3_0 = $Nivel3[0];
                    $query = "  SELECT
                                    Nivel1.Id AS Nivel1,
                                    Nivel2.id AS Nivel2
                                FROM
                                    Nivel3
                                INNER JOIN Nivel2 ON Nivel3.Id_Nivel2 = Nivel2.id
                                INNER JOIN Nivel1 ON Nivel2.Id_Nivel1 = Nivel1.Id
                                WHERE
                                    Nivel3.id = '".$Nivel3_0."'";
                    $Niveles = $db->select($query);
                    if($Niveles){
                        $Nivel1 = $Niveles[0]['Nivel1'];
                        $Nivel2 = $Niveles[0]['Nivel2'];
                    }else{
                        $Nivel1 = '';
                        $Nivel2 = '';
                    }
                }else{
                    $Nivel3 = '';
                }
            }
            
            return array('Nivel1' => $Nivel1, 'Nivel2' => $Nivel2, 'Nivel3' => $Nivel3);
        }
        public function updateNiveles($tipoNivel, $Niveles){
            $db = new DB();
            $query = "UPDATE config_derivaciones SET ".$tipoNivel." = '".implode(',',$Niveles)."'";
            $ToReturn = $db->query($query);
            return $ToReturn;
        }
    }
?>