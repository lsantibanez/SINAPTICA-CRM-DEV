<?php

include("/var/www/html/db/db.php");
require '/var/www/html/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$sqlCarterizacionFinal = $mysqli->query("SELECT * FROM disal.carterizacionProvisoriaAcumulada");

$spreadsheet = new Spreadsheet(); 
$sheet = $spreadsheet->getActiveSheet();
$spreadsheet->getActiveSheet()->getStyle('A1:AA1')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
$spreadsheet->getActiveSheet()->getStyle('A1:AA1')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('A1:AA1')
    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$spreadsheet->getActiveSheet()->getStyle('A1:AA1')->getFill()->getStartColor()->setARGB('DF7401');

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
$sheet->setCellValue('N1', 'contacto');
$sheet->setCellValue('O1', 'fono');
$sheet->setCellValue('P1', 'mail');
$sheet->setCellValue('Q1', 'direccion');
$sheet->setCellValue('R1', 'celular');
$sheet->setCellValue('S1', 'estado');
$sheet->setCellValue('T1', 'dias_atraso');
$sheet->setCellValue('U1', 'cobrador');
$sheet->setCellValue('V1', 'exCobrador');
$sheet->setCellValue('W1', 'origen');
$sheet->setCellValue('X1', 'fecha_ingreso');
$sheet->setCellValue('Y1', 'tramo');
$sheet->setCellValue('Z1', 'rebajado');
$sheet->setCellValue('AA1', 'fechaRebaja');

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
    $sheet->setCellValue('N'.$i, $row['contacto']);
    $sheet->setCellValue('O'.$i, $row['fono']);
    $sheet->setCellValue('P'.$i, $row['mail']);
    $sheet->setCellValue('Q'.$i, $row['direccion']);
    $sheet->setCellValue('R'.$i, $row['celular']);
    $sheet->setCellValue('S'.$i, $row['estado']);
    $sheet->setCellValue('T'.$i, $row['dias_atraso']);
    $sheet->setCellValue('U'.$i, $row['cobrador']);
    $sheet->setCellValue('V'.$i, $row['exCobrador']);
    $sheet->setCellValue('W'.$i, $row['origen']);
    $sheet->setCellValue('X'.$i, $row['fecha_ingreso']);
    $sheet->setCellValue('Y'.$i, $row['tramo']);
    $sheet->setCellValue('Z'.$i, $row['rebajado']);
    $sheet->setCellValue('AA'.$i,$row['fechaRebaja']);
    $i++;
    
}

header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Carterizacion.xlsx");
$writer = new Xlsx($spreadsheet);
$writer->save('/home/DISAL/carterizacionProvisoria.xlsx');
shell_exec("cp /home/DISAL/carterizacionProvisoria.xlsx /var/www/html/reporte/");

?>