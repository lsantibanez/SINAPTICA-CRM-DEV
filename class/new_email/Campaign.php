<?php

include_once(__DIR__ . '/../../vendor/autoload.php');
include_once(__DIR__ . '/../db/DB.php');
include_once(__DIR__ . '/../logs.php');
include_once(__DIR__ . '/../../includes/functions/Functions.php');
ini_set('memory_limit', '1024M');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Campaign
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

    function select()
    {
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
        try {
            $sql = "SELECT * FROM mail_campaigns WHERE id= " . $id;
            $campaign = $this->db->select($sql);

            if (!$campaign) {
                $this->logs->error("No se encontró la plantilla por el id: " . $id);
                return [
                    'success' => false,
                    'items' => [],
                ];
            }
            return ['success' => true, 'item' => $campaign[0]];

        } catch (Exception $e) {
            $this->logs->error($e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al actualizar el estado',
                'error' => $e->getMessage(),
            ];
        }
    }

    function validateExcel($file)
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

            $requiredHeaders = ['EMAIL', 'NOMBRE', 'IDENTIFICADOR'];
            $fileHeaders = array_map('strtoupper', $headers);

            foreach ($requiredHeaders as $header) {
                if (!in_array($header, $fileHeaders)) {
                    return [
                        'success' => false,
                        'message' => 'El archivo no contiene las cabeceras requeridas: EMAIL, NOMBRE, IDENTIFICADOR.',
                    ];
                }
            }

            $rows = $sheet->rangeToArray("A2:{$highestColumn}11");
            $preview = [];
            foreach ($rows as $row) {
                $row = array_map('trim', $row);
                $preview[] = array_combine($fileHeaders, $row);
            }

            return ['success' => true, 'message' => 'El archivo es válido.', 'preview' => $preview];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error al procesar el archivo: ' . $e->getMessage()];
        }
    }


    function insert($request)
    {
        $requiredFields = ['name', 'subject', 'sender', 'emailResponse', 'file'];
        foreach ($requiredFields as $field) {
            if (empty($request[$field])) {
                return ['success' => false, 'message' => "El campo $field es obligatorio."];
            }
        }

        if (!filter_var($request['emailResponse'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'El campo emailResponse debe ser un email válido.'];
        }

        $file = $request['file'];
        if (!isset($file) || $file['error'] !== 0) {
            return ['success' => false, 'message' => 'No se cargó un archivo o hubo un error al cargarlo.'];
        }

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
            $fileHeaders = array_map('strtoupper', $headers);

            $requiredHeaders = ['EMAIL', 'NOMBRE', 'IDENTIFICADOR'];
            foreach ($requiredHeaders as $header) {
                if (!in_array($header, $fileHeaders)) {
                    return ['success' => false, 'message' => 'El archivo no contiene las cabeceras requeridas: EMAIL, NOMBRE, IDENTIFICADOR.'];
                }
            }

            $campaignData = [
                'name' => $request['name'],
                'subject' => $request['subject'],
                'schedule' => 0,
                'emailResponse' => $request['emailResponse'],
                'sender' => $request['sender'],
                'status' => 'CARGADA',
                'unsubcribe' => $request['unsubcribe'] ?? null,
                'created_at' => date('Y-m-d H:i:s'),
                'idCedente' => $this->idCedente,
                'idMandante' => $this->idMandante
            ];
            $campaignId = $this->db->insertWithParams('mail_campaigns', $campaignData);

            if (!$campaignId) {
                return ['success' => false, 'message' => 'Error al insertar la campaña.'];
            }

            $rows = $sheet->rangeToArray("A2:{$highestColumn}" . $sheet->getHighestRow());

            foreach (array_chunk($rows, 1000) as $chunk) {
                foreach ($chunk as $row) {
                    $row = array_combine($fileHeaders, array_map('trim', $row));
                    $customVariables = json_encode($row);

                    $emailData = [
                        'identity' => $row['IDENTIFICADOR'],
                        'fullName' => $row['NOMBRE'],
                        'email' => $row['EMAIL'],
                        'customVariables' => $customVariables,
                        'campaign_id' => $campaignId,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->db->insertWithParams('mail_data_emails', $emailData);
                }
            }

            return ['success' => true, 'message' => 'Campaña creada y datos almacenados exitosamente.', 'campaignId' => $campaignId];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error al procesar la campaña: ' . $e->getMessage()];
        }
    }

    function update($request){
        $requiredFields = ['name', 'subject', 'sender', 'emailResponse'];
        foreach ($requiredFields as $field) {
            if (empty($request[$field])) {
                return ['success' => false, 'message' => "El campo $field es obligatorio."];
            }
        }

        if (!filter_var($request['emailResponse'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'El campo Correo de respuesta debe ser un email válido.'];
        }

       if(!empty($request['file'])){
           $file = $request['file'];
           if (!isset($file) || $file['error'] !== 0) {
               return ['success' => false, 'message' => 'No se cargó un archivo o hubo un error al cargarlo.'];
           }

           $allowedExtensions = ['xls', 'xlsx'];
           $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
           if (!in_array($extension, $allowedExtensions)) {
               return ['success' => false, 'message' => 'El archivo debe ser un Excel con extensión .xls o .xlsx.'];
           }
       }

        try {
            if(!empty($request['file'])){
            $tempPath = $file['tmp_name'];
            $reader = IOFactory::createReaderForFile($tempPath);
            $spreadsheet = $reader->load($tempPath);
            $sheet = $spreadsheet->getActiveSheet();

            $highestColumn = $sheet->getHighestColumn();
            $headers = $sheet->rangeToArray("A1:{$highestColumn}1")[0];
            $fileHeaders = array_map('strtoupper', $headers);

            $requiredHeaders = ['EMAIL', 'NOMBRE', 'IDENTIFICADOR'];
            foreach ($requiredHeaders as $header) {
                if (!in_array($header, $fileHeaders)) {
                    return ['success' => false, 'message' => 'El archivo no contiene las cabeceras requeridas: EMAIL, NOMBRE, IDENTIFICADOR.'];
                }
            }
            }

            $campaignData = [
                'name' => $request['name'],
                'subject' => $request['subject'],
                'schedule' => 0,
                'emailResponse' => $request['emailResponse'],
                'sender' => $request['sender'],
                'status' => 'CARGADA',
                'updated_at' => date('Y-m-d H:i:s'),
                'idCedente' => $this->idCedente,
                'idMandante' => $this->idMandante
            ];

            $conditions = [
                'id' => $request['id']
            ];
            $campaignId = $this->db->updateWithParams('mail_campaigns', $campaignData,$conditions);

            if (!$campaignId) {
                return ['success' => false, 'message' => 'Error al insertar la campaña.'];
            }

            if(!empty($request['file'])){
                $sql = "DELETE FROM mail_data_emails WHERE campaig_id= ".$request['id'];
                $this->db->query($sql);

                $rows = $sheet->rangeToArray("A2:{$highestColumn}" . $sheet->getHighestRow());

                foreach (array_chunk($rows, 1000) as $chunk) {
                    foreach ($chunk as $row) {
                        $row = array_combine($fileHeaders, array_map('trim', $row));
                        $customVariables = json_encode($row);

                        $emailData = [
                            'identity' => $row['IDENTIFICADOR'],
                            'fullName' => $row['NOMBRE'],
                            'email' => $row['EMAIL'],
                            'customVariables' => $customVariables,
                            'campaign_id' => $campaignId,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                        $this->db->insertWithParams('mail_data_emails', $emailData);
                    }
                }
            }

            return ['success' => true, 'message' => 'Campaña actualizada y datos almacenados exitosamente.'];
        } catch (\Exception $e) {
           $this->logs->error($e->getMessage());
            return ['success' => false, 'message' => 'Error al procesar la campaña: ' . $e->getMessage()];
        }
    }

    function customVariables($id)
    {
        try {
            $sql = "
            SELECT mde.customVariables
            FROM mail_campaigns mc
            INNER JOIN mail_data_emails mde ON mc.id = mde.campaign_id
            WHERE mc.id = " . intval($id) . "
            LIMIT 1";

            $this->logs->debug($sql);

            $result = $this->db->select($sql);
            $this->logs->debug($result);
            if (!$result || count($result) === 0) {
                $this->logs->error("No se encontró información para la campaña con id: " . $id);
                return [
                    'success' => false,
                    'message' => 'No se encontró información para la campaña.',
                    'items' => [],
                ];
            }

            $customVariables = $result[0]['customVariables'];
            $customVariables = is_string($customVariables) ? json_decode($customVariables, true) : $customVariables;

            if (!is_array($customVariables)) {
                $this->logs->error("El campo customVariables no tiene un formato válido.");
                return [
                    'success' => false,
                    'message' => 'El campo customVariables no tiene un formato válido.',
                    'items' => [],
                ];
            }

            $keysInUppercase = array_filter(array_keys($customVariables), function ($key) {
                return preg_match('/^[A-Z_]+$/', $key);
            });

            return [
                'success' => true,
                'items' => array_values($keysInUppercase),
            ];

        } catch (Exception $e) {
            $this->logs->error("Error al procesar customVariables: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al procesar customVariables.',
                'error' => $e->getMessage(),
            ];
        }
    }


}
