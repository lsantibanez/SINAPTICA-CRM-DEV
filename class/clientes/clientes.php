<?php

class Clientes{

    function __construct(){
		if(!isset($_SESSION)){
			session_start();
		}
	}
    
    public function getAsignacion($idUsuario){
		$Id_Cedente = $_SESSION["cedente"];
        $mysqli = new DB();
        $nivel = 0;
        $cobrador = "";
        $totalDeuda = 0;
        $vencido = 0;
        $noVencido = 0;
        $query = $mysqli->select("SELECT usuario,nombre,nivelFactura FROM Usuarios WHERE id = $idUsuario");
        foreach($query as $row){
            $nivel = $row['nivelFactura'];
            $cobrador = strtolower($row['nombre']);
        }
        if($Id_Cedente==2){
            echo "<table id='tabla_asignacion'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Nombre</th>";
            echo "<th>Rut</th>";
            echo "<th>Facturas</th>";
            echo "<th>Saldo Total</th>";
            echo "<th>Por Vencer / Vencido</th>";
            echo "<th>Cobrador</th>";
            echo "<th>Detalle</th>";

            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            $queryCobrador = $mysqli->select("SELECT P.Nombre_Completo as nombre,count(D.Numero_Factura) as documento,
            SUM(D.Saldo_ML) as deuda , D.COBRADOR as cobrador ,D.Rut as Rut FROM `Persona` P  
            JOIN Deuda D ON P.Rut = D.Rut WHERE D.Id_Cedente = '$Id_Cedente' group by P.Rut ORDER BY deuda DESC");
            foreach($queryCobrador as $rowC){
                $totalDeuda = $rowC['deuda'] + $totalDeuda;
                $rut = $rowC['Rut'];
                $saldo = $rowC['deuda'];
                echo "<tr>";
                echo "<th>".utf8_decode($rowC['nombre'])."</th>";
                echo "<th>".$rowC['Rut']."</th>";
                echo "<th>".$rowC['documento']."</th>";
                echo "<th>$ ".number_format($rowC['deuda'], 0, '', '.')."</th>";
                $queryNv = $mysqli->select("SELECT sum(Saldo_ML) as saldo FROM `Deuda` WHERE dias_atraso <= 0 and Rut = '$rut'");
                foreach($queryNv as $rowNV){
                    $noVencido = $rowNV['saldo'];
                }    
                $queryV = $mysqli->select("SELECT sum(Saldo_ML) as saldo FROM `Deuda` WHERE dias_atraso > 0 and Rut = '$rut'");
                foreach($queryV as $rowV){
                    $vencido = $rowV['saldo'];
                }    
                $p1 = round(100*$noVencido/$saldo);
                $p2 = round(100*$vencido/$saldo);
                echo "<th>";
                echo "<div class='progress'>";
                echo "<div class='progress-bar bg-primary' role='progressbar' style='width: $p1%' aria-valuenow='$p1' aria-valuemin='0' aria-valuemax='100'></div>";
                echo "<div class='progress-bar bg-danger' role='progressbar' style='width: $p2%'' aria-valuenow='$p2' aria-valuemin='0' aria-valuemax='100'></div>";
                echo "</div>";
                echo "</th>";
                echo "<th>".$rowC['cobrador']."</th>";
                echo "<th><a href='../../clientes/clientesDetalle.php?rut=$rut'><i class='fa fa-search'</i></a></th>";
                echo "</tr>";

            }
            echo "<tbody>";
            echo "<tfoot>";
            echo "<tr>";
            echo "<th colspan='5' style='text-align:right' bgcolor='#9ED9F9'>Total:</th>";
            echo "<th bgcolor='#9ED9F9'>".number_format($totalDeuda, 0, '', '.')."</th>";
            echo "<th bgcolor='#9ED9F9'></th>";

            echo "</tr>";
            echo "</tfoot>";

            
            echo "<tbody>";
            echo "</tbody>";
            echo "</table>";
        }else{
            if($nivel == 1){
                echo "<table id='tabla_asignacion'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Nombre</th>";
                echo "<th>Rut</th>";
                echo "<th>Facturas</th>";
                echo "<th>Saldo Total</th>";
                echo "<th>Por Vencer / Vencido</th>";
                echo "<th>Cobrador</th>";
                echo "<th>Detalle</th>";
    
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                $queryCobrador = $mysqli->select("SELECT P.Nombre_Completo as nombre,count(D.Numero_Factura) as documento,
                SUM(D.Saldo_ML) as deuda , D.COBRADOR as cobrador ,D.Rut as Rut FROM `Persona` P  
                JOIN Deuda D ON P.Rut = D.Rut WHERE D.Id_Cedente = '$Id_Cedente' group by P.Rut ORDER BY deuda DESC");
                foreach($queryCobrador as $rowC){
                    $totalDeuda = $rowC['deuda'] + $totalDeuda;
                    $rut = $rowC['Rut'];
                    $saldo = $rowC['deuda'];
                    echo "<tr>";
                    echo "<th>".utf8_decode($rowC['nombre'])."</th>";
                    echo "<th>".$rowC['Rut']."</th>";
                    echo "<th>".$rowC['documento']."</th>";
                    echo "<th>$ ".number_format($rowC['deuda'], 0, '', '.')."</th>";
                    $queryNv = $mysqli->select("SELECT sum(Saldo_ML) as saldo FROM `Deuda` WHERE dias_atraso <= 0 and Rut = '$rut'");
                    foreach($queryNv as $rowNV){
                        $noVencido = $rowNV['saldo'];
                    }    
                    $queryV = $mysqli->select("SELECT sum(Saldo_ML) as saldo FROM `Deuda` WHERE dias_atraso > 0 and Rut = '$rut'");
                    foreach($queryV as $rowV){
                        $vencido = $rowV['saldo'];
                    }    
                    $p1 = round(100*$noVencido/$saldo);
                    $p2 = round(100*$vencido/$saldo);
                    echo "<th>";
                    echo "<div class='progress'>";
                    echo "<div class='progress-bar bg-primary' role='progressbar' style='width: $p1%' aria-valuenow='$p1' aria-valuemin='0' aria-valuemax='100'></div>";
                    echo "<div class='progress-bar bg-danger' role='progressbar' style='width: $p2%'' aria-valuenow='$p2' aria-valuemin='0' aria-valuemax='100'></div>";
                    echo "</div>";
                    echo "</th>";
                    echo "<th>".$rowC['cobrador']."</th>";
                    echo "<th><a href='../../clientes/clientesDetalle.php?rut=$rut'><i class='fa fa-search'</i></a></th>";
                    echo "</tr>";
    
                }
                echo "<tbody>";
                echo "<tfoot>";
                echo "<tr>";
                echo "<th colspan='5' style='text-align:right' bgcolor='#9ED9F9'>Total:</th>";
                echo "<th bgcolor='#9ED9F9'>".number_format($totalDeuda, 0, '', '.')."</th>";
                echo "<th bgcolor='#9ED9F9'></th>";
    
                echo "</tr>";
                echo "</tfoot>";
    
               
                echo "<tbody>";
                echo "</tbody>";
                echo "</table>";
            }else{
                echo "<table id='tabla_asignacion'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Nombre</th>";
                echo "<th>Rut</th>";
                echo "<th>Facturas</th>";
                echo "<th>Saldo Total</th>";
                echo "<th>Por Vencer / Vencido</th>";
                echo "<th>Cobrador Asignado</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                $queryCobrador = $mysqli->select("SELECT P.Nombre_Completo as nombre,count(D.Numero_Factura) as documento,
                SUM(D.Saldo_ML) as deuda , D.COBRADOR as cobrador ,D.Rut as Rut FROM `Persona` P  
                JOIN Deuda D ON P.Rut = D.Rut  AND D.COBRADOR = '$cobrador' WHERE D.Id_Cedente = '$Id_Cedente' group by P.Rut ORDER BY deuda DESC");
                foreach($queryCobrador as $rowC){
                    $totalDeuda = $rowC['deuda'] + $totalDeuda;
                    $rut = $rowC['Rut'];
                    $saldo = $rowC['deuda'];
                    echo "<tr>";
                    echo "<th>".utf8_decode($rowC['nombre'])."</th>";
                    echo "<th>".$rowC['Rut']."</th>";
                    echo "<th>".$rowC['documento']."</th>";
                    echo "<th>".$rowC['deuda']."</th>";
                    $queryNv = $mysqli->select("SELECT sum(Saldo_ML) as saldo FROM `Deuda` WHERE dias_atraso <= 0 and Rut = '$rut'");
                    foreach($queryNv as $rowNV){
                        $noVencido = $rowNV['saldo'];
                    }    
                    $queryV = $mysqli->select("SELECT sum(Saldo_ML) as saldo FROM `Deuda` WHERE dias_atraso > 0 and Rut = '$rut'");
                    foreach($queryV as $rowV){
                        $vencido = $rowV['saldo'];
                    }    
                    $p1 = round(100*$noVencido/$saldo);
                    $p2 = round(100*$vencido/$saldo);
                    echo "<th>";
                    echo "<div class='progress'>";
                    echo "<div class='progress-bar bg-primary' role='progressbar' style='width: $p1%' aria-valuenow='$p1' aria-valuemin='0' aria-valuemax='100'></div>";
                    echo "<div class='progress-bar bg-danger' role='progressbar' style='width: $p2%'' aria-valuenow='$p2' aria-valuemin='0' aria-valuemax='100'></div>";
                    echo "</div>";
                    echo "</th>";
                    echo "<th>".$rowC['cobrador']."</th>";
                    echo "</tr>";
    
                }
                echo "<tbody>";
                echo "<tfoot>";
                echo "<tr>";
                echo "<th colspan='5' style='text-align:right' bgcolor='#9ED9F9'>Total:</th>";
                echo "<th bgcolor='#9ED9F9'>".number_format($totalDeuda, 0, '', '.')."</th>";
                echo "</tr>";
                echo "</tfoot>";
    
               
                echo "<tbody>";
                echo "</tbody>";
                echo "</table>";
            }

        }

    }
    
}
?>
