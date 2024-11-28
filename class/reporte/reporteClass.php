<?php
include("../../mail/class.phpmailer.php");
include("../../mail/class.smtp.php");

class Reporte
{

	public $cedenteGlobal;

	public function mostrarReporteOnce($fecha,$cedente)
	{
        $db = new DB();
		$this->fecha=$fecha;
		$this->cedente=$cedente;

		$queryCedenteGlobal = "SELECT Cedente_Global FROM Cedente WHERE Id_Cedente = $this->cedente LIMIT 1";
		$queryExecCedenteGlobal = $db->select($queryCedenteGlobal);

		foreach($queryExecCedenteGlobal as $row){
			$cedenteGlobal = $row["Cedente_Global"];
		}	

        $queryCedenteArray = $db->select("SELECT Id_Cedente FROM Cedente WHERE Cedente_Global = $cedenteGlobal");
        $resultsArray = array();
        foreach($queryCedenteArray as $row){
           $cedArray = $rowArray["Id_Cedente"];
           array_push($resultsArray, $cedArray);
        }
        $cantArray = count($resultsArray);
        $i = 0;
        while($i<$cantArray)
        {
            $resultsArray[$i];
            $arrayIn = $arrayIn.$resultsArray[$i].",";
            $i++;
        }    
        $arrayIn = substr($arrayIn, 0, -1);
        echo "<a href='../includes/reporte/reporteOnceExcel.php'><button class='fa fa-file-excel-o btn btn-success btn-icon' id='exportarExcel'></button></a>";
        echo "<br>";
        echo "<br>";
		echo "<table id='demo-dt-basic' class='table table-striped table-bordered' cellspacing='0' width='100%'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>FECHA</th>";
        echo "<th>HORA</th>";
        echo "<th>CUENTA</th>";
        echo "<th>PRODUCTO</th>";
        echo "<th>RUT</th>";
        echo "<th>TRAMO MORA</th>";                                      
        echo "<th>MONTO MORA</th>";
        echo "<th>SALDO MORA</th>";
        echo "<th>NOMBRE GESTION</th>";
        echo "<th>TIPO GESTION</th>";
        echo "<th>NOMBRE CAUSAL</th>";
        echo "<th>NOMBRE CODIGO</th>";
        echo "<th>ID CAUSAL</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";                                
  
        $queryUno = $db->select("SELECT g.fecha_gestion,g.hora_gestion,g.rut_cliente , p.Digito_Verificador,g.resultado ,g.resultado_n2,g.resultado_n3 , g.cedente FROM gestion_ult_semestre   g , Persona p  WHERE g.fecha_gestion = '$this->fecha' AND cedente IN ($arrayIn) AND g.rut_cliente = p.Rut "); 
        foreach($$queryUno as $row1){
            $rut = $row1["rut_cliente"];
            $resultado = $row1["resultado"];
            $resultado2 = $row1["resultado_n2"];
            $resultado3 = $row1["resultado_n3"];
            $cedenteNuevo = $row1["cedente"];
            echo "<tr>";
            echo "<td>".$row1["fecha_gestion"]."</td>";
            echo "<td>".$row1["hora_gestion"]."</td>";                                
            echo "<td>".$row1["rut_cliente"]."</td>";
            echo "<td>".$cedenteNuevo."</td>";
            echo "<td>".$row1["rut_cliente"]."-".$row1["Digito_Verificador"]."</td>";
            $queryDos = $db->select("SELECT Tramo_Dias_Mora,Deuda,Saldo_Mora FROM Deuda WHERE Rut = $rut AND Id_Cedente IN ($arrayIn) GROUP BY Rut LIMIT 1");
            foreach($$queryDos as $row2){
                $tramo = $row2["Tramo_Dias_Mora"];
                $monto = $row2["Deuda"];
                $saldo = $row2["Saldo_Mora"];
            }   
            echo "<td>".$tramo."</td>";
            echo "<td>".$monto."</td>";
            echo "<td>".$saldo."</td>";
            echo "<td>GESTION TELEFONICA</td>";
            $queryTres = $db->select("SELECT Nivel_2_Claro ,Nivel_3_Claro FROM  CLARO_homologacion_foco  WHERE id1 = $resultado AND id2 = $resultado2 AND id3 = $resultado3 LIMIT 1");
            foreach($$queryTres as $row3){
                $result = $row3["Nivel_2_Claro"];
                $result3 = $row3["Nivel_3_Claro"];
                $queryCuatro = $db->select("SELECT tipo_contacto,descripcion FROM  CLARO_resultado_foco_gestion  WHERE codigo='$result' LIMIT 1");
                foreach($$queryCuatro as $row4){
                    $result_final = $row4["tipo_contacto"];
                    $id_causal = $row4["descripcion"];                             
                }  
                $queryCinco = $db->select("SELECT Id,Descripcion FROM  CLARO_causal_mora WHERE codigo='$result3' LIMIT 1");
                foreach($$queryCinco as $row5){
                    $id = $row5["Id"];
                    $desc= $row5["Descripcion"];                              
                }   
            }
                                                    
            echo "<td>".$result_final."</td>";
            echo "<td>".$desc."</td>";
            echo "<td>".$id_causal."</td>";
            echo "<td>".$id."</td>";
            echo "</tr>";  
                                       
        } 
        echo "</tbody>";
        echo "</table>";
	}

