<?php

include("/var/www/html/db/db.php");
require '/var/www/html/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$cobrador = $_GET['cobrador'];
$agingCobrador = $mysqli->query("SELECT  `rut`, `cliente`, `deudaCarterizada`, `cobrador`, `saldoActual`, `documentos`, 
`pv`, `tr`, `se`, `nv`, `cv`, `co`, `com` FROM disal.`reporteAging` WHERE cobrador = '$cobrador'");

$spreadsheet = new Spreadsheet(); 
$sheet = $spreadsheet->getActiveSheet();
$spreadsheet->getActiveSheet()->getStyle('A1:S1')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
$spreadsheet->getActiveSheet()->getStyle('A1:S1')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('A1:S1')
    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$spreadsheet->getActiveSheet()->getStyle('A1:S1')->getFill()->getStartColor()->setARGB('DF7401');

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);


$sheet->setCellValue('A1', 'Rut'); 
$sheet->setCellValue('B1', 'Cliente');
$sheet->setCellValue('C1', 'Deuda Carterizada');
$sheet->setCellValue('D1', 'Deuda Recuperada');
$sheet->setCellValue('E1', '% Recupero');
$sheet->setCellValue('F1', 'Cobrador');
$sheet->setCellValue('G1', 'Saldo Actual');
$sheet->setCellValue('H1', 'Documentos');
$sheet->setCellValue('I1', 'Llamadas con Gestion');
$sheet->setCellValue('J1', 'Total llamadas');
$sheet->setCellValue('K1', 'Compromisos Vigentes');
$sheet->setCellValue('L1', 'Compromisos Rotos');
$sheet->setCellValue('M1', 'Por Vencer');
$sheet->setCellValue('N1', '0 - 30');
$sheet->setCellValue('O1', '30 - 60');
$sheet->setCellValue('P1', '60 - 90');
$sheet->setCellValue('Q1', '90 - 120');
$sheet->setCellValue('R1', '120 - 180');
$sheet->setCellValue('S1', '>180');

$i = 2; 

foreach($agingCobrador as $row){
    $rut = $row['rut'];
    $saldoCarterizado = $row['deudaCarterizada'];
    $sheet->setCellValue('A'.$i, $row['rut']); 
    $sheet->setCellValue('B'.$i, $row['cliente']); 
    $sheet->setCellValue('C'.$i, $row['deudaCarterizada']); 
    $sqlPago = $mysqli->query("SELECT SUM(saldo) as sumaPagado FROM disal.carterizacionFinal WHERE Rut = '$rut' AND  pagado = 1");
    foreach($sqlPago as $rowP){
        $sumaPagado = $rowP['sumaPagado'];
        $sheet->setCellValue('D'.$i, $sumaPagado); 
        $porcentaje = round(($sumaPagado/$saldoCarterizado)*100);
        $porcentaje = $porcentaje."%";
        $sheet->setCellValue('E'.$i, $porcentaje);
    }
    $sheet->setCellValue('F'.$i, $row['cobrador']); 
    $sheet->setCellValue('G'.$i, $row['saldoActual']); 
    $sheet->setCellValue('H'.$i, $row['documentos']);

    $sqlGestion = $mysqli->query("SELECT Cantidad FROM foco.Cantidad_Llamadas_Gestion_Mes WHERE Rut = '$rut' ");
    foreach($sqlGestion as $row4){
        $cantidadLlamadasGestion = $row4['Cantidad'];
        $sheet->setCellValue('I'.$i, $cantidadLlamadasGestion);

    }
    $sqlLlamadas = $mysqli->query("SELECT Cantidad FROM foco.Cantidad_Llamadas_Mes WHERE Rut = '$rut' ");
    foreach($sqlLlamadas as $row3){
        $cantidadLlamadas = $row3['Cantidad'];
        $sheet->setCellValue('J'.$i, $cantidadLlamadas);
    }
    $sqlComp = $mysqli->query("SELECT count(*) as cantidad FROM foco.Compromisos WHERE Rut = '$rut' AND Compromiso IN ('FUTUROS','HOY')");
    foreach($sqlComp as $rowC){
        $cantidadComp = $rowC['cantidad'];
        $sheet->setCellValue('K'.$i, $cantidadComp);
    }
    $sqlCompRoto = $mysqli->query("SELECT count(*) as cantidad FROM foco.Compromisos WHERE Rut = '$rut' AND Compromiso NOT IN ('FUTUROS','HOY')");
    foreach($sqlCompRoto as $rowR){
        $cantidadCompRoto = $rowR['cantidad'];
        $sheet->setCellValue('L'.$i, $cantidadCompRoto);
    }
    $sheet->setCellValue('M'.$i, $row['pv']);
    $sheet->setCellValue('N'.$i, $row['tr']);
    $sheet->setCellValue('O'.$i, $row['se']);
    $sheet->setCellValue('P'.$i, $row['nv']);
    $sheet->setCellValue('Q'.$i, $row['cv']);
    $sheet->setCellValue('R'.$i, $row['co']);
    $sheet->setCellValue('S'.$i, $row['com']);
    $i++;
    
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ReporteCobrador.xlsx"');
$writer =  new Xlsx($spreadsheet);
$writer->save('php://output');

?>