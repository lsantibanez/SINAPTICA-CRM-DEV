<?php

include("/var/www/html/db/db.php");
require '/var/www/html/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$sqlCarterizacionFinal = $mysqli->query("SELECT D.empresa empresa,D.Rut rut,P.Digito_Verificador dv,
P.Nombre_Completo cliente, D.`Numero_Factura` documento, D.`Tipo` tipoDocumento,D.`fechaEmision` fechaEmision, D.`fechaVencimiento` FechaVencimiento,
D.`Monto_Factura` monto, D.`Saldo_ML` saldo,D.`sedeNumero` sedeNumero, D.`sedeNombre` sedeNombre, D.`zona` zona,D.`estado` estado, 
D.`dias_atraso` dias_atraso, D.`COBRADOR` cobrador, D.`origen` origen, D.`aging` aging FROM foco.`Deuda` D JOIN 
foco.Persona P ON D.Rut = P.Rut WHERE 1 group by D.Numero_Factura,D.Rut");

$spreadsheet = new Spreadsheet(); 
$sheet = $spreadsheet->getActiveSheet();
$spreadsheet->getActiveSheet()->getStyle('A1:R1')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
$spreadsheet->getActiveSheet()->getStyle('A1:R1')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('A1:R1')
    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$spreadsheet->getActiveSheet()->getStyle('A1:R1')->getFill()->getStartColor()->setARGB('DF7401');

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);


$sheet->setCellValue('A1', 'empresa'); 
$sheet->setCellValue('B1', 'rut');
$sheet->setCellValue('C1', 'dv');
$sheet->setCellValue('D1', 'cliente');
$sheet->setCellValue('E1', 'documento');
$sheet->setCellValue('F1', 'tipoDocumento');
$sheet->setCellValue('G1', 'fechaEmision');
$sheet->setCellValue('H1', 'FechaVencimiento');
$sheet->setCellValue('I1', 'monto');
$sheet->setCellValue('J1', 'saldo');
$sheet->setCellValue('K1', 'sedeNumero');
$sheet->setCellValue('L1', 'sedeNombre');
$sheet->setCellValue('M1', 'zona');
$sheet->setCellValue('N1', 'estado');
$sheet->setCellValue('O1', 'dias_atraso');
$sheet->setCellValue('P1', 'cobrador');
$sheet->setCellValue('Q1', 'origen');
$sheet->setCellValue('R1', 'aging');


$i = 2; 

foreach($sqlCarterizacionFinal as $row){
     
    $sheet->setCellValue('A'.$i, $row['empresa']); 
    $sheet->setCellValue('B'.$i, $row['rut']); 
    $sheet->setCellValue('C'.$i, $row['dv']); 
    $sheet->setCellValue('D'.$i, $row['cliente']); 
    $sheet->setCellValue('E'.$i, $row['documento']); 
    $sheet->setCellValue('F'.$i, $row['tipoDocumento']);
    $sheet->setCellValue('G'.$i, $row['fechaEmision']);
    $sheet->setCellValue('H'.$i, $row['FechaVencimiento']);
    $sheet->setCellValue('I'.$i, $row['monto']);
    $sheet->setCellValue('J'.$i, $row['saldo']);
    $sheet->setCellValue('K'.$i, $row['sedeNumero']);
    $sheet->setCellValue('L'.$i, $row['sedeNombre']);
    $sheet->setCellValue('M'.$i, $row['zona']);
    $sheet->setCellValue('N'.$i, $row['estado']);
    $sheet->setCellValue('O'.$i, $row['dias_atraso']);
    $sheet->setCellValue('P'.$i, $row['cobrador']);
    $sheet->setCellValue('Q'.$i, $row['origen']);
    $sheet->setCellValue('R'.$i, $row['aging']);

    $i++;
    
}

header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Carterizacion.xlsx");
$writer = new Xlsx($spreadsheet);
$writer->save('/home/DISAL/carterizacionDiaria.xlsx');
shell_exec("cp /home/DISAL/carterizacionDiaria.xlsx /var/www/html/reporte/");

?>