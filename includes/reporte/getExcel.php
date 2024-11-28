<?php

include("../../class/db/database_connection.php");
require_once '../../class/helpers/helpers.php';
include("../../class/reporte/reporte.php");
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$reporte = new reporte();
$idFocoFactura = $_GET['idFocoFactura'];
$cobrador = $_GET['cobrador'];
$fechaReporte = date('Y-m-d')." ".date("H:i:s").".xlsx";
$respuesta = $reporte->getExcelCarterizacion($idFocoFactura,$cobrador);



if($respDial == 1){

    $spreadsheet = new Spreadsheet(); 
    $sheet = $spreadsheet->getActiveSheet();
    $spreadsheet->getActiveSheet()->getStyle('A2:F2')
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $spreadsheet->getActiveSheet()->getStyle('A2:F2')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $spreadsheet->getActiveSheet()->getStyle('A2:F2')
        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $spreadsheet->getActiveSheet()->getStyle('A2:F2')
        ->getFill()->getStartColor()->setARGB('0431B4');

    $spreadsheet->getActiveSheet()->getStyle('G2')
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $spreadsheet->getActiveSheet()->getStyle('G2')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $spreadsheet->getActiveSheet()->getStyle('G2')
        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $spreadsheet->getActiveSheet()->getStyle('G2')
        ->getFill()->getStartColor()->setARGB('FF0000');


    $spreadsheet->getActiveSheet()->getStyle('A1:G1')
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $spreadsheet->getActiveSheet()->getStyle('A1:G1')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $spreadsheet->getActiveSheet()->getStyle('A1:G1')
        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $spreadsheet->getActiveSheet()->getStyle('A1:F1')
        ->getFill()->getStartColor()->setARGB('2E2E2E');
    


    $spreadsheet->getActiveSheet()->mergeCells('A1:G1');

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    //$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    //$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $sheet->setCellValue('A1', ''); 




    $i = 3;
    $fechaHora = "";
    $decision = "";
    foreach($respuesta as $row){
        
        
        $sheet->setCellValue('A2', 'Código Cliente'); 
        $sheet->setCellValue('A'.$i, $row['Rut']); 

        $sheet->setCellValue('B2', 'Teléfono'); 
        $sheet->setCellValue('B'.$i, $row['Fono']); 

        $sheet->setCellValue('C2', 'Tipo Gestión'); 
        $sheet->setCellValue('C'.$i, $row['gestion']);

        $sheet->setCellValue('D2', 'Fecha'); 
        $sheet->setCellValue('D'.$i, $row['fecha']);  
        
        $fechaHora = $row['fecha']." ".$row['hora'];
        $sheet->setCellValue('E2', 'Fecha Hora'); 
        $sheet->setCellValue('E'.$i, $fechaHora); 

        $sheet->setCellValue('F2', 'Link Grabación'); 
        $sheet->setCellValue('F'.$i, $row['urlGrabacion']); 

        


        $i++;
        
    }
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=Reporte_$fechaReporte");
    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");

}else{
    $spreadsheet = new Spreadsheet(); 
    $sheet = $spreadsheet->getActiveSheet();
    $spreadsheet->getActiveSheet()->getStyle('A2:G2')
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $spreadsheet->getActiveSheet()->getStyle('A2:G2')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $spreadsheet->getActiveSheet()->getStyle('A2:G2')
        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $spreadsheet->getActiveSheet()->getStyle('A2:G2')
        ->getFill()->getStartColor()->setARGB('0431B4');

    $spreadsheet->getActiveSheet()->getStyle('A1:G1')
        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
    $spreadsheet->getActiveSheet()->getStyle('A1:G1')
        ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $spreadsheet->getActiveSheet()->getStyle('A1:G1')
        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $spreadsheet->getActiveSheet()->getStyle('A1:G1')
        ->getFill()->getStartColor()->setARGB('2E2E2E');


    $spreadsheet->getActiveSheet()->mergeCells('A1:G1');

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    //$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    //$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $sheet->setCellValue('A1', 'Reporte Genérico'); 




    $i = 3;
    $fechaHora = "";
    foreach($respuesta as $row){
        
        
        $sheet->setCellValue('A2', 'Código Cliente'); 
        $sheet->setCellValue('A'.$i, $row['Rut']); 

        $sheet->setCellValue('B2', 'Teléfono'); 
        $sheet->setCellValue('B'.$i, $row['Fono']); 

        $sheet->setCellValue('C2', 'Tipo Gestión'); 
        $sheet->setCellValue('C'.$i, $row['gestion']);

        $sheet->setCellValue('D2', 'Fecha'); 
        $sheet->setCellValue('D'.$i, $row['fecha']);  
        
        $fechaHora = $row['fecha']." ".$row['hora'];
        $sheet->setCellValue('E2', 'Fecha Hora'); 
        $sheet->setCellValue('E'.$i, $fechaHora); 

        $sheet->setCellValue('F2', 'Link Grabación'); 
        $sheet->setCellValue('F'.$i, $row['urlGrabacion']); 

        $sheet->setCellValue('G2', 'Comentario'); 
        $sheet->setCellValue('G'.$i, $row['observacion']); 

        $i++;
        
    }
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=Reporte_$fechaReporte");
    $writer = new Xlsx($spreadsheet);
    $writer->save("php://output");


}



?>