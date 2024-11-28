<?php
$Id_Grabacion = $_GET['id'];
include_once("../includes/functions/Functions.php");
Main_IncludeClasses("calidad");
Main_IncludeClasses("personal");
Main_IncludeClasses("global");
    //Main_IncludeClasses("db");
include_once("../class/db/DB.php");

$CalidadClass = new Calidad();
$PersonalClass = new Personal();
$CedenteClass = new Cedente();


$FocoConfig = getFocoConfig();
$LogoEmpresa = $FocoConfig["logoEmpresa"];
if($LogoEmpresa == ""){
    $LogoEmpresa = "/Foco/Soporte.png";
}else{
    $LogoEmpresa = "/Foco/".$LogoEmpresa;
}


$CalidadClass->Id_Grabacion = $Id_Grabacion;
$Evaluation = $CalidadClass->getEvaluationByUser();
$Evaluation = $Evaluation[0];
$PersonalClass->id = $Evaluation["Id_Personal"];
$Personal = $PersonalClass->getPersonal();
$Personal = $Personal[0];
$Competencias = $CalidadClass->getNotesGroupedByCompetencias($Evaluation["id"],$Personal["Id_Personal"]);
foreach($Competencias as $key => $Competencia){
    $idCompetencia = $Competencia["idCompetencia"];
    $Aspectos = $CalidadClass->getAspectosIndividualesByEvaluacion($Evaluation["id"],"Corregir",$idCompetencia);
    $Cont = 1;
    if(count($Aspectos) > 0){
        foreach($Aspectos as $Aspecto){
            if(!isset($Competencias[$key]["Aspectos"])){
                $Competencias[$key]["Aspectos"] = $Cont.". ".$Aspecto["Aspecto"]."<br>";
            }else{
                $Competencias[$key]["Aspectos"] .= $Cont.". ".$Aspecto["Aspecto"]."<br>";
            }
            $Cont++;
        }
    }else{
        $Competencias[$key]["Aspectos"] = "";
    }
}

if(!isset($NombreCierre)){
    $NombreCierre = '';
}
$mysqli = new DB();

