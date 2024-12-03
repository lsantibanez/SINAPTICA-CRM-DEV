<?php

include_once(__DIR__.'/../../vendor/autoload.php');
include_once(__DIR__.'/../db/DB.php');
include_once(__DIR__.'/../logs.php');
include_once(__DIR__.'/../../includes/functions/Functions.php');
ini_set('memory_limit', '1024M');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Campaign {
    public $idCedente;
    public $idMandante;

    private $db;
    private $logs;

    function __construct()
    {
        $this->idMandante = isset($_SESSION['mandante']) ? $_SESSION['mandante'] : "";
        $this->idCedente = isset($_SESSION['cedente']) ? $_SESSION['cedente']: "";
        $this->db = new Db();
        $this->logs = new Logs();
    }

    function select(){
        try {
            $sqlCampaign = "SELECT id, name, statistics, status, created_at 
                        FROM mail_campaigns 
                        WHERE idCedente='" . $this->idCedente . "' 
                        AND idMandante='" . $this->idMandante . "' 
                        AND isDeleted = 0 
                        ORDER BY id DESC";

            $campaigns = $this->db->select($sqlCampaign);

            return ['success' => true, 'items' => $campaigns];
        } catch (Exception $e) {
            $this->logs->error($e->getMessage());
            return ['success' => false, 'items' => []];
        }
    }

    function getCampaign($id)
    {
        try{
            $sql = "SELECT * FROM mail_campaigns WHERE id= ".$id;
            $campaign = $this->db->select($sql);
            if (!$campaign) {
                $this->logs->error("No se encontró la plantilla por el id: " . $id);
                return [
                    'success' => false,
                    'items' => [],
                ];
            }
            return ['success' => true, 'item' => $campaign[0]];

        }catch(Exception $e){
            $this->logs->error($e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al actualizar el estado',
                'error' => $e->getMessage(),
            ];
        }
    }
    function getCustomVariables($id){
        $sql = "SELECT * FROM mail_campaigns WHERE id = ".$id;
        $campaign = $this->db->select($sql);

        if (!$campaign) {
            $this->logs->error("No se encontró la campaña con el id: " . $id);
            return [
                'success' => false,
                'items' => [],
            ];
        }
        //TODO: Obtener las custom variables de la tabla data_emails


    }
    function insert($request){
        $requiredFields = ['name', 'date', 'subject', 'sender', 'emailResponse', 'unsubcribe', 'file'];
        foreach ($requiredFields as $field) {
            if (empty($request[$field])) {
                return ['success' => false, 'message' => "El campo $field es obligatorio."];
            }
            $this->db->escape($request[$field]);
        }

        if (!filter_var($request['emailResponse'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'El campo emailResponse debe ser un email válido.'];
        }

//        if (!filter_var($request['unsubcribe'], FILTER_VALIDATE_URL)) {
//            return ['success' => false, 'message' => 'El campo unsubcribe debe ser una URL válida.'];
//        }

        $file = $request['file'];
        if (!in_array(pathinfo($file['name'], PATHINFO_EXTENSION), ['xls', 'xlsx'])) {
            return ['success' => false, 'message' => 'El archivo debe ser un Excel con extensión .xls o .xlsx.'];
        }

        try {
            $tempPath = $file['tmp_name'];

            $reader = IOFactory::createReaderForFile($tempPath);
            $spreadsheet = $reader->load($tempPath);
            $sheet = $spreadsheet->getActiveSheet();
            $headers = $sheet->rangeToArray('A1:C1')[0];
            $expectedHeaders = ['EMAIL', 'NOMBRE', 'IDENTIFICADOR'];

            if (array_map('strtoupper', $headers) !== $expectedHeaders) {
                return ['success' => false, 'message' => 'El archivo no contiene las cabeceras requeridas: EMAIL, NOMBRE, IDENTIFICADOR.'];
            }

            $tempPath = $file['tmp_name'];

            // Leer y validar cabeceras del Excel
            $reader = IOFactory::createReaderForFile($tempPath);
            $spreadsheet = $reader->load($tempPath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestColumn = $sheet->getHighestColumn();
            $headers = $sheet->rangeToArray("A1:{$highestColumn}1")[0];
            $expectedHeaders = ['EMAIL', 'NOMBRE', 'IDENTIFICADOR'];

            if (array_map('strtoupper', $headers) !== $expectedHeaders) {
                return ['success' => false, 'message' => 'El archivo no contiene las cabeceras requeridas: EMAIL, NOMBRE, IDENTIFICADOR.'];
            }

            $campaignData = [
                'name' => $request['name'],
                'subject' => $request['subject'],
                'schedule' => $request['date'],
                'emailResponse' => $request['emailResponse'],
                'sender' => $request['sender'],
                'date' => $request['date'],
//            'unsubcribe' => $request['unsubcribe'],
                'created_at' => date('Y-m-d H:i:s'),
                'idCedente' => $this->idCedente,
                'idMandante' => $this->idMandante
            ];

            $campaignId = $this->db->insertWithParams('mail_campaigns', $campaignData);

            if (!$campaignId) {
                $this->logs->error('Error al insertar la campaña.');
                return ['success' => false, 'message' => 'Error al insertar la campaña.'];
            }

            $rows = $sheet->rangeToArray('A2:C' . $sheet->getHighestRow());
            $data = [];
            foreach ($rows as $row) {
                $row = array_map('trim', $row);
                $data[] = [
                    'IDENTIFICADOR' => $row[2],
                    'NOMBRE' => $row[1],
                    'EMAIL' => $row[0]
                ];
            }
            $items = array_slice($data, 0, 10);
            foreach (array_chunk($data, 1000) as $chunk) {
                foreach ($chunk as $row) {
                    $emailData = [
                        'identity' => $row['IDENTIFICADOR'],
                        'fullName' => $row['NOMBRE'],
                        'email' => $row['EMAIL'],
                        'customVariables' => json_encode($row),
                        'campaign_id' => $campaignId,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->db->insertWithParams('mail_data_emails', $emailData);
                }
            }

            return ['success' => true, 'message' => 'Los datos se han procesado correctamente.', 'item' => $items];
        }catch (Exception $e){
            $this->logs->error($e->getMessage());
            return ['success' => false, 'items' => []];
        }
    }

}
