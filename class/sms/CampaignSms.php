<?php

include_once(__DIR__ . '/../../vendor/autoload.php');
include_once(__DIR__ . '/../db/DB.php');
include_once(__DIR__ . '/../logs.php');
include_once(__DIR__ . '/../../includes/functions/Functions.php');
ini_set('memory_limit', '1024M');

use PhpOffice\PhpSpreadsheet\IOFactory;

class CampaignSms
{
    public $idCedente;
    public $idMandante;

    private $db;
    private $logs;

    function __construct()
    {
        $this->idMandante = isset($_SESSION['mandante']) ? $_SESSION['mandante'] : "";
        $this->idCedente = isset($_SESSION['cedente']) ? $_SESSION['cedente'] : "";
        $this->db = new Db();
        $this->logs = new Logs();
    }

    function getAllSms($search = null)
    {
        try {

            $sql = "SELECT id,name,phone,start_date,end_date,status,created_at 
                        FROM msj_campaign_sms 
                        WHERE idCedente='" . $this->idCedente . "' 
                        AND idMandante='" . $this->idMandante . "' 
                        AND isDeleted = 0";

            if ($search) {
                $sql .= " AND (name LIKE '%" . $search . "%')";
            }

            $sql .= " ORDER BY id DESC";

            $campaignSms = $this->db->select($sql);
            if ($campaignSms) {
                return ['success' => true, 'items' => $campaignSms];
            } else {
                return ['success' => false, 'items' => []];
            }
        } catch (Exception $e) {
            $this->logs->error($e->getMessage());
            return ['success' => false, 'items' => [], 'error' => $e->getMessage()];
        }
    }

    function verifyExcel($file)
    {
        $allowedExtensions = ['xls', 'xlsx'];
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array($extension, $allowedExtensions)) {
            return ['success' => false, 'message' => 'El archivo debe ser un Excel con extensión .xls o .xlsx.'];
        }

        try {
            $tempPath = $file['tmp_name'];
            $reader = IOFactory::createReaderForFile($tempPath);
            $spreadsheet = $reader->load($tempPath);
            $sheet = $spreadsheet->getActiveSheet();

            $highestColumn = $sheet->getHighestColumn();
            $headers = $sheet->rangeToArray("A1:{$highestColumn}1")[0];

            $requiredHeaders = ['IDENTIFICADOR', 'FONO'];
            $fileHeaders = array_map('strtoupper', $headers);

            $missingHeaders = array_diff($requiredHeaders, $fileHeaders);
            if (!empty($missingHeaders)) {
                return [
                    'success' => false,
                    'message' => 'El archivo no contiene las siguientes cabeceras requeridas: ' . implode(', ', $missingHeaders),
                ];
            }

            $rows = $sheet->rangeToArray("A2:{$highestColumn}11");
            $preview = [];
            foreach ($rows as $row) {
                $row = array_map('trim', $row);
                $preview[] = array_combine($fileHeaders, $row);
            }

            usort($preview, function ($a, $b) {
                return $a['IDENTIFICADOR'] <=> $b['IDENTIFICADOR'];
            });

            $firstFiveRecords = array_slice($preview, 0, 5);

            return [
                'success' => true,
                'message' => 'El archivo es válido.',
                'headers' => $fileHeaders,
                'topRecords' => $firstFiveRecords
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error al procesar el archivo: ' . $e->getMessage()];
        }
    }

    function insert($rerquest)
    {
        return $rerquest;
    }


}