    public function mostrarReporteSupervisor($cedente)
    {
        $db = new DB();
        $this->cedente=$cedente;
        //echo "<a href='../includes/reporte/reporteOnceExcel.php'><button class='fa fa-file-excel-o btn btn-success btn-icon' id='exportarExcel'></button></a>";
        echo "<br>";
        echo "<br>";
        echo "<table id='demo-dt-basic' class='table table-striped table-bordered' cellspacing='0' width='100%'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Rut</th>";
        echo "<th>Nombre</th>";
        echo "<th>Fono</th>";
        echo "<th>Ultima Gestion</th>";
        echo "<th>Fecha Ult Gestion</th>";
        echo "<th>Color Fono</th>";
        echo "<th>Cant. Discado <br> Ultimo Semestre</th>";
        echo "<th>Fecha Comp</th>";
        echo "<th>Monto Comp</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>"; 
        $queryPersona = $db->select("SELECT p.Rut,p.Nombre_Completo,f.formato_subtel,f.color FROM Persona p , fono_cob f WHERE p.Rut = f.Rut AND FIND_IN_SET ('$this->cedente',p.Id_Cedente) ");
        foreach($queryPersona as $row){
            
            $rutPersona = $row['Rut'];
            $nombrePersona = $row['Nombre_Completo'];
            $fonoCob = $row['formato_subtel'];
            $colorFonoCob = $row['color'];

            $queryColor = $db->select("SELECT tipo_var ,color_hex  FROM SIS_Categoria_Fonos WHERE color = $colorFonoCob");
            $colorFono = '';
            $colorHex = '';
            foreach($queryColor as $row){
                $colorFono = $row['tipo_var'];
                $colorHex= $row['color_hex'];
            }
            echo "<tr>";
            echo "<td>".$rutPersona."</td>";
            echo "<td>".$nombrePersona."</td>";
            echo "<td>".$fonoCob."</td>";
            $queryMejorGestionPeriodo = $db->select("SELECT Tipo_Contacto,Fecha_Gestion FROM Ultima_Gestion WHERE Rut = $rutPersona AND Fono_Gestion = $fonoCob ");
            $tipoContacto = '';
            $fechaUltGestion = '';
            foreach($queryMejorGestionPeriodo as $row1){
                $tipoContacto = $row1["Tipo_Contacto"];
                $fechaUltGestion = $row1["Fecha_Gestion"];
            } 
            
            $queryTipoFinal = $db->select("SELECT Nombre FROM Tipo_Contacto WHERE Id_TipoContacto = $tipoContacto");
            $tipoContactoFinal = '';
            foreach($queryTipoFinal as $row1){
                $tipoContactoFinal = $row1["Nombre"];
            }  
         
            echo "<td>".$tipoContactoFinal."</td>";
            echo "<td>".$fechaUltGestion."</td>";
            echo "<td><i class='fa fa-flag fa-lg icon-lg' style='color:$colorHex'></i>"." ".$colorFono."</td>";
            $contarFono = 0;
            $queryContarFono = $db->select("SELECT rut_cliente FROM gestion_ult_semestre WHERE fono_discado = $fonoCob AND rut_cliente = $rutPersona");
            $contarFono = count($queryContarFono);          
            echo "<td>".$contarFono."</td>";
            $fechaComp = '';
            $queryFechaComp = $db->select("SELECT fec_compromiso,monto_comp FROM gestion_ult_semestre WHERE fono_discado = $fonoCob AND rut_cliente = $rutPersona");
            foreach($queryFechaComp as $row2){
                $fechaComp = $row2["fec_compromiso"];
                $montoComp = $row2["monto_comp"];
            }  
         

            echo "<td>".$fechaComp."</td>";
            echo "<td>".$montoComp."</td>";

            echo "</tr>";  
        }    
        echo "</tbody>";
        echo "</table>";
    }
  
}
?>