$rut = $_GET['rut'];
/*Quuery cliente*/
$q1 = $mysqli->select("SELECT A.Nombre_Completo,SUM(B.Saldo_ML) as deudaTotal FROM Persona A JOIN Deuda B 
    ON A.Rut = B.Rut WHERE A.Rut = '$rut'");
foreach($q1 as $row){
    $nombreCliente= $row['Nombre_Completo'];
    $deudaTotal = $row['deudaTotal'];
}

$q2 = $mysqli->select("SELECT SUM(Saldo_ML) deudaVencida FROM Deuda WHERE Rut = '$rut' AND dias_atraso > 0");
foreach($q2 as $row){
    $deudaVencida = $row['deudaVencida'];
}

$q3 = $mysqli->select("SELECT SUM(Saldo_ML) porVencer FROM Deuda WHERE Rut = '$rut' AND dias_atraso <= 0");
foreach($q3 as $row){
    $porVencer = $row['porVencer'];
}

$q4 = $mysqli->select("SELECT contacto,fono,mail,direccion,cobrador,sedeNombre FROM disal.carterizacionFinal WHERE rut = '$rut' ");
foreach($q4 as $row){
    $contacto = $row['contacto'];
    $fono = $row['fono'];
    $mail = $row['mail'];
    $direccion = $row['direccion'];
    $cobrador = $row['cobrador'];
   
}

$q5 = $mysqli->select("SELECT fechaPago,monto FROM disal.pagosProcess WHERE rut = '$rut' ORDER By fechaPago DESC LIMIT 1");
foreach($q5 as $row){
    $fechaPago = $row['fechaPago'];
    $montoPago = $row['monto'];

}

//var_dump($q4);exit;





ob_start(); 
?>
<style>
    .text-danger{
        color: #d12909;
    }
    .text-primary{
       color: #266fb0;
   }
   .label-purple {

    background-color: #9f5594;

}
.label-success {
    background-color: #91c957;
}
.label-warning {
    background-color: #f1aa40;
}
.label-primary {
    background-color: #5fa2dd;
}

.page-break{
  page-break-before: always;
} 

.p5{
  width:5%;
} 
.p7{
  width:7%;
} 
.p10{
  width:10%;
} 
.p15{
  width:15%;
} 
.p20{
  width:20%;
} 
.p34{
  width:34%;
} 
tbody tr{
    text-transform: lowercase;
}

.cabezera{
 padding: 10px 0px;
 text-transform: capitalize;
 border-bottom: 1px solid #111;
 text-align: center;
}

.hijos{
 padding: 5px 0px;

 border-bottom: 1px solid #d0d0d0;
}

.gestiones{
    font-size: 12px!important;
}



</style>
<page footer="page" backtop="100px" backleft="10px" backbottom="50px" backright="30px">
    <page_header>
    <div class="Head" style="width: 100%; ">
        <img style="width: 150px; position: absolute;  right: 20px;  top: 20px;" src="../img/disal.png" />
    </div>
    </page_header>
    <table style="width: 100%;">
        <tr >
            <td style="width: 33%; vertical-align:top; font-size: 15px; line-height: 150%">
               <b><?= $nombreCliente ?></b> <br>
               Rut : <b><?= $rut ?></b>
               <br>
               Contacto : <?= $contacto ?>  <br>
               Teléfono : <?= $fono ?>  <br>
               Email :   <?= $mail ?> <br>
               Dirección : <?= $direccion ?><br>

               Cobrador : <b><?= $cobrador ?></b><br>
               Nombre Sede : <b><?= $sede ?></b><br>
           </td>           
           <td style="width: 33%;  vertical-align:top; text-align: center; font-size: 15px;line-height: 150% ">
              <b>Deuda Total: $ <?php echo number_format($deudaTotal, 0, '', '.'); ?></b><br><br>
              <span class="text-danger"> Vencida: $ <?php echo number_format($deudaVencida, 0, '', '.'); ?></span><br>
              <span class="text-primary"> No Vencida: <?php echo number_format($porVencer, 0, '', '.'); ?></span><br>
              Monto línea asignada :  Pendiente<br>
              Línea disponible : Pendiente
          </td>          
          <td style="width: 33%;  vertical-align:top;text-align: right; font-size: 15px; line-height: 150%">
            Días promedio de pago último año : Pendiente<br>
            Fecha último pago : <span class="label label-table label-purple"><?php echo $fechaPago; ?></span><br>
            Monto último pago : <?php echo number_format($montoPago, 0, '', '.'); ?>  <br>

            Facturación promedio mes : Pendiente<br>
            Pago último mes : Pendiente<br>

            Compromisos vigentes : Pendiente<br>
            % Compromisos cumplidos : Pendiente
        </td>

    </tr>
</table>

<br>
<br>
<h2 style="margin-bottom: 30px;" > Listado de facturas</h2>
<table style="pad" style="width: 100%;" >
    <thead>
        <tr role="row">
            <th class="p10 cabezera">Factura</th>
            <th class="p15 cabezera">Fecha Emisión</th>
            <th class="p15 cabezera">Fecha Vencimiento</th>
            <th class="p10 cabezera">Días Atraso</th>
            <th class="p15 cabezera">Nombre Sede</th>
            <th class="p10 cabezera">Aging</th>
            <th class="p10 cabezera">Monto</th>
            <th class="p10 cabezera">Saldo</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $query = $mysqli->select("SELECT Numero_Factura,fechaEmision,
            fechaVencimiento,Monto_Factura,Saldo_ML,dias_atraso,origen,aging,sedeNombre FROM Deuda WHERE Rut = '$rut' ORDER BY fechaEmision desc");
        foreach($query as $row){
            $Numero_Factura = $row['Numero_Factura']; 
            $fechaEmision = $row['fechaEmision']; 
            $fechaVencimiento = $row['fechaVencimiento']; 
            $Monto_Factura = $row['Monto_Factura']; 
            $Monto_Factura = number_format($Monto_Factura, 0, ",", ".");
            $Saldo_ML = $row['Saldo_ML']; 
            $Saldo_ML  = number_format($Saldo_ML, 0, ",", ".");
            $dias_atraso = $row['dias_atraso']; 
            $origen = $row['sedeNombre']; 
            $aging = $row['aging']; 

            echo "<tr>";
            echo "<th class='p10 hijos'> $Numero_Factura </th>" ;
            echo "<th class='p15 hijos label-success'> $fechaEmision </th>" ;
            echo "<th class='p15 hijos label-warning'>  $fechaVencimiento  </th>" ;
            echo "<th class='p15 hijos'> $dias_atraso </th>" ;
            echo "<th class='p15 hijos'> $origen </th>" ;
            echo "<th class='p10 hijos label-primary'>  $aging </th>" ;
            echo "<th class='p10 hijos'> $Monto_Factura </th>" ;
            echo "<th class='p10 hijos'> $Saldo_ML </th>" ;
            echo "</tr>";

        }
        ?>

    </tbody>

</table>


</page>



<page footer="page" backtop="100px" backleft="10px" backbottom="50px" backright="30px" >
    <page_header>
    <div class="Head" style="width: 100%;">
        <img style="width: 150px; position: absolute;  right: 20px;  top: 20px;" src="../img/disal.png" />
    </div>
    </page_header>
    <h2 style="margin-bottom: 30px;" > Listado de Gestiones</h2>
    <table  style="width: 100%; font-size: 11px " >
        <thead>
            <tr role="row">
                <th class="p7 cabezera">Fecha Gestión </th>
                <th class="p7 cabezera">Nombre Ejecutivo  </th>
                <th class="p7 cabezera">Teléfono  </th>
                <th class="p10 cabezera">Respuesta  </th>
                <th class="p15 cabezera">Sub Respuesta  </th>
                <th class="p10 cabezera">Sub Respuesta  </th>
                <th class="p10 cabezera">Número Factura  </th>
                <th class="p34 cabezera">Observación  </th>
            </tr>
        </thead>
        <tbody>
            <?php 
            //$queryGestion = $mysqli->select("SELECT * FROM gestion_ult_trimestre WHERE rut_cliente = '$rut' and fecha_gestion > NOW() - INTERVAL 90 DAY ORDER BY fecha_gestion desc ");
            $queryGestion = $mysqli->select("SELECT * FROM gestion_ult_trimestre WHERE rut_cliente = '$rut'  ORDER BY fecha_gestion desc ");
            foreach($queryGestion as $rowG){
                $fecha_gestion = $rowG['fecha_gestion']; 
                $nombre_ejecutivo = $rowG['nombre_ejecutivo']; 
                $fono_discado = $rowG['fono_discado']; 
                $n1 = $rowG['n1']; 
                $n2 = $rowG['n2']; 
                $n3 = $rowG['n3']; 
                $factura = $rowG['factura']; 
                $observacion = $rowG['observacion']; 

                echo "<tr>";
                echo "<th class='p7  hijos label-purple'> $fecha_gestion </th>" ;
                echo "<th class='p7  hijos'> $nombre_ejecutivo</th>" ;
                echo "<th class='p7  hijos'> $fono_discado</th>" ;
                echo "<th class='p10 hijos'> $n1 </th>" ;
                echo "<th class='p15 hijos'> $n2 </th>" ;
                echo "<th class='p10 hijos'> $n3</th>" ;
                echo "<th class='p10 hijos'> $factura </th>" ;
                echo "<th class='p34 hijos'> $observacion </th>" ;
                echo "</tr>";

            }
            ?>

        </tbody>

    </table>
</page>

<?php

$content = ob_get_clean();

    // convert in PDF
include("../includes/html2pdf/class/html2pdf.class.php");
include("../includes/html2pdf/class/exception.class.php");
include("../includes/html2pdf/class/locale.class.php");
include("../includes/html2pdf/class/myPdf.class.php");
include("../includes/html2pdf/class/parsingHtml.class.php");
include("../includes/html2pdf/class/parsingCss.class.php");
try
{
    $html2pdf = new HTML2PDF('L','A4', 'es', true, 'UTF-8', array(10,0,0,0));
        //$html2pdf->setModeDebug();
    $html2pdf->setDefaultFont('Arial');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('pdf.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}

?>